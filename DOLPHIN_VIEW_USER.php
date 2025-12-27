<?php

session_start();
require 'php\config.php';

if (!isset($_SESSION['user_id'])){
	header('Location: DOLPHIN_LOGIN.php');
	exit;
}

if($_SESSION['role'] !== 'Admin') {
	echo "<p class='error-message'>Access Denied. Only Admins can view this page.</p>";
	exit;
}

$sql = "SELECT firstname, lastname, email, role, created_at
	FROM Users
	ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dolphin CRM - Users List</title>
	<link href="styles\styles.css" rel="stylesheet">
</head>
<body>
<header>
	<img src="images\dolphin-8045833_640.png" alt="Dolphin Logo">

	<h1>Dolphin CRM</h1>
	<div class="header-subtitle">
		Logged in as <?php echo htmlspecialchars($_SESSION['fullname']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)
	</div>
</header>

<aside class="sidebar">
	<!-- <h2>Dolphin CRM</h2> -->
	<div class="user_info">
		<h3>Account Info</h3>
		<?php echo htmlspecialchars($fullname); ?> 
		(<?php echo htmlspecialchars($role); ?>)
	</div>
	<nav>
		<a href="dashboard.php">Home</a><br>
		<a href="create_contact.php">New Contact</a><br>
		<?php if ($_SESSION['role'] === "Admin"){ echo '<a href="DOLPHIN_VIEW_USER.php"> View Users</a><br>';} ?>
		<?php if ($_SESSION['role'] === "Admin"){ echo '<a href="new_user.php"> Add User</a><br>';} ?>
		<a href="logout.php">Logout</a>
	</nav>
</aside>

<main>
	<div class="page-header">
		<h2>Users</h2>
		<a href="#" class="btn btn-primary">Add Users +</a>
	</div>

	<table id="user_table">
		<thead>
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Email</th>
				<th>Role</th>
				<th>Created</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($users as $user): ?>
			<tr>
				<td><?php echo htmlspecialchars($user['firstname']); ?></td>
				<td><?php echo htmlspecialchars($user['lastname']); ?></td>
				<td><?php echo htmlspecialchars($user['email']); ?></td>
				<td><?php echo htmlspecialchars($user['role']); ?></td>
				<td><?php echo htmlspecialchars($user['created_at']); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</main>

<footer>
    Dolphin Customer Relationship Management &copy 2025
</footer>     
</body>
</html>	