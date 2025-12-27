<?php
    session_start();

    if (!isset($_SESSION['user_id'])){
        header('Location: DOLPHIN_LOGIN.php');
        exit;
    }

    if($_SESSION['role'] !== 'Admin') {
        // echo "<p class='error-message'>Access Denied. Only Admins can view this page.</p>";
        echo "<script>alert('Access Denied. Only Admins can view this page.')</script>";
        exit;
    }
    
    $key = hash("sha512", microtime());
    $_SESSION['csrf_token'] = $key;
    require 'php\config.php';

    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New User Test</title>
    <link rel="stylesheet" href="styles\styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="scripts\new_user.js"></script>
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
            <h2>New User</h2>
            <div class='content' id='user-form'>
                <!-- <form name="add_user" id="add_user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"> -->
                <form name="add_user" id="add_user" action="" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $key?>">

                    <label for="firstname">First Name</label>
                    <input maxlength="32" id='firstname' type="text" size="32" name='firstname' required>

                    <label for="lastname">Last Name</label>
                    <input maxlength="32" id='lastname' type="text" size="32" name='lastname' required>
                    <br>

                    <label for="email">Email</label>
                    <input type="email" size="32" name="email" id="email" required>

                    <label for="password">Password</label>
                    <input type="password" minlength="8" maxlength="32" size="32" pattern="^(?=.*\d)(?=.*[A-Z])(?=.*[a-zA-Z])\S{8,}$" name="password" id="password" required>
                    <br>

                    <label for="role">Role</label>
                    <select size=1 name="role" id="role">
                        <option value="Member">Member</option>
                        <option value="Admin">Admin</option>
                    </select>
                    <br>
                    <button id="submit_user" type="submit">SUBMIT</button>
                </form>

            </div>
            <p id="message"></p>
        </main>
        <footer>
            Dolphin Customer Relationship Management &copy 2025
        </footer>            
    </div>
</body>

</html>