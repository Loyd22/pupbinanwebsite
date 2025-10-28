<?php
declare(strict_types=1);

$pageTitle = 'Media Library';
$currentSection = 'media';
require_once __DIR__ . '/includes/header.php';

if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM media_library WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $deleteId);
        $stmt->execute();
        $stmt->close();
        add_flash('success', 'Media item removed.');
    }
    header('Location: media.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'image') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($title === '') {
            add_flash('error', 'Image title is required.');
            header('Location: media.php');
            exit;
        }

        $imageUpload = handle_file_upload('image_upload', 'images/uploads', ['png', 'jpg', 'jpeg', 'webp']);
        if (isset($imageUpload['error'])) {
            add_flash('error', $imageUpload['error']);
            header('Location: media.php');
            exit;
        }

        if (!isset($imageUpload['path'])) {
            add_flash('error', 'Please choose an image to upload.');
            header('Location: media.php');
            exit;
        }

        $sql = "INSERT INTO media_library (title, description, file_path, media_type, uploaded_by)
                VALUES (?, ?, ?, 'image', ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $uploadedBy = (int)($_SESSION['admin_id'] ?? 0);
            $stmt->bind_param('sssi', $title, $description, $imageUpload['path'], $uploadedBy);
            $stmt->execute();
            $stmt->close();
            add_flash('success', 'Image uploaded.');
        }

        header('Location: media.php');
        exit;
    }

    if ($action === 'video') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $videoUrl = trim($_POST['video_url'] ?? '');

        if ($title === '' || $videoUrl === '') {
            add_flash('error', 'Video title and URL are required.');
            header('Location: media.php');
            exit;
        }

        $sql = "INSERT INTO media_library (title, description, media_type, video_url, uploaded_by)
                VALUES (?, ?, 'video', ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $uploadedBy = (int)($_SESSION['admin_id'] ?? 0);
            $stmt->bind_param('sssi', $title, $description, $videoUrl, $uploadedBy);
            $stmt->execute();
            $stmt->close();
            add_flash('success', 'Video added.');
        }

        header('Location: media.php');
        exit;
    }
}

$mediaItems = $conn->query("SELECT * FROM media_library ORDER BY uploaded_at DESC");
?>

<section class="card">
    <h2>Upload Image</h2>
    <form method="post" enctype="multipart/form-data" class="form">
        <input type="hidden" name="action" value="image">

        <label for="image_title">Title</label>
        <input type="text" id="image_title" name="title" required>

        <label for="image_description">Description</label>
        <textarea class="js-editor" id="image_description" name="description" rows="3"></textarea>

        <label for="image_upload">Select Image</label>
        <input type="file" id="image_upload" name="image_upload" accept=".png,.jpg,.jpeg,.webp" required>

        <button type="submit" class="btn btn--primary">Upload Image</button>
    </form>
</section>

<section class="card">
    <h2>Add Video</h2>
    <form method="post" class="form">
        <input type="hidden" name="action" value="video">

        <label for="video_title">Title</label>
        <input type="text" id="video_title" name="title" required>

        <label for="video_url">Video URL (YouTube, Vimeo, etc.)</label>
        <input type="url" id="video_url" name="video_url" placeholder="https://www.youtube.com/watch?v=..." required>

        <label for="video_description">Description</label>
        <textarea class="js-editor" id="video_description" name="description" rows="3"></textarea>

        <button type="submit" class="btn btn--primary">Add Video</button>
    </form>
</section>

<section class="card">
    <h2>Media Library</h2>
    <div class="media-grid">
        <?php if ($mediaItems && $mediaItems->num_rows > 0): ?>
            <?php while ($item = $mediaItems->fetch_assoc()): ?>
                <article class="media-card">
                    <header>
                        <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                        <span class="media-card__type"><?php echo ucfirst($item['media_type']); ?></span>
                    </header>
                    <div class="media-card__body">
                        <?php if ($item['media_type'] === 'image' && $item['file_path']): ?>
                            <img src="../<?php echo htmlspecialchars($item['file_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <?php elseif ($item['media_type'] === 'video' && $item['video_url']): ?>
                            <a href="<?php echo htmlspecialchars($item['video_url']); ?>" target="_blank" rel="noopener">View video</a>
                        <?php else: ?>
                            <span>No preview available.</span>
                        <?php endif; ?>
                        <?php if (!empty($item['description'])): ?>
                            <p><?php echo htmlspecialchars($item['description']); ?></p>
                        <?php endif; ?>
                    </div>
                    <footer>
                        <small>Uploaded <?php echo htmlspecialchars(date('M j, Y', strtotime($item['uploaded_at']))); ?></small>
                        <a class="btn btn--small btn--danger" href="media.php?delete=<?php echo (int)$item['id']; ?>" onclick="return confirm('Remove this media item?');">Delete</a>
                    </footer>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No media uploaded yet.</p>
        <?php endif; ?>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
