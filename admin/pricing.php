<?php include("includes/header.php"); ?>

<?php
// Handle Add Package
if(isset($_POST['add_package'])) {
    $page_slug = mysqli_real_escape_string($conn, $_POST['page_slug']);
    $package_name = mysqli_real_escape_string($conn, $_POST['package_name']);
    $product_count = mysqli_real_escape_string($conn, $_POST['product_count']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);
    $features = mysqli_real_escape_string($conn, $_POST['features']);
    $booking_link = mysqli_real_escape_string($conn, $_POST['booking_link']);

    $sql = "INSERT INTO pricing_packages (page_slug, package_name, product_count, price, unit, features, booking_link) 
            VALUES ('$page_slug', '$package_name', '$product_count', '$price', '$unit', '$features', '$booking_link')";
    if(mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Package added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Handle Update Package
if(isset($_POST['update_package'])) {
    $id = $_POST['id'];
    $package_name = mysqli_real_escape_string($conn, $_POST['package_name']);
    $product_count = mysqli_real_escape_string($conn, $_POST['product_count']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);
    $features = mysqli_real_escape_string($conn, $_POST['features']);
    $booking_link = mysqli_real_escape_string($conn, $_POST['booking_link']);

    $sql = "UPDATE pricing_packages SET 
            package_name='$package_name', 
            product_count='$product_count', 
            price='$price', 
            unit='$unit', 
            features='$features', 
            booking_link='$booking_link' 
            WHERE id=$id";
            
    if(mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Package updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Handle Delete Package
if(isset($_POST['delete_package'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM pricing_packages WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Package deleted successfully!</div>";
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
$packages = mysqli_query($conn, "SELECT * FROM pricing_packages WHERE page_slug='$selectedPage' ORDER BY id ASC");
?>

<h2>Manage Pricing Packages</h2>

<div class="mb-3">
    <label>Select Page:</label>
    <select class="form-control w-25 d-inline-block" onchange="window.location.href='pricing.php?page='+this.value">
        <?php foreach($pageList as $p) { ?>
            <option value="<?= $p['slug'] ?>" <?= ($selectedPage == $p['slug']) ? 'selected' : '' ?>><?= $p['page_name'] ?></option>
        <?php } ?>
    </select>
    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addPackageModal">Add New Package</button>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Name</th>
            <th>Count</th>
            <th>Price</th>
            <th>Unit</th>
            <th>Features</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(mysqli_num_rows($packages) > 0) {
            while($row = mysqli_fetch_assoc($packages)) { ?>
        <tr>
            <td><?= $row['package_name'] ?></td>
            <td><?= $row['product_count'] ?></td>
            <td><?= $row['price'] ?></td>
            <td><?= $row['unit'] ?></td>
            <td><?= substr($row['features'], 0, 50) ?>...</td>
            <td>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPackageModal<?= $row['id'] ?>">Edit</button>
                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this package?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_package" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editPackageModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Package</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Package Name</label>
                                    <input type="text" name="package_name" class="form-control" value="<?= $row['package_name'] ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Product Count/Subtitle</label>
                                    <input type="text" name="product_count" class="form-control" value="<?= $row['product_count'] ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Price</label>
                                    <input type="text" name="price" class="form-control" value="<?= $row['price'] ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Unit (e.g., Per Product)</label>
                                    <input type="text" name="unit" class="form-control" value="<?= $row['unit'] ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Features (comma or newline separated)</label>
                                    <textarea name="features" class="form-control" rows="3"><?= $row['features'] ?></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Booking Link</label>
                                    <input type="text" name="booking_link" class="form-control" value="<?= $row['booking_link'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_package" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php } } else { echo "<tr><td colspan='6' class='text-center'>No packages found for this page.</td></tr>"; } ?>
    </tbody>
</table>

<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="page_slug" value="<?= $selectedPage ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Package Name</label>
                            <input type="text" name="package_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Product Count/Subtitle</label>
                            <input type="text" name="product_count" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Unit (e.g., Per Product)</label>
                            <input type="text" name="unit" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Features (comma or newline separated)</label>
                            <textarea name="features" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Booking Link</label>
                            <input type="text" name="booking_link" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_package" class="btn btn-primary">Add Package</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>