<?php
    $servername = "localhost";
    $username = "root";
    $pw = '';

    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
    
    $pdo = new PDO('mysql:host=localhost;dbname=dolphin_crm', 'root', '');

    if ($_SERVER["REQUEST METHOD"] == 'POST'){
        if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $_POST['csrf_token']){
            throw new Exception("Invalid CSRF");
        }

        $firstname = strip_tags($_POST['firstname']);
        $lastname = strip_tags($_POST['lastname']);
        $email = strip_tags($_POST['email']);
        $password = strip_tags($_POST['password']);
        $role = strip_tags($_POST['role']);        
    }



    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, role) 
    VALUES (:firstname, :lastname, :email, :password, :role)");
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hash);
    $stmt->bindParam(':role', $role);

    try {
        $stmt->execute();
        echo "Insert Done";
    } catch (\Throwable $th) {
        throw $th;
    }
?>