<?php
header("Access-Control-Allow-Origin: http://localhost:4500/");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

require_once "config.php";
require_once "responseClass.php";
require "logWriter.php";

$response = new dbResponse;
$log = new logWriter;

function loginUser($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$username = trim($data->username);
	$password = trim($data->password);
    $id=0;
    $sql = "CALL login_User('$username','$password',?)"; 
   
    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, 'i', $id);
        if(mysqli_stmt_execute($stmt)){            
            $result = mysqli_stmt_get_result($stmt);
            $myArray = null;
            while ($row = mysqli_fetch_assoc($result)) {
                $myArray = $row;
            }
            
            if($myArray == null){
                $response->data = null;
                $response->success = false;
                $response->message = "Username or password is wrong. <br> If You are new to the website please register.";
            }
            else{
                $response->data = $myArray;
                $response->success = true;
                $response->message = "Login successfully!!!";
            }
        } 
        else{
            $dberror= "DB Error: ".mysqli_stmt_error($stmt);
            $log->info("".$dberror."");
            $response->success = false;
            $response->message = "Something went wrong.Please try again later.";
        }
    }
            
    // Close statement
    mysqli_stmt_close($stmt);
            
    $log->info("Completed update function.");
}


function getAllAppConfig(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM app_config ";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $myArray[] = $row;
            }

            $response->success = true;
            $response->data = $myArray;
            $result->close();
        } else {
            $response->success = false;
            $response->message = "No data available in table";
        }
    } else {
        $response->success = false;
        $response->message = "Error: " . $sql . " < br > " . mysqli_error($link);
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	$data = $request->data;
	$procedureName = $data->procedureName;
	if ($procedureName == "loginUser") {
		loginUser($data);
	}

    
	mysqli_close($link);
	echo json_encode($response);
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $procedureName = $_GET['procedureName'];
	if ($procedureName == "getAllAppConfig") {
		getAllAppConfig();
	}

}
?>