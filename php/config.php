<?php

$config = array(
    "host" => "localhost",
    "user" => "root",
    "dbname" => "dolphin_crm",
    "dbpassword" => ""
);

$pdo = new PDO('mysql:host='.$config['host'].';dbname='.$config['dbname'], $config['user'], $config['dbpassword']);

$stmt = $pdo->query("SELECT email FROM users WHERE email = 'admin@project2.com'");

$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data){
    echo "Database Empty";
    $hash = password_hash('password123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO `users` (`password`, `email`, `role`) VALUES (:password, 'admin@project2.com', 'Admin')");
    $stmt->bindParam(':password', $hash);
    try {
        $stmt->execute();
        echo "Admin Added";
    } catch (\Throwable $th) {
        throw $th;
    }
}
if ($_SESSION['user_id']){
    $fullname = $_SESSION['fullname'];
    $role = $_SESSION['role'];    
}
?>
