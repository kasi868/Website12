<?php include("includes/header.php"); ?>

<?php
$msg = "";
$error = "";

// Handle Add New Admin
if (isset($_POST['add_admin'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($password)) {
        $error = "Username and Password are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $check = mysqli_query($conn, "SELECT id FROM admins WHERE username='$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO admins (username, password) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($conn, $sql)) {
                $msg = "New admin added successfully!";
            } else {
                $error = "Error adding admin: " . mysqli_error($conn);
            }
        }
    }
}

// Handle Delete Admin
if (isset($_POST['delete_admin'])) {
    $admin_id = (int)$_POST['admin_id'];
    
    // Prevent deleting the currently logged-in admin
    if ($admin_id == $_SESSION['admin_id']) {
        $error = "You cannot delete your own account while logged in.";
    } else {
        // Check how many admins are left
        $count_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM admins");
        $count_data = mysqli_fetch_assoc($count_res);
        
        if ($count_data['total'] <= 1) {
            $error = "At least one admin must exist.";
        } else {
            if (mysqli_query($conn, "DELETE FROM admins WHERE id=$admin_id")) {
                $msg = "Admin deleted successfully!";
            } else {
                $error = "Error deleting admin: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch all admins
$admins = mysqli_query($conn, "SELECT id, username FROM admins ORDER BY id DESC");
?>

<h2>Manage Admins</h2>

<?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
<?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Add New Admin</div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" name="add_admin" class="btn btn-primary">Add Admin</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Existing Admins</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($admins)) { ?>
                        <tr>
                            <td><?= $row['username'] ?></td>
                            <td>
                                <?php if ($row['id'] != $_SESSION['admin_id']) { ?>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                    <input type="hidden" name="admin_id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="delete_admin" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                <?php } else { ?>
                                <span class="badge bg-info">Current User</span>
                                <?php } ?>
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
