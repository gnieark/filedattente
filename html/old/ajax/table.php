<div class="add_delete_toolbar"></div>
<table class="display" cellspacing="0" width="100%" id="tbRHS">
	<thead>
		<tr>
			<th COLSPAN="7" ALIGN="CENTER" VALIGN="MIDDLE" style="background:#f1f1f1;">Signalement de l'agent</th>
			<th COLSPAN="6" ALIGN="CENTER" VALIGN="MIDDLE" style="background:#f9f9f9;">R&eacute;ponse de l'administration</th>
		</tr>
		<tr>
			<th>Date</th>
			<th >Nom(s) pr&eacute;nom(s)</th>
			<th >Affectation</th>
			<th >Localisation de l'incident</th>
			<!--<th >Cause</th>-->
			<th >Description de l'incident </th>
			<th >Suite de l'incident</th>
			<th >Fichier</th>
			<th >Date</th>
			<th >Nom de la personne qui r&eacute;pond</th>
			<th >R&eacute;ponse</th>
			<th >Vu en CHSCT du </th>
			<th >Obs. CHSCT </th>
			<th >Action(s) r&eacute;alis&eacute;e(s) </th>
			<th >Imprimer </th>
		</tr>
	</thead>
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
foreach($xml->children() as $item){
	echo "<tr id='qn-".$item->id."' class='tr_to_click'>";
	echo "<td><span style='display:none'>".usdate($item->date_signalement)."</span>".$item->date_signalement."</td>";
	echo "<td>".$item->name."</td>";
	echo "<td>".$item->affectation."</td>";
	echo "<td>".$item->localisation."</td>";
	// echo "<td>".$item->cause."</td>";
	echo "<td><div class='tablecell'>".$item->what_happened."</div></td>";
	echo "<td><div class='tablecell'>".$item->what_did_you_do."</div></td>";

	echo "<td>";
	if ($item->file != "") {
		echo "<a href='uploads/".$item->file."'><img src='./img/picto_dld.gif'></a>";
	}
	echo "</td>";
	echo "<td>".$item->date_reponse."</td>";
	echo "<td>".$item->name_reponse."</td>";
	echo "<td><div class='tablecell'>".$item->reponse."</div></td>";
	echo "<td>".$item->date_vu_chsct."</td>";	
	echo "<td><div class='tablecell'>".$item->obs_chsct."</div></td>";	
	echo "<td><div class='tablecell'>".$item->action."</div></td>";	
	echo "<td><a href=\"javascript:OuvrirFenetre('ajax/print.php?id=".$item->id."','popup','width=1000,height=900')\">Imprimer</a></td>";	
	echo "</tr>";
}
?>
	</tbody>
</table>