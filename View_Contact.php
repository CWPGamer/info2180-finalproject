<?php
session_start();
require 'php\config.php';

function clean($v) {
  return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}

if (!isset($_SESSION['user_id'])){
    header('Location: DOLPHIN_LOGIN.php');
    exit;
}

// if (!isset($_SESSION['user_id'])) {
//   $_SESSION['user_id'] = 0;
// }
$userId = (int)$_SESSION['user_id'];
// echo $userId;
$contactId = null;
if ($_SERVER['REQUEST_METHOD'] === 'GET'){

  if (!isset($_GET['id'])) {
    die("No contact selected.");
  } else{
    $contactId = (int)$_GET['id'];
  }
} else{
  $contactId = trim($_POST['contact_id'] ?? '');  
}
// if ($contactId <= 0) {
//   die("Invalid contact id.");
// }

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


  if (isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);
    $contactId = trim($_POST['contact_id']);

    if ($comment === '') {
      $message = "Note cannot be empty.";
    } else {
      $stmt = $pdo->prepare(
        "INSERT INTO notes (contact_id, comment, created_by, created_at)
         VALUES (?, ?, ?, NOW())"
      );
      $stmt->execute([$contactId, htmlspecialchars($comment), $userId]);

      
      $stmt = $pdo->prepare("UPDATE contacts SET updated_at=NOW() WHERE id=?");
      $stmt->execute([$contactId]);

      $message = "Note added.";
    }
  }
}


$stmt = $pdo->prepare("SELECT * FROM contacts WHERE id=?");
$stmt->execute([$contactId]);
$contact = $stmt->fetch();

$user_assigned_Id = clean($contact['assigned_to']);
$stmt = $pdo->prepare('SELECT id, firstname, lastname FROM users WHERE id = :id');
$stmt->bindParam(':id', $user_assigned_Id);
$stmt->execute();
$user_assigned = $stmt->fetch();

$user_created_Id = clean($contact['created_by']);
$stmt->bindParam(':id', $user_created_Id);
$stmt->execute();
$user_created = $stmt->fetch();

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

// \
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
  <title>Dolphin CRM - Contact Details</title>
  <link rel="stylesheet" href="styles\styles.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="scripts\add_note.js"></script>
  <style>
    .actions { margin: 12px 0; }
    .actions button { margin-right: 8px; }
    .noteslist { margin-top: 18px; }
    .note { padding: 10px 0; border-top: 1px solid #e9e9ee; }
    textarea { width: 100%; min-height: 90px; padding: 8px; box-sizing: border-box; }
  </style>
</head>

<body>
<div id="container" class="layout">
	<header>
		<img src="images\dolphin-8045833_640.png" alt="Dolphin Logo">
		<h1>Dolphin CRM</h1>
		<p class="header-subtitle">Logged in as <?php echo htmlspecialchars($_SESSION['fullname']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</p>
	</header>
  
	<aside class="sidebar">
		<div class="user_info">
			<h3>Account Info</h3>
			<?php echo htmlspecialchars($fullname); ?> 
			(<?php echo htmlspecialchars($role); ?>)
			<hr>
		</div>

		<nav>
			<a href="dashboard.php">Home</a><br><br>
			<a href="create_contact.php">New Contact</a><br><br>
			<?php if ($_SESSION['role'] === "Admin"){ echo '<a href="DOLPHIN_VIEW_USER.php"> View Users</a><br><br>';} ?>
			<?php if ($_SESSION['role'] === "Admin"){ echo '<a href="new_user.php"> Add User</a><br>';} ?>
			<hr>
			<a href="logout.php">Logout</a>
		</nav>
	</aside>

  <main>
    <div class="top-bar">
      <div id="contact_header">
        <h2>
          <?= clean($contact['title']) ?>
          <?= clean($contact['firstname']) ?>
          <?= clean($contact['lastname']) ?>
        </h2>
        <p>Created: <?= clean($contact['created_at'] . ' by ' . $user_created['firstname'] . ' ' . $user_created['lastname']); ?></p>
        <p style="clear: both;">Updated: <?= clean($contact['updated_at']) ?></p>
      </div>
      <form method="post" class="actions">
        <button type="submit" name="assign">Assign to Me</button>
        <button type="submit" name="switch"><?= ($contact['type'] === 'Sales Lead') ? 'Switch to Support' : 'Switch to Sales Lead'; ?></button>
      </form>
    </div>

    <?php if ($message): ?>
      <p class="msg ok"><?= clean($message) ?></p>
    <?php endif; ?>
    
    <div class="content" id="contact">
      <p style=""><strong>Email:</strong> <?= clean($contact['email']) ?></p> <p style=""><strong>Telephone:</strong> <?= clean($contact['telephone']) ?></p>
      <p style=""><strong>Company:</strong> <?= clean($contact['company']) ?></p> <p style=""><strong>Assigned To:</strong> <?= clean($user_assigned['firstname'] . ' ' . $user_assigned['lastname'])?></p>
    </div>

  
    <div id="notes-section" class="noteslist content" style="clear: both;">
      <h3>Notes</h3>

      <?php if (empty($notes)): ?>
        <p class="no-notes">No notes available</p>
      <?php else: ?>
        <?php foreach ($notes as $note): ?>
          <div class="note">
            <p><strong><?= htmlspecialchars(displayUser($note), ENT_QUOTES, 'UTF-8') ?></strong></p>
            <p><?= nl2br(htmlspecialchars_decode($note['comment'], ENT_QUOTES)) ?></p>
            <p><small><?= htmlspecialchars($note['created_at'], ENT_QUOTES, 'UTF-8') ?></small></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
      <br>
      
      <form id="note_form" method="post">
        <input type="hidden" name='contact_id' value="<?php echo $contactId;?>">
        <h4>Add a Note about <?=$contact['firstname']; ?></h4>
        <textarea placeholder="Type Here..." name="comment" required></textarea><br>
        <button type="submit" name="add_note">Add Note</button>
      </form>
      <p id='message'></p>
    </div>
  </main>
  <footer>
    Dolphin Customer Relationship Management &copy 2025
  </footer>
</div>
</body>
</html>
