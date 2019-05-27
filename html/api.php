<?php

//database connection

//Get params
$sqlparams = json_decode ( file_get_contents("../config/bdd.json"), true );

// database connexion
try {
    $con = new PDO($sqlparams["dsn"], $sqlparams["user"], $sqlparams["password"]);
} catch (PDOException $e) {
    echo 'Database connection failed : ' . $e->getMessage();
}

$entryPoint = $_GET["entry"];
switch ($entryPoint){
    case "guichets":
        header('Content-Type: application/json');
        echo file_get_contents("../config/guichets.json");
        break;
    default:
}