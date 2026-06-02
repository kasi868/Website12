<?php include("includes/header.php"); ?>

<?php
// Helper function for file upload
function uploadSectionImage($fileInputName) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
        $targetDir = "../uploads/sections/";
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

// Handle Add Section
if(isset($_POST['add_section'])) {
    $page_slug = mysqli_real_escape_string($conn, $_POST['page_slug']);
    $section_key = mysqli_real_escape_string($conn, $_POST['section_key']);
    $sub_heading = mysqli_real_escape_string($conn, $_POST['sub_heading']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $heading_tag = mysqli_real_escape_string($conn, cms_heading_tag($_POST['heading_tag'], 'h2'));
    $sort_order = (int) $_POST['sort_order'];
    $image_alt = mysqli_real_escape_string($conn, $_POST['image_alt']);
    $image_position = mysqli_real_escape_string($conn, value($_POST, 'image_position', 'right'));
    $layout_style = mysqli_real_escape_string($conn, value($_POST, 'layout_style', 'default'));

    $imageUpdate = "";
    $sectionImage = uploadSectionImage('image');
    if ($sectionImage) {
        $imageUpdate = ", image='" . mysqli_real_escape_string($conn, $sectionImage) . "'";
    }

    $sql = "INSERT INTO sections (page_slug, section_key, sub_heading, heading_tag, sort_order, content, image_alt, image_position, layout_style) 
            VALUES ('$page_slug', '$section_key', '$sub_heading', '$heading_tag', '$sort_order', '$content', '$image_alt', '$image_position', '$layout_style')";
    if(mysqli_query($conn, $sql)) {
        if ($sectionImage) {
            $insertId = mysqli_insert_id($conn);
            mysqli_query($conn, "UPDATE sections SET image='" . mysqli_real_escape_string($conn, $sectionImage) . "' WHERE id=$insertId");
        }
        echo "<div class='alert alert-success'>Section added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Handle Update Section
if(isset($_POST['update_section'])) {
    $id = (int) $_POST['id'];
    $sub_heading = mysqli_real_escape_string($conn, $_POST['sub_heading']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $heading_tag = mysqli_real_escape_string($conn, cms_heading_tag($_POST['heading_tag'], 'h2'));
    $sort_order = (int) $_POST['sort_order'];
    $image_alt = mysqli_real_escape_string($conn, $_POST['image_alt']);
    $image_position = mysqli_real_escape_string($conn, value($_POST, 'image_position', 'right'));
    $layout_style = mysqli_real_escape_string($conn, value($_POST, 'layout_style', 'default'));

    $imageUpdate = "";
    $sectionImage = uploadSectionImage('image');
    if ($sectionImage) {
        $imageUpdate = ", image='" . mysqli_real_escape_string($conn, $sectionImage) . "'";
    }

    $sql = "UPDATE sections SET sub_heading='$sub_heading', heading_tag='$heading_tag', sort_order=$sort_order, content='$content', image_alt='$image_alt', image_position='$image_position', layout_style='$layout_style' $imageUpdate WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Section updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Handle Delete Section
if(isset($_POST['delete_section'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM sections WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Section deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch Pages for Dropdown
$pages = mysqli_query($conn, "SELECT * FROM pages ORDER BY id ASC");
$pageList = [];
while($p = mysqli_fetch_assoc($pages)) { $pageList[] = $p; }

// Filter by Page
$selectedPage = isset($_GET['page']) ? $_GET['page'] : (count($pageList) > 0 ? $pageList[0]['slug'] : '');
$sections = mysqli_query($conn, "SELECT * FROM sections WHERE page_slug='$selectedPage' ORDER BY sort_order ASC, id ASC");
?>

<h2>Manage Sections</h2>

<div class="mb-3">
    <label>Select Page:</label>
    <select class="form-control w-25 d-inline-block" onchange="window.location.href='sections.php?page='+this.value">
        <?php foreach($pageList as $p) { ?>
            <option value="<?= $p['slug'] ?>" <?= ($selectedPage == $p['slug']) ? 'selected' : '' ?>><?= $p['page_name'] ?></option>
        <?php } ?>
    </select>
    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addSectionModal">Add New Section</button>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>ID</th>
            <th>Section Key</th>
            <th>Sub Heading</th>
            <th>Heading Tag</th>
            <th>Order</th>
            <th>Content</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(mysqli_num_rows($sections) > 0) {
            while($row = mysqli_fetch_assoc($sections)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['section_key'] ?></td>
            <td><?= $row['sub_heading'] ?></td>
            <td><?= strtoupper(value($row, 'heading_tag', 'h2')) ?></td>
            <td><?= (int) value($row, 'sort_order', 0) ?></td>
            <td><?= substr(strip_tags($row['content']), 0, 50) ?>...</td>
            <td>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSectionModal<?= $row['id'] ?>">Edit</button>
                <form method="post" action="sections.php" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this section?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_section" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editSectionModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" action="sections.php" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Section (<?= $row['section_key'] ?>)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="mb-3">
                                <label>Sub Heading</label>
                                <input type="text" name="sub_heading" class="form-control" value="<?= $row['sub_heading'] ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Heading Tag</label>
                                    <select name="heading_tag" class="form-control">
                                        <?php foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $headingTag) { ?>
                                            <option value="<?= $headingTag ?>" <?= value($row, 'heading_tag', 'h2') === $headingTag ? 'selected' : '' ?>><?= strtoupper($headingTag) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" value="<?= (int) value($row, 'sort_order', 0) ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Image Position</label>
                                    <select name="image_position" class="form-control">
                                        <option value="left" <?= value($row, 'image_position', 'right') === 'left' ? 'selected' : '' ?>>Left</option>
                                        <option value="right" <?= value($row, 'image_position', 'right') === 'right' ? 'selected' : '' ?>>Right</option>
                                        <option value="top" <?= value($row, 'image_position', 'right') === 'top' ? 'selected' : '' ?>>Top</option>
                                        <option value="bottom" <?= value($row, 'image_position', 'right') === 'bottom' ? 'selected' : '' ?>>Bottom</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Layout Style</label>
                                    <select name="layout_style" class="form-control">
                                        <option value="default" <?= value($row, 'layout_style', 'default') === 'default' ? 'selected' : '' ?>>Default</option>
                                        <option value="card" <?= value($row, 'layout_style', 'default') === 'card' ? 'selected' : '' ?>>Card</option>
                                        <option value="full-width" <?= value($row, 'layout_style', 'default') === 'full-width' ? 'selected' : '' ?>>Full Width</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control">
                                <?php if (value($row, 'image')) { ?>
                                    <div class="mt-2">
                                        <img src="../uploads/sections/<?= $row['image'] ?>" style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3">
                                <label>Image Alt Text</label>
                                <input type="text" name="image_alt" class="form-control" value="<?= cms_e(value($row, 'image_alt')) ?>">
                            </div>
                            <div class="mb-3">
                                <label>Content</label>
                                <textarea name="content" class="form-control tinymce" rows="10"><?= $row['content'] ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_section" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php } } else { echo "<tr><td colspan='5' class='text-center'>No sections found for this page.</td></tr>"; } ?>
    </tbody>
</table>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="sections.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="page_slug" value="<?= $selectedPage ?>">
                    <div class="mb-3">
                        <label>Section Key (unique per page)</label>
                        <input type="text" name="section_key" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Sub Heading</label>
                        <input type="text" name="sub_heading" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Heading Tag</label>
                            <select name="heading_tag" class="form-control">
                                <option value="h2" selected>H2</option>
                                <option value="h1">H1</option>
                                <option value="h3">H3</option>
                                <option value="h4">H4</option>
                                <option value="h5">H5</option>
                                <option value="h6">H6</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Image Position</label>
                            <select name="image_position" class="form-control">
                                <option value="right" selected>Right</option>
                                <option value="left">Left</option>
                                <option value="top">Top</option>
                                <option value="bottom">Bottom</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Layout Style</label>
                            <select name="layout_style" class="form-control">
                                <option value="default" selected>Default</option>
                                <option value="card">Card</option>
                                <option value="full-width">Full Width</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Image Alt Text</label>
                        <input type="text" name="image_alt" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Content</label>
                        <textarea name="content" class="form-control tinymce" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_section" class="btn btn-primary">Add Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
