<?php
require_once('../JSON.php');
require_once('../DB/db.php');

$json = new Services_JSON();

if(isset($_POST['type'])) {
	try {
		DBQuery("INSERT INTO `cloudinator`.`Formularios` (`id`, `name`, `chain`, `deleted`, `created`, `modified` ) VALUES 
			(NULL, '$_POST[name]', NULL, 0, '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."' );
			");

		//$data = DBQuery("SELECT id FROM Subformularios WHERE name = '$_POST[name]'");
		//print($json->encode(mysql_result($data, 0)));
		$data = array(
		'result' => 'true',
		);
		print($json->encode($data));
	
	} catch (Exception $e) {
		print($json->encode($e));
	}
	
}
else if(isset($_POST['clonename'])) {
	
	$sql = "SELECT id FROM Subformularios WHERE name = '$_POST[clonename]'";
	$data1 = DBQuery($sql);
	$idclone = mysql_result($data1, 0);
	
	$sql2 = "SELECT name, type, posx, posy FROM nodos WHERE Subformulario = $idclone";
	$data2 = DBQuery($sql2);
	
	$sql3 = "SELECT count(id) FROM nodos WHERE Subformulario = $idclone";
	$data3 = DBQuery($sql3);
	$max = mysql_result($data3, 0);
	
	DBQuery("INSERT INTO `cloudinator`.`Subformularios` (`id`, `name`,`Formulario`, `deleted`, `created`) VALUES 
			(NULL, '$_POST[name]', $_POST[to] ,0,'".date("Y-m-d H:i:s")."');
			");
	
	$sqlgetSubformulario = "SELECT id FROM Subformularios WHERE name = '$_POST[name]'";
	$data4 = DBQuery($sqlgetSubformulario);
	$idnew = mysql_result($data4, 0);
	
	
	$num = 0;
	for ($i = 0; $i < $max; $i++) {
		$name = mysql_result($data2, $i, 'nodos.name');
		$type = mysql_result($data2, $i, 'nodos.type');
		$posx = mysql_result($data2, $i, 'nodos.posx');
		$posy = mysql_result($data2, $i, 'nodos.posy');
		
		$query = "INSERT INTO `cloudinator`.`nodos` (`id`, `Subformulario`, `name`, `type`, `posx`, `posy`, `metaname`, `metadata`, `metatype`) VALUES 
				(NULL, $idnew, '$name', '$type', '$posx', '$posy', null, null, null);
				";
		DBQuery($query);
		
		$num++;
	}
	
	
	$linksprevquery = "SELECT source, target FROM links WHERE Subformulario = $idclone";
	$linksprev= DBQuery($linksprevquery);
	
	$countprevquery = "SELECT count(id) FROM links WHERE Subformulario = $idclone";
	$countprev = DBQuery($countprevquery);
	$maxlinks = mysql_result($countprev, 0);
	
	for ($i = 0; $i < $maxlinks; $i++) {
		$targeta = mysql_result($linksprev, $i, 'links.target');
		$querytarget = "select id
						from nodos
						where name= (select name from nodos where id = $targeta ) order by id desc";
		$newtarget = DBQuery($querytarget);
		$idnewtarget = mysql_result($newtarget, 0);
		
		$sourca = mysql_result($linksprev, $i, 'links.source');
		$querysource = "select id
						from nodos
						where name= (select name from nodos where id = $sourca ) order by id desc";
		$newsource = DBQuery($querysource);
		$idnewsource = mysql_result($newsource, 0);
		
		
		$query = "INSERT INTO `cloudinator`.`links` (`id`, `Subformulario`, `name`, `source`, `target`) VALUES 
				(NULL, $idnew, '', '$idnewsource', '$idnewtarget');";
				
		DBQuery($query);
		
	}
	
	
	$data = array(
		'result' => 'true',
	);
	print($json->encode($data));
	
	
}else if (isset($_POST['name'])) {
	try {
		DBQuery("INSERT INTO `cloudinator`.`Subformularios` (`id`, `name`, `Formulario`,`deleted`, `created`) VALUES 
			(NULL, '$_POST[name]',$_POST[Formulario] ,0, '".date("Y-m-d H:i:s")."');
			");
		$data = array(
			'result' => 'true',
		);
		print($json->encode($data));

		//$data = DBQuery("SELECT id FROM Subformularios WHERE name = '$_POST[name]'");
		//print($json->encode(mysql_result($data, 0)));
	} catch (Exception $e) {
		print($e);
	}
}else if(isset($_POST['action'])){
	
	try {
		DBQuery("DELETE FROM links WHERE Subformulario = $_POST[Subformulario]");
		DBQuery("DELETE FROM nodos WHERE Subformulario = $_POST[Subformulario]");
		DBQuery("DELETE FROM Subformularios WHERE id = $_POST[Subformulario]");
		$data = array(
			'result' => 'true',
		);
		print($json->encode($data));
	}catch (Exception $e){
		print($e);
	}

	

}else if(isset($_POST['nuevonombre'])){
	try {
		DBQuery("UPDATE Subformularios SET name = '$_POST[nuevonombre]' WHERE id = $_POST[Subformulario]");
		$data = array(
			'result' => 'true',
		);
		print($json->encode($data));
	}catch (Exception $e){
		print($e);
	}
	
	
}else{


	try {	
		$query = 'SELECT * FROM Subformularios';
		$datos = DBQueryReturnArray($query);
		$salida = $json->encode($datos);

	} catch (Exception $e) {
		print('ERROR! '.$e);
	}

	print($salida);
}

?> 