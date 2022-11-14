<?php
require_once "config.php";
require_once "responseClass.php";
require "logWriter.php";

$response = new dbResponse;
$log = new logWriter();

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_FILES['blob']['name'];
    $filetype = $_FILES['blob']['type'];

    $size = $_POST['size'];
    $type = $_POST['type'];
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $tableName = $_POST['tableName'];

    $log->info("Filename = $name.\r\n");
    $log->info("id = $id.\r\n");
    $log->info("type = $type.\r\n");
    $log->info("filetype = $filetype.\r\n");
    
    $log->info("Started uploading file.\r\n");
    $fileblob = file_get_contents($_FILES['blob']['tmp_name']);
    $fileData =addslashes (file_get_contents($_FILES['blob']['tmp_name']));

    $sql = "SELECT * FROM `user_blob` WHERE user_id = $user_id and blob_type = '$type';";
    $result = mysqli_query($link, $sql);
    $num_rows = $result->num_rows;
    
    $log->info("num_rows = ".$num_rows);

    if($num_rows <= 0){
        $sql = "INSERT INTO `user_blob` (`blob`,`blob_type`,`file_type`,`user_id`)
        VALUES ('{$fileData}' ,  '$type' ,'$filetype', $user_id)";}
    else{
        $sql = "UPDATE `user_blob` SET `blob`='{$fileData}',`file_type`='$filetype',`updated_at`=sysdate() where `user_id`=$user_id and `blob_type` = '$type'";    
    
    }
    
    //$log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $id = mysqli_insert_id($link);
   
    if ($result) {
        $response->success = true;
        $response->message = "File added Successfully.";
    } 
    else {
        $response->success = false;
        $response->message = "Error: " . $sql . "<br>" . mysqli_error($link);
    }
    $log->info("Completed uploading file.\r\n");  

    mysqli_close($link);
    echo json_encode($response);
}
