<?php include("includes/header.php"); ?>

<?php
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_settings'])) {
        $siteBaseUrl = trim($_POST['site_base_url']);
        $robotsContent = trim($_POST['robots_txt_content']);

        cms_set_setting($conn, 'site_base_url', $siteBaseUrl);
        cms_set_setting($conn, 'robots_txt_content', $robotsContent);
        cms_generate_sitemap($conn);
        cms_write_robots_txt($conn);
        $message = 'Settings saved, sitemap.xml regenerated, and robots.txt updated.';
    }

    if (isset($_POST['save_social_link'])) {
        $id = (int) value($_POST, 'id', 0);
        $label = mysqli_real_escape_string($conn, trim($_POST['label']));
        $iconClass = mysqli_real_escape_string($conn, trim($_POST['icon_class']));
        $url = mysqli_real_escape_string($conn, trim($_POST['url']));
        $sortOrder = (int) value($_POST, 'sort_order', 0);
        $openNewTab = isset($_POST['open_new_tab']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($label === '' || $url === '') {
            $error = 'Label and URL are required for social links.';
        } else {
            if ($id > 0) {
                mysqli_query($conn, "UPDATE social_links SET
                    label='$label',
                    icon_class='$iconClass',
                    url='$url',
                    sort_order=$sortOrder,
                    open_new_tab=$openNewTab,
                    is_active=$isActive
                    WHERE id=$id");
                $message = 'Social link updated successfully.';
            } else {
                mysqli_query($conn, "INSERT INTO social_links
                    (label, icon_class, url, sort_order, open_new_tab, is_active)
                    VALUES
                    ('$label', '$iconClass', '$url', $sortOrder, $openNewTab, $isActive)");
                $message = 'Social link added successfully.';
            }
        }
    }

    if (isset($_POST['delete_social_link'])) {
        $id = (int) $_POST['id'];
        mysqli_query($conn, "DELETE FROM social_links WHERE id=$id");
        $message = 'Social link deleted successfully.';
    }

    if (isset($_POST['regenerate_sitemap'])) {
        cms_generate_sitemap($conn);
        $message = 'sitemap.xml regenerated successfully.';
    }

    if (isset($_POST['update_robots'])) {
        cms_write_robots_txt($conn);
        $message = 'robots.txt updated successfully.';
    }
}

$settings = cms_get_settings($conn);
$socialLinks = cms_get_social_links($conn);
$editSocialLink = null;

if (isset($_GET['edit_social'])) {
    $editId = (int) $_GET['edit_social'];
    $socialResult = mysqli_query($conn, "SELECT * FROM social_links WHERE id=$editId");
    if ($socialResult) {
        $editSocialLink = mysqli_fetch_assoc($socialResult);
    }
}
?>

<h2>SEO & Settings</h2>

<?php if ($message) { ?>
    <div class="alert alert-success"><?= cms_e($message) ?></div>
<?php } ?>

<?php if ($error) { ?>
    <div class="alert alert-danger"><?= cms_e($error) ?></div>
<?php } ?>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">Site Settings</div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label>Site Base URL</label>
                        <input type="text" name="site_base_url" class="form-control" placeholder="https://conceptphotography.co.in" value="<?= cms_e(value($settings, 'site_base_url')) ?>">
                        <small class="text-muted">Used for canonical tags, sitemap URLs, and robots sitemap links.</small>
                    </div>
                    <div class="mb-3">
                        <label>robots.txt Content</label>
                        <textarea name="robots_txt_content" class="form-control" rows="10"><?= cms_e(value($settings, 'robots_txt_content', "User-agent: *\nAllow: /\n\nSitemap: " . cms_absolute_url('sitemap.xml', $conn))) ?></textarea>
                    </div>
                    <button type="submit" name="save_settings" class="btn btn-primary">Save Settings</button>
                    <button type="submit" name="regenerate_sitemap" class="btn btn-outline-success">Regenerate Sitemap</button>
                    <button type="submit" name="update_robots" class="btn btn-outline-secondary">Write robots.txt</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header"><?= $editSocialLink ? 'Edit Social Link' : 'Add Social Link' ?></div>
            <div class="card-body">
                <form method="post">
                    <?php if ($editSocialLink) { ?>
                        <input type="hidden" name="id" value="<?= (int) $editSocialLink['id'] ?>">
                    <?php } ?>
                    <div class="mb-3">
                        <label>Label</label>
                        <input type="text" name="label" class="form-control" value="<?= cms_e(value($editSocialLink, 'label')) ?>" placeholder="Instagram">
                    </div>
                    <div class="mb-3">
                        <label>Icon Class</label>
                        <input type="text" name="icon_class" class="form-control" value="<?= cms_e(value($editSocialLink, 'icon_class')) ?>" placeholder="bx bxl-instagram or fab fa-instagram">
                    </div>
                    <div class="mb-3">
                        <label>URL</label>
                        <input type="text" name="url" class="form-control" value="<?= cms_e(value($editSocialLink, 'url')) ?>" placeholder="https://www.instagram.com/yourprofile/">
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="<?= cms_e(value($editSocialLink, 'sort_order', 0)) ?>">
                        </div>
                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="open_new_tab" class="form-check-input" id="open_new_tab" <?= value($editSocialLink, 'open_new_tab', 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="open_new_tab">Open in new tab</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" <?= value($editSocialLink, 'is_active', 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="save_social_link" class="btn btn-primary"><?= $editSocialLink ? 'Update Social Link' : 'Add Social Link' ?></button>
                    <?php if ($editSocialLink) { ?>
                        <a href="settings.php" class="btn btn-secondary">Cancel</a>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Existing Social Links</div>
    <div class="card-body">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Label</th>
                    <th>Icon Class</th>
                    <th>URL</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($socialLinks)) { ?>
                    <?php foreach ($socialLinks as $link) { ?>
                        <tr>
                            <td><?= cms_e($link['label']) ?></td>
                            <td><code><?= cms_e($link['icon_class']) ?></code></td>
                            <td><?= cms_e($link['url']) ?></td>
                            <td><?= (int) $link['sort_order'] ?></td>
                            <td><?= (int) $link['is_active'] === 1 ? 'Active' : 'Inactive' ?></td>
                            <td>
                                <a href="settings.php?edit_social=<?= (int) $link['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                <form method="post" class="d-inline" onsubmit="return confirm('Delete this social link?');">
                                    <input type="hidden" name="id" value="<?= (int) $link['id'] ?>">
                                    <button type="submit" name="delete_social_link" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6" class="text-center">No social links added yet.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("includes/footer.php"); ?>
