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

function UpdateChildFoldersToTop($data){
    global $response;
    global $log;
    global $link;
    
    $log->info("Started update function.");

    $folder_id = trim($data->folder_id);

    $sql = "update folders set parent_folder_id = null where parent_folder_id = $folder_id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "update successfully!!!";
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

function updateDocumentsFolderToNull($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started update function.");

    $folder_id = trim($data->folder_id);

    $sql = "update documents set folder_id = null where folder_id = $folder_id; ";

    $log->info("sql".$sql."");

    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;
            $priority_id = $stmt->insert_id;
            $response->data = $stmt->insert_id;
            $response->message = "update successfully!!!";
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

function getAllMentions(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql =  "select tb.id,updated_at,tb.name,'buildings' content_type, ct.icon, ct.primary_color from buildings tb join content_types ct where ct.name = 'Buildings' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'characters' content_type, ct.icon, ct.primary_color from characters tb join content_types ct where ct.name = 'Characters' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'conditions' content_type, ct.icon, ct.primary_color from conditions tb join content_types ct where ct.name = 'Conditions' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'continents' content_type, ct.icon, ct.primary_color from continents tb join content_types ct where ct.name = 'Continents' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'countries' content_type, ct.icon, ct.primary_color from countries tb join content_types ct where ct.name = 'Countries' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'creatures' content_type, ct.icon, ct.primary_color from creatures tb join content_types ct where ct.name = 'Creatures' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'deities' content_type, ct.icon, ct.primary_color from deities tb join content_types ct where ct.name = 'Deities' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'floras' content_type, ct.icon, ct.primary_color from floras tb join content_types ct where ct.name = 'Floras' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'foods' content_type, ct.icon, ct.primary_color from foods tb join content_types ct where ct.name = 'Foods' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'governments' content_type, ct.icon, ct.primary_color from governments tb join content_types ct where ct.name = 'Governments' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'my_book.groups' content_type, ct.icon, ct.primary_color from my_book.groups tb join content_types ct where ct.name = 'Groups' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'items' content_type, ct.icon, ct.primary_color from items tb join content_types ct where ct.name = 'Items' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'jobs' content_type, ct.icon, ct.primary_color from jobs tb join content_types ct where ct.name = 'Jobs' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'landmarks' content_type, ct.icon, ct.primary_color from landmarks tb join content_types ct where ct.name = 'Landmarks' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'languages' content_type, ct.icon, ct.primary_color from languages tb join content_types ct where ct.name = 'Languages' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'locations' content_type, ct.icon, ct.primary_color from locations tb join content_types ct where ct.name = 'Locations' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'lores' content_type, ct.icon, ct.primary_color from lores tb join content_types ct where ct.name = 'Lores' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'magics' content_type, ct.icon, ct.primary_color from magics tb join content_types ct where ct.name = 'Magics' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'planets' content_type, ct.icon, ct.primary_color from planets tb join content_types ct where ct.name = 'Planets' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'races' content_type, ct.icon, ct.primary_color from races tb join content_types ct where ct.name = 'Races' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'religions' content_type, ct.icon, ct.primary_color from religions tb join content_types ct where ct.name = 'Religions' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'scenes' content_type, ct.icon, ct.primary_color from scenes tb join content_types ct where ct.name = 'Scenes' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'sports' content_type, ct.icon, ct.primary_color from sports tb join content_types ct where ct.name = 'Sports' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'technologies' content_type, ct.icon, ct.primary_color from technologies tb join content_types ct where ct.name = 'Technologies' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'towns' content_type, ct.icon, ct.primary_color from towns tb join content_types ct where ct.name = 'Towns' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'traditions' content_type, ct.icon, ct.primary_color from traditions tb join content_types ct where ct.name = 'Traditions' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'universes' content_type, ct.icon, ct.primary_color from universes tb join content_types ct where ct.name = 'Universes' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'vehicles' content_type, ct.icon, ct.primary_color from vehicles tb join content_types ct where ct.name = 'Vehicles' and user_id = $user_id union all " .
    "select tb.id,updated_at,tb.name,'organizations' content_type, ct.icon, ct.primary_color from organizations tb join content_types ct where ct.name = 'Organizations' and user_id = $user_id  ";

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

function getAllDocuments(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM documents Where user_id = '$user_id'";

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

function getAllDocumentsForFolderId(){
    $user_id = $_GET['user_id']; 
    $folder_id = $_GET['folder_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM documents Where user_id = '$user_id' and folder_id = $folder_id";

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

function getDocuments(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT d.*, us.name userName FROM documents d join users us on d.user_id = us.id Where user_id = '$user_id' and d.id = '$id'";

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

function addDocument($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$body = trim($data->body);
	$title = trim($data->title);
	$user_id = trim($data->user_id);
	$folder_id = trim($data->folder_id);
    

    $sql = "INSERT INTO documents(body,title,user_id,folder_id) VALUES('$body','$title','$user_id','$folder_id')";


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

function deleteDocument($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM documents WHERE id = $id; ";

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

function updateDocument($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$body = trim($data->body);
	$cached_word_count = trim($data->cached_word_count);
	$favorite = trim($data->favorite);
	$folder_id = trim($data->folder_id);
	$notes_text = trim($data->notes_text);
	$privacy = trim($data->privacy);
	$synopsis = trim($data->synopsis);
	$title = trim($data->title);
	$universe_id = trim($data->universe_id);


    $sql = "UPDATE documents SET 
body = '$body',cached_word_count = '$cached_word_count',favorite = '$favorite',folder_id = '$folder_id',notes_text = '$notes_text',privacy = '$privacy',synopsis = '$synopsis',title = '$title',universe_id = '$universe_id'    WHERE id = $id"; 

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

function GetAllChildFolders(){
    $user_id = $_GET['user_id']; 
    $folder_id = $_GET['folder_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM folders WHERE user_id = '$user_id' and parent_folder_id = $folder_id ";

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

function getAllFolders(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM folders WHERE parent_folder_id is not null or parent_folder_id > 0 and user_id = '$user_id'";

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

function getAllParentFolders(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM folders WHERE parent_folder_id is null or parent_folder_id <=0 and user_id = '$user_id'";

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

function getFolders(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM folders Where user_id = '$user_id' and id = '$id'";

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

function addFolder($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$context = trim($data->context);
	$parent_folder_id = trim($data->parent_folder_id);
	$title = trim($data->title);
	$user_id = trim($data->user_id);


    $sql = "INSERT INTO folders(context,parent_folder_id,title,user_id) 
VALUES('$context','$parent_folder_id','$title','$user_id')"; 


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

function deleteFolders($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM folders WHERE id = $id; ";

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

function updateFolder($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$context = trim($data->context);
	$parent_folder_id = trim($data->parent_folder_id);
	$title = trim($data->title);


    $sql = "UPDATE folders SET 
context = '$context',parent_folder_id = '$parent_folder_id',title = '$title'    WHERE id = $id"; 

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
    
    
	if ($procedureName == "UpdateChildFoldersToTop") {
		UpdateChildFoldersToTop($data);
	}
    
	if ($procedureName == "updateDocumentsFolderToNull") {
		updateDocumentsFolderToNull($data);
	}
    
	if ($procedureName == "addFolder") {
		addFolder($data);
	}

	if ($procedureName == "deleteFolders") {
		deleteFolders($data);
	}

	if ($procedureName == "addDocuments") {
		addDocument($data);
	}

	if ($procedureName == "deleteDocument") {
		deleteDocument($data);
	}


	mysqli_close($link);
	echo json_encode($response->data);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $procedureName = $_GET['procedureName'];

    
    
	if ($procedureName == "getAllParentFolders") {
		getAllParentFolders();
	}

	if ($procedureName == "GetAllChildFolders") {
		GetAllChildFolders();
	}

	if ($procedureName == "getAllDocumentsForFolderId") {
		getAllDocumentsForFolderId();
	}

	if ($procedureName == "getAllMentions") {
		getAllMentions();
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

    mysqli_close($link);
	echo json_encode($response->data);
}
?>