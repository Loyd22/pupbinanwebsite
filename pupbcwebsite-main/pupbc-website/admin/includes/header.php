<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$pageTitle = $pageTitle ?? 'Admin Panel';
$currentSection = $currentSection ?? '';
$adminName = $_SESSION['admin_name'] ?? $_SESSION['admin_username'] ?? 'Administrator';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> | PUP Biñan Admin</title>
    <link rel="stylesheet" href="../asset/css/admin.css">
    <script src="../asset/vendors/tinymce/js/tinymce/tinymce.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof tinymce === 'undefined') {
                return;
            }

            tinymce.init({
                selector: 'textarea.js-editor',
                height: 280,
                menubar: false,
                plugins: 'lists link table autoresize',
                toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link table | removeformat',
                branding: false,
                convert_urls: false
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggle = document.getElementById('jsSidebarToggle');
            if (!toggle) return;
            toggle.addEventListener('click', function () {
                document.body.classList.toggle('sidebar-collapsed');
            });
        });
    </script>
</head>
<body>
<div class="admin-shell">
    <aside class="sidebar">
        <div class="sidebar__brand">
            <div class="sidebar__logo" aria-hidden="true">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" focusable="false" aria-hidden="true"><circle cx="6" cy="8" r="3"></circle><circle cx="18" cy="8" r="3"></circle><path d="M3 20c0-3 3-5 7-5h4c4 0 7 2 7 5v1H3v-1z"></path></svg>
            </div>
            <span>PUP Biñan Admin</span>
        </div>
        <nav class="sidebar__nav">
            <a href="dashboard.php" class="sidebar__link <?php echo $currentSection === 'dashboard' ? 'is-active' : ''; ?>">
                <span class="sidebar__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 12l9-9 9 9v9a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1v-9z"></path></svg></span>
                <span>Dashboard</span>
            </a>
            <a href="announcements.php" class="sidebar__link <?php echo $currentSection === 'announcements' ? 'is-active' : ''; ?>">
                <span class="sidebar__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 5h18v4H3zM3 11h12v4H3zM3 17h8v4H3z"></path></svg></span>
                <span>Announcements</span>
            </a>
            <a href="news.php" class="sidebar__link <?php echo $currentSection === 'news' ? 'is-active' : ''; ?>">
                <span class="sidebar__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h16v14a2 2 0 0 1-2 2H4z"></path><path d="M7 8h10M7 12h10M7 16h6" stroke="#fff" stroke-width="2"/></svg></span>
                <span>News</span>
            </a>
            <a href="media.php" class="sidebar__link <?php echo $currentSection === 'media' ? 'is-active' : ''; ?>">
                <span class="sidebar__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14l4-4h12a2 2 0 0 0 2-2z"></path></svg></span>
                <span>Media Library</span>
            </a>
            <a href="settings.php" class="sidebar__link <?php echo $currentSection === 'settings' ? 'is-active' : ''; ?>">
                <span class="sidebar__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 8a4 4 0 1 1 0 8 4 4 0 0 1 0-8zm8.94 4a7.94 7.94 0 0 0-.34-2l2.1-1.64-2-3.46-2.48.5a8.07 8.07 0 0 0-1.74-1l-.38-2.51H9.9l-.38 2.51a8.07 8.07 0 0 0-1.74 1l-2.48-.5-2 3.46L5.4 10a7.94 7.94 0 0 0 0 4l-2.1 1.64 2 3.46 2.48-.5a8.07 8.07 0 0 0 1.74 1l.38 2.51h4.2l.38-2.51a8.07 8.07 0 0 0 1.74-1l2.48.5 2-3.46L20.6 14c.23-.64.34-1.31.34-2z"></path></svg></span>
                <span>Site Settings</span>
            </a>
        </nav>
        <div class="sidebar__spacer"></div>
        <div class="sidebar__footer">
            <a class="sidebar__logout" href="logout.php">Logout</a>
        </div>
    </aside>
    <div class="workspace">
        <header class="topbar">
            <button class="topbar__toggle" id="jsSidebarToggle" aria-label="Toggle navigation">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 6h18v2H3zM3 11h18v2H3zM3 16h18v2H3z"></path></svg>
            </button>
            <div class="topbar__title">
                <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
            </div>
            <div class="topbar__user">
                <span>Signed in as <?php echo htmlspecialchars($adminName); ?></span>
            </div>
        </header>
        <main class="admin-main">
            <?php if ($message = get_flash('success')): ?>
                <div class="alert alert--success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php if ($message = get_flash('error')): ?>
                <div class="alert alert--error"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
