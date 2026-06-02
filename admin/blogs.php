<?php include("includes/header.php"); ?>

<!-- Quill Rich Text Editor (Free - No API Key Required) -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var quill = new Quill('#blog_content', {
      theme: 'snow',
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline', 'strike'],
          ['blockquote', 'code-block'],
          [{ 'header': 1 }, { 'header': 2 }],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          [{ 'script': 'sub'}, { 'script': 'super' }],
          [{ 'indent': '-1'}, { 'indent': '+1' }],
          [{ 'size': ['small', false, 'large', 'huge'] }],
          [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
          [{ 'color': [] }, { 'background': [] }],
          [{ 'align': [] }],
          ['link', 'image'],
          ['clean']
        ]
      },
      placeholder: 'Enter your blog content here...',
    });

    // Before form submission, copy Quill content to hidden input
    var form = document.querySelector('form');
    if (form) {
      form.addEventListener('submit', function() {
        document.querySelector('input[name="content_hidden"]').value = quill.root.innerHTML;
      });
    }
  });
</script>

<?php
function ensureUniqueBlogSlug($conn, $slug, $id = 0) {
    $baseSlug = cms_slugify($slug);
    if ($baseSlug === '') {
        $baseSlug = 'blog';
    }

    $candidate = $baseSlug;
    $suffix = 2;

    while (true) {
        $safeSlug = mysqli_real_escape_string($conn, $candidate);
        $query = "SELECT id FROM blogs WHERE slug='$safeSlug'";
        if ($id > 0) {
            $query .= " AND id != " . (int) $id;
        }
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) === 0) {
            return $candidate;
        }
        $candidate = $baseSlug . '-' . $suffix;
        $suffix++;
    }
}

// Handle Create/Update Blog
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_blog'])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $slug = ensureUniqueBlogSlug($conn, !empty($_POST['slug']) ? $_POST['slug'] : $_POST['title'], $id);
    $slugEscaped = mysqli_real_escape_string($conn, $slug);
    $intro_text = mysqli_real_escape_string($conn, $_POST['intro_text']);
    $content = mysqli_real_escape_string($conn, isset($_POST['content_hidden']) ? $_POST['content_hidden'] : $_POST['content']);
    $conclusion = mysqli_real_escape_string($conn, $_POST['conclusion']);
    
    // New Fields
    $header_image_alt = mysqli_real_escape_string($conn, $_POST['header_image_alt']);
    $image_alt = mysqli_real_escape_string($conn, $_POST['image_alt']);
    $image2_alt = mysqli_real_escape_string($conn, $_POST['image2_alt']);
    $image3_alt = mysqli_real_escape_string($conn, $_POST['image3_alt']);
    $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);
    $meta_tags = mysqli_real_escape_string($conn, $_POST['meta_tags']);
    $meta_keywords = mysqli_real_escape_string($conn, $_POST['meta_keywords']);
    $backlinks = mysqli_real_escape_string($conn, $_POST['backlinks']);
    $canonical_url = mysqli_real_escape_string($conn, trim($_POST['canonical_url']));
    $title_heading_tag = mysqli_real_escape_string($conn, cms_heading_tag($_POST['title_heading_tag'], 'h1'));
    $subtitle_heading_tag = mysqli_real_escape_string($conn, cms_heading_tag($_POST['subtitle_heading_tag'], 'h2'));
    
    // Handle Image Upload
    $imageUpdate = "";
    
    // Helper function for file upload
    function uploadBlogImage($fileInputName) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
            $targetDir = "../uploads/blogs/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $fileName = time() . '_' . $fileInputName . '_' . basename($_FILES[$fileInputName]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $allowTypes = array('jpg','png','jpeg','gif', 'webp');
            if(in_array(strtolower($fileType), $allowTypes)){
                if(move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFilePath)){
                    return $fileName;
                }
            }
        }
        return false;
    }

    $imgH = uploadBlogImage('header_image');
    if($imgH) $imageUpdate .= ", header_image='$imgH'";

    $img1 = uploadBlogImage('image');
    if($img1) $imageUpdate .= ", image='$img1'";
    
    $img2 = uploadBlogImage('image2');
    if($img2) $imageUpdate .= ", image2='$img2'";
    
    $img3 = uploadBlogImage('image3');
    if($img3) $imageUpdate .= ", image3='$img3'";

    if ($id > 0) {
        // Update
        $sql = "UPDATE blogs SET title='$title', subtitle='$subtitle', slug='$slugEscaped', intro_text='$intro_text', content='$content', conclusion='$conclusion', header_image_alt='$header_image_alt', image_alt='$image_alt', image2_alt='$image2_alt', image3_alt='$image3_alt', meta_title='$meta_title', meta_description='$meta_description', meta_tags='$meta_tags', meta_keywords='$meta_keywords', backlinks='$backlinks', canonical_url='$canonical_url', title_heading_tag='$title_heading_tag', subtitle_heading_tag='$subtitle_heading_tag' $imageUpdate WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            cms_generate_sitemap($conn);
            echo "<div class='alert alert-success'>Blog updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating blog: " . mysqli_error($conn) . "</div>";
        }
    } else {
        // Insert
        $hImg = $imgH ? $imgH : '';
        $imageName = $img1 ? $img1 : '';
        $imageName2 = $img2 ? $img2 : '';
        $imageName3 = $img3 ? $img3 : '';
        $sql = "INSERT INTO blogs (title, subtitle, slug, intro_text, content, conclusion, header_image, header_image_alt, image, image_alt, image2, image2_alt, image3, image3_alt, meta_title, meta_description, meta_tags, meta_keywords, backlinks, canonical_url, title_heading_tag, subtitle_heading_tag) VALUES ('$title', '$subtitle', '$slugEscaped', '$intro_text', '$content', '$conclusion', '$hImg', '$header_image_alt', '$imageName', '$image_alt', '$imageName2', '$image2_alt', '$imageName3', '$image3_alt', '$meta_title', '$meta_description', '$meta_tags', '$meta_keywords', '$backlinks', '$canonical_url', '$title_heading_tag', '$subtitle_heading_tag')";
        if (mysqli_query($conn, $sql)) {
            cms_generate_sitemap($conn);
            echo "<div class='alert alert-success'>Blog created successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error creating blog: " . mysqli_error($conn) . "</div>";
        }
    }
}

// Handle Delete Blog
if (isset($_POST['delete_blog'])) {
    $id = (int)$_POST['id'];
    mysqli_query($conn, "DELETE FROM blogs WHERE id=$id");
    cms_generate_sitemap($conn);
    echo "<div class='alert alert-success'>Blog deleted successfully.</div>";
}

// Fetch Blogs
$blogs = mysqli_query($conn, "SELECT * FROM blogs ORDER BY created_at DESC");
$editBlog = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editBlog = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blogs WHERE id=$editId"));
}
?>

<h2>Manage Blogs</h2>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><?= $editBlog ? 'Edit Blog' : 'Add New Blog' ?></div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <?php if ($editBlog) { ?>
                        <input type="hidden" name="id" value="<?= $editBlog['id'] ?>">
                    <?php } ?>
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required value="<?= $editBlog ? $editBlog['title'] : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label>Subtitle (Displayed below Title on details page)</label>
                        <input type="text" name="subtitle" class="form-control" value="<?= $editBlog ? (isset($editBlog['subtitle']) ? $editBlog['subtitle'] : '') : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label>Slug (Optional, auto-generated from title)</label>
                        <input type="text" name="slug" class="form-control" value="<?= $editBlog ? $editBlog['slug'] : '' ?>">
                        <small class="text-muted">The final blog URL will be `/blog/your-slug/`.</small>
                    </div>
                    <hr>
                    <h5>SEO Section</h5>
                    <div class="mb-3">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="<?= $editBlog ? (isset($editBlog['meta_title']) ? $editBlog['meta_title'] : '') : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label>Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="3"><?= $editBlog ? (isset($editBlog['meta_description']) ? $editBlog['meta_description'] : '') : '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control" value="<?= $editBlog ? (isset($editBlog['meta_keywords']) ? $editBlog['meta_keywords'] : '') : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label>Meta Tags</label>
                        <input type="text" name="meta_tags" class="form-control" value="<?= $editBlog ? (isset($editBlog['meta_tags']) ? $editBlog['meta_tags'] : '') : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label>Canonical URL</label>
                        <input type="text" name="canonical_url" class="form-control" value="<?= $editBlog ? (isset($editBlog['canonical_url']) ? $editBlog['canonical_url'] : '') : '' ?>" placeholder="https://conceptphotography.co.in/blog/example/">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Title Heading Tag</label>
                            <select name="title_heading_tag" class="form-control">
                                <?php foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $headingTag) { ?>
                                    <option value="<?= $headingTag ?>" <?= ($editBlog && value($editBlog, 'title_heading_tag', 'h1') === $headingTag) ? 'selected' : (!$editBlog && $headingTag === 'h1' ? 'selected' : '') ?>><?= strtoupper($headingTag) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Subtitle Heading Tag</label>
                            <select name="subtitle_heading_tag" class="form-control">
                                <?php foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $headingTag) { ?>
                                    <option value="<?= $headingTag ?>" <?= ($editBlog && value($editBlog, 'subtitle_heading_tag', 'h2') === $headingTag) ? 'selected' : (!$editBlog && $headingTag === 'h2' ? 'selected' : '') ?>><?= strtoupper($headingTag) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <h5>Header Section</h5>
                    <div class="mb-3">
                        <label>Header Image (Optional)</label>
                        <input type="file" name="header_image" class="form-control">
                        <?php if ($editBlog && isset($editBlog['header_image']) && $editBlog['header_image']) { ?>
                            <div class="mt-2">
                                <img src="../uploads/blogs/<?= $editBlog['header_image'] ?>" style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mb-3">
                        <label>Header Image Alt Text</label>
                        <input type="text" name="header_image_alt" class="form-control" value="<?= $editBlog ? (isset($editBlog['header_image_alt']) ? $editBlog['header_image_alt'] : '') : '' ?>">
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label>Intro Text (Displayed above Main Image)</label>
                        <textarea name="intro_text" class="form-control" rows="5"><?= $editBlog ? (isset($editBlog['intro_text']) ? $editBlog['intro_text'] : '') : '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Main Image (Image 1)</label>
                        <input type="file" name="image" class="form-control" <?= $editBlog ? '' : 'required' ?>>
                        <?php if ($editBlog && $editBlog['image']) { ?>
                            <div class="mt-2">
                                <img src="../uploads/blogs/<?= $editBlog['image'] ?>" style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mb-3">
                        <label>Main Image Alt Text</label>
                        <input type="text" name="image_alt" class="form-control" value="<?= $editBlog ? (isset($editBlog['image_alt']) ? $editBlog['image_alt'] : '') : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label>Sidebar Image 1 (Image 2 - Optional)</label>
                        <input type="file" name="image2" class="form-control">
                        <?php if ($editBlog && isset($editBlog['image2']) && $editBlog['image2']) { ?>
                            <div class="mt-2">
                                <img src="../uploads/blogs/<?= $editBlog['image2'] ?>" style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mb-3">
                        <label>Sidebar Image 1 Alt Text</label>
                        <input type="text" name="image2_alt" class="form-control" value="<?= $editBlog ? (isset($editBlog['image2_alt']) ? $editBlog['image2_alt'] : '') : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label>Sidebar Image 2 (Image 3 - Optional)</label>
                        <input type="file" name="image3" class="form-control">
                        <?php if ($editBlog && isset($editBlog['image3']) && $editBlog['image3']) { ?>
                            <div class="mt-2">
                                <img src="../uploads/blogs/<?= $editBlog['image3'] ?>" style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mb-3">
                        <label>Sidebar Image 2 Alt Text</label>
                        <input type="text" name="image3_alt" class="form-control" value="<?= $editBlog ? (isset($editBlog['image3_alt']) ? $editBlog['image3_alt'] : '') : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label>Main Content (Rich Text Editor - Headings, Bolds, Lists support)</label>
                        <div id="blog_content" style="height: 400px; background-color: white; border: 1px solid #ddd; border-radius: 4px;"><?= $editBlog ? $editBlog['content'] : '' ?></div>
                        <input type="hidden" name="content_hidden" value="">
                    </div>
                    <div class="mb-3">
                        <label>Conclusion (Displayed in Quote Box)</label>
                        <textarea name="conclusion" class="form-control" rows="4"><?= $editBlog ? (isset($editBlog['conclusion']) ? $editBlog['conclusion'] : '') : '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Backlinks (HTML or text links)</label>
                        <textarea name="backlinks" class="form-control" rows="3"><?= $editBlog ? (isset($editBlog['backlinks']) ? $editBlog['backlinks'] : '') : '' ?></textarea>
                    </div>
                    <button type="submit" name="save_blog" class="btn btn-primary"><?= $editBlog ? 'Update Blog' : 'Add Blog' ?></button>
                    <?php if ($editBlog) { ?>
                        <a href="blogs.php" class="btn btn-secondary">Cancel</a>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Existing Blogs</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($blogs)) { ?>
                        <tr>
                            <td>
                                <?php if($row['image']) { ?>
                                    <img src="../uploads/blogs/<?= $row['image'] ?>" width="50">
                                <?php } ?>
                            </td>
                            <td>
                                <?= $row['title'] ?>
                                <br><small class="text-muted"><?= $row['slug'] ?></small>
                            </td>
                            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <a href="blogs.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="delete_blog" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
