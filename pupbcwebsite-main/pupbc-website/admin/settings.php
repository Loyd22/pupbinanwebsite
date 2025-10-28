<?php
declare(strict_types=1);

$pageTitle = 'Site Settings';
$currentSection = 'settings';
require_once __DIR__ . '/includes/header.php';

$settingKeys = [
    'site_title',
    'campus_name',
    'hero_heading',
    'hero_text',
    'logo_path',
    'hero_image_path',
    'footer_about',
    'footer_address',
    'footer_email',
    'footer_phone'
];

$settings = array_merge(
    array_fill_keys($settingKeys, ''),
    get_settings($conn, $settingKeys)
);

function ensureSocialLinksTable(mysqli $conn): bool
{
    $createSql = "CREATE TABLE IF NOT EXISTS social_links (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        label VARCHAR(100) NOT NULL,
        url VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY uniq_social_label (label)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    return (bool)$conn->query($createSql);
}

$hasSocialTable = ensureSocialLinksTable($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'identity') {
        $identityData = [
            'site_title'   => trim($_POST['site_title'] ?? ''),
            'campus_name'  => trim($_POST['campus_name'] ?? ''),
            'hero_heading' => trim($_POST['hero_heading'] ?? ''),
            'hero_text'    => trim($_POST['hero_text'] ?? '')
        ];

        $logoUpload = handle_file_upload('logo_upload', 'images/uploads', ['png', 'jpg', 'jpeg', 'svg']);
        if (isset($logoUpload['error'])) {
            add_flash('error', $logoUpload['error']);
            header('Location: settings.php');
            exit;
        }

        if (isset($logoUpload['path'])) {
            $identityData['logo_path'] = $logoUpload['path'];
        }

        $heroUpload = handle_file_upload('hero_image_upload', 'images/uploads', ['png', 'jpg', 'jpeg', 'webp']);
        if (isset($heroUpload['error'])) {
            add_flash('error', $heroUpload['error']);
            header('Location: settings.php');
            exit;
        }

        if (isset($heroUpload['path'])) {
            $identityData['hero_image_path'] = $heroUpload['path'];
        }

        if (save_settings($conn, $identityData)) {
            add_flash('success', 'Site identity updated successfully.');
        } else {
            add_flash('error', 'Unable to update site identity.');
        }

        header('Location: settings.php');
        exit;
    } elseif ($action === 'footer') {
        $footerData = [
            'footer_about'    => trim($_POST['footer_about'] ?? ''),
            'footer_address'  => trim($_POST['footer_address'] ?? ''),
            'footer_email'    => trim($_POST['footer_email'] ?? ''),
            'footer_phone'    => trim($_POST['footer_phone'] ?? '')
        ];

        if (save_settings($conn, $footerData)) {
            add_flash('success', 'Footer details updated successfully.');
        } else {
            add_flash('error', 'Unable to update footer details.');
        }

        header('Location: settings.php');
        exit;
    } elseif ($action === 'social_add') {
        if (!$hasSocialTable) {
            add_flash('error', 'Social links could not be saved because the table is missing.');
            header('Location: settings.php');
            exit;
        }

        $label = trim($_POST['social_label'] ?? '');
        $url = trim($_POST['social_url'] ?? '');

        if ($label === '' || $url === '') {
            add_flash('error', 'Please provide both a label and a URL for the social link.');
        } elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
            add_flash('error', 'Please provide a valid URL for the social link.');
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO social_links (label, url) VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE url = VALUES(url), updated_at = CURRENT_TIMESTAMP"
            );

            if ($stmt) {
                $stmt->bind_param('ss', $label, $url);
                if ($stmt->execute()) {
                    add_flash('success', 'Social link saved.');
                } else {
                    add_flash('error', 'Unable to save the social link.');
                }
                $stmt->close();
            } else {
                add_flash('error', 'Unable to prepare the social link query.');
            }
        }

        header('Location: settings.php');
        exit;
    } elseif ($action === 'social_delete') {
        if (!$hasSocialTable) {
            add_flash('error', 'Social links table is missing.');
            header('Location: settings.php');
            exit;
        }

        $id = isset($_POST['social_id']) ? (int)$_POST['social_id'] : 0;

        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM social_links WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    add_flash('success', 'Social link removed.');
                } else {
                    add_flash('error', 'Unable to remove the social link.');
                }
                $stmt->close();
            } else {
                add_flash('error', 'Unable to prepare the remove link query.');
            }
        } else {
            add_flash('error', 'Invalid social link selected.');
        }

        header('Location: settings.php');
        exit;
    }
}

$logoPath = $settings['logo_path'] ?: 'images/PUPLogo.png';
$heroImagePath = $settings['hero_image_path'];

$socialLinks = [];
if ($hasSocialTable && ($result = $conn->query("SELECT id, label, url FROM social_links ORDER BY created_at ASC, id ASC"))) {
    while ($row = $result->fetch_assoc()) {
        $socialLinks[] = [
            'id' => (int)$row['id'],
            'label' => (string)$row['label'],
            'url' => (string)$row['url']
        ];
    }
    $result->free();
}
?>

<section class="card">
    <h2>Site Identity</h2>
    <form method="post" enctype="multipart/form-data" class="form">
        <input type="hidden" name="action" value="identity">
        <label for="site_title">University / Site Title</label>
        <input type="text" id="site_title" name="site_title" value="<?php echo htmlspecialchars($settings['site_title']); ?>" required>

        <label for="campus_name">Campus Name</label>
        <input type="text" id="campus_name" name="campus_name" value="<?php echo htmlspecialchars($settings['campus_name']); ?>" required>

        <label for="hero_heading">Hero Heading</label>
        <input type="text" id="hero_heading" name="hero_heading" value="<?php echo htmlspecialchars($settings['hero_heading']); ?>" required>

        <label for="hero_text">Hero Description</label>
        <textarea class="js-editor" id="hero_text" name="hero_text" rows="4" required><?php echo htmlspecialchars($settings['hero_text']); ?></textarea>

        <div class="form__group--inline">
            <div>
                <label>Current Logo</label>
                <div class="media-preview">
                    <img src="../<?php echo htmlspecialchars($logoPath); ?>" alt="Current logo">
                </div>
            </div>
            <div>
                <label for="logo_upload">Upload New Logo</label>
                <input type="file" id="logo_upload" name="logo_upload" accept=".png,.jpg,.jpeg,.svg">
            </div>
        </div>

        <div class="form__group--inline">
            <div>
                <label>Hero Image (optional)</label>
                <div class="media-preview">
                    <?php if ($heroImagePath): ?>
                        <img src="../<?php echo htmlspecialchars($heroImagePath); ?>" alt="Hero background">
                    <?php else: ?>
                        <span>No hero image uploaded.</span>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <label for="hero_image_upload">Upload Hero Image</label>
                <input type="file" id="hero_image_upload" name="hero_image_upload" accept=".png,.jpg,.jpeg,.webp">
            </div>
        </div>

        <button type="submit" class="btn btn--primary">Save Identity</button>
    </form>
</section>

<section class="card">
    <h2>Footer Details</h2>
    <form method="post" class="form">
        <input type="hidden" name="action" value="footer">
        <label for="footer_about">About Text</label>
       <textarea class="js-editor" id="footer_about" name="footer_about" rows="4" required>
    <?php echo $settings['footer_about']; ?>
</textarea>


        <label for="footer_address">Address</label>
        <textarea class="js-editor" id="footer_address" name="footer_address" rows="3" required>
    <?php echo $settings['footer_address']; ?>
</textarea>


        <label for="footer_email">Email</label>
        <input type="email" id="footer_email" name="footer_email" value="<?php echo htmlspecialchars($settings['footer_email']); ?>" required>

        <label for="footer_phone">Phone</label>
        <input type="text" id="footer_phone" name="footer_phone" value="<?php echo htmlspecialchars($settings['footer_phone']); ?>" required>

        <button type="submit" class="btn btn--primary">Save Footer</button>
    </form>
</section>

<section class="card">
    <h2>Social Links</h2>
    <form method="post" class="form form--inline">
        <input type="hidden" name="action" value="social_add">
        <label for="social_label">Social Label</label>
        <input type="text" id="social_label" name="social_label" placeholder="e.g., Facebook" required>

        <label for="social_url">Profile URL</label>
        <input type="url" id="social_url" name="social_url" placeholder="https://example.com/your-page" required>

        <button type="submit" class="btn btn--primary">Add Social Link</button>
    </form>

    <div class="table-responsive">
        <table class="table--align-middle">
            <thead>
                <tr>
                    <th>Label</th>
                    <th>URL</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($socialLinks)): ?>
                    <?php foreach ($socialLinks as $link): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($link['label']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($link['url']); ?></a></td>
                            <td class="table-actions">
                                <form method="post" onsubmit="return confirm('Remove this social link?');">
                                    <input type="hidden" name="action" value="social_delete">
                                    <input type="hidden" name="social_id" value="<?php echo (int)$link['id']; ?>">
                                    <button type="submit" class="btn btn--small btn--danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No social links added yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
