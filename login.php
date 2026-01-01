<?php
// login.php
session_start();
require_once __DIR__ . '/db.php';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $password   = $_POST['password'] ?? '';

    if ($student_id === '' || $password === '') {
        $errors[] = 'Student ID and password are required.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id, full_name, password_hash FROM students WHERE student_id = ?');
            $stmt->execute([$student_id]);
            $student = $stmt->fetch();

            if (!$student) {
                $errors[] = 'Invalid credentials.';
            } else {
                if (password_verify($password, $student['password_hash'])) {
                    $_SESSION['logged_in']  = true;
                    $_SESSION['student_id'] = $student_id;
                    $_SESSION['full_name']  = $student['full_name'];
                    // Regenerate session ID to prevent fixation
                    session_regenerate_id(true);
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $errors[] = 'Invalid credentials.';
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Login failed. Please try again.';
        }
    }
}
$registered = isset($_GET['registered']) ? 'Registration successful. Please login.' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<style>
body { font-family: Arial, sans-serif; margin: 2rem; }
form { max-width: 360px; }
input, button { padding: 0.6rem; width: 100%; margin: 0.4rem 0; }
.error { color: #b00020; }
.note { color: #1b5e20; }
</style>
</head>
<body>
<h2>Login</h2>

<?php if ($registered): ?><div class="note"><?= htmlspecialchars($registered) ?></div><?php endif; ?>
<?php if (!empty($errors)): ?>
<div class="error">
    <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="post" action="login.php" novalidate>
    <label>Student ID</label>
    <input type="text" name="student_id" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

<p>No account? <a href="register.php">Register here</a>.</p>
</body>
</html>