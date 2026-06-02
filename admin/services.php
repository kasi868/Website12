<?php include("includes/header.php"); ?>

<?php
function uploadServiceImage($fileInputName) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
        $targetDir = "../uploads/services/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES[$fileInputName]["name"]));
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowTypes = array('jpg','png','jpeg','gif','webp');
        if (in_array(strtolower($fileType), $allowTypes) && move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFilePath)) {
            return 'uploads/services/' . $fileName;
        }
    }
    return '';
}

// Handle Add Service
if(isset($_POST['add_service'])) {
    $page_slug = mysqli_real_escape_string($conn, $_POST['page_slug']);
    $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    $icon_class = mysqli_real_escape_string($conn, $_POST['icon_class']);
    $position = (int) $_POST['position'];
    $image = uploadServiceImage('image');

    $sql = "INSERT INTO service_list (page_slug, service_name, subtitle, content, image, link, icon_class, position) 
            VALUES ('$page_slug', '$service_name', '$subtitle', '$content', '$image', '$link', '$icon_class', $position)";
    if(mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Service added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Handle Update Service
if(isset($_POST['update_service'])) {
    $id = $_POST['id'];
    $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    $icon_class = mysqli_real_escape_string($conn, $_POST['icon_class']);
    $position = (int) $_POST['position'];
    $image = uploadServiceImage('image');
    $imageSql = $image ? ", image='$image'" : '';

    $sql = "UPDATE service_list SET service_name='$service_name', subtitle='$subtitle', content='$content', link='$link', icon_class='$icon_class', position=$position $imageSql WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Service updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Handle Delete Service
if(isset($_POST['delete_service'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM service_list WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Service deleted successfully!</div>";
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
$services = mysqli_query($conn, "SELECT * FROM service_list WHERE page_slug='$selectedPage' ORDER BY position ASC, id ASC");
?>

<h2>Manage Repeater Items</h2>

<div class="mb-3">
    <label>Select Page:</label>
    <select class="form-control w-25 d-inline-block" onchange="window.location.href='services.php?page='+this.value">
        <?php foreach($pageList as $p) { ?>
            <option value="<?= $p['slug'] ?>" <?= ($selectedPage == $p['slug']) ? 'selected' : '' ?>><?= $p['page_name'] ?></option>
        <?php } ?>
    </select>
    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addServiceModal">Add New Item</button>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Title</th>
            <th>Subtitle</th>
            <th>Link/Icon</th>
            <th>Position</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(mysqli_num_rows($services) > 0) {
            while($row = mysqli_fetch_assoc($services)) { ?>
        <tr>
            <td><?= $row['service_name'] ?></td>
            <td><?= $row['subtitle'] ?></td>
            <td><?= $row['link'] ?: $row['icon_class'] ?></td>
            <td><?= (int) $row['position'] ?></td>
            <td>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editServiceModal<?= $row['id'] ?>">Edit</button>
                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this service?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_service" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editServiceModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                        <h5 class="modal-title">Edit Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="mb-3">
                                <label>Title</label>
                                <input type="text" name="service_name" class="form-control" value="<?= $row['service_name'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Subtitle</label>
                                <input type="text" name="subtitle" class="form-control" value="<?= $row['subtitle'] ?>">
                            </div>
                            <div class="mb-3">
                                <label>Content / Description</label>
                                <textarea name="content" class="form-control tinymce" rows="6"><?= $row['content'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Link</label>
                                <input type="text" name="link" class="form-control" value="<?= $row['link'] ?>">
                            </div>
                            <div class="mb-3">
                                <label>Icon Class</label>
                                <input type="text" name="icon_class" class="form-control" value="<?= $row['icon_class'] ?>">
                            </div>
                            <div class="mb-3">
                                <label>Position</label>
                                <input type="number" name="position" class="form-control" value="<?= (int) $row['position'] ?>">
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control">
                                <?php if(!empty($row['image'])) { ?>
                                    <div class="mt-2"><img src="../<?= $row['image'] ?>" alt="" style="max-width:140px;height:auto;"></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_service" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php } } else { echo "<tr><td colspan='2' class='text-center'>No services found for this page.</td></tr>"; } ?>
    </tbody>
</table>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
                    <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="page_slug" value="<?= $selectedPage ?>">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="service_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Subtitle</label>
                        <input type="text" name="subtitle" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Content / Description</label>
                        <textarea name="content" class="form-control tinymce" rows="6"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Link</label>
                        <input type="text" name="link" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Icon Class</label>
                        <input type="text" name="icon_class" class="form-control" placeholder="fa fa-star">
                    </div>
                    <div class="mb-3">
                        <label>Position</label>
                        <input type="number" name="position" class="form-control" value="0">
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
