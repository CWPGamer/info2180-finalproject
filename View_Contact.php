<?php
session_start();
require_once 'db.php';

function clean($v) {
  return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}


if (!isset($_SESSION['user_id'])) {
  $_SESSION['user_id'] = 0; 
}
$userId = (int)$_SESSION['user_id'];


if (!isset($_GET['id'])) {
  die("No contact selected.");
}
$contactId = (int)$_GET['id'];
if ($contactId <= 0) {
  die("Invalid contact id.");
}

$message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (isset($_POST['assign'])) {
    $stmt = $pdo->prepare("UPDATE contacts SET assigned_to=?, updated_at=NOW() WHERE id=?");
    $stmt->execute([$userId, $contactId]);
    $message = "Contact assigned to you.";
  }

  if (isset($_POST['switch'])) {
    $stmt = $pdo->prepare("SELECT type FROM contacts WHERE id=?");
    $stmt->execute([$contactId]);
    $row = $stmt->fetch();

    if ($row) {
      $newType = ($row['type'] === 'Support') ? 'Sales Lead' : 'Support';
      $stmt = $pdo->prepare("UPDATE contacts SET type=?, updated_at=NOW() WHERE id=?");
      $stmt->execute([$newType, $contactId]);
      $message = "Contact type updated.";
    }
  }

  
  if (isset($_POST['add_note'])) {
    $comment = trim($_POST['comment'] ?? '');

    if ($comment === '') {
      $message = "Note cannot be empty.";
    } else {
      $stmt = $pdo->prepare(
        "INSERT INTO notes (contact_id, comment, created_by, created_at)
         VALUES (?, ?, ?, NOW())"
      );
      $stmt->execute([$contactId, clean($comment), $userId]);

      
      $stmt = $pdo->prepare("UPDATE contacts SET updated_at=NOW() WHERE id=?");
      $stmt->execute([$contactId]);

      $message = "Note added.";
    }
  }
}


$stmt = $pdo->prepare("SELECT * FROM contacts WHERE id=?");
$stmt->execute([$contactId]);
$contact = $stmt->fetch();

if (!$contact) {
  die("Contact not found.");
}


$stmt = $pdo->prepare(
  "SELECT n.comment, n.created_at, n.created_by,
          u.firstname, u.lastname, u.email
   FROM notes n
   LEFT JOIN users u ON n.created_by = u.id
   WHERE n.contact_id = ?
   ORDER BY n.created_at DESC"
);
$stmt->execute([$contactId]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

\
function displayUser($note) {
  $name = trim(($note['firstname'] ?? '') . ' ' . ($note['lastname'] ?? ''));
  if ($name !== '') return $name;
  if (!empty($note['email'])) return $note['email'];
  return (string)($note['created_by'] ?? 'Unknown');
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Details</title>
  <link rel="stylesheet" href="create_contact.css">
  <style>
    .actions { margin: 12px 0; }
    .actions button { margin-right: 8px; }
    .noteslist { margin-top: 18px; }
    .note { padding: 10px 0; border-top: 1px solid #e9e9ee; }
    textarea { width: 100%; min-height: 90px; padding: 8px; box-sizing: border-box; }
  </style>
</head>

<body>
<div class="wrap">
  <h2>
    <?= clean($contact['title']) ?>
    <?= clean($contact['firstname']) ?>
    <?= clean($contact['lastname']) ?>
  </h2>

  <?php if ($message): ?>
    <p class="msg ok"><?= clean($message) ?></p>
  <?php endif; ?>

  <p><strong>Email:</strong> <?= clean($contact['email']) ?></p>
  <p><strong>Company:</strong> <?= clean($contact['company']) ?></p>
  <p><strong>Telephone:</strong> <?= clean($contact['telephone']) ?></p>
  <p><strong>Type:</strong> <?= clean($contact['type']) ?></p>
  <p><strong>Created:</strong> <?= clean($contact['created_at']) ?></p>
  <p><strong>Updated:</strong> <?= clean($contact['updated_at']) ?></p>

  <form method="post" class="actions">
    <button type="submit" name="assign">Assign to me</button>
    <button type="submit" name="switch">Switch Type</button>
  </form>

 
  <div class="noteslist">
    <h3>Notes</h3>

    <?php if (empty($notes)): ?>
      <p>No notes available</p>
    <?php else: ?>
      <?php foreach ($notes as $note): ?>
        <div class="note">
          <p><strong><?= htmlspecialchars(displayUser($note), ENT_QUOTES, 'UTF-8') ?></strong></p>
          <p><?= nl2br(htmlspecialchars($note['comment'], ENT_QUOTES, 'UTF-8')) ?></p>
          <p><small><?= htmlspecialchars($note['created_at'], ENT_QUOTES, 'UTF-8') ?></small></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <h4>Add Note</h4>
  <form method="post">
    <textarea name="comment" required></textarea><br>
    <button type="submit" name="add_note">Add Note</button>
  </form>

</div>
</body>
</html>
