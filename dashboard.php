<?php

session_start();
require 'php\config.php';

if (!isset($_SESSION['user_id'])){
	header('Location: DOLPHIN_LOGIN.php');
	exit;
}

$filter = $_GET['filter'] ?? 'all';
$userId = $_SESSION['user_id'];


switch($filter) {
	case 'sales':
		$sql = "SELECT id, title, firstname, lastname, email, company, type
			FROM contacts WHERE type = 'Sales Lead'";
		break;
	case 'support':
		$sql = "SELECT id, title, firstname, lastname, email, company, type
			FROM contacts WHERE type = 'Support'";
		break;
	case 'assigned':
		$sql = "SELECT id, title, firstname, lastname, email, company, type
			FROM contacts WHERE assigned_to = ?";
		break;
	default:
		$sql = "SELECT id, title, firstname, lastname, email, company, type
			FROM contacts";
		break;
}

if ($filter === 'assigned') {
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$userId]);
} else {
	$stmt = $pdo->query($sql);
}

$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$fullname = $_SESSION['fullname'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dolphin CRM - Dashboard</title>
	<link href="styles\styles.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="scripts\dashboard.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
		<div class="top-bar">
			<h2>Dashboard</h2>
			<!-- <button onclick="window.location.href='create_contact.php'">Add New Contact</button> -->
			<button class='dead' disabled='disabled'><a class="btn-add" href="create_contact.php">Add New Contact</a></button>
		</div>
		<div class="content">
			<div style="clear: both;" class="filters">
				<span><strong>Filter By:</strong></span>
				<a href="#" class="filter-link" data-filter="all">All</a>
				<a href="#" class="filter-link" data-filter="sales">Sales Leads</a>
				<a href="#" class="filter-link" data-filter="support">Support</a>
				<a href="#" class="filter-link" data-filter="assigned">Assigned to me</a>
			</div>
			
			<table class="table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Company</th>
						<th>Type</th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($contacts as $c): ?>
						<tr>
							<td><?php echo htmlspecialchars($c['title'].' '.$c['firstname'].' '.$c['lastname']); ?></td>
							<td><?php echo htmlspecialchars($c['email']); ?></td>
							<td><?php echo htmlspecialchars($c['company']); ?></td>
							<td><?php echo htmlspecialchars($c['type']); ?></td>
							<td><a href="View_Contact.php?id=<?php echo $c['id']; ?>">View</a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</main>
    <footer>
        Dolphin Customer Relationship Management &copy 2025
    </footer>  	
</div>

</body>
</html>	