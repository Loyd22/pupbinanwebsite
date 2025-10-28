<?php
declare(strict_types=1);

$pageTitle = 'Announcements';
$currentSection = 'announcements';

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/auth.php';

$currentAdminId = get_current_admin_id($conn);
$editing = null;

// Load for edit
if (isset($_GET['id'])) {
    $announcementId = (int)$_GET['id'];
    $editing = fetch_announcement_item($conn, $announcementId);
    if (!$editing) {
        add_flash('error', 'Announcement not found.');
        header('Location: announcements.php');
        exit;
    }
}

function fetch_announcement_item(mysqli $conn, int $id): ?array {
    $stmt = $conn->prepare("SELECT * FROM announcements WHERE id = ?");
    if (!$stmt) return null;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row ?: null;
}

// Delete
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $deleteId);
        $stmt->execute();
        $stmt->close();
        add_flash('success', 'Announcement deleted.');
    }
    header('Location: announcements.php');
    exit;
}

// Create/Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $publishDate = trim($_POST['publish_date'] ?? '');
    $ctaLabel = trim($_POST['cta_label'] ?? '');
    $ctaUrl = trim($_POST['cta_url'] ?? '');
    $isPublished = isset($_POST['is_published']) ? 1 : 0;

    if ($title === '' || $body === '') {
        add_flash('error', 'Title and body are required.');
        header('Location: announcements.php' . ($id ? '?id='.$id : ''));
        exit;
    }

    // Normalize/validate publish date
    $publishDate = ($publishDate !== '') ? $publishDate : null;
    if ($publishDate !== null) {
        $d = DateTime::createFromFormat('Y-m-d', $publishDate);
        if (!$d || $d->format('Y-m-d') !== $publishDate) {
            add_flash('error', 'Publish date must be YYYY-MM-DD.');
            header('Location: announcements.php' . ($id ? '?id='.$id : ''));
            exit;
        }
    }

    $stmt = null;
    try {
        if ($id) {
            // UPDATE
            $sql = "UPDATE announcements 
                    SET title = ?, body = ?, category = ?, publish_date = ?, cta_label = ?, cta_url = ?, is_published = ?, updated_at = NOW()
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                'sssssssi',
                $title,
                $body,
                $category,
                $publishDate,
                $ctaLabel,
                $ctaUrl,
                $isPublished,
                $id
            );
            $stmt->execute();
            add_flash('success', 'Announcement updated.');
        } else {
            // INSERT
            if ($currentAdminId !== null) {
                $sql = "INSERT INTO announcements (title, body, category, publish_date, cta_label, cta_url, is_published, created_by)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    'sssssssi',
                    $title,
                    $body,
                    $category,
                    $publishDate,
                    $ctaLabel,
                    $ctaUrl,
                    $isPublished,
                    $currentAdminId
                );
            } else {
                $sql = "INSERT INTO announcements (title, body, category, publish_date, cta_label, cta_url, is_published)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    'sssssss',
                    $title,
                    $body,
                    $category,
                    $publishDate,
                    $ctaLabel,
                    $ctaUrl,
                    $isPublished
                );
            }
            $stmt->execute();
            add_flash('success', 'Announcement created.');
        }
    } catch (mysqli_sql_exception $e) {
        if ($stmt instanceof mysqli_stmt) $stmt->close();
        add_flash('error', 'Unable to save the announcement. Please review the form and try again.');
        header('Location: announcements.php' . ($id ? '?id='.$id : ''));
        exit;
    }

    if ($stmt instanceof mysqli_stmt) $stmt->close();
    header('Location: announcements.php');
    exit;
}

// List items
$announcementItems = $conn->query("
    SELECT id, title, category, publish_date, is_published
    FROM announcements
    ORDER BY COALESCE(publish_date, created_at, NOW()) DESC
");

require_once __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h2><?php echo $editing ? 'Edit Announcement' : 'New Announcement'; ?></h2>
    <form method="post" action="announcements.php" class="form">
        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?php echo (int)$editing['id']; ?>">
        <?php endif; ?>

        <label for="title">Title</label>
        <input type="text" id="title" name="title"
               value="<?php echo htmlspecialchars($editing['title'] ?? '', ENT_QUOTES); ?>" required>

        <label for="body">Body</label>
        <textarea class="js-editor" id="body" name="body" rows="6"><?php
            echo htmlspecialchars($editing['body'] ?? '', ENT_QUOTES); ?></textarea>

        <label for="category">Category</label>
        <input type="text" id="category" name="category"
               value="<?php echo htmlspecialchars($editing['category'] ?? '', ENT_QUOTES); ?>"
               placeholder="e.g., Registrar, Student Affairs, Administration">

        <label for="publish_date">Publish Date</label>
        <input type="date" id="publish_date" name="publish_date"
               value="<?php echo htmlspecialchars($editing['publish_date'] ?? '', ENT_QUOTES); ?>">

        <div class="form__group--inline">
            <div>
                <label for="cta_label">Call-to-Action Label</label>
                <input type="text" id="cta_label" name="cta_label"
                       value="<?php echo htmlspecialchars($editing['cta_label'] ?? '', ENT_QUOTES); ?>"
                       placeholder="e.g., View requirements, Apply now">
            </div>
            <div>
                <label for="cta_url">Call-to-Action URL</label>
                <input type="url" id="cta_url" name="cta_url"
                       value="<?php echo htmlspecialchars($editing['cta_url'] ?? '', ENT_QUOTES); ?>"
                       placeholder="https://example.com">
            </div>
        </div>

        <label class="checkbox">
            <input type="checkbox" name="is_published" <?php
                echo isset($editing['is_published'])
                    ? ($editing['is_published'] ? 'checked' : '')
                    : 'checked'; ?>>
            <span>Published</span>
        </label>

        <button type="submit" class="btn btn--primary">
            <?php echo $editing ? 'Update Announcement' : 'Create Announcement'; ?>
        </button>
        <?php if ($editing): ?>
            <a class="btn btn--secondary" href="announcements.php">Cancel</a>
        <?php endif; ?>
    </form>
</section>

<section class="card">
    <h2>Existing Announcements</h2>
    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Publish Date</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if ($announcementItems && $announcementItems->num_rows > 0): ?>
                <?php while ($row = $announcementItems->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($row['category'] ?: '—', ENT_QUOTES); ?></td>
                        <td><?php echo $row['publish_date']
                                ? htmlspecialchars(date('M j, Y', strtotime($row['publish_date'])), ENT_QUOTES)
                                : '—'; ?></td>
                        <td><?php echo $row['is_published'] ? 'Published' : 'Draft'; ?></td>
                        <td class="table-actions">
                            <a class="btn btn--small" href="announcements.php?id=<?php echo (int)$row['id']; ?>">Edit</a>
                            <a class="btn btn--small btn--danger"
                               href="announcements.php?delete=<?php echo (int)$row['id']; ?>"
                               onclick="return confirm('Delete this announcement?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No announcements created yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Force WYSIWYG editors to sync value back to textareas before submit -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form.form');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    // TinyMCE
    if (window.tinymce && typeof tinymce.triggerSave === 'function') {
      tinymce.triggerSave();
    }
    // Quill (commonly stored on textarea._quill)
    document.querySelectorAll('textarea.js-editor').forEach(function (ta) {
      if (ta._quill && ta._quill.root) {
        ta.value = ta._quill.root.innerHTML.trim();
      }
    });
    // Trix: <trix-editor input="id"> → mirror back
    document.querySelectorAll('trix-editor[input]').forEach(function (ed) {
      const id = ed.getAttribute('input');
      const hidden = document.getElementById(id);
      if (hidden) hidden.value = ed.innerHTML.trim();
    });

    // Validate required fields
    const title = document.getElementById('title');
    const body = document.getElementById('body');
    
    if (!title.value.trim()) {
      e.preventDefault();
      alert('Title is required.');
      title.focus();
      return false;
    }
    
    if (!body.value.trim()) {
      e.preventDefault();
      alert('Body is required.');
      body.focus();
      return false;
    }
  });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php';
