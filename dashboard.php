<?php

session_start();
require 'config.php';

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

$fullname = $_SESSION['firstname'] . " " . $_SESSION['lastname'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dolphin CRM Dashboard</title>
	<link href="styles.css" rel="stylesheet">
</head>
<body>

<div class="layout">

	<aside class="sidebar">
		<h2>Dolphin CRM</h2>
		<ul>
			<li><a href="dashboard.php">Home</a></li>
			<li><a href="create_contact.php">New Contact</a></li>
			<li><a href="DOLPHIN_VIEW_USER.php">Users</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>

		<p class="user_info">
			<?php echo htmlspecialchars($fullname); ?><br>
			(<?php echo htmlspecialchars($role); ?>)
		</p>
	</aside>

	<main>
		<div class="top-bar">
			<h1>Dashboard</h1>
			<a class="btn-add" href="create_contact.php">+ Add New Contact</a>
		</div>

		<div class="filters">
			<span>Filter By:</span>
			<a href="dashboard.php?filter=all">All</a>
			<a href="dashboard.php?filter=sales">Sales Leads</a>
			<a href="dashboard.php?filter=support">Support</a>
			<a href="dashboard.php?filter=assigned">Assigned to me</a>
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
					<td><a href="contact_details.php?id=<?php echo $c['id']; ?>">View</a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</main>
</div>

</body>
</html>	