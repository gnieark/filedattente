<?php
$xml = simplexml_load_file('../xml/setup.xml');
// $sxe = new SimpleXMLElement($xml->asXML());
$id = $_GET["id"];
$val = $_GET["val"];
if ($id == "pwd"){
	$xml->pwd = $val;
	$xml ->asXML('../xml/setup.xml');
	exit();	
}
if ($id == "delete"){
	$V = explode("_",$val);
	$T = $xml->xpath('//setup/'.$V[0].'/item[@id="'.$V[1].'"]');
	echo (string)$T[0]; 
	unset($T[0][0]);
	$xml ->asXML('../xml/setup.xml');
	exit();
}
$N = $xml->xpath("//setup/".$id);
if (count($N[0]->children()) == 0){
	$i = 1;
}else {
	$i = count($N[0]->children())+1;
}
echo $i;
$new = $N[0]->addChild("item", $val);
$new -> addAttribute('id', $i);


$xml ->asXML('../xml/setup.xml');
?>