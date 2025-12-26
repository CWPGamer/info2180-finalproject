<?php
    session_start();
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
    <script type="text/javascript" src="scripts\new_user.js"></script>
</head>

<body>
    <div class="container">
        <header>
            <p>Dolphin CRM</p>
        </header>
        <aside>
            <p>Aside</p>
        </aside>
        <main>
            <p>New User</p>
            <div class='container'>
                <!-- <form name="add_user" id="add_user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"> -->
                <form name="add_user" id="add_user" action="php\process_new_user.php" method="post">
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
        </main>
        <footer>
            Dolphin Customer Relationship Management &copy 2025
        </footer>            
    </div>
</body>

</html>