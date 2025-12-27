<?php
session_start();
require_once 'db.php';

function clean($v) {
  return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}

$message = '';
$messageClass = '';

$users = $pdo->query("SELECT id, firstname, lastname, email FROM users")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $title     = clean($_POST['title'] ?? '');
  $firstname = clean($_POST['firstname'] ?? '');
  $lastname  = clean($_POST['lastname'] ?? '');
  $email     = clean($_POST['email'] ?? '');
  $telephone = clean($_POST['telephone'] ?? '');
  $company   = clean($_POST['company'] ?? '');
  $type      = clean($_POST['type'] ?? 'Support');
  $assigned  = $_POST['assigned_to'] ?? '';

  if ($firstname !== '' && $lastname !== '' && $email !== '' && $telephone !== '' && $company !== '' && $assigned !== '') {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $message = "Invalid email.";
      $messageClass = "bad";

    // Phone number: must be exactly 10 digits
    } elseif (!preg_match('/^[0-9]{10}$/', $telephone)) {
      $message = "Telephone number must be exactly 10 digits.";
        $messageClass = "bad";


    } else {
      $stmt = $pdo->prepare(
        "INSERT INTO contacts
         (title, firstname, lastname, email, telephone, company, type, assigned_to, created_by, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
      );

      $stmt->execute([
        $title ?: null,
        $firstname,
        $lastname,
        $email,
        $telephone,
        $company,
        $type,
        $assigned,
        $_SESSION['user_id'] ?? $assigned
      ]);

      $message = "Contact created successfully.";
      $messageClass = "ok";
    }

  } else {
    $message = "All fields are required.";
    $messageClass = "bad";
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>New Contact</title>
  <link rel="stylesheet" href="./create_contact.css">
</head>

<body>
<div class="wrap">
  <h2>New Contact</h2>

  <div class="card">
    <form id="contactForm" method="post">

      <div class="field">
        <label for="title">Title</label>
        <select name="title" id="title">
          <option value="">--</option>
          <option>Mr</option>
          <option>Mrs</option>
          <option>Ms</option>
          <option>Dr</option>
        </select>
      </div>

      <div class="field">
        <label for="firstname">First Name</label>
        <input id="firstname" name="firstname" required>
      </div>

      <div class="field">
        <label for="lastname">Last Name</label>
        <input id="lastname" name="lastname" required>
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" required>
      </div>

      <div class="field">
        <label for="telephone">Telephone</label>
        <input id="telephone" name="telephone" required>
      </div>

      <div class="field">
        <label for="company">Company</label>
        <input id="company" name="company" required>
      </div>

      <div class="field">
        <label for="type">Type</label>
        <select name="type" id="type">
          <option>Sales Lead</option>
          <option selected>Support</option>
        </select>
      </div>

      <div class="field">
        <label for="assigned_to">Assigned To</label>
        <select name="assigned_to" id="assigned_to" required>
          <option value="">-- Select --</option>
          <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>">
              <?= clean(($u['firstname'] ?? '') . ' ' . ($u['lastname'] ?? '')) ?: $u['email'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit">Save</button>

      <?php if ($message): ?>
        <div class="msg <?= $messageClass ?>"><?= $message ?></div>
      <?php endif; ?>

    </form>
  </div>
</div>

<script src="./create_contact.js"></script>
</body>
</html>
