<?php
require_once('../DB/db.php');
 require_once('../JSON.php');

$json = new Services_JSON();
 
try {	
		 
	$sql = "SELECT * FROM empresas";
	 
	if ($empresas = DBquery3($sql)){
	    if (mysql_num_rows($empresas) > 0){
	    	
	        $data = array(
	        	'total' => mysql_num_rows($empresas)
			);
			
			for ($i = 0; $i < mysql_num_rows($empresas); $i++) {
				$data[$i] = array(
					'nombre' => mysql_result($empresas, $i, 'empresas.nombre'),
					'id' => mysql_result($empresas, $i, 'empresas.id')
				);
			}
			
			
			
	    }else{
	    	$data = array(
				'total' => 0
			);
	    	
	    }
	    
	}
	else{
	    $data = array(
				'total' => 0
		);
	}
	print($json->encode($data));
	
}catch (Exception $e) {
	$data = array(
				'total' => 0,
				'exception' => $e
			);
	print($json->encode($data));
}