<div class="add_delete_toolbar"></div>
<table class="display" cellspacing="0" width="100%" id="tbRHS">
	<thead>
		<tr>
			<th COLSPAN="7" ALIGN="CENTER" VALIGN="MIDDLE" style="background:#f1f1f1;">Signalement de l'agent</th>
			<th COLSPAN="3" ALIGN="CENTER" VALIGN="MIDDLE" style="background:#f9f9f9;">R&eacute;ponse de l'administration</th>
		</tr>
		<tr>
			<th>Date</th>
			<th >Nom(s) pr&eacute;nom(s)</th>
			<th >Affectation</th>
			<th >Localisation de l'incident</th>
			<th >Cause</th>
			<th >Que s'est-il pass&eacute;&nbsp;? </th>
			<th >Qu'avez-vous fait&nbsp;?</th>
			<th >Date</th>
			<th >R&eacute;ponse</th>
			<th >Vu en CHSCT du </th>
		</tr>
	</thead>
	<tbody>
<?php 
$xml = simplexml_load_file('../xml/rhs.xml');
function usdate($d) {
	$D = explode('/',$d);
	$dateUs = $D[2].$D[1].$D[0];
	return $dateUs;
}
foreach($xml->children() as $item){
	if ($item->id == "25") {
		echo "<tr id='qn-".$item->id."' class='tr_to_click'>";
		echo "<td>".$item->id."</td>";
		echo "<td><span style='display:none'>".usdate($item->date_signalement)."</span>".$item->date_signalement."</td>";
		echo "<td>".$item->name."</td>";
		echo "<td>".$item->affectation."</td>";
		echo "<td>".$item->localisation."</td>";
		echo "<td>".$item->cause."</td>";
		echo "<td>".$item->what_happened."</td>";
		echo "<td>".$item->what_did_you_do."</td>";
		echo "<td>".$item->date_reponse."</td>";
		echo "<td>".$item->reponse."</td>";
		echo "<td>".$item->date_vu_chsct."</td>";
		echo "</tr>";
		
		
		require_once('../PHPMailer/class.phpmailer.php');

		$mail             = new PHPMailer();
		$date=date("d_m_y");
		$body             = "Gotcha?\n\r<br/>BONJOUR,\n\r <br/>".$item->name."( ".$item->affectation." ) a signale un nouvel incident \n\r <br/>";
		// $body             .= "".$item->localisation." le ".$item->date_signalement." \n\r <br/> ".$item->cause." => ".$item->what_happened." \n\r <br/> ".$item->what_did_you_do." \n\r <br/>";
		$body             .= "<br/>A CONSULTER ICI : <a href="http://10.76.51.14/RHS/">http://10.76.51.14/RHS/</a>\n\r <br/>";
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = "smtp.melanie2.i2"; // SMTP server
		// $mail->Host       = "sd001803.sd.intranet.sante.gouv.fr"; // SMTP server
		$mail->SMTPDebug  = 1; 
		$mail->SetFrom('rhs@ddcspp-76.i2');
		$mail->Subject    = "RHS NOUVEL ENREGISTREMENT";
		$mail->AltBody    = "RHS"; // optional, comment out and test
		$mail->MsgHTML($body);
		
		// $mail->AddAddress('estelle.jardin@calvados.gouv.fr');
		$mail->AddAddress('marion.jourdan@calvados.gouv.fr');
		$mail->AddAddress('david.malo@calvados.gouv.fr');

		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;		
			$F = fopen("error.txt","a");
			fwrite($F,$item->id." ".$mail->ErrorInfo."\n");
			fclose($F);
		
		} else {
			echo "Message sent!";
			$F = fopen("send.txt","a");
			fwrite($F,$item->id."\n");
			fclose($F);
		}
		
		
	}
}
?>
	</tbody>
</table>