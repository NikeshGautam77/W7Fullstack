<?php
// dashboard.php
session_start();
if (empty($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
$bg    = ($theme === 'dark') ? '#121212' : '#ffffff';
$fg    = ($theme === 'dark') ? '#e0e0e0' : '#222222';
$card  = ($theme === 'dark') ? '#1e1e1e' : '#f7f7f7';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<style>
:root { --bg: <?= $bg ?>; --fg: <?= $fg ?>; --card: <?= $card ?>; }
body { background: var(--bg); color: var(--fg); font-family: Arial, sans-serif; margin: 2rem; }
.nav a { color: var(--fg); margin-right: 1rem; text-decoration: none; }
.card { background: var(--card); padding: 1rem; border-radius: 8px; max-width: 640px; }
button { padding: 0.6rem 1rem; }
</style>
</head>
<body>
<div class="nav">
  <a href="dashboard.php">Dashboard</a>
  <a href="preference.php">Preferences</a>
  <a href="logout.php">Logout</a>
</div>

<div class="card">
  <h2>Welcome, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Student') ?>!</h2>
  <p>Your theme is currently set to <strong><?= htmlspecialchars($theme) ?></strong>.</p>
  <p>Navigate using the links above.</p>
</div>
</body>
</html>