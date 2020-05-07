<?php
	/*
	 * Script:    DataTables server-side script for PHP and MySQL
	 * Copyright: 2010 - Allan Jardine
	 * License:   GPL v2 or BSD (3-point)
	 */
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'engine', 'browser', 'platform', 'version', 'grade' );
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id";
	
	/* DB table to use */
	$sTable = "ajax";
	
	/* Database connection information */
	$gaSql['server']     = "localhost";
	$gaSql['user']       = "root";
	$gaSql['password']   = "";
	$gaSql['db']         = "dmoney14_champ";
	$gaSql['table']		 = "images";

	

	$NAME_COLUMN = "image_file_name";

	$EMAIL_COLUMN = "image_url";

	$ID_COLUMN = "image_id";
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * MySQL connection
	 */
	$conn =  mysqli_connect( $gaSql['server'], $gaSql['user'], $gaSql['password'] , $gaSql['db'] ) or
		die( 'Could not open connection to server' );
	
	// mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
	// 	die( 'Could not select database '. $gaSql['db'] );
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	if ($_POST['action'] == 'edit' && $_POST['id']) {	
			$updateField='';
			if(isset($_POST['name'])) {
				$updateField.= " $NAME_COLUMN='".$_POST['name']."', ";
			}

			if(isset($_POST['email'])) {
				$updateField.= " $EMAIL_COLUMN='".$_POST['email']."' ";
			}

			if($updateField && $_POST['id']) {
				$sqlQuery = "UPDATE " . $gaSql['table'] . " SET $updateField WHERE $ID_COLUMN='" . $_POST['id'] . "'";	
				mysqli_query($conn, $sqlQuery) or die("database error:". mysqli_error($conn));	
				$data = array(
					"message"	=> "Record Updated",	
					"status" => 1
				);
				echo json_encode($sqlQuery);		
			}

		//please modify above source


		}
	if ($_POST['action'] == 'delete' && $_POST['ids']) {

		$sqlQuery = "DELETE FROM " . $gaSql['table'] . " WHERE $ID_COLUMN in (" .implode(',',$_POST['ids']).")";	
		mysqli_query($conn, $sqlQuery) or die("database error:". mysqli_error($conn));	
		$data = array(
			"message"	=> "Record Deleted",	
			"status" => 1
		);
		echo json_encode($data);	
	}

	}	
	else{
		$sqlQuery = "Select * FROM " . $gaSql['table'] . " WHERE 1=1";
	$output = [];
	$result = mysqli_query($conn, $sqlQuery) or die("database error:". mysqli_error($conn));	
	if ($result->num_rows > 0) {
    // output data of each row
	    while($row = $result->fetch_assoc()) {
	    	$output []= [
	    		'id' => $row[$ID_COLUMN],
	    		'name' => $row[$NAME_COLUMN],
	    		'email' => $row[$EMAIL_COLUMN],
	    		'active'	=> false,
	    		'operate' => true
	    	];
	        
	    }

	}

	echo json_encode( $output );
	}

	




?>