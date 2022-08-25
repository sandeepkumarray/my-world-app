<?php
require_once "config.php";
require_once "responseClass.php";
require "logWriter.php";

$response = new dbResponse;
$log = new logWriter();

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $object_name = $_FILES['object_blob']['name'];
    $object_type = $_FILES['object_blob']['type'];

    $object_size = $_POST['object_size'];
    $content_type = $_POST['content_type'];
    $content_id = $_POST['content_id'];

    $log->info("Filename = $object_name.\r\n");
    $log->info("content_id = $content_id.\r\n");
    $log->info("content_type = $content_type.\r\n");
    $log->info("filetype = $object_type.\r\n");
    
    $log->info("Started uploading file.\r\n");  
    $fileblob = file_get_contents($_FILES['object_blob']['tmp_name']);
    $fileData =addslashes (file_get_contents($_FILES['object_blob']['tmp_name']));
        
    $sql = "INSERT INTO `content_blob_object`(object_name, object_type, object_size, object_blob, created_at)
    VALUES ('$object_name' ,  '$object_type' , '$object_size', '{$fileData}', sysdate())";

    $result = mysqli_query($link, $sql);
    $object_id = mysqli_insert_id($link);
   
    if ($result) {

        $sql = "INSERT INTO content_object_attachment(`content_id`,`content_type`,`object_id`) 
        VALUES('$content_id','$content_type','$object_id')"; 

        $result2 = mysqli_query($link, $sql);
        if ($result2){
            $response->success = true;
            $response->message = "File added Successfully.";
        }
        else {
            $response->success = false;
            $response->message = "Error: " . $sql . "<br>" . mysqli_error($link);
        }
    } 
    else {
        $response->success = false;
        $response->message = "Error: " . $sql . "<br>" . mysqli_error($link);
    }
    
    $log->info("Completed uploading file.\r\n");  

    mysqli_close($link);
    echo json_encode($response);
}
