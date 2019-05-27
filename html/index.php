<?php

setlocale (LC_TIME, 'fr_FR.utf8','fra');

//autoload classes
spl_autoload_register(function ($class_name) {
    $classFolders = array(  "../classes/", 
                            "../classes/users/",
                            "../classes/forms/",
                            "../classes/menus/",
                            "../classes/lists/"
                        );
    foreach($classFolders as $folder)
    {
        if(file_exists( $folder . $class_name . '.php')){
            include $folder. $class_name . '.php';
            return;
        }
    }
});

//If site not configured
if(!file_exists("../config/sql.json")){
    include ("../src/install.php");
    die();
}

//Get params
$params = json_decode ( file_get_contents("../config/sql.json"), true );

// database connexion
try {
    $con = new PDO($params["database"]["dsn"], $params["database"]["user"], $params["database"]["password"]);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}
//set authentifications methods configured:
User_Manager::set_available_auth_methods($params["auth"]);

//here we open session
@session_start();

//logout
if(isset($_GET["menu"]) && $_GET["menu"] == "logout"){
    unset($_SESSION["user"]);
    header('Location: /'); 
    die();
}

if(isset($_SESSION['user'])){
    //session user déjà instanciée précédement
    $currentUser = unserialize($_SESSION["user"]);
    $currentUser->set_db($con);
}else{
    $currentUser = new User($con);
}

//let pass POST action for authentificate
if(isset($_POST['act']) && $_POST["act"] == 'auth'){
    $currentUser = User_Manager::authentificate($con,$_POST['login'], $_POST['password']);
}
$_SESSION["user"] = serialize($currentUser);


if($currentUser->is_connected() === false)
{
    //send authentification form
    $tpl = new TplBlock();
    $tpl->addVars(
        array("headTitle" => "Connectez-vous. Registre hygiène et sécurité")
    );
    echo $tpl->applyTplFile("../templates/connect.html");
    die();
}

//At this the user is authentificated

//load available menus
$mManager = new Menus_manager();

$mManager->add_menus_items_from_json_file( realpath( __DIR__ . '/../') . '/config/menus.json');

//Apply current Menu:
$currentMenu = $mManager->get_current_menu();
$messages = $currentMenu->apply_post($con,$currentUser);

//show the page

$tpl = new TplBlock();
$tpl->addVars(
    array(
        "headTitle" => $currentMenu->get_name() . " Registre hygiène et sécurité ",
        "userDisplayName"   => $currentUser->get_display_name(),
        "headerTitle" => $currentMenu->get_name(),
        "customJS"  => $currentMenu->get_custom_js($con,$currentUser),
        "customCSS" => $currentMenu->get_custom_css($con,$currentUser),
        "content"   => $currentMenu->get_content_html($con,$currentUser)
    )
);

//menu de navigation

$navMenus = $mManager->get_user_menu_list($currentUser,true);
foreach($navMenus as $navItem){
    $tplNav = new TplBlock("navmenus");
    $tplNav ->addVars(
        array(
            "url"  => $navItem->get_url(),
            "caption"  => htmlentities($navItem->get_name())
        )
    );
    $tpl->addSubBlock($tplNav);

}

echo $tpl->applyTplFile("../templates/main.html");