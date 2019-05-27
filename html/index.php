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

    $guichets = json_decode( file_get_contents("../config/guichets.json") ,true);
    foreach($guichets as $guichet)
    {
        $tplGuichetOption = new TplBlock("guichets");
        $tplGuichetOption->addVars(array(
            "id"        => $guichet["id"],
            "selected"  => (($_GET["guichet"] == $guichet["id"]) ? 'selected="selected"' : ''),
            "text"      => $guichet["text"]
        ));
        $tplForm->addSubBlock($tplGuichetOption);
    }


    $tpl->addSubBlock($tplForm);

}

echo $tpl->applyTplFile("../templates/main.html");