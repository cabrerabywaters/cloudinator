<?php
require_once('../JSON.php');
require_once('../DB/db.php');

$json = new Services_JSON();

if(isset($_POST['action'])) {
	
	if($_POST['action']=="delete"){
		try {
			$id = $_POST['id'];
			$sqlSubformularioscount = "SELECT count(id) FROM Subformularios WHERE Formulario=$id";
			$data1 = DBQuery($sqlSubformularioscount);
			$maxSubformularios = mysql_result($data1, 0);
			
			$sqlSubformularios = "SELECT id FROM Subformularios WHERE Formulario=$id";
			$data2 = DBQuery($sqlSubformularios);
			
			for ($i = 0; $i < $maxSubformularios; $i++) {
				$idSubformulario= mysql_result($data2, $i, 'Subformularios.id');
				$querydeletenodes = "DELETE FROM nodos WHERE Subformulario=$idSubformulario";	
				DBQuery($querydeletenodes);
			}
			//TODO:falta borrar links
			
			$querySubformulario = "DELETE FROM Subformularios WHERE Formulario=$id;";
			DBQuery($querySubformulario);
			
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