<?php include("includes/header.php"); ?>

<?php
if (isset($_POST['add_faq'])) {
    $page_slug = mysqli_real_escape_string($conn, $_POST['page_slug']);
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $answer = mysqli_real_escape_string($conn, $_POST['answer']);
    $position = (int) $_POST['position'];

    $sql = "INSERT INTO faqs (page_slug, question, answer, position) VALUES ('$page_slug', '$question', '$answer', $position)";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>FAQ added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

if (isset($_POST['update_faq'])) {
    $id = (int) $_POST['id'];
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $answer = mysqli_real_escape_string($conn, $_POST['answer']);
    $position = (int) $_POST['position'];

    $sql = "UPDATE faqs SET question='$question', answer='$answer', position=$position WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>FAQ updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

if (isset($_POST['delete_faq'])) {
    $id = (int) $_POST['id'];
    $sql = "DELETE FROM faqs WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>FAQ deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

$pages = mysqli_query($conn, "SELECT * FROM pages ORDER BY id ASC");
$pageList = [];
while ($p = mysqli_fetch_assoc($pages)) {
    $pageList[] = $p;
}

$selectedPage = isset($_GET['page']) ? $_GET['page'] : (count($pageList) > 0 ? $pageList[0]['slug'] : '');
$faqs = mysqli_query($conn, "SELECT * FROM faqs WHERE page_slug='$selectedPage' ORDER BY position ASC, id ASC");
?>

<h2>Manage FAQs</h2>

<div class="mb-3">
    <label>Select Page:</label>
    <select class="form-control w-25 d-inline-block" onchange="window.location.href='faqs.php?page='+this.value">
        <?php foreach($pageList as $p) { ?>
            <option value="<?= $p['slug'] ?>" <?= ($selectedPage == $p['slug']) ? 'selected' : '' ?>><?= $p['page_name'] ?></option>
        <?php } ?>
    </select>
    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addFAQModal">Add New FAQ</button>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Question</th>
            <th>Answer / Detail</th>
            <th>Position</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(mysqli_num_rows($faqs) > 0) {
            while($row = mysqli_fetch_assoc($faqs)) { ?>
        <tr>
            <td><?= $row['question'] ?></td>
            <td><?= substr(strip_tags($row['answer']), 0, 80) ?>...</td>
            <td><?= (int) $row['position'] ?></td>
            <td>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editFAQModal<?= $row['id'] ?>">Edit</button>
                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_faq" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>

        <div class="modal fade" id="editFAQModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit FAQ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="mb-3">
                                <label>Question</label>
                                <input type="text" name="question" class="form-control" value="<?= $row['question'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Answer / Detail</label>
                                <textarea name="answer" class="form-control tinymce" rows="5"><?= $row['answer'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Position</label>
                                <input type="number" name="position" class="form-control" value="<?= (int) $row['position'] ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_faq" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php } } else { echo "<tr><td colspan='4' class='text-center'>No FAQs found for this page.</td></tr>"; } ?>
    </tbody>
</table>

<div class="modal fade" id="addFAQModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add New FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="page_slug" value="<?= $selectedPage ?>">
                    <div class="mb-3">
                        <label>Question</label>
                        <input type="text" name="question" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Answer / Detail</label>
                        <textarea name="answer" class="form-control tinymce" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Position</label>
                        <input type="number" name="position" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_faq" class="btn btn-primary">Add FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
