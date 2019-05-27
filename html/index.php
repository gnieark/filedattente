<?php

//autoload classes
spl_autoload_register(function ($class_name) {
    $classFolders = array(  "../classes/");
    foreach($classFolders as $folder)
    {
        if(file_exists( $folder . $class_name . '.php')){
            include $folder. $class_name . '.php';
            return;
        }
    }
});

$tpl = new TplBlock();

if(isset($_GET["guichet"]))
{
    //Show the form
    $tplForm = new TplBlock ("formulaire");
    $tplForm->addVars(array("plop"   => ""));
    $tpl->addSubBlock($tplForm);

}

echo $tpl->applyTplFile("../templates/main.html");