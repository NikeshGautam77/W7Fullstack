<?php
// preference.php
session_start();
if (empty($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $value = ($_POST['theme'] ?? 'light') === 'dark' ? 'dark' : 'light';
    // 30 days cookie; consider secure/httponly in production if using HTTPS
    setcookie('theme', $value, time() + 86400 * 30, '/');
    // Redirect to avoid form resubmission and ensure cookie is available
    header('Location: preference.php?saved=1');
    exit;
}

$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
$bg    = ($theme === 'dark') ? '#121212' : '#ffffff';
$fg    = ($theme === 'dark') ? '#e0e0e0' : '#222222';
$card  = ($theme === 'dark') ? '#1e1e1e' : '#f7f7f7';
$saved = isset($_GET['saved']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Preferences</title>
<style>
:root { --bg: <?= $bg ?>; --fg: <?= $fg ?>; --card: <?= $card ?>; }
body { background: var(--bg); color: var(--fg); font-family: Arial, sans-serif; margin: 2rem; }
.card { background: var(--card); padding: 1rem; border-radius: 8px; max-width: 640px; }
label { display: block; margin: 0.4rem 0; }
button { padding: 0.6rem 1rem; }
a { color: var(--fg); }
.notice { color: #1b5e20; }
</style>
</head>
<body>
<div class="card">
  <h2>Theme Preferences</h2>
  <?php if ($saved): ?><p class="notice">Preference saved.</p><?php endif; ?>
  <form method="post" action="preference.php">
    <label>
      <input type="radio" name="theme" value="light" <?= $theme === 'light' ? 'checked' : '' ?>> Light mode
    </label>
    <label>
      <input type="radio" name="theme" value="dark" <?= $theme === 'dark' ? 'checked' : '' ?>> Dark mode
    </label>
    <button type="submit">Save</button>
  </form>
  <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>