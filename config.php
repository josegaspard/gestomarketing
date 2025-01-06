<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'josegasp3_admin');
define('DB_PASSWORD', 'TurboPost2024!');
define('DB_NAME', 'josegasp3_turbopost');

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($conn === false){
    die("ERROR: No se pudo conectar. " . mysqli_connect_error());
}
?>