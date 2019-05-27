<title>RHS</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link href="../jquery/DataTables-1.9.4/media/css/classic.jquery.dataTables.css"rel="stylesheet" type="text/css">-->
<!--<link href="../css/flick/jquery-ui-1.10.0.custom.css" rel="stylesheet" type="text/css">-->
<style>
table {
    border-collapse: collapse;
}

table, th, td {
    border: 2px solid black;
}
.ui-state-print{
	background:#ffcc99 none;
	font-weight: bold;
}
.ui-state-print-blue{
	background:#00ccff none;
	font-weight: bold;
}
.empty{
	border-left: hidden;
	border-right: hidden;
}
</style>
<?php	
$id = $_GET["id"];
?>	
<table class="display" cellspacing="0" width="100%" id="popTab" border="1">
	<tbody>
<?php 
$xml = simplexml_load_file('../xml/rhs.xml');
function usdate($d) {
    //on split sur le slash
    $D = explode('/',$d);
    //si 3 parties on reformat au format yyyymmdd
    if (count($D)==3){
        $dateUs = $D[2].$D[1].$D[0];
        return $dateUs;
    }
    //sinon on renvoie tel quel
    else{
        return $d;
    }
}
$items = $xml->xpath('item[@id="'.$id.'"]');
$item = $items[0];

echo "<tr  ALIGN='CENTER' VALIGN='MIDDLE'  class='ui-state-print-blue'><td colspan='3'>Fiche de signalement d'incident</td></tr>";
echo "<tr  ALIGN='CENTER' VALIGN='MIDDLE'  class='empty'><td colspan='3' style='height:10px;'></td></tr>";
// echo "<tr  ALIGN='CENTER' VALIGN='MIDDLE'  class='ui-state-print-blue'><td colspan='3'>".utf8_encode("N° ".$id."-".$item->date_signalement)."</td></tr>";
echo "<tr  ALIGN='CENTER' VALIGN='MIDDLE'  class='ui-state-print-blue'><td colspan='3'>".utf8_encode("N° ".$id."-".date("d/m/Y",(string)$item->timestamp))."</td></tr>";
echo "<tr  ALIGN='CENTER' VALIGN='MIDDLE'  class='ui-state-print'><td colspan='3'>Signalement de l'agent</td></tr>";
echo "<tr><td colspan='2' class='ui-state-print'>Date Incident</td><td>".$item->date_signalement."</td></tr>";
echo ("<tr><td colspan='2' class='ui-state-print' width='20%'>Nom Prenom</td><td>".$item->name."</td></tr>");
echo ("<tr><td colspan='2' class='ui-state-print'>Affectation</td><td>".$item->affectation."</td></tr>");
echo ("<tr><td colspan='2' class='ui-state-print'>Localisation</td><td>".$item->localisation."</td></tr>");
echo ("<tr><td colspan='2' class='ui-state-print'>PJ</td><td>".$item->file."</td></tr>");
// echo utf8_encode("<tr><td colspan='2' class='ui-state-print'>Cause</td><td>".$item->cause."</td></tr>");
echo ("<tr><td colspan='2' class='ui-state-print'>Description de l'incident</td><td>".$item->what_happened."</td></tr>");
echo ("<tr><td colspan='2' class='ui-state-print'>Suite de l'incident</td><td>".$item->what_did_you_do."</td></tr>");
echo "<tr  ALIGN='CENTER' VALIGN='MIDDLE'  class='empty'><td colspan='3' style='height:20px;'></td></tr>";
echo ("<tr align='CENTER'><td colspan='3' class='ui-state-print'>R&eacute;ponse de l'administration</td></tr>");
echo utf8_encode("<tr ><td colspan='2' class='ui-state-print'>Date</td><td>".$item->date_reponse."</td></tr>");
echo ("<tr><td colspan='2' class='ui-state-print'>Nom de la personne qui r&eacute;pond</td><td>".$item->name_reponse."</td></tr>");
echo ("<tr><td colspan='2' class='ui-state-print'>R&eacute;ponse</td><td>".$item->reponse."</td></tr>");
echo utf8_encode("<tr><td colspan='2' class='ui-state-print'>Vu en CHSCT du</td><td >".$item->date_vu_chsct."</td></tr>");
echo ("<tr><td colspan='2' class='ui-state-print'>Observations</td><td>".$item->obs_chsct."</td></tr>");
echo ("<tr><td colspan='2' class='ui-state-print'>Action(s) r&eacute;alis&eacute;e(s)</td><td>".$item->action."</td></tr>");


?>
	</tbody>
</table>
<i>CTRL+P pour impression</i>