<?php
    session_start();
    require "config.php";

    if ($_SERVER['REQUEST_METHOD']==='POST'){
        // echo "lol";
        // if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $_POST['csrf_token']){
        //     throw new Exception("Invalid CSRF");
        //     // header('Location: ..\new_user.php');
        // }
        $email=htmlspecialchars(trim($_POST['Email']?? ''));
        $password=trim($_POST['Password']);
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if (!$pdo) {
            die("error connecting");
        }
        else{
        
            $stmt= $pdo->prepare('SELECT * FROM Users WHERE email =:email');
            $stmt->bindParam(":email",$email,PDO::PARAM_STR);
            $stmt->execute();
            $user=$stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['firstname'] . ' ' . $user['lastname'];
                $_SESSION['role'] = $user['role'];
                // echo($_SESSION['user_id']);
                // header("Location: dashboard.php");
                // header("Location: ..\DOLPHIN_VIEW_USER.php");
                echo "Success";
                exit();
            }
            
            else{
                echo "Re-enter either username or password";
                // echo "Declined";
                // header("Location: ..\DOLPHIN_LOGIN.php");
                exit;
            }
        }

    }
?>