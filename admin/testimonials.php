<?php include("includes/header.php"); ?>

<?php
// Handle Add Testimonial
if(isset($_POST['add_testimonial'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $review = mysqli_real_escape_string($conn, $_POST['review']);
    $rating = (int)$_POST['rating'];

    $sql = "INSERT INTO testimonials (name, review, rating) VALUES ('$name', '$review', $rating)";
    if(mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Testimonial added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Handle Update Testimonial
if(isset($_POST['update_testimonial'])) {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $review = mysqli_real_escape_string($conn, $_POST['review']);
    $rating = (int)$_POST['rating'];

    $sql = "UPDATE testimonials SET name='$name', review='$review', rating=$rating WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Testimonial updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Handle Delete Testimonial
if(isset($_POST['delete_testimonial'])) {
    $id = $_POST['id'];
    mysqli_query($conn, "DELETE FROM testimonials WHERE id=$id");
    echo "<div class='alert alert-success'>Testimonial deleted successfully!</div>";
}

$testimonials = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Testimonials</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTestimonialModal">Add New Testimonial</button>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Review</th>
            <th>Rating</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(mysqli_num_rows($testimonials) > 0) {
            while($row = mysqli_fetch_assoc($testimonials)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= substr(strip_tags($row['review']), 0, 100) ?>...</td>
            <td><?= $row['rating'] ?> <i class="fas fa-star text-warning"></i></td>
            <td>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTestimonialModal<?= $row['id'] ?>">Edit</button>
                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_testimonial" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editTestimonialModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Testimonial</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="<?= $row['name'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Review</label>
                                <textarea name="review" class="form-control" rows="5" required><?= $row['review'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Rating (1-5)</label>
                                <select name="rating" class="form-control">
                                    <?php for($i=1; $i<=5; $i++) { ?>
                                    <option value="<?= $i ?>" <?= ($row['rating'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_testimonial" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php } } else { echo "<tr><td colspan='5' class='text-center'>No testimonials found.</td></tr>"; } ?>
    </tbody>
</table>

<!-- Add Modal -->
<div class="modal fade" id="addTestimonialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Review</label>
                        <textarea name="review" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Rating (1-5)</label>
                        <select name="rating" class="form-control">
                            <option value="5">5</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_testimonial" class="btn btn-primary">Add Testimonial</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>