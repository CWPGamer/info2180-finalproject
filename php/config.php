<?php

$config = array(
    "host" => "localhost",
    "user" => "root",
    "dbname" => "dolphin_crm",
    "dbpassword" => ""
);

$pdo = new PDO('mysql:host='.$config['host'].';dbname='.$config['dbname'], $config['user'], $config['dbpassword']);

$stmt = $pdo->query("SELECT email FROM users WHERE email = 'admin@project2.com'");
// $stmt->bindParam(':email', 'admin@project2.com', PDO::PARAM_STR);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
// echo htmlentities($data);
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
?>