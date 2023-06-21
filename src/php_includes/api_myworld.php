<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once "config.php";
require_once "responseClass.php";
require "logWriter.php";

$response = new dbResponse;
$log = new logWriter;

function getContentAttributes(){
    $contentType = $_GET['contentType'];
    $id = $_GET['id'];

    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM user_content_attributes Where content_type = '$contentType' and content_id =$id";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function createItemForUniverse($data){
    global $response;
    global $log;
    global $link;

	$content_type = trim($data->content_type);
	$user_id = trim($data->user_id);
	$universeId = trim($data->universeId);
	$name = trim($data->name);
    
    $sql = "INSERT INTO `$content_type`(name,created_at,user_id, universe) VALUES('$name',sysdate(), $user_id, $universeId)";

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            {                
                $sql = "INSERT INTO `user_content_attributes` (`universe_id`,`user_id`,`content_id`,`name`,`content_type`)
                 VALUES($universeId, $user_id, $stmt->insert_id, '$name','$content_type')";

                $log->info("sql".$sql."");
                
                if($stmt2 = mysqli_prepare($link, $sql)){
                    
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt2)){                       
                      
                    } 
                    else{
                        $dberror= "DB Error: ".mysqli_stmt_error($stmt2);
                        $log->info("".$dberror."");
                        $response->success = false;
                        $response->message = "Something went wrong.Please try again later.";
                    }
                }
            
            }
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Created successfully!!!";
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

function getContentsForUniverse(){
    $user_id = $_GET['user_id']; 
    $id = $_GET['id']; 
    global $response;
    global $log;
    global $link;

    $sql = "CALL get_universe_content('$user_id', '$id')"; 
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

function deleteContent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $content_type = trim($data->content_type);
    $content_id = trim($data->content_id);

    $sql = "DELETE FROM `$content_type` WHERE id = $content_id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function getAllContentBlobObject(){    
    $user_id = $_GET['user_id'];
    
    global $response;
    global $log;
    global $link;

    $sql = "SELECT cbo.`object_id`,`object_name`,`object_type`,`object_size`,`created_at`,coa.`content_type`,coa.`content_id`,
    ct.icon, ct.primary_color, ct.fa_icon,
    uca.name,`object_blob`
        FROM `content_blob_object` cbo
        Join content_object_attachment coa on cbo.object_id = coa.object_id
        join content_types ct on coa.`content_type` = ct.name
        inner Join user_content_attributes uca on coa.content_id = uca.content_id and uca.content_type = coa.content_type
        Where coa.user_id = $user_id";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    $log->info("row_cnt = ".$row_cnt);
    if ($result) {
        if ($row_cnt > 0) {
            $myArray = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $log->info("row loop");
                $obj = new stdClass();
                $obj->object_id = $row['object_id'];
                $obj->object_name = $row['object_name'];
                $obj->object_type = $row['object_type'];
                $obj->object_size = $row['object_size'];
                $obj->created_at = $row['created_at'];
                $obj->content_type = $row['content_type'];
                $obj->content_id = $row['content_id'];
                $obj->name = $row['name'];
                $obj->icon = $row['icon'];
                $obj->primary_color = $row['primary_color'];
                $obj->fa_icon = $row['fa_icon'];
                $obj->object_blob = base64_encode($row['object_blob']);
                array_push($myArray, $obj);
            }
            $response->success = true;
            $response->data = $myArray;
            $result->close();
        } else {
            $log->info("no data");
            $response->success = false;
            $response->message = "No data available in table";
        }
    } else {
        $log->info("no result");
        $response->success = false;
        $response->message = "Error: " . $sql . " < br > " . mysqli_error($link);
    }
}

function getUserBlob(){
    $user_id = $_GET['user_id'];
    $blob_type = $_GET['blob_type'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT `user_id`, `blob`, `blob_type`, `file_type`, `created_at`, `updated_at` FROM `user_blob` WHERE user_id = '$user_id' and blob_type = '$blob_type'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {

            while($obj = mysqli_fetch_object($result)){
                $log->info("user_id: ".$obj->user_id."\n");
                $log->info("blob_type: ".$obj->blob_type."\n");
                $log->info("file_type: ".$obj->file_type."\n");
                $log->info("created_at: ".$obj->created_at."\n");
                $log->info("updated_at: ".$obj->updated_at."\n");
                $objClass = new stdClass();
                $objClass->blob_type = $obj->blob_type;
                $objClass->file_type = $obj->file_type;
                $objClass->created_at = $obj->created_at;
                $objClass->updated_at = $obj->updated_at;
                $objClass->blob =  base64_encode($obj->blob);
             }
            // $row = $result->fetch_object();
           
            // $myArray = $obj;

            $response->success = true;
            $response->data = $objClass;
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

function addUserBlob($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$blob = $data->blob == null ? NULL : $data->blob; 
	$blob_type = $data->blob_type == null ? NULL : $data->blob_type; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO user_blob(blob,blob_type,user_id) 
VALUES('$blob','$blob_type',$user_id)"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteUserBlob($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM user_blob WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function createContent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started createContent function.");

	$content_type = trim($data->content_type);
	$jsonData = trim($data->jsonData);
	$user_id = trim($data->user_id);

    $sqlInsertValues="";
    $sqlInsertColumns="";

    $arrayInsertValues = array();
    $arrayInsertColumns = array();

    $json = json_decode($jsonData, TRUE);
    $columns = implode(", ",array_keys($json));
    
    $values  = implode("', '", array_values($json));
    $sql = "INSERT INTO `$content_type`($columns) VALUES ('$values')";
    
    foreach($json as $key => $val) {
        $log->info("KEY IS: $key<br/>");
        $log->info("VALUE IS: $val<br/>");        
    }

    
    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Created successfully!!!";
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
            
    $log->info("Completed createContent function.");
}

function getRecents(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "CALL get_recents('$user_id')"; 
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

function addUserContentAttributes($data){
    global $response;
    global $log;
    global $link;

    $content_type = $data->content_type == null ? NULL : $data->content_type; 
    $user_id = $data->user_id == null ? NULL : $data->user_id; 
    $name = $data->name == null ? NULL : $data->name; 
    $universe_id = $data->universe_id == null ? NULL : $data->universe_id;

    $content_id = $data->content_id == null ? NULL : $data->content_id; 
    
    $sql = "INSERT INTO `user_content_attributes` (`user_id`,`content_id`,`name`,`content_type`,universe_id)
    VALUES($user_id, $content_id, '$name','$content_type',$data->universe_id)";

   $log->info("sql".$sql."");
   
   if($stmt = mysqli_prepare($link, $sql)){
       
       // Attempt to execute the prepared statement
       if(mysqli_stmt_execute($stmt)){                       
        $response->success = true;
        $priority_id = $stmt->insert_id;
        $response->data = $stmt->insert_id;
        $response->message = "Created successfully!!!";
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

function updateContentAttribute($data){
    global $response;
    global $log;
    global $link;

    $content_type = $data->content_type == null ? NULL : $data->content_type; 
    $attributeType = $data->attributeType == null ? NULL : $data->attributeType; 
    $attributeValue = $data->attributeValue == null ? NULL : $data->attributeValue; 
    $content_id = $data->content_id == null ? NULL : $data->content_id; 
    
    $sql = "UPDATE user_content_attributes SET 
    $attributeType = '$attributeValue' WHERE content_type = '$content_type' and content_id = $content_id"; 
    
        $log->info("sql".$sql."");
                    
        if($stmt = mysqli_prepare($link, $sql)){
              
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $response->success = true;
                $priority_id = $stmt->insert_id;
                $response->data = $stmt->insert_id;
                $response->message = "Updated successfully!!!";
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

function createItem($data){
    global $response;
    global $log;
    global $link;

	$content_type = trim($data->content_type);
	$user_id = trim($data->user_id);
	$name = trim($data->name);
    
    $sql = "INSERT INTO `$content_type`(name,created_at,user_id) VALUES('$name',sysdate(), $user_id)";

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            {                
                $sql = "INSERT INTO `user_content_attributes` (`universe_id`,`user_id`,`content_id`,`name`,`content_type`)
                 VALUES(null, $user_id, $stmt->insert_id, '$name','$content_type')";

                $log->info("sql".$sql."");
                
                if($stmt2 = mysqli_prepare($link, $sql)){
                    
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt2)){                       
                      
                    } 
                    else{
                        $dberror= "DB Error: ".mysqli_stmt_error($stmt2);
                        $log->info("".$dberror."");
                        $response->success = false;
                        $response->message = "Something went wrong.Please try again later.";
                    }
                }
            
            }
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Created successfully!!!";
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

function getDashboard(){
    global $response;
    global $log;
    global $link;
    $user_id = $_GET['user_id']; 

    $log->info("Started save function.");

	//$user_id = trim($data->user_id);
    $id=0;
    $sql = "CALL get_dashboard('$user_id')"; 
   
    $log->info("sql".$sql."");
    if($stmt = mysqli_prepare($link, $sql)){
        //mysqli_stmt_bind_param($stmt, 'i', $id);
        if(mysqli_stmt_execute($stmt)){            
            $result = mysqli_stmt_get_result($stmt);
            $myArray = null;
            while ($row = mysqli_fetch_assoc($result)) {
                $myArray = $row;
            }
            
            if($myArray == null){
                $response->data = null;
                $response->success = false;
                $response->message = "Data not available.";
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

function getUserContentPlans(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT cp.* FROM user_plan up join content_plans cp on up.plan_id = cp.id WHERE user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    $log->info("row_cnt = ".$row_cnt);
    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function deleteContentBlobObject($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $object_id = trim($data->object_id);

    $sql = "DELETE FROM content_blob_object WHERE object_id = $object_id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            
            $sql = "DELETE FROM content_object_attachment WHERE object_id = $object_id; ";
            $result2 = mysqli_query($link, $sql);
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function getContentTypeCardImage(){
    $name = $_GET['name'];
    
    global $response;
    global $log;
    global $link;

    $sql = "SELECT `card_image` FROM `content_types` WHERE `name` = '$name'";

    $log->info("sql = $sql");
    
        $result = mysqli_query($link, $sql);
        $row_cnt = $result->num_rows;

        if ($result) {
            if ($row_cnt > 0) {

                $row = mysqli_fetch_array($result);

                $response->success = true;
                $response->data = base64_encode($row['card_image']);
                $response->message = "";
                $result->close();
            } else {
                $response->success = false;
                $response->message = "No data available in table";
            }
        }
        else {
            $response->success = false;
            $response->message = "Error: " . $sql . "<br>" . mysqli_error($link);
        }
}

function getContentBlobObject(){
    $content_id = $_GET['content_id'];
    $content_type = $_GET['content_type'];

    global $response;
    global $log;
    global $link;

    $sql = "SELECT cbo.`object_id`,`object_name`,`object_type`,`object_size`,`object_blob` FROM `content_blob_object` cbo
    Join content_object_attachment coa on cbo.object_id = coa.object_id 
Where coa.content_type = '$content_type' and coa.content_id = $content_id";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    $log->info("row_cnt = ".$row_cnt);
    if ($result) {
        if ($row_cnt > 0) {
            $myArray = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $log->info("row loop");
                $obj = new stdClass();
                $obj->object_id = $row['object_id'];
                $obj->object_name = $row['object_name'];
                $obj->object_type = $row['object_type'];
                $obj->object_size = $row['object_size'];
                $obj->object_blob =  base64_encode($row['object_blob']);
                array_push($myArray, $obj);
            }
            $response->success = true;
            $response->data = $myArray;
            $result->close();
        } else {
            $log->info("no data");
            $response->success = false;
            $response->message = "No data available in table";
        }
    } else {
        $log->info("no result");
        $response->success = false;
        $response->message = "Error: " . $sql . " < br > " . mysqli_error($link);
    }
}

function getUsersContentTemplate(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM user_content_template Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    $log->info("row_cnt = ".$row_cnt);
    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function getAppConfig(){
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM app_config Where id = '$id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addAppConfig($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$isactive = trim($data->isactive);
	$key = trim($data->key);
	$value = trim($data->value);


    $sql = "INSERT INTO app_config(isactive,key,value) 
VALUES('$isactive','$key','$value')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteAppConfig($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM app_config WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateAppConfig($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$isactive = trim($data->isactive);
	$key = trim($data->key);
	$value = trim($data->value);


    $sql = "UPDATE app_config SET 
isactive = '$isactive',key = '$key',value = '$value'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllUserContentTemplate(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM user_content_template Where user_id = '$user_id'";

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

function getUserContentTemplate(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM user_content_template Where user_id = '$user_id',id = '$id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addUserContentTemplate($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$template = trim($data->template);


    $sql = "INSERT INTO user_content_template(template) 
VALUES('$template')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteUserContentTemplate($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM user_content_template WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateUserContentTemplate($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started update function.");

	$template = addslashes($data->template);
	$id = trim($data->id);


    $sql = "UPDATE user_content_template SET 
template = '$template' WHERE id = $id";

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllUserContentBucket(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM user_content_bucket Where user_id = '$user_id'";

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

function getUserContentBucket(){
    $user_id = $_GET['user_id'];

    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM user_content_bucket Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addUserContentBucket($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$bucket_Name = trim($data->bucket_Name);
	$Universe = trim($data->Universe);


    $sql = "INSERT INTO user_content_bucket(bucket_Name,Universe) 
VALUES('$bucket_Name','$Universe')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteUserContentBucket($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM user_content_bucket WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateUserContentBucket($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$bucket_Name = trim($data->bucket_Name);
	$Universe = trim($data->Universe);


    $sql = "UPDATE user_content_bucket SET 
bucket_Name = '$bucket_Name',Universe = '$Universe'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllObjectStorageKeys(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM object_storage_keys ";

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

function getObjectStorageKeys(){
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM object_storage_keys Where id = '$id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addObjectstoragekey($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$accessKey = trim($data->accessKey);
	$bucketName = trim($data->bucketName);
	$Created_at = trim($data->Created_at);
	$endpoint = trim($data->endpoint);
	$location = trim($data->location);
	$secretKey = trim($data->secretKey);


    $sql = "INSERT INTO object_storage_keys(accessKey,bucketName,Created_at,endpoint,location,secretKey) 
VALUES('$accessKey','$bucketName','$Created_at','$endpoint','$location','$secretKey')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteObjectstoragekey($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM object_storage_keys WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateObjectstoragekey($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$accessKey = trim($data->accessKey);
	$bucketName = trim($data->bucketName);
	$Created_at = trim($data->Created_at);
	$endpoint = trim($data->endpoint);
	$location = trim($data->location);
	$secretKey = trim($data->secretKey);


    $sql = "UPDATE object_storage_keys SET 
accessKey = '$accessKey',bucketName = '$bucketName',Created_at = '$Created_at',endpoint = '$endpoint',location = '$location',secretKey = '$secretKey'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllContentObjectAttachment(){
    $content_id = $_GET['content_id']; 
    $content_type = $_GET['content_type']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM content_object_attachment coa
    JOIN `content_object` co on coa.object_id = co.object_id
    WHERE coa.content_id = $content_id AND coa.content_type = '$content_type'; ";

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

function getContentObjectAttachment(){


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM content_object_attachment ";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addContentObjectAttachment($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$content_id = trim($data->content_id);
	$content_type = trim($data->content_type);
	$object_id = trim($data->object_id);


    $sql = "INSERT INTO content_object_attachment(content_id,content_type,object_id) 
VALUES('$content_id','$content_type','$object_id')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteContentObjectAttachment($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM content_object_attachment WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateContentObjectAttachment($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$content_id = trim($data->content_id);
	$content_type = trim($data->content_type);
	$object_id = trim($data->object_id);


    $sql = "UPDATE content_object_attachment SET 
content_id = '$content_id',content_type = '$content_type',object_id = '$object_id'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllContentObject(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM content_object ";

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

function getContentObject(){


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM content_object ";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addContentObject($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$object_id = trim($data->object_id);
	$object_name = trim($data->object_name);
	$object_size = trim($data->object_size);
	$object_type = trim($data->object_type);


    $sql = "INSERT INTO content_object(object_id,object_name,object_size,object_type) 
VALUES('$object_id','$object_name','$object_size','$object_type')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteContentObject($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM content_object WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateContentObject($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$object_id = trim($data->object_id);
	$object_name = trim($data->object_name);
	$object_size = trim($data->object_size);
	$object_type = trim($data->object_type);


    $sql = "UPDATE content_object SET 
object_id = '$object_id',object_name = '$object_name',object_size = '$object_size',object_type = '$object_type'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllCharacterBirthtowns(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_birthtowns Where user_id = '$user_id'";

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

function getCharacterBirthtowns(){
    $user_id = $_GET['user_id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_birthtowns Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addCharacterbirthtown($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$birthtown_id = trim($data->birthtown_id);
	$character_id = trim($data->character_id);


    $sql = "INSERT INTO character_birthtowns(birthtown_id,character_id) 
VALUES('$birthtown_id','$character_id')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteCharacterbirthtown($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM character_birthtowns WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateCharacterbirthtown($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$birthtown_id = trim($data->birthtown_id);
	$character_id = trim($data->character_id);


    $sql = "UPDATE character_birthtowns SET 
birthtown_id = '$birthtown_id',character_id = '$character_id'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllCharacterCompanions(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_companions Where user_id = '$user_id'";

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

function getCharacterCompanions(){
    $user_id = $_GET['user_id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_companions Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addCharactercompanion($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$character_id = trim($data->character_id);
	$companion_id = trim($data->companion_id);


    $sql = "INSERT INTO character_companions(character_id,companion_id) 
VALUES('$character_id','$companion_id')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteCharactercompanion($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM character_companions WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateCharactercompanion($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$character_id = trim($data->character_id);
	$companion_id = trim($data->companion_id);


    $sql = "UPDATE character_companions SET 
character_id = '$character_id',companion_id = '$companion_id'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllCharacterEnemies(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_enemies Where user_id = '$user_id'";

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

function getCharacterEnemies(){
    $user_id = $_GET['user_id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_enemies Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addCharacterenemie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$character_id = trim($data->character_id);
	$enemy_id = trim($data->enemy_id);


    $sql = "INSERT INTO character_enemies(character_id,enemy_id) 
VALUES('$character_id','$enemy_id')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteCharacterenemie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM character_enemies WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateCharacterenemie($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$character_id = trim($data->character_id);
	$enemy_id = trim($data->enemy_id);


    $sql = "UPDATE character_enemies SET 
character_id = '$character_id',enemy_id = '$enemy_id'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllCharacterFloras(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_floras Where user_id = '$user_id'";

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

function getCharacterFloras(){
    $user_id = $_GET['user_id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_floras Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addCharacterflora($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$character_id = trim($data->character_id);
	$flora_id = trim($data->flora_id);


    $sql = "INSERT INTO character_floras(character_id,flora_id) 
VALUES('$character_id','$flora_id')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteCharacterflora($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM character_floras WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateCharacterflora($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$character_id = trim($data->character_id);
	$flora_id = trim($data->flora_id);


    $sql = "UPDATE character_floras SET 
character_id = '$character_id',flora_id = '$flora_id'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllCharacterFriends(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_friends Where user_id = '$user_id'";

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

function getCharacterFriends(){
    $user_id = $_GET['user_id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_friends Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addCharacterfriend($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$character_id = trim($data->character_id);
	$friend_id = trim($data->friend_id);


    $sql = "INSERT INTO character_friends(character_id,friend_id) 
VALUES('$character_id','$friend_id')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteCharacterfriend($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM character_friends WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateCharacterfriend($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$character_id = trim($data->character_id);
	$friend_id = trim($data->friend_id);


    $sql = "UPDATE character_friends SET 
character_id = '$character_id',friend_id = '$friend_id'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllCharacterItems(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_items Where user_id = '$user_id'";

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

function getCharacterItems(){
    $user_id = $_GET['user_id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_items Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addCharacteritem($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$character_id = trim($data->character_id);
	$item_id = trim($data->item_id);


    $sql = "INSERT INTO character_items(character_id,item_id) 
VALUES('$character_id','$item_id')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteCharacteritem($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM character_items WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateCharacteritem($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$character_id = trim($data->character_id);
	$item_id = trim($data->item_id);


    $sql = "UPDATE character_items SET 
character_id = '$character_id',item_id = '$item_id'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllCharacterLoveInterests(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_love_interests Where user_id = '$user_id'";

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

function getCharacterLoveInterests(){
    $user_id = $_GET['user_id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_love_interests Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addCharacterloveinterest($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$character_id = trim($data->character_id);
	$love_interest_id = trim($data->love_interest_id);


    $sql = "INSERT INTO character_love_interests(character_id,love_interest_id) 
VALUES('$character_id','$love_interest_id')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteCharacterloveinterest($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM character_love_interests WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateCharacterloveinterest($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$character_id = trim($data->character_id);
	$love_interest_id = trim($data->love_interest_id);


    $sql = "UPDATE character_love_interests SET 
character_id = '$character_id',love_interest_id = '$love_interest_id'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllCharacterMagics(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_magics Where user_id = '$user_id'";

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

function getCharacterMagics(){
    $user_id = $_GET['user_id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_magics Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addCharactermagic($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$character_id = trim($data->character_id);
	$magic_id = trim($data->magic_id);


    $sql = "INSERT INTO character_magics(character_id,magic_id) 
VALUES('$character_id','$magic_id')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteCharactermagic($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM character_magics WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateCharactermagic($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$character_id = trim($data->character_id);
	$magic_id = trim($data->magic_id);


    $sql = "UPDATE character_magics SET 
character_id = '$character_id',magic_id = '$magic_id'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllCharacterTechnologies(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_technologies Where user_id = '$user_id'";

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

function getCharacterTechnologies(){
    $user_id = $_GET['user_id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM character_technologies Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addCharactertechnologie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$character_id = trim($data->character_id);
	$technology_id = trim($data->technology_id);


    $sql = "INSERT INTO character_technologies(character_id,technology_id) 
VALUES('$character_id','$technology_id')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteCharactertechnologie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM character_technologies WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateCharactertechnologie($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$character_id = trim($data->character_id);
	$technology_id = trim($data->technology_id);


    $sql = "UPDATE character_technologies SET 
character_id = '$character_id',technology_id = '$technology_id'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllContentPlans(){
    
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM content_plans ";

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

function getContentPlans(){
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM content_plans Where id = '$id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addContentplan($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$available = trim($data->available);
	$buildings_count = trim($data->buildings_count);
	$characters_count = trim($data->characters_count);
	$conditions_count = trim($data->conditions_count);
	$contents = trim($data->contents);
	$continents_count = trim($data->continents_count);
	$countries_count = trim($data->countries_count);
	$created_by = trim($data->created_by);
	$created_date = trim($data->created_date);
	$creatures_count = trim($data->creatures_count);
	$deities_count = trim($data->deities_count);
	$floras_count = trim($data->floras_count);
	$foods_count = trim($data->foods_count);
	$governments_count = trim($data->governments_count);
	$groups_count = trim($data->groups_count);
	$items_count = trim($data->items_count);
	$jobs_count = trim($data->jobs_count);
	$landmarks_count = trim($data->landmarks_count);
	$languages_count = trim($data->languages_count);
	$locations_count = trim($data->locations_count);
	$lores_count = trim($data->lores_count);
	$magics_count = trim($data->magics_count);
	$monthly_cents = trim($data->monthly_cents);
	$name = trim($data->name);
	$plan_description = trim($data->plan_description);
	$plan_template = trim($data->plan_template);
	$planets_count = trim($data->planets_count);
	$races_count = trim($data->races_count);
	$religions_count = trim($data->religions_count);
	$scenes_count = trim($data->scenes_count);
	$sports_count = trim($data->sports_count);
	$technologies_count = trim($data->technologies_count);
	$towns_count = trim($data->towns_count);
	$traditions_count = trim($data->traditions_count);
	$universes_count = trim($data->universes_count);
	$vehicles_count = trim($data->vehicles_count);


    $sql = "INSERT INTO content_plans(available,buildings_count,characters_count,conditions_count,contents,continents_count,countries_count,created_by,created_date,creatures_count,deities_count,floras_count,foods_count,governments_count,groups_count,items_count,jobs_count,landmarks_count,languages_count,locations_count,lores_count,magics_count,monthly_cents,name,plan_description,plan_template,planets_count,races_count,religions_count,scenes_count,sports_count,technologies_count,towns_count,traditions_count,universes_count,vehicles_count) 
VALUES('$available','$buildings_count','$characters_count','$conditions_count','$contents','$continents_count','$countries_count','$created_by','$created_date','$creatures_count','$deities_count','$floras_count','$foods_count','$governments_count','$groups_count','$items_count','$jobs_count','$landmarks_count','$languages_count','$locations_count','$lores_count','$magics_count','$monthly_cents','$name','$plan_description','$plan_template','$planets_count','$races_count','$religions_count','$scenes_count','$sports_count','$technologies_count','$towns_count','$traditions_count','$universes_count','$vehicles_count')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteContentplan($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM content_plans WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateContentplan($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$available = trim($data->available);
	$buildings_count = trim($data->buildings_count);
	$characters_count = trim($data->characters_count);
	$conditions_count = trim($data->conditions_count);
	$contents = trim($data->contents);
	$continents_count = trim($data->continents_count);
	$countries_count = trim($data->countries_count);
	$created_by = trim($data->created_by);
	$created_date = trim($data->created_date);
	$creatures_count = trim($data->creatures_count);
	$deities_count = trim($data->deities_count);
	$floras_count = trim($data->floras_count);
	$foods_count = trim($data->foods_count);
	$governments_count = trim($data->governments_count);
	$groups_count = trim($data->groups_count);
	$items_count = trim($data->items_count);
	$jobs_count = trim($data->jobs_count);
	$landmarks_count = trim($data->landmarks_count);
	$languages_count = trim($data->languages_count);
	$locations_count = trim($data->locations_count);
	$lores_count = trim($data->lores_count);
	$magics_count = trim($data->magics_count);
	$monthly_cents = trim($data->monthly_cents);
	$name = trim($data->name);
	$plan_description = trim($data->plan_description);
	$plan_template = trim($data->plan_template);
	$planets_count = trim($data->planets_count);
	$races_count = trim($data->races_count);
	$religions_count = trim($data->religions_count);
	$scenes_count = trim($data->scenes_count);
	$sports_count = trim($data->sports_count);
	$technologies_count = trim($data->technologies_count);
	$towns_count = trim($data->towns_count);
	$traditions_count = trim($data->traditions_count);
	$universes_count = trim($data->universes_count);
	$vehicles_count = trim($data->vehicles_count);


    $sql = "UPDATE content_plans SET 
available = '$available',buildings_count = '$buildings_count',characters_count = '$characters_count',conditions_count = '$conditions_count',contents = '$contents',continents_count = '$continents_count',countries_count = '$countries_count',created_by = '$created_by',created_date = '$created_date',creatures_count = '$creatures_count',deities_count = '$deities_count',floras_count = '$floras_count',foods_count = '$foods_count',governments_count = '$governments_count',groups_count = '$groups_count',items_count = '$items_count',jobs_count = '$jobs_count',landmarks_count = '$landmarks_count',languages_count = '$languages_count',locations_count = '$locations_count',lores_count = '$lores_count',magics_count = '$magics_count',monthly_cents = '$monthly_cents',name = '$name',plan_description = '$plan_description',plan_template = '$plan_template',planets_count = '$planets_count',races_count = '$races_count',religions_count = '$religions_count',scenes_count = '$scenes_count',sports_count = '$sports_count',technologies_count = '$technologies_count',towns_count = '$towns_count',traditions_count = '$traditions_count',universes_count = '$universes_count',vehicles_count = '$vehicles_count'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllContentTypes(){
    global $response;
    global $log;
    global $link;

    $sql = "SELECT `id`, `name`, `icon`, `fa_icon`, `primary_color`, `sec_color`, `created_date`, `created_by` FROM content_types ";

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

function getContentTypes(){
    $name = $_GET['name'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT `id`, `name`, `icon`, `fa_icon`, `primary_color`, `sec_color`, `created_date`, `created_by` FROM content_types Where name = '$name'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addContenttype($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$created_by = trim($data->created_by);
	$created_date = trim($data->created_date);
	$fa_icon = trim($data->fa_icon);
	$icon = trim($data->icon);
	$name = trim($data->name);
	$primary_color = trim($data->primary_color);
	$sec_color = trim($data->sec_color);


    $sql = "INSERT INTO content_types(created_by,created_date,fa_icon,icon,name,primary_color,sec_color) 
VALUES('$created_by','$created_date','$fa_icon','$icon','$name','$primary_color','$sec_color')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteContenttype($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM content_types WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateContenttype($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$created_by = trim($data->created_by);
	$created_date = trim($data->created_date);
	$fa_icon = trim($data->fa_icon);
	$icon = trim($data->icon);
	$name = trim($data->name);
	$primary_color = trim($data->primary_color);
	$sec_color = trim($data->sec_color);


    $sql = "UPDATE content_types SET 
created_by = '$created_by',created_date = '$created_date',fa_icon = '$fa_icon',icon = '$icon',name = '$name',primary_color = '$primary_color',sec_color = '$sec_color'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllUserDetails(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM user_details Where user_id = '$user_id'";

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

function getUserDetails(){
    $user_id = $_GET['user_id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM user_details Where user_id = '$user_id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addUserdetail($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$last_seen_at = trim($data->last_seen_at);
	$latest_activity_at = trim($data->latest_activity_at);
	$moderation_state = trim($data->moderation_state);
	$moderation_state_changed_at = trim($data->moderation_state_changed_at);
	$posts_count = trim($data->posts_count);
	$topics_count = trim($data->topics_count);


    $sql = "INSERT INTO user_details(last_seen_at,latest_activity_at,moderation_state,moderation_state_changed_at,posts_count,topics_count) 
VALUES('$last_seen_at','$latest_activity_at','$moderation_state','$moderation_state_changed_at','$posts_count','$topics_count')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteUserdetail($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM user_details WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateUserdetail($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$last_seen_at = trim($data->last_seen_at);
	$latest_activity_at = trim($data->latest_activity_at);
	$moderation_state = trim($data->moderation_state);
	$moderation_state_changed_at = trim($data->moderation_state_changed_at);
	$posts_count = trim($data->posts_count);
	$topics_count = trim($data->topics_count);


    $sql = "UPDATE user_details SET 
last_seen_at = '$last_seen_at',latest_activity_at = '$latest_activity_at',moderation_state = '$moderation_state',moderation_state_changed_at = '$moderation_state_changed_at',posts_count = '$posts_count',topics_count = '$topics_count'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllUserPlan(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM user_plan Where user_id = '$user_id'";

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

function getUserPlan(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM user_plan Where user_id = '$user_id',id = '$id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addUserPlan($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$plan_id = trim($data->plan_id);


    $sql = "INSERT INTO user_plan(plan_id) 
VALUES('$plan_id')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteUserPlan($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM user_plan WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateUserPlan($data){
    global $response;
    global $log;
    global $link;
    
    $log->info("Started update function.");

	$plan_id = trim($data->plan_id);
	$user_id = trim($data->user_id);


    $sql = "UPDATE user_plan SET 
plan_id = '$plan_id'    WHERE user_id = $user_id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function getAllUsers(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM users ";

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

function getUsers(){
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM users Where id = '$id'";

    $log->info("sql = ".$sql);
    $result = mysqli_query($link, $sql);
    $row_cnt = $result->num_rows;

    if ($result) {
        if ($row_cnt > 0) {
            while ($row = $result->fetch_object()) {
                $myArray = $row;
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

function addUser($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$age = trim($data->age);
	$bio = trim($data->bio);
	$community_features_enabled = trim($data->community_features_enabled);
	$current_sign_in_at = trim($data->current_sign_in_at);
	$current_sign_in_ip = trim($data->current_sign_in_ip);
	$dark_mode_enabled = trim($data->dark_mode_enabled);
	$email = trim($data->email);
	$email_confirm = trim($data->email_confirm);
	$email_updates = trim($data->email_updates);
	$encrypted_password = trim($data->encrypted_password);
	$favorite_author = trim($data->favorite_author);
	$favorite_book = trim($data->favorite_book);
	$favorite_genre = trim($data->favorite_genre);
	$favorite_page_type = trim($data->favorite_page_type);
	$favorite_quote = trim($data->favorite_quote);
	$fluid_preference = trim($data->fluid_preference);
	$forum_administrator = trim($data->forum_administrator);
	$forum_moderator = trim($data->forum_moderator);
	$forums_badge_text = trim($data->forums_badge_text);
	$gender = trim($data->gender);
	$inspirations = trim($data->inspirations);
	$interests = trim($data->interests);
	$keyboard_shortcuts_preference = trim($data->keyboard_shortcuts_preference);
	$last_sign_in_at = trim($data->last_sign_in_at);
	$last_sign_in_ip = trim($data->last_sign_in_ip);
	$location = trim($data->location);
	$name = trim($data->name);
	$notification_updates = trim($data->notification_updates);
	$occupation = trim($data->occupation);
	$old_password = trim($data->old_password);
	$other_names = trim($data->other_names);
	$private_profile = trim($data->private_profile);
	$remember_created_at = trim($data->remember_created_at);
	$reset_password_sent_at = trim($data->reset_password_sent_at);
	$reset_password_token = trim($data->reset_password_token);
	$secure_code = trim($data->secure_code);
	$selected_billing_plan_id = trim($data->selected_billing_plan_id);
	$sign_in_count = trim($data->sign_in_count);
	$site_administrator = trim($data->site_administrator);
	$site_template = trim($data->site_template);
	$stripe_customer_id = trim($data->stripe_customer_id);
	$upload_bandwidth_kb = trim($data->upload_bandwidth_kb);
	$username = trim($data->username);
	$website = trim($data->website);


    $sql = "INSERT INTO users(age,bio,community_features_enabled,current_sign_in_at,current_sign_in_ip,dark_mode_enabled,email,email_confirm,email_updates,encrypted_password,favorite_author,favorite_book,favorite_genre,favorite_page_type,favorite_quote,fluid_preference,forum_administrator,forum_moderator,forums_badge_text,gender,inspirations,interests,keyboard_shortcuts_preference,last_sign_in_at,last_sign_in_ip,location,name,notification_updates,occupation,old_password,other_names,private_profile,remember_created_at,reset_password_sent_at,reset_password_token,secure_code,selected_billing_plan_id,sign_in_count,site_administrator,site_template,stripe_customer_id,upload_bandwidth_kb,username,website) 
VALUES('$age','$bio','$community_features_enabled','$current_sign_in_at','$current_sign_in_ip','$dark_mode_enabled','$email','$email_confirm','$email_updates','$encrypted_password','$favorite_author','$favorite_book','$favorite_genre','$favorite_page_type','$favorite_quote','$fluid_preference','$forum_administrator','$forum_moderator','$forums_badge_text','$gender','$inspirations','$interests','$keyboard_shortcuts_preference','$last_sign_in_at','$last_sign_in_ip','$location','$name','$notification_updates','$occupation','$old_password','$other_names','$private_profile','$remember_created_at','$reset_password_sent_at','$reset_password_token','$secure_code','$selected_billing_plan_id','$sign_in_count','$site_administrator','$site_template','$stripe_customer_id','$upload_bandwidth_kb','$username','$website')"; 


    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

function deleteUser($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM users WHERE id = $id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Deleted successfully!!!";
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
    $log->info("Completed delete function.");
}

function updateUser($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$age = trim($data->age);
	$bio = trim($data->bio);
	$community_features_enabled = trim($data->community_features_enabled);
	$current_sign_in_at = trim($data->current_sign_in_at);
	$current_sign_in_ip = trim($data->current_sign_in_ip);
	$dark_mode_enabled = trim($data->dark_mode_enabled);
	$email = trim($data->email);
	$email_confirm = trim($data->email_confirm);
	$email_updates = trim($data->email_updates);
	$encrypted_password = trim($data->encrypted_password);
	$favorite_author = trim($data->favorite_author);
	$favorite_book = trim($data->favorite_book);
	$favorite_genre = trim($data->favorite_genre);
	$favorite_page_type = trim($data->favorite_page_type);
	$favorite_quote = trim($data->favorite_quote);
	$fluid_preference = trim($data->fluid_preference);
	$forum_administrator = trim($data->forum_administrator);
	$forum_moderator = trim($data->forum_moderator);
	$forums_badge_text = trim($data->forums_badge_text);
	$gender = trim($data->gender);
	$inspirations = trim($data->inspirations);
	$interests = trim($data->interests);
	$keyboard_shortcuts_preference = trim($data->keyboard_shortcuts_preference);
	$last_sign_in_at = trim($data->last_sign_in_at);
	$last_sign_in_ip = trim($data->last_sign_in_ip);
	$location = trim($data->location);
	$name = trim($data->name);
	$notification_updates = trim($data->notification_updates);
	$occupation = trim($data->occupation);
	$old_password = trim($data->old_password);
	$other_names = trim($data->other_names);
	$private_profile = trim($data->private_profile);
	$remember_created_at = trim($data->remember_created_at);
	$reset_password_sent_at = trim($data->reset_password_sent_at);
	$reset_password_token = trim($data->reset_password_token);
	$secure_code = trim($data->secure_code);
	$selected_billing_plan_id = trim($data->selected_billing_plan_id);
	$sign_in_count = trim($data->sign_in_count);
	$site_administrator = trim($data->site_administrator);
	$site_template = trim($data->site_template);
	$stripe_customer_id = trim($data->stripe_customer_id);
	$upload_bandwidth_kb = trim($data->upload_bandwidth_kb);
	$username = trim($data->username);
	$website = trim($data->website);


    $sql = "UPDATE users SET 
age = '$age',bio = '$bio',community_features_enabled = '$community_features_enabled',current_sign_in_at = '$current_sign_in_at',current_sign_in_ip = '$current_sign_in_ip',dark_mode_enabled = '$dark_mode_enabled',email = '$email',email_confirm = '$email_confirm',email_updates = '$email_updates',encrypted_password = '$encrypted_password',favorite_author = '$favorite_author',favorite_book = '$favorite_book',favorite_genre = '$favorite_genre',favorite_page_type = '$favorite_page_type',favorite_quote = '$favorite_quote',fluid_preference = '$fluid_preference',forum_administrator = '$forum_administrator',forum_moderator = '$forum_moderator',forums_badge_text = '$forums_badge_text',gender = '$gender',inspirations = '$inspirations',interests = '$interests',keyboard_shortcuts_preference = '$keyboard_shortcuts_preference',last_sign_in_at = '$last_sign_in_at',last_sign_in_ip = '$last_sign_in_ip',location = '$location',name = '$name',notification_updates = '$notification_updates',occupation = '$occupation',old_password = '$old_password',other_names = '$other_names',private_profile = '$private_profile',remember_created_at = '$remember_created_at',reset_password_sent_at = '$reset_password_sent_at',reset_password_token = '$reset_password_token',secure_code = '$secure_code',selected_billing_plan_id = '$selected_billing_plan_id',sign_in_count = '$sign_in_count',site_administrator = '$site_administrator',site_template = '$site_template',stripe_customer_id = '$stripe_customer_id',upload_bandwidth_kb = '$upload_bandwidth_kb',username = '$username',website = '$website'    WHERE id = $id"; 

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "Updated successfully!!!";
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	$data = $request->data;
	$procedureName = $data->procedureName;
    
	if ($procedureName == "createItemForUniverse") {
		createItemForUniverse($data);
	}
	if ($procedureName == "updateContentAttribute") {
		updateContentAttribute($data);
	}

	if ($procedureName == "addUserContentAttributes") {
		addUserContentAttributes($data);
	}

	if ($procedureName == "deleteContent") {
		deleteContent($data);
	}

    if ($procedureName == "addUserBlob") {
		addUserBlob($data);
	}

	if ($procedureName == "deleteUserBlob") {
		deleteUserBlob($data);
	}

	if ($procedureName == "createContent") {
		createContent($data);
	}

	if ($procedureName == "createItem") {
		createItem($data);
	}

	if ($procedureName == "getDashboard") {
		getDashboard($data);
	}

	if ($procedureName == "deleteContentBlobObject") {
		deleteContentBlobObject($data);
	}

	if ($procedureName == "addContentBlobObject") {
		addContentBlobObject($data);
	}

	if ($procedureName == "addAppConfig") {
		addAppConfig($data);
	}

	if ($procedureName == "updateAppConfig") {
		updateAppConfig($data);
	}

	if ($procedureName == "deleteAppConfig") {
		deleteAppConfig($data);
	}

	if ($procedureName == "addUserContentTemplate") {
		addUserContentTemplate($data);
	}

	if ($procedureName == "updateUserContentTemplate") {
		updateUserContentTemplate($data);
	}

	if ($procedureName == "deleteUserContentTemplate") {
		deleteUserContentTemplate($data);
	}

	if ($procedureName == "addUserContentBucket") {
		addUserContentBucket($data);
	}

	if ($procedureName == "updateUserContentBucket") {
		updateUserContentBucket($data);
	}

	if ($procedureName == "deleteUserContentBucket") {
		deleteUserContentBucket($data);
	}

	if ($procedureName == "addObjectstoragekey") {
		addObjectstoragekey($data);
	}

	if ($procedureName == "updateObjectstoragekey") {
		updateObjectstoragekey($data);
	}

	if ($procedureName == "deleteObjectstoragekey") {
		deleteObjectstoragekey($data);
	}

	if ($procedureName == "addContentObjectAttachment") {
		addContentObjectAttachment($data);
	}

	if ($procedureName == "updateContentObjectAttachment") {
		updateContentObjectAttachment($data);
	}

	if ($procedureName == "deleteContentObjectAttachment") {
		deleteContentObjectAttachment($data);
	}

	if ($procedureName == "addContentObject") {
		addContentObject($data);
	}

	if ($procedureName == "updateContentObject") {
		updateContentObject($data);
	}

	if ($procedureName == "deleteContentObject") {
		deleteContentObject($data);
	}

	if ($procedureName == "addCharacterbirthtown") {
		addCharacterbirthtown($data);
	}

	if ($procedureName == "updateCharacterbirthtown") {
		updateCharacterbirthtown($data);
	}

	if ($procedureName == "deleteCharacterbirthtown") {
		deleteCharacterbirthtown($data);
	}

	if ($procedureName == "addCharactercompanion") {
		addCharactercompanion($data);
	}

	if ($procedureName == "updateCharactercompanion") {
		updateCharactercompanion($data);
	}

	if ($procedureName == "deleteCharactercompanion") {
		deleteCharactercompanion($data);
	}

	if ($procedureName == "addCharacterenemie") {
		addCharacterenemie($data);
	}

	if ($procedureName == "updateCharacterenemie") {
		updateCharacterenemie($data);
	}

	if ($procedureName == "deleteCharacterenemie") {
		deleteCharacterenemie($data);
	}

	if ($procedureName == "addCharacterflora") {
		addCharacterflora($data);
	}

	if ($procedureName == "updateCharacterflora") {
		updateCharacterflora($data);
	}

	if ($procedureName == "deleteCharacterflora") {
		deleteCharacterflora($data);
	}

	if ($procedureName == "addCharacterfriend") {
		addCharacterfriend($data);
	}

	if ($procedureName == "updateCharacterfriend") {
		updateCharacterfriend($data);
	}

	if ($procedureName == "deleteCharacterfriend") {
		deleteCharacterfriend($data);
	}

	if ($procedureName == "addCharacteritem") {
		addCharacteritem($data);
	}

	if ($procedureName == "updateCharacteritem") {
		updateCharacteritem($data);
	}

	if ($procedureName == "deleteCharacteritem") {
		deleteCharacteritem($data);
	}

	if ($procedureName == "addCharacterloveinterest") {
		addCharacterloveinterest($data);
	}

	if ($procedureName == "updateCharacterloveinterest") {
		updateCharacterloveinterest($data);
	}

	if ($procedureName == "deleteCharacterloveinterest") {
		deleteCharacterloveinterest($data);
	}

	if ($procedureName == "addCharactermagic") {
		addCharactermagic($data);
	}

	if ($procedureName == "updateCharactermagic") {
		updateCharactermagic($data);
	}

	if ($procedureName == "deleteCharactermagic") {
		deleteCharactermagic($data);
	}

	if ($procedureName == "addCharactertechnologie") {
		addCharactertechnologie($data);
	}

	if ($procedureName == "updateCharactertechnologie") {
		updateCharactertechnologie($data);
	}

	if ($procedureName == "deleteCharactertechnologie") {
		deleteCharactertechnologie($data);
	}

	if ($procedureName == "addContentplan") {
		addContentplan($data);
	}

	if ($procedureName == "updateContentplan") {
		updateContentplan($data);
	}

	if ($procedureName == "deleteContentplan") {
		deleteContentplan($data);
	}

	if ($procedureName == "addContenttype") {
		addContenttype($data);
	}

	if ($procedureName == "updateContenttype") {
		updateContenttype($data);
	}

	if ($procedureName == "deleteContenttype") {
		deleteContenttype($data);
	}

	if ($procedureName == "addDocument") {
		addDocument($data);
	}

	if ($procedureName == "updateDocument") {
		updateDocument($data);
	}

	if ($procedureName == "deleteDocument") {
		deleteDocument($data);
	}

	if ($procedureName == "addFolder") {
		addFolder($data);
	}

	if ($procedureName == "updateFolder") {
		updateFolder($data);
	}

	if ($procedureName == "deleteFolder") {
		deleteFolder($data);
	}

	if ($procedureName == "addUserdetail") {
		addUserdetail($data);
	}

	if ($procedureName == "updateUserdetail") {
		updateUserdetail($data);
	}

	if ($procedureName == "deleteUserdetail") {
		deleteUserdetail($data);
	}

	if ($procedureName == "addUserPlan") {
		addUserPlan($data);
	}

	if ($procedureName == "updateUserPlan") {
		updateUserPlan($data);
	}

	if ($procedureName == "deleteUserPlan") {
		deleteUserPlan($data);
	}

	if ($procedureName == "addUser") {
		addUser($data);
	}

	if ($procedureName == "updateUser") {
		updateUser($data);
	}

	if ($procedureName == "deleteUser") {
		deleteUser($data);
	}


	mysqli_close($link);
	echo json_encode($response->data);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$procedureName = $_GET['procedureName'];
    
    if ($procedureName == "getContentAttributes") {
		getContentAttributes();
	}

    if ($procedureName == "getContentsForUniverse") {
		getContentsForUniverse();
	}

	if ($procedureName == "getAllContentBlobObject") {
		getAllContentBlobObject();
	}
    
	if ($procedureName == "getUserBlob") {
		getUserBlob();
	}
    
    if ($procedureName == "getRecents") {
		getRecents();
	}

    if ($procedureName == "getDashboard") {
		getDashboard();
	}

    if ($procedureName == "getUserContentPlans") {
		getUserContentPlans();
	}

  if ($procedureName == "getContentTypeCardImage") {
		getContentTypeCardImage();
	}

  if ($procedureName == "getContentBlobObject") {
		getContentBlobObject();
	}

	if ($procedureName == "getUsersContentTemplate") {
		getUsersContentTemplate();
	}

	if ($procedureName == "getAllAppConfig") {
		getAllAppConfig();
	}

	if ($procedureName == "getAppConfig") {
		getAppConfig();
	}

	if ($procedureName == "getAllUserContentTemplate") {
		getAllUserContentTemplate();
	}

	if ($procedureName == "getUserContentTemplate") {
		getUserContentTemplate();
	}

	if ($procedureName == "getAllUserContentBucket") {
		getAllUserContentBucket();
	}

	if ($procedureName == "getUserContentBucket") {
		getUserContentBucket();
	}

	if ($procedureName == "getAllObjectStorageKeys") {
		getAllObjectStorageKeys();
	}

	if ($procedureName == "getObjectStorageKeys") {
		getObjectStorageKeys();
	}

	if ($procedureName == "getAllContentObjectAttachment") {
		getAllContentObjectAttachment();
	}

	if ($procedureName == "getContentObjectAttachment") {
		getContentObjectAttachment();
	}

	if ($procedureName == "getAllContentObject") {
		getAllContentObject();
	}

	if ($procedureName == "getContentObject") {
		getContentObject();
	}

	if ($procedureName == "getAllCharacterBirthtowns") {
		getAllCharacterBirthtowns();
	}

	if ($procedureName == "getCharacterBirthtowns") {
		getCharacterBirthtowns();
	}

	if ($procedureName == "getAllCharacterCompanions") {
		getAllCharacterCompanions();
	}

	if ($procedureName == "getCharacterCompanions") {
		getCharacterCompanions();
	}

	if ($procedureName == "getAllCharacterEnemies") {
		getAllCharacterEnemies();
	}

	if ($procedureName == "getCharacterEnemies") {
		getCharacterEnemies();
	}

	if ($procedureName == "getAllCharacterFloras") {
		getAllCharacterFloras();
	}

	if ($procedureName == "getCharacterFloras") {
		getCharacterFloras();
	}

	if ($procedureName == "getAllCharacterFriends") {
		getAllCharacterFriends();
	}

	if ($procedureName == "getCharacterFriends") {
		getCharacterFriends();
	}

	if ($procedureName == "getAllCharacterItems") {
		getAllCharacterItems();
	}

	if ($procedureName == "getCharacterItems") {
		getCharacterItems();
	}

	if ($procedureName == "getAllCharacterLoveInterests") {
		getAllCharacterLoveInterests();
	}

	if ($procedureName == "getCharacterLoveInterests") {
		getCharacterLoveInterests();
	}

	if ($procedureName == "getAllCharacterMagics") {
		getAllCharacterMagics();
	}

	if ($procedureName == "getCharacterMagics") {
		getCharacterMagics();
	}

	if ($procedureName == "getAllCharacterTechnologies") {
		getAllCharacterTechnologies();
	}

	if ($procedureName == "getCharacterTechnologies") {
		getCharacterTechnologies();
	}

	if ($procedureName == "getAllContentPlans") {
		getAllContentPlans();
	}

	if ($procedureName == "getContentPlans") {
		getContentPlans();
	}

	if ($procedureName == "getAllContentTypes") {
		getAllContentTypes();
	}

	if ($procedureName == "getContentTypes") {
		getContentTypes();
	}

	if ($procedureName == "getAllDocuments") {
		getAllDocuments();
	}

	if ($procedureName == "getDocuments") {
		getDocuments();
	}

	if ($procedureName == "getAllFolders") {
		getAllFolders();
	}

	if ($procedureName == "getFolders") {
		getFolders();
	}

	if ($procedureName == "getAllUserDetails") {
		getAllUserDetails();
	}

	if ($procedureName == "getUserDetails") {
		getUserDetails();
	}

	if ($procedureName == "getAllUserPlan") {
		getAllUserPlan();
	}

	if ($procedureName == "getUserPlan") {
		getUserPlan();
	}

	if ($procedureName == "getAllUsers") {
		getAllUsers();
	}

	if ($procedureName == "getUsers") {
		getUsers();
	}


	// Close connection
	mysqli_close($link);
	echo json_encode($response->data);
}


?>
