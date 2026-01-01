<?php
// register.php
session_start();
require_once __DIR__ . '/db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $full_name  = trim($_POST['full_name'] ?? '');
    $password   = $_POST['password'] ?? '';

    // Basic validation
    if ($student_id === '' || $full_name === '' || $password === '') {
        $errors[] = 'All fields are required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (empty($errors)) {
        try {
            // Check for existing student_id
            $stmt = $pdo->prepare('SELECT id FROM students WHERE student_id = ?');
            $stmt->execute([$student_id]);
            if ($stmt->fetch()) {
                $errors[] = 'Student ID already registered.';
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $insert = $pdo->prepare('INSERT INTO students (student_id, full_name, password_hash) VALUES (?, ?, ?)');
                $insert->execute([$student_id, $full_name, $hash]);

                // Redirect to login
                header('Location: login.php?registered=1');
                exit;
            }
        } catch (Exception $e) {
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<style>
body { font-family: Arial, sans-serif; margin: 2rem; }
form { max-width: 420px; }
input, button { padding: 0.6rem; width: 100%; margin: 0.4rem 0; }
.error { color: #b00020; }
.success { color: #1b5e20; }
</style>
</head>
<body>
<h2>Student Registration</h2>

<?php if (!empty($errors)): ?>
<div class="error">
    <ul>
        <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="post" action="register.php" novalidate>
    <label>Student ID</label>
    <input type="text" name="student_id" required>

    <label>Full Name</label>
    <input type="text" name="full_name" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>