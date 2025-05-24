<?php
// db.php (CONECTANDO A AMAZON RDS)
$host = 'incargo365-db.ctgao2q62bzx.us-east-1.rds.amazonaws.com';
$dbname = 'incargo365';
$username = 'loginuser';
$password = 'InCargo365-LoginUser';

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}



