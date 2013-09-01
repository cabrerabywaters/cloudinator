<?php
require_once('../JSON.php');
require_once('../DB/db.php');

$json = new Services_JSON();

if(isset($_POST['action'])) {
	
	if($_POST['action']=="delete"){
		try {
			$db = DBConnect();
			$db->autocommit(FALSE);
			try {
				$id = $_POST['id'];
				$sqltrees = "SELECT id FROM trees WHERE megatree=$id";

				if(!$result = $db->query($sqltrees)){
				    throw new Exception('There was an error running the query [' . $db->error . ']', 1);
				}

				$querytree = "UPDATE trees SET deleted = 1 WHERE megatree=$id;";
				if(!$result = $db->query($querytree)){
				    throw new Exception('There was an error running the query [' . $db->error . ']', 1);
				}
				$querymegatree = "UPDATE megatrees SET deleted = 1 WHERE id=$id;";
				if(!$result = $db->query($querymegatree)){
				    throw new Exception('There was an error running the query [' . $db->error . ']', 1);
				}

			} catch (Exception $e) {
				$db->rollback();
				$db->close();
				throw new Exception("Error Processing Query", 1);
			}

			$db->commit();
			$db->close();
			//$result->free();
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
			$query = "SELECT * FROM  megatrees WHERE id = $id";
			$datos = DBQueryReturnArray($query);
		
			$salida = $json->encode($datos);
			
		}catch (Exception $e){
			print('ERROR! '.$e);
		}
		print($salida);
		
	}
		
}else{

	try {	
		$query = 'SELECT * FROM  megatrees WHERE deleted = 0';
		$datos = DBQueryReturnArray($query);
		
		$salida = $json->encode($datos);
	
	} catch (Exception $e) {
		print('ERROR! '.$e);
	}
	
	print($salida);
}