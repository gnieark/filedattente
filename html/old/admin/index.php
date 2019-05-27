<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<title>ADMIN - RHS</title>
	<meta charset="iso-8859-1">

	<link rel="stylesheet" type="text/css" href="../theme.css" media="screen" />
	<link href="../css/flick/jquery-ui-1.10.0.custom.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" language="javascript" src="../jquery/jquery-1.9.0.js"></script>
	<script type="text/javascript" language="javascript" src="../jquery/jquery-ui-1.10.0.custom.js"></script>

	<script>
	function deleteItem(idItem){
		$.ajax({
			url : "../ajax/setup.php",
			data : {id:"delete",val:idItem},
			success : function(r){$("#"+idItem).parent().hide();}				
		});	
	}
	$(document).ready(function(){
		$('.delete').on('click',  function() {
			var idItem = $(this).attr("id");
			deleteItem(idItem)			
		});			
		$(".valid").click(function(){
			var inpt = $(this).parent().find("input");
			var id = $(inpt).attr("id");
			var val = $(inpt).val();
			$.ajax({
				url : "../ajax/setup.php",
				data : {id:id,val:val},
				success : function(r){
					$(inpt).animate({
						'backgroundColor': '#b3e17b',
						'color': 'red'
						}, 1500, function(){
							$(inpt).css({
								'backgroundColor': 'white',
								'color': 'black'
								})
							}		
						);
					$("."+id).find("ul").append('<li>'+val+'<img id="'+id+'_'+r+'" class="delete" src="../img/picto_stat_disabled.gif"></li>');					
					$('.delete').on('click',  function() {
						var idItem = $(this).attr("id");
						deleteItem(idItem)			
					});						
				}				
			});
		});
	});
	</script>
<head>
<body>
<?php
$xml = simplexml_load_file('../xml/setup.xml');

?>
<div id="content">
<table>
<tr><td>Mot de passe Reponse Administration :<br/><i>*laisser vide pour desactiver</i></td><td><input id="pwd" value="<?php echo (string)$xml->pwd; ?>"><img src="../img/picto_stat_enabled.gif" class="valid"></td></tr>
<tr><td>Ajouter un site :</td><td><input id="sites"><img src="../img/picto_stat_enabled.gif" class="valid"></td></tr>
<tr><td>Site :</td><td><div class="sites">
<?php
$sxe = $xml->xpath("//setup/sites");
echo "<ul>";
foreach($sxe[0]->children() as $item){
	echo "<li>".(string)$item." <img src='../img/picto_stat_disabled.gif' class='delete' id='sites_".$item["id"]."'></li>";
}
echo "</ul>";
?>
</div></td></tr>
<tr><td>Ajouter une adresse mail :</td><td><input id="mails"><img src="../img/picto_stat_enabled.gif" class="valid"></td></tr>
<tr><td>Adresse active :</td><td><div class="mails">
<?php
$sxe = $xml->xpath("//setup/mails");
echo "<ul>";
foreach($sxe[0]->children() as $item){
	echo "<li>".(string)$item." <img src='../img/picto_stat_disabled.gif' class='delete' id='mails_".$item["id"]."'></li>";
}
echo "</ul>";
?>
</div></td></tr>
</table>
</div>
</body>	