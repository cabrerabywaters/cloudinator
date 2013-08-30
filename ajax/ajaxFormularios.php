<?php
require_once('../JSON.php');
require_once('../DB/db.php');

$json = new Services_JSON();

if(isset($_POST['action'])) {
	
	if($_POST['action']=="delete"){
		try {
			$id = $_POST['id'];
			$sqltreescount = "SELECT count(id) FROM trees WHERE Formulario=$id";
			$data1 = DBQuery($sqltreescount);
			$maxtrees = mysql_result($data1, 0);
			
			$sqltrees = "SELECT id FROM trees WHERE Formulario=$id";
			$data2 = DBQuery($sqltrees);
			
			for ($i = 0; $i < $maxtrees; $i++) {
				$idtree= mysql_result($data2, $i, 'trees.id');
				$querydeletenodes = "DELETE FROM nodos WHERE tree=$idtree";	
				DBQuery($querydeletenodes);
			}
			//TODO:falta borrar links
			
			$querytree = "DELETE FROM trees WHERE Formulario=$id;";
			DBQuery($querytree);
			
			$queryFormulario = "DELETE FROM Formularios WHERE id=$id;";
			DBQuery($queryFormulario);
			
			$data = array(
			'result' => 'true',
			);
			print($json->encode($data));
		}catch (Exception $e) {
			print($json->encode($e));
		}
	}else if($_POST['action']== "setname"){
		try{
			$id = $_POST['id'];
			$query = "SELECT * FROM  Formularios WHERE id = $id";
			$datos = DBQueryReturnArray($query);
		
			$salida = $json->encode($datos);
			
		}catch (Exception $e){
			print('ERROR! '.$e);
		}
		print($salida);
		
	}
		
}else{

	try {	
		$query = 'SELECT * FROM  Formularios';
		$datos = DBQueryReturnArray($query);
		
		$salida = $json->encode($datos);
	
	} catch (Exception $e) {
		print('ERROR! '.$e);
	}
	
	print($salida);
}