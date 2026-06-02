<?php include("includes/header.php"); ?>
    <h2>Dashboard</h2>
    <p>Welcome, <?= $_SESSION['admin_username'] ?>!</p>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pages</h5>
                    <p class="card-text">Manage website pages.</p>
                    <a href="pages.php" class="btn btn-light">Go to Pages</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Sections</h5>
                    <p class="card-text">Manage page sections.</p>
                    <a href="sections.php" class="btn btn-light">Go to Sections</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Gallery</h5>
                    <p class="card-text">Manage gallery images.</p>
                    <a href="gallery.php" class="btn btn-light">Go to Gallery</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Messages</h5>
                    <p class="card-text">View contact form leads.</p>
                    <a href="messages.php" class="btn btn-light">Go to Messages</a>
                </div>
            </div>
        </div>
    </div>
<?php include("includes/footer.php"); ?>
