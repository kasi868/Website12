<?php include("includes/header.php"); ?>

<?php
if (isset($_POST['delete_message'])) {
    $id = (int) $_POST['id'];
    if (mysqli_query($conn, "DELETE FROM messages WHERE id=$id")) {
        echo "<div class='alert alert-success'>Message deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

$messages = mysqli_query($conn, "SELECT * FROM messages ORDER BY created_at DESC, id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Contact Messages</h2>
</div>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($messages && mysqli_num_rows($messages) > 0) {
            while ($row = mysqli_fetch_assoc($messages)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td style="max-width: 320px; white-space: normal;"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                    <td><?= date('d M Y h:i A', strtotime($row['created_at'])) ?></td>
                    <td>
                        <form method="post" onsubmit="return confirm('Delete this message?');">
                            <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                            <button type="submit" name="delete_message" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php }
        } else {
            echo "<tr><td colspan='7' class='text-center'>No messages found.</td></tr>";
        } ?>
    </tbody>
</table>

<?php include("includes/footer.php"); ?>
