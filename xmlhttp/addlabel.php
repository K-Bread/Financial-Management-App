<?php
	include_once '../functions.php';
	checklogin();
	opendb();
	
	if($loggedin==1){
		$label=sanitise('label');
		//Need a long list of colours!
		$colourarray=array('#A00', '#0A0', '#565600', '#00A', '#0F7GEE');
		$query="SELECT * FROM labels WHERE UserID='$user'";
		$result=mysql_query($query) or die(mysql_error());
		while($row=mysql_fetch_assoc($result)){
			foreach($colourarray as $key=>$colour){
				if($row['Colour']==$colour){
					$colourarray[$key]=NULL;	
				}
			}
		}
		foreach($colourarray as $key=>$colour){
			if($pickedcolour==NULL){
				if($colour!=NULL){
					$pickedcolour=$colour;
				}
			}
		}
		$query="INSERT INTO labels (LabelName, Colour, UserID) VALUES ('$label', '$pickedcolour', '$user')";
		mysql_query($query) or die(mysql_error());
		echo "<span style='color:$pickedcolour'>".stripslashes($label)."</span>";
	}else{
		loginform();
	}
	
	
?>