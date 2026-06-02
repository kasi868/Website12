<?php include("includes/header.php"); ?>

<?php
function ensureUniquePageSlug($conn, $slug, $id = 0) {
    $baseSlug = cms_slugify($slug);
    if ($baseSlug === '') {
        $baseSlug = 'page';
    }

    $candidate = $baseSlug;
    $suffix = 2;

    while (true) {
        $safeSlug = mysqli_real_escape_string($conn, $candidate);
        $query = "SELECT id FROM pages WHERE slug='$safeSlug'";
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

// Helper function for file upload
function uploadPageImage($fileInputName) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
        $targetDir = "../uploads/pages/";
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

// Handle Add Page
if(isset($_POST['add_page'])) {
    $slug = ensureUniquePageSlug($conn, $_POST['slug']);
    $page_name = mysqli_real_escape_string($conn, $_POST['page_name']);
    $banner_title = mysqli_real_escape_string($conn, $_POST['banner_title']);
    $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);
    $meta_keywords = mysqli_real_escape_string($conn, $_POST['meta_keywords']);
    $meta_tags = mysqli_real_escape_string($conn, $_POST['meta_tags']);
    $backlinks = mysqli_real_escape_string($conn, $_POST['backlinks']);
    $canonical_url = mysqli_real_escape_string($conn, $_POST['canonical_url']);
    $banner_heading_tag = mysqli_real_escape_string($conn, cms_heading_tag($_POST['banner_heading_tag'], 'h1'));
    $service_list_heading_tag = mysqli_real_escape_string($conn, cms_heading_tag($_POST['service_list_heading_tag'], 'h2'));
    $sort_order = (int) $_POST['sort_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $template_name = mysqli_real_escape_string($conn, value($_POST, 'template_name', 'service'));
    $headerImage = uploadPageImage('header_image');
    $header_image_alt = mysqli_real_escape_string($conn, $_POST['header_image_alt']);
    $headerImageValue = $headerImage ? "'" . mysqli_real_escape_string($conn, $headerImage) . "'" : "NULL";

    $sql = "INSERT INTO pages (slug, page_name, banner_title, header_image, header_image_alt, meta_title, meta_description, meta_keywords, meta_tags, canonical_url, banner_heading_tag, service_list_heading_tag, sort_order, is_active, template_name, backlinks) 
            VALUES ('" . mysqli_real_escape_string($conn, $slug) . "', '$page_name', '$banner_title', $headerImageValue, '$header_image_alt', '$meta_title', '$meta_description', '$meta_keywords', '$meta_tags', '$canonical_url', '$banner_heading_tag', '$service_list_heading_tag', $sort_order, $is_active, '$template_name', '$backlinks')";
    if(mysqli_query($conn, $sql)) {
        cms_generate_sitemap($conn);
        echo "<div class='alert alert-success'>Page added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Handle Update Page
if(isset($_POST['update_page'])) {
    $id = (int) $_POST['id'];
    $slug = ensureUniquePageSlug($conn, $_POST['slug'], $id);
    $page_name = mysqli_real_escape_string($conn, $_POST['page_name']);
    $banner_title = mysqli_real_escape_string($conn, $_POST['banner_title']);
    $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);
    $meta_keywords = mysqli_real_escape_string($conn, $_POST['meta_keywords']);
    $meta_tags = mysqli_real_escape_string($conn, $_POST['meta_tags']);
    $backlinks = mysqli_real_escape_string($conn, $_POST['backlinks']);
    $canonical_url = mysqli_real_escape_string($conn, $_POST['canonical_url']);
    $banner_heading_tag = mysqli_real_escape_string($conn, cms_heading_tag($_POST['banner_heading_tag'], 'h1'));
    $service_list_heading_tag = mysqli_real_escape_string($conn, cms_heading_tag($_POST['service_list_heading_tag'], 'h2'));
    $sort_order = (int) $_POST['sort_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $template_name = mysqli_real_escape_string($conn, value($_POST, 'template_name', 'service'));
    $header_image_alt = mysqli_real_escape_string($conn, $_POST['header_image_alt']);
    $imageUpdate = '';
    $headerImage = uploadPageImage('header_image');
    if ($headerImage) {
        $imageUpdate = ", header_image='" . mysqli_real_escape_string($conn, $headerImage) . "'";
    }

    $sql = "UPDATE pages SET 
            slug='" . mysqli_real_escape_string($conn, $slug) . "',
            page_name='$page_name', 
            banner_title='$banner_title',
            header_image_alt='$header_image_alt',
            meta_title='$meta_title',
            meta_description='$meta_description',
            meta_keywords='$meta_keywords',
            meta_tags='$meta_tags',
            canonical_url='$canonical_url',
            banner_heading_tag='$banner_heading_tag',
            service_list_heading_tag='$service_list_heading_tag',
            sort_order=$sort_order,
            is_active=$is_active,
            template_name='$template_name',
            backlinks='$backlinks'
            $imageUpdate
            WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        cms_generate_sitemap($conn);
        echo "<div class='alert alert-success'>Page updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

if (isset($_POST['delete_page'])) {
    $id = (int) $_POST['id'];
    mysqli_query($conn, "DELETE FROM pages WHERE id=$id");
    cms_generate_sitemap($conn);
    echo "<div class='alert alert-success'>Page deleted successfully!</div>";
}

// Fetch Pages
$pages = mysqli_query($conn, "SELECT * FROM pages ORDER BY sort_order ASC, id ASC");
?>

<h2>Manage Pages</h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPageModal">Add New Page</button>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>ID</th>
            <th>Page Name</th>
            <th>Slug</th>
            <th>Banner Title</th>
            <th>Order</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($pages)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['page_name'] ?></td>
            <td><?= $row['slug'] ?></td>
            <td><?= $row['banner_title'] ?></td>
            <td><?= (int) value($row, 'sort_order', 0) ?></td>
            <td><?= (int) value($row, 'is_active', 1) === 1 ? 'Active' : 'Inactive' ?></td>
            <td>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPageModal<?= $row['id'] ?>">Edit</button>
                <form method="post" class="d-inline" onsubmit="return confirm('Delete this page?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_page" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editPageModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Page</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Page Name</label>
                                        <input type="text" name="page_name" class="form-control" value="<?= $row['page_name'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Slug</label>
                                        <input type="text" name="slug" class="form-control" value="<?= $row['slug'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Banner Title</label>
                                        <input type="text" name="banner_title" class="form-control" value="<?= $row['banner_title'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Header Image</label>
                                        <input type="file" name="header_image" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Header Image Alt Text</label>
                                        <input type="text" name="header_image_alt" class="form-control" value="<?= cms_e(value($row, 'header_image_alt')) ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Meta Title</label>
                                        <input type="text" name="meta_title" class="form-control" value="<?= $row['meta_title'] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label>Meta Description</label>
                                        <textarea name="meta_description" class="form-control" rows="3"><?= $row['meta_description'] ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Meta Keywords</label>
                                        <textarea name="meta_keywords" class="form-control" rows="2"><?= $row['meta_keywords'] ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Meta Tags</label>
                                        <textarea name="meta_tags" class="form-control" rows="2"><?= $row['meta_tags'] ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Canonical URL</label>
                                        <input type="text" name="canonical_url" class="form-control" value="<?= cms_e(value($row, 'canonical_url')) ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>Banner Heading Tag</label>
                                            <select name="banner_heading_tag" class="form-control">
                                                <?php foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $headingTag) { ?>
                                                    <option value="<?= $headingTag ?>" <?= value($row, 'banner_heading_tag', 'h1') === $headingTag ? 'selected' : '' ?>><?= strtoupper($headingTag) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Service List Heading Tag</label>
                                            <select name="service_list_heading_tag" class="form-control">
                                                <?php foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $headingTag) { ?>
                                                    <option value="<?= $headingTag ?>" <?= value($row, 'service_list_heading_tag', 'h2') === $headingTag ? 'selected' : '' ?>><?= strtoupper($headingTag) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label>Sort Order</label>
                                            <input type="number" name="sort_order" class="form-control" value="<?= (int) value($row, 'sort_order', 0) ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>Template</label>
                                            <select name="template_name" class="form-control">
                                                <option value="service" <?= value($row, 'template_name', 'service') === 'service' ? 'selected' : '' ?>>Service</option>
                                                <option value="default" <?= value($row, 'template_name', 'service') === 'default' ? 'selected' : '' ?>>Default</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3 d-flex align-items-end">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active_<?= $row['id'] ?>" <?= (int) value($row, 'is_active', 1) === 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="is_active_<?= $row['id'] ?>">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>Backlinks (one per line)</label>
                                        <textarea name="backlinks" class="form-control" rows="3"><?= $row['backlinks'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_page" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php } ?>
    </tbody>
</table>

<!-- Add Page Modal -->
<div class="modal fade" id="addPageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <!-- <h5 class="modal-title">Add New Page</h5> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Page Name</label>
                        <input type="text" name="page_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Slug (e.g., product-photography)</label>
                        <input type="text" name="slug" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Banner Title</label>
                        <input type="text" name="banner_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Header Image</label>
                        <input type="file" name="header_image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Header Image Alt Text</label>
                        <input type="text" name="header_image_alt" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Meta Description</label>
                        <textarea name="meta_description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Meta Keywords</label>
                        <textarea name="meta_keywords" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Meta Tags</label>
                        <textarea name="meta_tags" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Canonical URL</label>
                        <input type="text" name="canonical_url" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Banner Heading Tag</label>
                            <select name="banner_heading_tag" class="form-control">
                                <option value="h1" selected>H1</option>
                                <option value="h2">H2</option>
                                <option value="h3">H3</option>
                                <option value="h4">H4</option>
                                <option value="h5">H5</option>
                                <option value="h6">H6</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Service List Heading Tag</label>
                            <select name="service_list_heading_tag" class="form-control">
                                <option value="h2" selected>H2</option>
                                <option value="h1">H1</option>
                                <option value="h3">H3</option>
                                <option value="h4">H4</option>
                                <option value="h5">H5</option>
                                <option value="h6">H6</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Template</label>
                            <select name="template_name" class="form-control">
                                <option value="service" selected>Service</option>
                                <option value="default">Default</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active_new" checked>
                                <label class="form-check-label" for="is_active_new">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Backlinks</label>
                        <textarea name="backlinks" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_page" class="btn btn-primary">Add Page</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
