<?php
$xml = simplexml_load_file('xml/setup.xml');
$pwd = $xml->pwd;
$sxe = $xml->xpath("//setup/sites");
if (count($sxe[0]->children())>0){
	foreach($sxe[0]->children() as $item){
		$SITES[] = (string)$item;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<title>RHS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="fr" />

	<link rel="stylesheet" type="text/css" href="theme.css" media="screen" />
	<link href="css/flick/jquery-ui-1.10.0.custom.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" language="javascript" src="jquery/jquery-1.9.0.js"></script>
	<script type="text/javascript" language="javascript" src="jquery/jquery.ui.datepicker-fr.js"></script>
	<script type="text/javascript" language="javascript" src="jquery/jquery-ui-1.10.0.custom.js"></script>
	<link href="jquery/DataTables-1.9.4/media/css/classic.jquery.dataTables.css"rel="stylesheet" type="text/css">
	<link href="jquery/DataTables-1.9.4/extras/TableTools/media/css/TableTools.css"rel="stylesheet" type="text/css">
	<script type="text/javascript" language="javascript" src="jquery/DataTables-1.9.4/media/js/jquery.dataTables.js"></script>
	<!--<script type="text/javascript" language="javascript" src="jquery/DataTables-1.9.4/extras/jquery.jeditable.js"></script>-->
	<script type="text/javascript" src="jquery/DataTables-1.9.4/extras/TableTools/media/js/ZeroClipboard.js"></script>
	<script type="text/javascript" src="jquery/DataTables-1.9.4/extras/TableTools/media/js/TableTools.js"></script>

	<script>
	function OuvrirFenetre(url,nom,details) {window.open(url,nom,details)}
	function renderTable() {
			$.get("ajax/table.php",function(data,status){
			$("#content").html(data);
				var oTable = $("#tbRHS").dataTable({
				"sScrollX": "100%",
				"bScrollCollapse": true,
				"aaSorting": [[ 0, "desc" ]],
				"bJQueryUI": true,
				"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tout"]],
				"iDisplayLength": -1,
				"oLanguage": {
					"sLengthMenu": "Voir _MENU_ lignes par page",
					"sZeroRecords": "Aucun Enregistrement",
					"sInfo": "Voir _START_ a _END_ de _TOTAL_ lignes",
					"sInfoEmpty": "Aucun Enregisrement",
					"sInfoFiltered": "(Filtres de _MAX_ lignes au total)",
					 "sNext": "Prochain",
					 "sPrevious": "Précédent",
					  "sSearch": "Recherche"
				},
				"sDom": 'T<"clear">lfrtip',
				"oTableTools": {
					"sSwfPath": "jquery/DataTables-1.9.4/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
					}
				});
				$("#tbRHS tbody tr").click(function(){
				// $(".tablecell").click(function(){
					var cells = $(this).find("div");
					cells.each(function(k){
						if ( $(this).hasClass("tablecell")){
							$( this ).removeClass(  "tablecell" );
						} else {
							$( this ).addClass(  "tablecell" );
						}						
					});
			
				});
				$("#tbRHS tbody tr").dblclick(function(){
					var pwd = "<?php echo $pwd;?>";
					if (pwd != ""){
						var pass = prompt("Mot de passe", "");
						if (pass != pwd) {
							alert("mot de passe incorrect!");
							$( "#DivAdd" ).dialog( "close" );
							return false
						}
						
					}
					var arr = <?php echo json_encode($SITES);?>;
					var tr = $(this).attr('id');
					var id = $(this).attr('id').split("-")[1];
					var checkedAffect = $("#"+tr+" td:eq(2)").text();

					$("input[name=affectation]").get($.inArray( checkedAffect, arr )).checked = true;
					$('.buttonRadio').buttonset("refresh");
					
					$("#update").val(id);
					$("#name").val($("#"+tr+" td:eq(1)").text());
					$("#date_signalement").val($("#"+tr+" td:eq(0)").text().substr(8,25));
					$("#localisation").val($("#"+tr+" td:eq(3)").text());
					// $("#cause").val($("#"+tr+" td:eq(4)").text());
					$("#what_happened").val($("#"+tr+" td:eq(4)").text());
					$("#what_did_you_do").val($("#"+tr+" td:eq(5)").text());
					$("#date_reponse").val($("#"+tr+" td:eq(7)").text());
					$("#name_reponse").val($("#"+tr+" td:eq(8)").text());
					$("#reponse").val($("#"+tr+" td:eq(9)").text());
					$("#date_vu_chsct").val($("#"+tr+" td:eq(10)").text());
					$("#obs_chsct").val($("#"+tr+" td:eq(11)").text());
					$("#action").val($("#"+tr+" td:eq(12)").text());
					$(".trHidden").show();
					$( "#DivAdd" ).dialog( "open" );
				});
			});
			
	}
	$(document).ready(function(){
		var dialog;
		var hash = window.location.hash.substring(1);
		renderTable();
		$(".trHidden").hide();
		function valid() {
			var data = new FormData();
			$("#form1 input,textarea").each(function(index) {
				if ($(this).attr('type') != "radio") {
					data.append($(this).attr('id'),$(this).val());
				}

			});
			data.append("affectation",$('input[name=affectation]:checked').val());
			
			$.ajax({
				url:'ajax/rec.php',
				type: "POST",
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				success: function(rep){
							renderTable();
						}
			});
			dialog.dialog( "close" );
			$(".trHidden").hide();
			return true;
		};
		function deleteItem(){
			var id = $("#update").val();		
			dialog.dialog( "close" );
			$.ajax({
				url:"ajax/del.php",
				data:{del:id}
			});
			renderTable();		
		}
		
		$( ".buttonRadio" ).buttonset();	
		$("button").button();
		$(".date").datepicker();
		dialog = $( "#DivAdd" ).dialog({
			autoOpen: false,
			height: 850,
			width: 850,
			modal: true,
			buttons: {
				"Enregistrer": valid,
				"Annuler": function() {
					dialog.dialog( "close" );
					$(".trHidden").hide();
					$("#update").val(0);
					$("input[type='text']").val("");
					$("textarea").val("");
				},
				"Supprimer":deleteItem
			},
			close: function() {
				$("input[type='text']").val("");
				$("textarea").val("");
			}
		});
		$( "#opener" ).click(function() {
			$(".trHidden").hide();
			$("#update").val(0);
			$( "#DivAdd" ).dialog( "open" );

		});
		
		$('input[type=file]').change(function () {
			console.log($(this).val());
			var file = $(this)[0].files[0];
			if($(this).val() != ""){
				$(".selFile").text($(this).val());
				// $("#form1").submit();
				var data = new FormData();
				data.append('file',file);				
				$.ajax({
					url:"ajax/up.php",
					type:"POST",					
					data:data,
					cache: false,
					contentType: false,
					processData: false,
					success:function(d){
						console.log(d);
						$("#inptFile").val(d);
						$(".selFile").html("Fichier <a target='_blank' href='uploads/"+d+"'>"+d+"</a> ajout&#233;");
					}
				});
			}
		});
		$( "#modeEmploi" ).click(function() {		
				window.location.href  = "mode_emploi.html"
		});
		if(hash == "ecrire") {
			console.log("clic");
			$( "#opener" ).trigger("click");
			// $( "#DivAdd" ).dialog( "open" );
		}
	});
	</script>
<head>
<body>
<header>
	<div class="content-wrapper">
		<div class="">
			<button id="opener">ECRIRE DANS LE REGISTRE</button>
			<button id="modeEmploi">MODE D'EMPLOI</button>
		</div>
	</div>
</header>



<div id="DivAdd" style="margin:30px auto;" class="ui-widget-content ui-corner-all" title="Ajout dans le registre">

	<table id="form1" style="margin:10px auto;" >
	<tr><td class="ui-state">Nom Prénom</td><td><input type="text" id="name"></td></tr>
	<tr><td class="ui-state">Date</td><td><input type="text" readonly id="date_signalement" class="date"></td></tr>	
	<tr>
		<td class="ui-state">Affectation</td>
		<td>
		<div class="buttonRadio">
		<?php
			
			if (count($sxe[0]->children())>0){
				echo "<ul>";
				foreach($sxe[0]->children() as $item){
					// echo "<li>".(string)$item." <img src='../img/picto_stat_disabled.gif' class='delete' id='sites_".$item["id"]."'></li>";
					echo '<input type="radio" id="affectation_'.(string)$item.'" value="'.(string)$item.'" name="affectation"><label for="affectation_'.(string)$item.'">'.(string)$item.'</label>';
				}
				echo "</ul>";
			}
			else {
				echo "Auncun lieu défini";
			}
		?>
		</div>
		</td>
	</tr>
	<tr><td class="ui-state">Localisation de l'incident</td><td><input type="text" id="localisation"></td></tr>
	<!--<tr><td class="ui-state">Cause</td><td><input type="text" id="cause"></td></tr>-->
	<tr><td class="ui-state">Description de l'incident</td><td><textarea  id="what_happened" style="width:500px;"></textarea></td></tr>
	<tr><td class="ui-state">Suite de l'incident</td><td><textarea id="what_did_you_do"style="width:500px;"></textarea></td></tr>
	<tr><td class="ui-state">Ajouter un fichier </td>
		<td>
			<div class="upload">
				<input type="file" id="upload" name="upload" style="display:block;" >
				<input type="hidden" id="inptFile"   >
			</div>
			<h5 class="selFile">Choisir un fichier...</h5>			
		</td>
	</tr>	
	<tr class="trHidden"><td>Date</td><td><input class="date" id="date_reponse" ></td></tr>
	<tr class="trHidden"><td>Nom</td><td><input  id="name_reponse" ></td></tr>
	<!--<tr class="trHidden"><td>Reponse</td><td><input id="reponse"></td></tr>-->
	<tr class="trHidden"><td>Reponse</td><td><textarea   id="reponse" style="width:500px;"></textarea></td></tr>
	<tr class="trHidden"><td>Vu en CHSCT</td><td><input class="date" id="date_vu_chsct"></td></tr>
	<tr class="trHidden"><td>Observations éventuelles du CHS-CT</td><td><textarea class="" id="obs_chsct" style="width:500px;"></textarea></td></tr>
	<tr class="trHidden"><td>Action(s) r&eacute;alis&eacute;e(s)</td><td><textarea class="" id="action" style="width:500px;"></textarea></td></tr>
	<tr class="trHidden"><td></td><td><input id="update" type="hidden" value="0" ></td></tr>
	<tr><td colspan="2"><small><i>* champ obligatoire</i></small></td></tr>
	</table>
</div>

<div id="content">
<img src="img/wait.gif"  style="margin:auto">
</div>
</body>	