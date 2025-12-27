<?php
    session_start();

    require 'php\config.php';

    $key = hash("sha512", microtime());
    $_SESSION['csrf_token'] = $key;

    if (isset($_SESSION['user_id'])){
        header('Location: DOLPHIN_VIEW_USER.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOLPHIN CRM</title>
    <link rel="stylesheet" href="styles\styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="scripts\LOGIN.js"></script>
</head>
<body>
    <div id="main-area">

        <header>
            <div class="container">
                <h1>DOLPHIN CRM</h1>
            </div>
        </header>
        
        <main>
            <h2>LOGIN</h2>
            
            <form id="login" method="post">
                <div class="form-group">
                    
                    <input type="hidden" name="csrf_token" value="<?php echo $key?>">
                    <input type="email" name="Email" id="Email" placeholder="Email address" required><br><br>
                    <input type="password" name="Password" id="Password" placeholder="Password" required>
			
                    
                </div>
            
                <br>
                <button type="submit" id="Submit" name="Submit" class="btn">LOGIN</button>
            </form>
                    
            <p id="message"></p>
                    
        </main>
                
        <footer>
            Dolphin Customer Relationship Management &copy 2025
        </footer>
    </div>
</body>
</html>




