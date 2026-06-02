<?php include("includes/header.php"); ?>

<?php
// Fetch Pages
$pages_query = mysqli_query($conn, "SELECT * FROM pages ORDER BY page_name ASC");
$pages = [];
while($p = mysqli_fetch_assoc($pages_query)) {
    $pages[] = $p;
}

$selectedPage = isset($_GET['page']) ? $_GET['page'] : (count($pages) > 0 ? $pages[0]['slug'] : '');

// Handle Image Upload
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_image']) && empty($_FILES) && empty($_POST['page_slug'])) {
        echo "<div class='alert alert-danger'>File too large. Exceeds post_max_size (" . ini_get('post_max_size') . ").</div>";
    }
    if(isset($_POST['upload_image'])) {
        $page_slug = $_POST['page_slug'];
        $section_key = $_POST['section_key']; 
        $image_type = $_POST['image_type']; // New field
        $base_position = isset($_POST['position']) ? (int)$_POST['position'] : 0;
        $title = isset($_POST['title']) ? mysqli_real_escape_string($conn, $_POST['title']) : '';
        $link = isset($_POST['link']) ? mysqli_real_escape_string($conn, $_POST['link']) : '';
        
        // Check if directory exists
        if (!file_exists('../uploads/sections')) {
            mkdir('../uploads/sections', 0777, true);
        }

        $successCount = 0;
        $errorCount = 0;
        $files = $_FILES['images'];
        $count = count($files['name']);

        for($i = 0; $i < $count; $i++) {
            $position = $base_position + $i; // Auto-increment position for batch

            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                $phpFileUploadErrors = array(
                    0 => 'There is no error, the file uploaded with success',
                    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                    3 => 'The uploaded file was only partially uploaded',
                    4 => 'No file was uploaded',
                    6 => 'Missing a temporary folder',
                    7 => 'Failed to write file to disk.',
                    8 => 'A PHP extension stopped the file upload.',
                );
                $errorMsg = $phpFileUploadErrors[$files['error'][$i]] ?? 'Unknown error';
                echo "<div class='alert alert-danger'>Error uploading " . htmlspecialchars($files['name'][$i]) . ": $errorMsg</div>";
                $errorCount++;
                continue;
            }

            $targetDir = "../uploads/sections/";
            // Sanitize filename and add timestamp to prevent overwrites
            $originalName = basename($files["name"][$i]);
            $cleanName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $fileName = time() . '_' . $i . '_' . $cleanName; // Added index to ensure uniqueness in batch
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('jpg','png','jpeg','gif', 'webp');
            if(in_array(strtolower($fileType), $allowTypes)){
                // Upload file to server
                if(move_uploaded_file($files["tmp_name"][$i], $targetFilePath)){
                    // Insert image file name into database
                    $insert = mysqli_query($conn, "INSERT INTO section_images (page_slug, section_key, image_type, image, position, title, link) VALUES ('$page_slug', '$section_key', '$image_type', 'uploads/sections/$fileName', $position, '$title', '$link')");
                    if($insert){
                        $successCount++;
                    }else{
                        echo "<div class='alert alert-danger'>Database insert failed for " . htmlspecialchars($files['name'][$i]) . ": " . mysqli_error($conn) . "</div>";
                        $errorCount++;
                    } 
                }else{
                    echo "<div class='alert alert-danger'>Sorry, there was an error moving your file " . htmlspecialchars($files['name'][$i]) . ". Check permissions.</div>";
                    $errorCount++;
                }
            }else{
                echo "<div class='alert alert-danger'>Sorry, " . htmlspecialchars($files['name'][$i]) . " has invalid type. Only JPG, JPEG, PNG, GIF, & WEBP files are allowed.</div>";
                $errorCount++;
            }
        }

        if ($successCount > 0) {
            echo "<div class='alert alert-success'>$successCount images uploaded successfully.</div>";
            // Refresh to show images
            echo "<meta http-equiv='refresh' content='1'>";
        }
    }

// Handle Delete Image
if(isset($_POST['delete_image'])) {
    $id = $_POST['id'];
    $image = $_POST['image'];
    
    // Delete from DB
    mysqli_query($conn, "DELETE FROM section_images WHERE id=$id");
    
    // Delete from Folder
    if(strpos($image, 'uploads/sections/') === 0) {
        $physicalPath = "../" . $image;
        if(file_exists($physicalPath)) {
            unlink($physicalPath);
        }
    }
    
    echo "<div class='alert alert-success'>Image deleted successfully.</div>";
}

// Fetch Images
$images = mysqli_query($conn, "SELECT * FROM section_images WHERE page_slug='$selectedPage' ORDER BY id DESC");
?>

<h2>Manage Gallery</h2>

<div class="mb-3">
    <label>Select Page:</label>
    <select class="form-control w-25 d-inline-block" onchange="window.location.href='gallery.php?page='+this.value">
        <?php foreach($pages as $p) { ?>
            <option value="<?= $p['slug'] ?>" <?= ($selectedPage == $p['slug']) ? 'selected' : '' ?>><?= $p['page_name'] ?></option>
        <?php } ?>
    </select>
    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#uploadModal">Upload Image</button>
</div>

<div class="row">
    <?php if(mysqli_num_rows($images) > 0) {
        while($row = mysqli_fetch_assoc($images)) { ?>
    <div class="col-md-3 mb-4">
        <div class="card">
            <img src="../<?= $row['image'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
            <div class="card-body text-center">
                <p class="card-text text-muted small mb-2">
                    Type: <span class="badge bg-<?= ($row['image_type'] == 'service') ? 'info' : 'success' ?>"><?= ucfirst($row['image_type']) ?></span><br>
                    Key: <?= $row['section_key'] ?><br>
                    Pos: <?= $row['position'] ?><br>
                    <?php if($row['title']) echo "<b>Title:</b> " . htmlspecialchars($row['title']) . "<br>"; ?>
                    <?php if($row['link']) echo "<b>Link:</b> " . htmlspecialchars($row['link']) . "<br>"; ?>
                </p>
                <form method="post" onsubmit="return confirm('Are you sure you want to delete this image?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="image" value="<?= $row['image'] ?>">
                    <button type="submit" name="delete_image" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
    <?php } } else { echo "<div class='col-12'><p class='text-center'>No images found.</p></div>"; } ?>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Image to <?= $selectedPage ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="page_slug" value="<?= $selectedPage ?>">
                    <div class="mb-3">
                        <label>Show Image On:</label>
                        <select name="image_type" class="form-control" required>
                            <option value="service">Service Page (Top Section)</option>
                            <option value="gallery" selected>Main Gallery Page</option>
                        </select>
                        <small class="text-muted">Choose where this image should be displayed.</small>
                    </div>
                    <div class="mb-3">
                        <label>Section Key</label>
                        <?php if($selectedPage == 'about') { ?>
                        <select name="section_key" class="form-control">
                            <option value="about_intro">Top Intro Images (3 Slots)</option>
                            <option value="why_choose_us">Why Choose Us Slider</option>
                            <option value="about_gallery">General Gallery</option>
                        </select>
                        <?php } elseif ($selectedPage == 'home') { ?>
                        <select name="section_key" class="form-control">
                            <option value="home_about">About Section (3 Slots)</option>
                            <option value="home_why_choose_us">Why Choose Us Slider</option>
                            <option value="home_hero_desktop">Hero Slider (Desktop)</option>
                            <option value="home_hero_mobile">Hero Slider (Mobile)</option>
                            <option value="home_services">Our Services (Grid)</option>
                            <option value="home_gallery">Photo Gallery (Masonry)</option>
                            <option value="home_portfolio">Client / Portfolio Slider</option>
                        </select>
                        <?php } else { ?>
                        <input type="text" name="section_key" class="form-control" value="<?= $selectedPage ?>_gallery">
                        <?php } ?>
                        <small class="text-muted">Select where this image should appear.</small>
                    </div>
                    <div class="mb-3">
                        <label>Position (Order)</label>
                        <input type="number" name="position" class="form-control" value="0">
                        <small class="text-muted">For Intro Images, use 1, 2, or 3.</small>
                    </div>
                    <div class="mb-3">
                        <label>Title (Optional)</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Product Photography">
                    </div>
                    <div class="mb-3">
                        <label>Link (Optional)</label>
                        <input type="text" name="link" class="form-control" placeholder="e.g. product-photography.php">
                        <small class="text-muted">For Services/Gallery, enter the page URL.</small>
                    </div>
                    <div class="mb-3">
                        <label>Select Images (Multiple allowed)</label>
                        <input type="file" name="images[]" class="form-control" multiple required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="upload_image" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
