<?php	
	if(isset($_FILES["file"])){
		$name = $_FILES['file']['name'];
		$uploadFile = $_SERVER['DOCUMENT_ROOT']."/RHS/uploads/".$name;
		$x = 0;
		while (file_exists($uploadFile)){
			$name = $x."_".$_FILES['file']['name'];
			$uploadFile = $_SERVER['DOCUMENT_ROOT']."/RHS/uploads/$name";
			$x++;
		}
		if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadFile )){
			echo $name;
		}	
		else{
			echo "ERROR $uploadFile";
		}
	}
?>	