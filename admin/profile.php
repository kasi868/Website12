<?php include("includes/header.php"); ?>

<?php
$id = $_SESSION['admin_id'];
$msg = "";
$error = "";

if (isset($_POST['update_profile'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($username)) {
        $error = "Username cannot be empty.";
    } else {
        // Prepare SQL for update
        $sql_query = "UPDATE admins SET username='$username'";

        // Password update if provided
        if (!empty($password)) {
            if ($password !== $confirm_password) {
                $error = "Passwords do not match.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql_query .= ", password='$hashed_password'";
            }
        }

        if (empty($error)) {
            $sql_query .= " WHERE id=$id";
            if (mysqli_query($conn, $sql_query)) {
                $msg = "Profile updated successfully!";
                $_SESSION['admin_username'] = $username; // Update session
            } else {
                $error = "Error updating profile: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch current details
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admins WHERE id=$id"));
?>

<h2>Admin Profile</h2>

<?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
<?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?= $admin['username'] ?>" required>
            </div>
            <hr>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password">
            </div>
            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>

<?php include("includes/footer.php"); ?>
