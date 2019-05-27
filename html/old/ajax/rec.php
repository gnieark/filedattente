<?php

$xml = simplexml_load_file('../xml/rhs.xml');
$sxe = new SimpleXMLElement($xml->asXML());

$xmlSetup = simplexml_load_file('../xml/setup.xml');
// $id =  count($xml)+1;
$name = $_POST['name'];
$date_signalement = $_POST['date_signalement'];
$affectation = $_POST['affectation'];
$localisation = $_POST['localisation'];
$cause = $_POST['cause'];
$what_happened = $_POST['what_happened'];
$what_did_you_do = $_POST['what_did_you_do'];
$file = $_POST['inptFile'];
$reponse = $_POST['reponse'];
$date_reponse = $_POST['date_reponse'];
$name_reponse = $_POST['name_reponse'];
$date_vu_chsct = $_POST['date_vu_chsct'];
$obs_chsct = $_POST['obs_chsct'];
$action = $_POST['action'];
if ($_POST["update"]== 0) {

	$ids = $xml->xpath("//item/id");
	function am($a) {
		return intval($a); 
	}
	$id = max(array_map(function($a){return intval($a);}, $ids)) + 1;
	$TS = time();

	$newItem = $sxe->addChild("item");
	$newItem->addAttribute('id', $id);
	$newItem->addChild("id", $id);
	$newItem->addChild("timestamp", $TS);
	$newItem->addChild("date_signalement", $date_signalement);
	$newItem->addChild("name", $name);
	$newItem->addChild("affectation",$affectation);
	$newItem->addChild("localisation",$localisation);
	$newItem->addChild("cause",$cause);
	$newItem->addChild("what_happened",$what_happened);
	$newItem->addChild("what_did_you_do",$what_did_you_do);
	$newItem->addChild("date_reponse",$date_reponse);
	$newItem->addChild("name_reponse",$name_reponse);
	$newItem->addChild("reponse",$reponse);
	$newItem->addChild("date_vu_chsct",$date_vu_chsct);
	$newItem->addChild("file",$file);
	$newItem->addChild("obs_chsct",$obs_chsct);
	$newItem->addChild("action",$action);

	$sxe->asXML('../xml/rhs.xml'); 
	
	//FORMATAGE
	$domxml = new DOMDocument('1.0');
	$domxml->preserveWhiteSpace = false;
	$domxml->formatOutput = true;
	$domxml->loadXML($sxe->asXML());
	$domxml->save('../xml/rhs.xml');
	
	
	require_once('../PHPMailer/class.phpmailer.php');

	$mail             = new PHPMailer();
	$date=date("d_m_y");
	$body             = "Bonjour,\n\r <br/>$name ( $affectation ) a signalé un nouvel incident \n\r <br/>";
	$body             .= '<br/>A CONSULTER ICI : <a href="http://'.$_SERVER['SERVER_NAME'].'/RHS/">http://'.$_SERVER['SERVER_NAME'].'/RHS/</a> <br/>';
	$mail->IsSMTP(); 
	$mail->Host       = "smtp.melanie2.i2"; // SMTP server
	// $mail->Host       = "sd001803.sd.intranet.sante.gouv.fr"; // SMTP server
	$mail->SMTPDebug  = 1; 
	$mail->SetFrom('rhs@ddpp-76.i2');
	$mail->Subject    = "RHS NOUVEL ENREGISTREMENT";
	$mail->AltBody    = "RHS"; // a tester
	$mail->MsgHTML($body);
	$xmails = $xmlSetup->xpath("//setup/mails");
	if (count($xmails[0]->children())>0){
		foreach($xmails[0]->children() as $item){
			$mail->AddAddress((string)$item);
		}
	}
	// $mail->AddAddress('patrick.delisle@seine-maritime.gouv.fr');	

	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;		
		$F = fopen("error.txt","a");
		fwrite($F,$id." ".$mail->ErrorInfo."\n");
		fclose($F);
	
	} else {
		echo "Message sent!";
		$F = fopen("send.txt","a");
		fwrite($F,"SENT $id \n");
		fclose($F);
	}

}
else {
	echo "UPDATE".$_POST["update"];
	$node = $_POST['update'];

	$results = $xml->xpath("//item[@id='$node']");
	$results[0]->name = $name;
	$results[0]->date_signalement = $date_signalement;
	$results[0]->affectation = $affectation;
	$results[0]->localisation = $localisation;
	$results[0]->cause = $cause;
	$results[0]->what_happened = $what_happened;
	$results[0]->what_did_you_do = $what_did_you_do;
	$results[0]->reponse = $reponse;
	$results[0]->date_reponse = $date_reponse;
	$results[0]->name_reponse = $name_reponse;
	$results[0]->date_vu_chsct = $date_vu_chsct;
	$results[0]->obs_chsct = $obs_chsct;
	$results[0]->action = $action;
	$results[0]->file = $file;
	$xml ->asXML('../xml/rhs.xml');
	
	//FORMATAGE
	$domxml = new DOMDocument('1.0');
	$domxml->preserveWhiteSpace = false;
	$domxml->formatOutput = true;
	$domxml->loadXML($xml->asXML());
	$domxml->save('../xml/rhs.xml');
}


?>