<?php
    session_start();
    require '..\php\config.php';

    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
    
    // $pdo = new PDO('mysql:host=localhost;dbname=dolphin_crm', 'root', '');
    function sanitize_data($data){
        $data = trim($data);
        $data = strip_tags($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $_POST['csrf_token']){
            throw new Exception("Invalid CSRF");
            // header('Location: ..\new_user.php');
            echo $_SESSION['csrf_token'];
            echo $_POST['csrf_token'];
        }
        $user = array(
            "firstname" => $_POST['firstname'],
            "lastname" => $_POST['lastname'],
            "email" => $_POST['email'],
            "password" => $_POST['password'],
            "role" => $_POST['role'],
        );

        foreach ($user as $key => $value) {
            $user[$key] = sanitize_data($user[$key]);
            if (!$user[$key]){
                throw new Exception("Empty Data");
            }
        }

        // $firstname = sanitize_data($_POST['firstname']);
        // $lastname = sanitize_data($_POST['lastname']);
        // $email = sanitize_data($_POST['email']);
        // $password = sanitize_data($_POST['password']);
        // $role = sanitize_data($_POST['role']);


        $hash = password_hash($user['password'], PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, role) 
        VALUES (:firstname, :lastname, :email, :password, :role)");
        $stmt->bindParam(':firstname', $user['firstname']);
        $stmt->bindParam(':lastname', $user['lastname']);
        $stmt->bindParam(':email', $user['email']);
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':role', $user['role']);
    
        // try {
        //     $stmt->execute();
        //     echo "Insert Done";
        // } catch (\Throwable $th) {
        //     throw $th;
        // }
    }
?>