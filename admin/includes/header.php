<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
include("../config/db.php");
include("../config/functions.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Summernote CSS (Free & Open Source WYSIWYG Editor) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <style>
        body { display: flex; min-height: 100vh; flex-direction: column; }
        .wrapper { display: flex; flex: 1; }
        .sidebar { width: 260px; background: #2c3e50; color: white; min-height: 100vh; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar h4 { background: #1a252f; margin: 0; padding: 20px; }
        .sidebar a { color: #bdc3c7; text-decoration: none; display: block; padding: 14px 24px; font-size: 15px; border-left: 4px solid transparent; transition: all 0.3s; }
        .sidebar a:hover { background: #34495e; color: white; border-left-color: #3498db; }
        .sidebar a.active { background: #34495e; color: white; border-left-color: #3498db; }
        .content { flex: 1; padding: 30px; background: #ecf0f1; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-radius: 8px; }
        .btn-primary { background: #3498db; border-color: #3498db; }
        .btn-primary:hover { background: #2980b9; border-color: #2980b9; }
    </style>
    
    <script>
        $(document).ready(function() {
            // Initialize Summernote on all textareas with class 'summernote'
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <h4 class="text-center border-bottom">RIO AD Agency</h4>
            <a href="dashboard.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : '' ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="pages.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'pages.php') ? 'active' : '' ?>"><i class="fas fa-file-alt me-2"></i> Pages</a>
            <a href="sections.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'sections.php') ? 'active' : '' ?>"><i class="fas fa-layer-group me-2"></i> Sections</a>
            <a href="services.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'services.php') ? 'active' : '' ?>"><i class="fas fa-briefcase me-2"></i> Services</a>
            <a href="blogs.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'blogs.php') ? 'active' : '' ?>"><i class="fas fa-blog me-2"></i> Blogs</a>
            <a href="gallery.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'gallery.php') ? 'active' : '' ?>"><i class="fas fa-images me-2"></i> Gallery</a>
            <a href="testimonials.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'testimonials.php') ? 'active' : '' ?>"><i class="fas fa-star me-2"></i> Testimonials</a>
            <a href="faqs.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'faqs.php') ? 'active' : '' ?>"><i class="fas fa-question-circle me-2"></i> FAQs</a>
            <a href="settings.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : '' ?>"><i class="fas fa-cog me-2"></i> SEO & Settings</a>
            <a href="messages.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'messages.php') ? 'active' : '' ?>"><i class="fas fa-envelope me-2"></i> Contact Messages</a>
            <a href="manage-admins.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'manage-admins.php') ? 'active' : '' ?>"><i class="fas fa-users-cog me-2"></i> Manage Admins</a>
            <a href="profile.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : '' ?>"><i class="fas fa-user-cog me-2"></i> Profile</a>
            <a href="logout.php" class="text-danger mt-4" style="border-top:1px solid #34495e;"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>
        <div class="content">
