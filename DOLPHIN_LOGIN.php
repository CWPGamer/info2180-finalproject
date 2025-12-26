<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOLPHIN CRM</title>
    <link rel="stylesheet" href="styles.css">
    <script src="LOGIN.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <h1>DOLPHIN CRM</h1>
        </div>
    </header>

    <main>
        <h2>LOGIN</h2>
       
        <form id="login"  method="post">
        	<div class="form-group">

                	
                <input type="text" name="Username" id="Username" placeholder="Username" required><br><br>
                <input type="password" name="Password" id="Password" placeholder="Password" pattern=".*[0-9].*" required>
				    
                
		    </div>

        	<br>
		<button type="submit" class="btn">LOGIN</button>
	    </form>

        <p id="message"></p>
       
    </main>

    <footer>
        <div class="container">
            <p> Copyright 2022 &copy DOLPHIN CRM</p>
        </div>
    </footer>

</body>
</html>

<?php

if ($_SERVER['REQUEST_METHOD']==='POST'){
    $username=htmlspecialchars(trim($_POST['Username']?? ''));
    $password=password_hash((trim($_POST['Password']?? '')));
  
    $pdo=new PDO('mysql:host=localhost; dbname=dolphin_crm;charset=utf8','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!$pdo) {
        die("error connecting");
    }
    else{

      
        $stmt= $pdo->prepare('SELECT * FROM Users WHERE username =:username ');
        $stmt->bindParam(":username",$username,PDO::PARAM_STR);
        $stmt->execute();
        $users=$stmt->fetch();


        
    if ($users && password_verify($password,$users['password'])){
      
        echo"LOGIN";
        exit();
   
        
    }
    else{
        echo"Re-enter either username or password";
        // if password is wrong 
        exit;
    }

   
}

}

?>
