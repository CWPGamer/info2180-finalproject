<?php
session_start();
require 'php\config.php';

if (!isset($_SESSION['user_id'])){
    header('Location: DOLPHIN_LOGIN.php');
    exit;
}

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
    } elseif (!preg_match('/^\d{3}-\d{3}-\d{4}$/', $telephone)) {
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
      echo $message;
      $messageClass = "ok";
      exit();
    }

  } else {
    $message = "All fields are required.";
    echo $message;
    $messageClass = "bad";
    exit();
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New Contact</title>
  <link rel="stylesheet" href="styles\styles.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>

<body>
<div class="layout">

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
  <div class="wrap">
    <h2>New Contact</h2>
    
    <div class="card">
      <form id="contactForm" method="post">
        
        <div class="field">
          <label for="title">Title</label>
          <select name="title" id="title">
            <option value="">--</option>
            <option>Mr.</option>
            <option>Mrs.</option>
            <option>Ms.</option>
            <option>Dr.</option>
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
          <input id="telephone" placeholder="888-888-8888" name="telephone" required>
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
          
          <!-- <?php if ($message): ?>
            <div class="msg <?= $messageClass ?>"><?= $message ?></div>
            <?php endif; ?> -->
          
          <p id="message"></p>
          </form>
        </div>
      </div>
    </main>
    <footer>
      Dolphin Customer Relationship Management &copy 2025
    </footer>  	

    <script src="scripts\create_contact.js"></script>
  </div>
</body>
</html>
