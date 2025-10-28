<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please provide both username and password.';
    } else {
        $sql = "SELECT id, username, password_hash, full_name FROM admins WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();  
            $admin = $result->fetch_assoc();
            $stmt->close();

            if ($admin) {
                $passwordHash = $admin['password_hash'] ?? '';
                $isValid = password_verify($password, $passwordHash);

                if (!$isValid && $passwordHash !== '' && hash_equals($passwordHash, $password)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $rehash = $conn->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
                    if ($rehash) {
                        $rehash->bind_param('si', $newHash, $admin['id']);
                        $rehash->execute();
                        $rehash->close();
                        $passwordHash = $newHash;
                        $isValid = true;
                    }
                }

                if ($isValid) {
                    $_SESSION['admin_id'] = (int)$admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_name'] = $admin['full_name'];

                    $update = $conn->prepare("UPDATE admins SET last_login = NOW(), password_hash = ? WHERE id = ?");
                    if ($update) {
                        $update->bind_param('si', $passwordHash, $admin['id']);
                        $update->execute();
                        $update->close();
                    }

                    header('Location: dashboard.php');
                    exit;
                }
            }
        }

        $error = 'Invalid credentials. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUP Biñan Admin Login</title>
    <link rel="stylesheet" href="../asset/css/admin.css">
</head>
<body class="auth-body">
    <main class="auth-container">
        <section class="auth-card">
            <h1>Admin Login</h1>
            <p class="auth-card__subtitle">Sign in to manage the PUP Biñan website.</p>
            <?php if ($error !== null): ?>
                <div class="alert alert--error"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif ($message = get_flash('success')): ?>
                <div class="alert alert--success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form method="post" class="form">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required autocomplete="username">

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required autocomplete="current-password">

                <button type="submit" class="btn btn--primary">Sign In</button>
            </form>
        </section>
    </main>
</body>
</html>
