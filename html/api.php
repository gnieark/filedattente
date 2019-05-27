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

    case "call":
        switch($_SERVER['REQUEST_METHOD']){
            case "POST":
                //verifier l'existance du numéro du guichet
                $guichets = json_decode( file_get_contents("../config/guichets.json") ,true);
                $guichetExists = false;
                foreach($guichets as $guichet)
                {
                    if($guichet["id"] == $_POST["guichet"]){
                        $guichetExists = true;
                        break;
                    }
                }
                if(!$guichetExists){
                
                    http_response_code(400);
                    echo "Ce guichet n'existe pas";
                    die();
                }

                //vérifier que le ticket est bien numérique
                if ( 
                    ($_POST["ticket"] < 0 )
                    ||  ($_POST["ticket"] > 99999)
                ){
                    http_response_code(400);
                    echo "Ce ticket n'est pas numérique";
                    die();          
                }

                $sql = "INSERT INTO calls (guichet,ticket,call_time) 
                        VALUES(:guichet, :ticket, NOW())
                        ON DUPLICATE KEY UPDATE
                        ticket=:ticket,
                        call_time=NOW();";
                $q = $con->prepare($sql);

                $q->execute(array(
                        ":guichet"  => $_POST["guichet"],
                        ":ticket"   => $_POST["ticket"]
                    )
                );
                break;
            case "GET":

                break;

        }
        break;

    case "state":

        break;
    default:
}