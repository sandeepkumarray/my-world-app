<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once "config.php";
require_once "responseClass.php";
require "logWriter.php";

$response = new dbResponse;
$log = new logWriter;


function getChangelogforContent(){
    $contentType = $_GET['contentType'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM content_change_events Where content_type = '$contentType' and content_id = $id order by created_at desc";

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

function addContentChangeEvent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$content_id = $data->content_id == null ? "NULL" : $data->content_id; 
	$changed_fields = $data->changed_fields == null ? NULL : $data->changed_fields; 
	$content_type = $data->content_type == null ? NULL : $data->content_type; 
	$action = $data->action == null ? NULL : $data->action; 
	$old_value = $data->old_value == null ? NULL : $data->old_value; 
	$new_value = $data->new_value == null ? NULL : $data->new_value; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO content_change_events(content_id,changed_fields,content_type,`action`,old_value,new_value,user_id) 
VALUES($content_id,'$changed_fields','$content_type','$action','$old_value','$new_value',$user_id)"; 


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

function getAllContentDataForUser(){
    $user_id = $_GET['user_id'];
	
    global $response;
    global $log;
    global $link;
	global $mysqli;
	
    $log->info("Started function.");

    $id=0;
    $sql = "CALL get_allContentData('$user_id')"; 
    $myArray=null;
    $log->info("sql".$sql."");
   
    // Execute multi query
    if (mysqli_multi_query($link, $sql)) {
    do {
        // Store first result set
        if ($result = mysqli_store_result($link)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $myArray[] = $row;
        }
        mysqli_free_result($result);
        }
        // if there are more result-sets, the print a divider
        if (mysqli_more_results($link)) {
            continue;
        }
        else{
            break;
        }
        //Prepare next result set
    } while (mysqli_next_result($link));
    $response->success = true;
    $response->data = $myArray;
    }
            
    $log->info("Completed function.");
}

function getAllContentTypeDataForUser(){
    $contentType = $_GET['contentType'];
    $user_id = $_GET['user_id'];

    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM `$contentType` Where user_id = '$user_id'";

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

function getContentDetailsFromTypeID(){
    $contentType = $_GET['contentType'];
    $id = $_GET['id'];

    global $response;
    global $log;
    global $link;

    $sql = "SELECT *, Name as content_name FROM `$contentType` Where id = '$id'";

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

function saveData($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save data function.");
    
	$id = trim($data->_id);
	$column_type = trim($data->column_type);
	$column_value = trim($data->column_value);
	$content_type = trim($data->content_type);

    $sql ="UPDATE `" . $content_type . "` SET `" . $column_type . "` = '" . $column_value . "' ,updated_at=sysdate() WHERE id = $id";

    $log->info("sql".$sql."");
                
    if($stmt = mysqli_prepare($link, $sql)){
          
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $response->success = true;            
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

function getAllBuildings(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM buildings Where user_id = '$user_id'";

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

function getBuildings(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM buildings Where user_id = '$user_id' and id = '$id'";

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

function addBuilding($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Capacity = $data->Capacity == null ? "NULL" : $data->Capacity; 
	$Price = $data->Price == null ? "NULL" : $data->Price; 
	$Floor_count = $data->Floor_count == null ? "NULL" : $data->Floor_count; 
	$Dimensions = $data->Dimensions == null ? "NULL" : $data->Dimensions; 
	$Constructed_year = $data->Constructed_year == null ? "NULL" : $data->Constructed_year; 
	$Construction_cost = $data->Construction_cost == null ? "NULL" : $data->Construction_cost; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_building = $data->Type_of_building == null ? NULL : $data->Type_of_building; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Owner = $data->Owner == null ? NULL : $data->Owner; 
	$Tenants = $data->Tenants == null ? NULL : $data->Tenants; 
	$Affiliation = $data->Affiliation == null ? NULL : $data->Affiliation; 
	$Facade = $data->Facade == null ? NULL : $data->Facade; 
	$Architectural_style = $data->Architectural_style == null ? NULL : $data->Architectural_style; 
	$Permits = $data->Permits == null ? NULL : $data->Permits; 
	$Purpose = $data->Purpose == null ? NULL : $data->Purpose; 
	$Address = $data->Address == null ? NULL : $data->Address; 
	$Architect = $data->Architect == null ? NULL : $data->Architect; 
	$Developer = $data->Developer == null ? NULL : $data->Developer; 
	$Notable_events = $data->Notable_events == null ? NULL : $data->Notable_events; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO buildings(Universe,Capacity,Price,Floor_count,Dimensions,Constructed_year,Construction_cost,Name,Description,Type_of_building,Alternate_names,Tags,Owner,Tenants,Affiliation,Facade,Architectural_style,Permits,Purpose,Address,Architect,Developer,Notable_events,Notes,Private_Notes,user_id) 
VALUES($Universe,$Capacity,$Price,$Floor_count,$Dimensions,$Constructed_year,$Construction_cost,'$Name','$Description','$Type_of_building','$Alternate_names','$Tags','$Owner','$Tenants','$Affiliation','$Facade','$Architectural_style','$Permits','$Purpose','$Address','$Architect','$Developer','$Notable_events','$Notes','$Private_Notes',$user_id)"; 


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

function deleteBuilding($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM buildings WHERE id = $id; ";

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

function updateBuilding($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Capacity = $data->Capacity == null ? "NULL" : $data->Capacity; 
	$Price = $data->Price == null ? "NULL" : $data->Price; 
	$Floor_count = $data->Floor_count == null ? "NULL" : $data->Floor_count; 
	$Dimensions = $data->Dimensions == null ? "NULL" : $data->Dimensions; 
	$Constructed_year = $data->Constructed_year == null ? "NULL" : $data->Constructed_year; 
	$Construction_cost = $data->Construction_cost == null ? "NULL" : $data->Construction_cost; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_building = $data->Type_of_building == null ? NULL : $data->Type_of_building; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Owner = $data->Owner == null ? NULL : $data->Owner; 
	$Tenants = $data->Tenants == null ? NULL : $data->Tenants; 
	$Affiliation = $data->Affiliation == null ? NULL : $data->Affiliation; 
	$Facade = $data->Facade == null ? NULL : $data->Facade; 
	$Architectural_style = $data->Architectural_style == null ? NULL : $data->Architectural_style; 
	$Permits = $data->Permits == null ? NULL : $data->Permits; 
	$Purpose = $data->Purpose == null ? NULL : $data->Purpose; 
	$Address = $data->Address == null ? NULL : $data->Address; 
	$Architect = $data->Architect == null ? NULL : $data->Architect; 
	$Developer = $data->Developer == null ? NULL : $data->Developer; 
	$Notable_events = $data->Notable_events == null ? NULL : $data->Notable_events; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE buildings SET Universe = $Universe,Capacity = $Capacity,Price = $Price,Floor_count = $Floor_count,Dimensions = $Dimensions,Constructed_year = $Constructed_year,Construction_cost = $Construction_cost
,Name = '$Name',Description = '$Description',Type_of_building = '$Type_of_building',Alternate_names = '$Alternate_names',Tags = '$Tags',Owner = '$Owner',Tenants = '$Tenants',Affiliation = '$Affiliation',Facade = '$Facade',Architectural_style = '$Architectural_style',Permits = '$Permits',Purpose = '$Purpose',Address = '$Address',Architect = '$Architect',Developer = '$Developer',Notable_events = '$Notable_events',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllCharacters(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM characters Where user_id = '$user_id'";

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

function getCharacters(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM characters Where user_id = '$user_id' and id = '$id'";

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

function addCharacter($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Favorite = $data->Favorite == null ? "NULL" : $data->Favorite; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Role = $data->Role == null ? NULL : $data->Role; 
	$Gender = $data->Gender == null ? NULL : $data->Gender; 
	$Age = $data->Age == null ? NULL : $data->Age; 
	$Height = $data->Height == null ? NULL : $data->Height; 
	$Weight = $data->Weight == null ? NULL : $data->Weight; 
	$Haircolor = $data->Haircolor == null ? NULL : $data->Haircolor; 
	$Hairstyle = $data->Hairstyle == null ? NULL : $data->Hairstyle; 
	$Facialhair = $data->Facialhair == null ? NULL : $data->Facialhair; 
	$Eyecolor = $data->Eyecolor == null ? NULL : $data->Eyecolor; 
	$Race = $data->Race == null ? NULL : $data->Race; 
	$Skintone = $data->Skintone == null ? NULL : $data->Skintone; 
	$Bodytype = $data->Bodytype == null ? NULL : $data->Bodytype; 
	$Identmarks = $data->Identmarks == null ? NULL : $data->Identmarks; 
	$Religion = $data->Religion == null ? NULL : $data->Religion; 
	$Politics = $data->Politics == null ? NULL : $data->Politics; 
	$Prejudices = $data->Prejudices == null ? NULL : $data->Prejudices; 
	$Occupation = $data->Occupation == null ? NULL : $data->Occupation; 
	$Pets = $data->Pets == null ? NULL : $data->Pets; 
	$Mannerisms = $data->Mannerisms == null ? NULL : $data->Mannerisms; 
	$Birthday = $data->Birthday == null ? NULL : $data->Birthday; 
	$Birthplace = $data->Birthplace == null ? NULL : $data->Birthplace; 
	$Education = $data->Education == null ? NULL : $data->Education; 
	$Background = $data->Background == null ? NULL : $data->Background; 
	$Fave_color = $data->Fave_color == null ? NULL : $data->Fave_color; 
	$Fave_food = $data->Fave_food == null ? NULL : $data->Fave_food; 
	$Fave_possession = $data->Fave_possession == null ? NULL : $data->Fave_possession; 
	$Fave_weapon = $data->Fave_weapon == null ? NULL : $data->Fave_weapon; 
	$Fave_animal = $data->Fave_animal == null ? NULL : $data->Fave_animal; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$Privacy = $data->Privacy == null ? NULL : $data->Privacy; 
	$Aliases = $data->Aliases == null ? NULL : $data->Aliases; 
	$Motivations = $data->Motivations == null ? NULL : $data->Motivations; 
	$Flaws = $data->Flaws == null ? NULL : $data->Flaws; 
	$Talents = $data->Talents == null ? NULL : $data->Talents; 
	$Hobbies = $data->Hobbies == null ? NULL : $data->Hobbies; 
	$Personality_type = $data->Personality_type == null ? NULL : $data->Personality_type; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO characters(Universe,Favorite,Name,Role,Gender,Age,Height,Weight,Haircolor,Hairstyle,Facialhair,Eyecolor,Race,Skintone,Bodytype,Identmarks,Religion,Politics,Prejudices,Occupation,Pets,Mannerisms,Birthday,Birthplace,Education,Background,Fave_color,Fave_food,Fave_possession,Fave_weapon,Fave_animal,Notes,Private_notes,Privacy,Aliases,Motivations,Flaws,Talents,Hobbies,Personality_type,user_id) 
VALUES($Universe,$Favorite,'$Name','$Role','$Gender','$Age','$Height','$Weight','$Haircolor','$Hairstyle','$Facialhair','$Eyecolor','$Race','$Skintone','$Bodytype','$Identmarks','$Religion','$Politics','$Prejudices','$Occupation','$Pets','$Mannerisms','$Birthday','$Birthplace','$Education','$Background','$Fave_color','$Fave_food','$Fave_possession','$Fave_weapon','$Fave_animal','$Notes','$Private_notes','$Privacy','$Aliases','$Motivations','$Flaws','$Talents','$Hobbies','$Personality_type',$user_id)"; 


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

function deleteCharacter($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM characters WHERE id = $id; ";

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

function updateCharacter($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Favorite = $data->Favorite == null ? "NULL" : $data->Favorite; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Role = $data->Role == null ? NULL : $data->Role; 
	$Gender = $data->Gender == null ? NULL : $data->Gender; 
	$Age = $data->Age == null ? NULL : $data->Age; 
	$Height = $data->Height == null ? NULL : $data->Height; 
	$Weight = $data->Weight == null ? NULL : $data->Weight; 
	$Haircolor = $data->Haircolor == null ? NULL : $data->Haircolor; 
	$Hairstyle = $data->Hairstyle == null ? NULL : $data->Hairstyle; 
	$Facialhair = $data->Facialhair == null ? NULL : $data->Facialhair; 
	$Eyecolor = $data->Eyecolor == null ? NULL : $data->Eyecolor; 
	$Race = $data->Race == null ? NULL : $data->Race; 
	$Skintone = $data->Skintone == null ? NULL : $data->Skintone; 
	$Bodytype = $data->Bodytype == null ? NULL : $data->Bodytype; 
	$Identmarks = $data->Identmarks == null ? NULL : $data->Identmarks; 
	$Religion = $data->Religion == null ? NULL : $data->Religion; 
	$Politics = $data->Politics == null ? NULL : $data->Politics; 
	$Prejudices = $data->Prejudices == null ? NULL : $data->Prejudices; 
	$Occupation = $data->Occupation == null ? NULL : $data->Occupation; 
	$Pets = $data->Pets == null ? NULL : $data->Pets; 
	$Mannerisms = $data->Mannerisms == null ? NULL : $data->Mannerisms; 
	$Birthday = $data->Birthday == null ? NULL : $data->Birthday; 
	$Birthplace = $data->Birthplace == null ? NULL : $data->Birthplace; 
	$Education = $data->Education == null ? NULL : $data->Education; 
	$Background = $data->Background == null ? NULL : $data->Background; 
	$Fave_color = $data->Fave_color == null ? NULL : $data->Fave_color; 
	$Fave_food = $data->Fave_food == null ? NULL : $data->Fave_food; 
	$Fave_possession = $data->Fave_possession == null ? NULL : $data->Fave_possession; 
	$Fave_weapon = $data->Fave_weapon == null ? NULL : $data->Fave_weapon; 
	$Fave_animal = $data->Fave_animal == null ? NULL : $data->Fave_animal; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$Privacy = $data->Privacy == null ? NULL : $data->Privacy; 
	$Aliases = $data->Aliases == null ? NULL : $data->Aliases; 
	$Motivations = $data->Motivations == null ? NULL : $data->Motivations; 
	$Flaws = $data->Flaws == null ? NULL : $data->Flaws; 
	$Talents = $data->Talents == null ? NULL : $data->Talents; 
	$Hobbies = $data->Hobbies == null ? NULL : $data->Hobbies; 
	$Personality_type = $data->Personality_type == null ? NULL : $data->Personality_type; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE characters SET Universe = $Universe,Favorite = $Favorite
,Name = '$Name',Role = '$Role',Gender = '$Gender',Age = '$Age',Height = '$Height',Weight = '$Weight',Haircolor = '$Haircolor',Hairstyle = '$Hairstyle',Facialhair = '$Facialhair',Eyecolor = '$Eyecolor',Race = '$Race',Skintone = '$Skintone',Bodytype = '$Bodytype',Identmarks = '$Identmarks',Religion = '$Religion',Politics = '$Politics',Prejudices = '$Prejudices',Occupation = '$Occupation',Pets = '$Pets',Mannerisms = '$Mannerisms',Birthday = '$Birthday',Birthplace = '$Birthplace',Education = '$Education',Background = '$Background',Fave_color = '$Fave_color',Fave_food = '$Fave_food',Fave_possession = '$Fave_possession',Fave_weapon = '$Fave_weapon',Fave_animal = '$Fave_animal',Notes = '$Notes',Private_notes = '$Private_notes',Privacy = '$Privacy',Aliases = '$Aliases',Motivations = '$Motivations',Flaws = '$Flaws',Talents = '$Talents',Hobbies = '$Hobbies',Personality_type = '$Personality_type'    WHERE id = $id"; 

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

function getAllConditions(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM conditions Where user_id = '$user_id'";

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

function getConditions(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM conditions Where user_id = '$user_id' and id = '$id'";

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

function addCondition($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_condition = $data->Type_of_condition == null ? NULL : $data->Type_of_condition; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Transmission = $data->Transmission == null ? NULL : $data->Transmission; 
	$Genetic_factors = $data->Genetic_factors == null ? NULL : $data->Genetic_factors; 
	$Environmental_factors = $data->Environmental_factors == null ? NULL : $data->Environmental_factors; 
	$Lifestyle_factors = $data->Lifestyle_factors == null ? NULL : $data->Lifestyle_factors; 
	$Epidemiology = $data->Epidemiology == null ? NULL : $data->Epidemiology; 
	$Duration = $data->Duration == null ? NULL : $data->Duration; 
	$Variations = $data->Variations == null ? NULL : $data->Variations; 
	$Prognosis = $data->Prognosis == null ? NULL : $data->Prognosis; 
	$Symptoms = $data->Symptoms == null ? NULL : $data->Symptoms; 
	$Mental_effects = $data->Mental_effects == null ? NULL : $data->Mental_effects; 
	$Visual_effects = $data->Visual_effects == null ? NULL : $data->Visual_effects; 
	$Prevention = $data->Prevention == null ? NULL : $data->Prevention; 
	$Treatment = $data->Treatment == null ? NULL : $data->Treatment; 
	$Medication = $data->Medication == null ? NULL : $data->Medication; 
	$Immunization = $data->Immunization == null ? NULL : $data->Immunization; 
	$Diagnostic_method = $data->Diagnostic_method == null ? NULL : $data->Diagnostic_method; 
	$Symbolism = $data->Symbolism == null ? NULL : $data->Symbolism; 
	$Specialty_Field = $data->Specialty_Field == null ? NULL : $data->Specialty_Field; 
	$Rarity = $data->Rarity == null ? NULL : $data->Rarity; 
	$Evolution = $data->Evolution == null ? NULL : $data->Evolution; 
	$Origin = $data->Origin == null ? NULL : $data->Origin; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO conditions(Universe,Tags,Name,Description,Type_of_condition,Alternate_names,Transmission,Genetic_factors,Environmental_factors,Lifestyle_factors,Epidemiology,Duration,Variations,Prognosis,Symptoms,Mental_effects,Visual_effects,Prevention,Treatment,Medication,Immunization,Diagnostic_method,Symbolism,Specialty_Field,Rarity,Evolution,Origin,Private_Notes,Notes,user_id) 
VALUES($Universe,'$Tags','$Name','$Description','$Type_of_condition','$Alternate_names','$Transmission','$Genetic_factors','$Environmental_factors','$Lifestyle_factors','$Epidemiology','$Duration','$Variations','$Prognosis','$Symptoms','$Mental_effects','$Visual_effects','$Prevention','$Treatment','$Medication','$Immunization','$Diagnostic_method','$Symbolism','$Specialty_Field','$Rarity','$Evolution','$Origin','$Private_Notes','$Notes',$user_id)"; 


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

function deleteCondition($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM conditions WHERE id = $id; ";

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

function updateCondition($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_condition = $data->Type_of_condition == null ? NULL : $data->Type_of_condition; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Transmission = $data->Transmission == null ? NULL : $data->Transmission; 
	$Genetic_factors = $data->Genetic_factors == null ? NULL : $data->Genetic_factors; 
	$Environmental_factors = $data->Environmental_factors == null ? NULL : $data->Environmental_factors; 
	$Lifestyle_factors = $data->Lifestyle_factors == null ? NULL : $data->Lifestyle_factors; 
	$Epidemiology = $data->Epidemiology == null ? NULL : $data->Epidemiology; 
	$Duration = $data->Duration == null ? NULL : $data->Duration; 
	$Variations = $data->Variations == null ? NULL : $data->Variations; 
	$Prognosis = $data->Prognosis == null ? NULL : $data->Prognosis; 
	$Symptoms = $data->Symptoms == null ? NULL : $data->Symptoms; 
	$Mental_effects = $data->Mental_effects == null ? NULL : $data->Mental_effects; 
	$Visual_effects = $data->Visual_effects == null ? NULL : $data->Visual_effects; 
	$Prevention = $data->Prevention == null ? NULL : $data->Prevention; 
	$Treatment = $data->Treatment == null ? NULL : $data->Treatment; 
	$Medication = $data->Medication == null ? NULL : $data->Medication; 
	$Immunization = $data->Immunization == null ? NULL : $data->Immunization; 
	$Diagnostic_method = $data->Diagnostic_method == null ? NULL : $data->Diagnostic_method; 
	$Symbolism = $data->Symbolism == null ? NULL : $data->Symbolism; 
	$Specialty_Field = $data->Specialty_Field == null ? NULL : $data->Specialty_Field; 
	$Rarity = $data->Rarity == null ? NULL : $data->Rarity; 
	$Evolution = $data->Evolution == null ? NULL : $data->Evolution; 
	$Origin = $data->Origin == null ? NULL : $data->Origin; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE conditions SET Universe = $Universe
,Tags = '$Tags',Name = '$Name',Description = '$Description',Type_of_condition = '$Type_of_condition',Alternate_names = '$Alternate_names',Transmission = '$Transmission',Genetic_factors = '$Genetic_factors',Environmental_factors = '$Environmental_factors',Lifestyle_factors = '$Lifestyle_factors',Epidemiology = '$Epidemiology',Duration = '$Duration',Variations = '$Variations',Prognosis = '$Prognosis',Symptoms = '$Symptoms',Mental_effects = '$Mental_effects',Visual_effects = '$Visual_effects',Prevention = '$Prevention',Treatment = '$Treatment',Medication = '$Medication',Immunization = '$Immunization',Diagnostic_method = '$Diagnostic_method',Symbolism = '$Symbolism',Specialty_Field = '$Specialty_Field',Rarity = '$Rarity',Evolution = '$Evolution',Origin = '$Origin',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllContinents(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM continents Where user_id = '$user_id'";

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

function getContinents(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM continents Where user_id = '$user_id' and id = '$id'";

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

function addContinent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Area = $data->Area == null ? "NULL" : $data->Area; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Local_name = $data->Local_name == null ? NULL : $data->Local_name; 
	$Regional_disadvantages = $data->Regional_disadvantages == null ? NULL : $data->Regional_disadvantages; 
	$Regional_advantages = $data->Regional_advantages == null ? NULL : $data->Regional_advantages; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Bodies_of_water = $data->Bodies_of_water == null ? NULL : $data->Bodies_of_water; 
	$Mineralogy = $data->Mineralogy == null ? NULL : $data->Mineralogy; 
	$Topography = $data->Topography == null ? NULL : $data->Topography; 
	$Population = $data->Population == null ? NULL : $data->Population; 
	$Shape = $data->Shape == null ? NULL : $data->Shape; 
	$Popular_foods = $data->Popular_foods == null ? NULL : $data->Popular_foods; 
	$Governments = $data->Governments == null ? NULL : $data->Governments; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Languages = $data->Languages == null ? NULL : $data->Languages; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Reputation = $data->Reputation == null ? NULL : $data->Reputation; 
	$Architecture = $data->Architecture == null ? NULL : $data->Architecture; 
	$Tourism = $data->Tourism == null ? NULL : $data->Tourism; 
	$Economy = $data->Economy == null ? NULL : $data->Economy; 
	$Politics = $data->Politics == null ? NULL : $data->Politics; 
	$Demonym = $data->Demonym == null ? NULL : $data->Demonym; 
	$Floras = $data->Floras == null ? NULL : $data->Floras; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Crops = $data->Crops == null ? NULL : $data->Crops; 
	$Natural_disasters = $data->Natural_disasters == null ? NULL : $data->Natural_disasters; 
	$Winds = $data->Winds == null ? NULL : $data->Winds; 
	$Precipitation = $data->Precipitation == null ? NULL : $data->Precipitation; 
	$Humidity = $data->Humidity == null ? NULL : $data->Humidity; 
	$Seasons = $data->Seasons == null ? NULL : $data->Seasons; 
	$Temperature = $data->Temperature == null ? NULL : $data->Temperature; 
	$Ruins = $data->Ruins == null ? NULL : $data->Ruins; 
	$Wars = $data->Wars == null ? NULL : $data->Wars; 
	$Discovery = $data->Discovery == null ? NULL : $data->Discovery; 
	$Formation = $data->Formation == null ? NULL : $data->Formation; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO continents(Universe,Area,Tags,Description,Name,Other_Names,Local_name,Regional_disadvantages,Regional_advantages,Landmarks,Bodies_of_water,Mineralogy,Topography,Population,Shape,Popular_foods,Governments,Traditions,Languages,Countries,Reputation,Architecture,Tourism,Economy,Politics,Demonym,Floras,Creatures,Crops,Natural_disasters,Winds,Precipitation,Humidity,Seasons,Temperature,Ruins,Wars,Discovery,Formation,Private_Notes,Notes,user_id) 
VALUES($Universe,$Area,'$Tags','$Description','$Name','$Other_Names','$Local_name','$Regional_disadvantages','$Regional_advantages','$Landmarks','$Bodies_of_water','$Mineralogy','$Topography','$Population','$Shape','$Popular_foods','$Governments','$Traditions','$Languages','$Countries','$Reputation','$Architecture','$Tourism','$Economy','$Politics','$Demonym','$Floras','$Creatures','$Crops','$Natural_disasters','$Winds','$Precipitation','$Humidity','$Seasons','$Temperature','$Ruins','$Wars','$Discovery','$Formation','$Private_Notes','$Notes',$user_id)"; 


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

function deleteContinent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM continents WHERE id = $id; ";

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

function updateContinent($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Area = $data->Area == null ? "NULL" : $data->Area; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Local_name = $data->Local_name == null ? NULL : $data->Local_name; 
	$Regional_disadvantages = $data->Regional_disadvantages == null ? NULL : $data->Regional_disadvantages; 
	$Regional_advantages = $data->Regional_advantages == null ? NULL : $data->Regional_advantages; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Bodies_of_water = $data->Bodies_of_water == null ? NULL : $data->Bodies_of_water; 
	$Mineralogy = $data->Mineralogy == null ? NULL : $data->Mineralogy; 
	$Topography = $data->Topography == null ? NULL : $data->Topography; 
	$Population = $data->Population == null ? NULL : $data->Population; 
	$Shape = $data->Shape == null ? NULL : $data->Shape; 
	$Popular_foods = $data->Popular_foods == null ? NULL : $data->Popular_foods; 
	$Governments = $data->Governments == null ? NULL : $data->Governments; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Languages = $data->Languages == null ? NULL : $data->Languages; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Reputation = $data->Reputation == null ? NULL : $data->Reputation; 
	$Architecture = $data->Architecture == null ? NULL : $data->Architecture; 
	$Tourism = $data->Tourism == null ? NULL : $data->Tourism; 
	$Economy = $data->Economy == null ? NULL : $data->Economy; 
	$Politics = $data->Politics == null ? NULL : $data->Politics; 
	$Demonym = $data->Demonym == null ? NULL : $data->Demonym; 
	$Floras = $data->Floras == null ? NULL : $data->Floras; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Crops = $data->Crops == null ? NULL : $data->Crops; 
	$Natural_disasters = $data->Natural_disasters == null ? NULL : $data->Natural_disasters; 
	$Winds = $data->Winds == null ? NULL : $data->Winds; 
	$Precipitation = $data->Precipitation == null ? NULL : $data->Precipitation; 
	$Humidity = $data->Humidity == null ? NULL : $data->Humidity; 
	$Seasons = $data->Seasons == null ? NULL : $data->Seasons; 
	$Temperature = $data->Temperature == null ? NULL : $data->Temperature; 
	$Ruins = $data->Ruins == null ? NULL : $data->Ruins; 
	$Wars = $data->Wars == null ? NULL : $data->Wars; 
	$Discovery = $data->Discovery == null ? NULL : $data->Discovery; 
	$Formation = $data->Formation == null ? NULL : $data->Formation; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE continents SET Universe = $Universe,Area = $Area
,Tags = '$Tags',Description = '$Description',Name = '$Name',Other_Names = '$Other_Names',Local_name = '$Local_name',Regional_disadvantages = '$Regional_disadvantages',Regional_advantages = '$Regional_advantages',Landmarks = '$Landmarks',Bodies_of_water = '$Bodies_of_water',Mineralogy = '$Mineralogy',Topography = '$Topography',Population = '$Population',Shape = '$Shape',Popular_foods = '$Popular_foods',Governments = '$Governments',Traditions = '$Traditions',Languages = '$Languages',Countries = '$Countries',Reputation = '$Reputation',Architecture = '$Architecture',Tourism = '$Tourism',Economy = '$Economy',Politics = '$Politics',Demonym = '$Demonym',Floras = '$Floras',Creatures = '$Creatures',Crops = '$Crops',Natural_disasters = '$Natural_disasters',Winds = '$Winds',Precipitation = '$Precipitation',Humidity = '$Humidity',Seasons = '$Seasons',Temperature = '$Temperature',Ruins = '$Ruins',Wars = '$Wars',Discovery = '$Discovery',Formation = '$Formation',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllCountries(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM countries Where user_id = '$user_id'";

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

function getCountries(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM countries Where user_id = '$user_id' and id = '$id'";

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

function addCountrie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Population = $data->Population == null ? "NULL" : $data->Population; 
	$Area = $data->Area == null ? "NULL" : $data->Area; 
	$Established_year = $data->Established_year == null ? "NULL" : $data->Established_year; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Locations = $data->Locations == null ? NULL : $data->Locations; 
	$Towns = $data->Towns == null ? NULL : $data->Towns; 
	$Bordering_countries = $data->Bordering_countries == null ? NULL : $data->Bordering_countries; 
	$Education = $data->Education == null ? NULL : $data->Education; 
	$Governments = $data->Governments == null ? NULL : $data->Governments; 
	$Religions = $data->Religions == null ? NULL : $data->Religions; 
	$Languages = $data->Languages == null ? NULL : $data->Languages; 
	$Sports = $data->Sports == null ? NULL : $data->Sports; 
	$Architecture = $data->Architecture == null ? NULL : $data->Architecture; 
	$Music = $data->Music == null ? NULL : $data->Music; 
	$Pop_culture = $data->Pop_culture == null ? NULL : $data->Pop_culture; 
	$Laws = $data->Laws == null ? NULL : $data->Laws; 
	$Currency = $data->Currency == null ? NULL : $data->Currency; 
	$Social_hierarchy = $data->Social_hierarchy == null ? NULL : $data->Social_hierarchy; 
	$Crops = $data->Crops == null ? NULL : $data->Crops; 
	$Climate = $data->Climate == null ? NULL : $data->Climate; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Flora = $data->Flora == null ? NULL : $data->Flora; 
	$Notable_wars = $data->Notable_wars == null ? NULL : $data->Notable_wars; 
	$Founding_story = $data->Founding_story == null ? NULL : $data->Founding_story; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO countries(Universe,Population,Area,Established_year,Tags,Name,Description,Other_Names,Landmarks,Locations,Towns,Bordering_countries,Education,Governments,Religions,Languages,Sports,Architecture,Music,Pop_culture,Laws,Currency,Social_hierarchy,Crops,Climate,Creatures,Flora,Notable_wars,Founding_story,Private_Notes,Notes,user_id) 
VALUES($Universe,$Population,$Area,$Established_year,'$Tags','$Name','$Description','$Other_Names','$Landmarks','$Locations','$Towns','$Bordering_countries','$Education','$Governments','$Religions','$Languages','$Sports','$Architecture','$Music','$Pop_culture','$Laws','$Currency','$Social_hierarchy','$Crops','$Climate','$Creatures','$Flora','$Notable_wars','$Founding_story','$Private_Notes','$Notes',$user_id)"; 


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

function deleteCountrie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM countries WHERE id = $id; ";

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

function updateCountrie($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Population = $data->Population == null ? "NULL" : $data->Population; 
	$Area = $data->Area == null ? "NULL" : $data->Area; 
	$Established_year = $data->Established_year == null ? "NULL" : $data->Established_year; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Locations = $data->Locations == null ? NULL : $data->Locations; 
	$Towns = $data->Towns == null ? NULL : $data->Towns; 
	$Bordering_countries = $data->Bordering_countries == null ? NULL : $data->Bordering_countries; 
	$Education = $data->Education == null ? NULL : $data->Education; 
	$Governments = $data->Governments == null ? NULL : $data->Governments; 
	$Religions = $data->Religions == null ? NULL : $data->Religions; 
	$Languages = $data->Languages == null ? NULL : $data->Languages; 
	$Sports = $data->Sports == null ? NULL : $data->Sports; 
	$Architecture = $data->Architecture == null ? NULL : $data->Architecture; 
	$Music = $data->Music == null ? NULL : $data->Music; 
	$Pop_culture = $data->Pop_culture == null ? NULL : $data->Pop_culture; 
	$Laws = $data->Laws == null ? NULL : $data->Laws; 
	$Currency = $data->Currency == null ? NULL : $data->Currency; 
	$Social_hierarchy = $data->Social_hierarchy == null ? NULL : $data->Social_hierarchy; 
	$Crops = $data->Crops == null ? NULL : $data->Crops; 
	$Climate = $data->Climate == null ? NULL : $data->Climate; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Flora = $data->Flora == null ? NULL : $data->Flora; 
	$Notable_wars = $data->Notable_wars == null ? NULL : $data->Notable_wars; 
	$Founding_story = $data->Founding_story == null ? NULL : $data->Founding_story; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE countries SET Universe = $Universe,Population = $Population,Area = $Area,Established_year = $Established_year
,Tags = '$Tags',Name = '$Name',Description = '$Description',Other_Names = '$Other_Names',Landmarks = '$Landmarks',Locations = '$Locations',Towns = '$Towns',Bordering_countries = '$Bordering_countries',Education = '$Education',Governments = '$Governments',Religions = '$Religions',Languages = '$Languages',Sports = '$Sports',Architecture = '$Architecture',Music = '$Music',Pop_culture = '$Pop_culture',Laws = '$Laws',Currency = '$Currency',Social_hierarchy = '$Social_hierarchy',Crops = '$Crops',Climate = '$Climate',Creatures = '$Creatures',Flora = '$Flora',Notable_wars = '$Notable_wars',Founding_story = '$Founding_story',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllCreatures(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM creatures Where user_id = '$user_id'";

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

function getCreatures(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM creatures Where user_id = '$user_id' and id = '$id'";

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

function addCreature($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Weight = $data->Weight == null ? "NULL" : $data->Weight; 
	$Color = $data->Color == null ? "NULL" : $data->Color; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Height = $data->Height == null ? "NULL" : $data->Height; 
	$Maximum_speed = $data->Maximum_speed == null ? "NULL" : $data->Maximum_speed; 
	$Reproduction_age = $data->Reproduction_age == null ? "NULL" : $data->Reproduction_age; 
	$Type_of_creature = $data->Type_of_creature == null ? NULL : $data->Type_of_creature; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Notable_features = $data->Notable_features == null ? NULL : $data->Notable_features; 
	$Materials = $data->Materials == null ? NULL : $data->Materials; 
	$Vestigial_features = $data->Vestigial_features == null ? NULL : $data->Vestigial_features; 
	$Shape = $data->Shape == null ? NULL : $data->Shape; 
	$Strongest_sense = $data->Strongest_sense == null ? NULL : $data->Strongest_sense; 
	$Aggressiveness = $data->Aggressiveness == null ? NULL : $data->Aggressiveness; 
	$Method_of_attack = $data->Method_of_attack == null ? NULL : $data->Method_of_attack; 
	$Methods_of_defense = $data->Methods_of_defense == null ? NULL : $data->Methods_of_defense; 
	$Strengths = $data->Strengths == null ? NULL : $data->Strengths; 
	$Weaknesses = $data->Weaknesses == null ? NULL : $data->Weaknesses; 
	$Sounds = $data->Sounds == null ? NULL : $data->Sounds; 
	$Spoils = $data->Spoils == null ? NULL : $data->Spoils; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Weakest_sense = $data->Weakest_sense == null ? NULL : $data->Weakest_sense; 
	$Herding_patterns = $data->Herding_patterns == null ? NULL : $data->Herding_patterns; 
	$Prey = $data->Prey == null ? NULL : $data->Prey; 
	$Predators = $data->Predators == null ? NULL : $data->Predators; 
	$Competitors = $data->Competitors == null ? NULL : $data->Competitors; 
	$Migratory_patterns = $data->Migratory_patterns == null ? NULL : $data->Migratory_patterns; 
	$Food_sources = $data->Food_sources == null ? NULL : $data->Food_sources; 
	$Habitats = $data->Habitats == null ? NULL : $data->Habitats; 
	$Preferred_habitat = $data->Preferred_habitat == null ? NULL : $data->Preferred_habitat; 
	$Similar_creatures = $data->Similar_creatures == null ? NULL : $data->Similar_creatures; 
	$Symbolisms = $data->Symbolisms == null ? NULL : $data->Symbolisms; 
	$Related_creatures = $data->Related_creatures == null ? NULL : $data->Related_creatures; 
	$Ancestors = $data->Ancestors == null ? NULL : $data->Ancestors; 
	$Evolutionary_drive = $data->Evolutionary_drive == null ? NULL : $data->Evolutionary_drive; 
	$Tradeoffs = $data->Tradeoffs == null ? NULL : $data->Tradeoffs; 
	$Predictions = $data->Predictions == null ? NULL : $data->Predictions; 
	$Mortality_rate = $data->Mortality_rate == null ? NULL : $data->Mortality_rate; 
	$Offspring_care = $data->Offspring_care == null ? NULL : $data->Offspring_care; 
	$Requirements = $data->Requirements == null ? NULL : $data->Requirements; 
	$Mating_ritual = $data->Mating_ritual == null ? NULL : $data->Mating_ritual; 
	$Reproduction = $data->Reproduction == null ? NULL : $data->Reproduction; 
	$Reproduction_frequency = $data->Reproduction_frequency == null ? NULL : $data->Reproduction_frequency; 
	$Parental_instincts = $data->Parental_instincts == null ? NULL : $data->Parental_instincts; 
	$Variations = $data->Variations == null ? NULL : $data->Variations; 
	$Phylum = $data->Phylum == null ? NULL : $data->Phylum; 
	$Class = $data->Class == null ? NULL : $data->Class; 
	$Order = $data->Order == null ? NULL : $data->Order; 
	$Family = $data->Family == null ? NULL : $data->Family; 
	$Genus = $data->Genus == null ? NULL : $data->Genus; 
	$Species = $data->Species == null ? NULL : $data->Species; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO creatures(Universe,Weight,Color,Size,Height,Maximum_speed,Reproduction_age,Type_of_creature,Tags,Name,Description,Notable_features,Materials,Vestigial_features,Shape,Strongest_sense,Aggressiveness,Method_of_attack,Methods_of_defense,Strengths,Weaknesses,Sounds,Spoils,Conditions,Weakest_sense,Herding_patterns,Prey,Predators,Competitors,Migratory_patterns,Food_sources,Habitats,Preferred_habitat,Similar_creatures,Symbolisms,Related_creatures,Ancestors,Evolutionary_drive,Tradeoffs,Predictions,Mortality_rate,Offspring_care,Requirements,Mating_ritual,Reproduction,Reproduction_frequency,Parental_instincts,Variations,Phylum,Class,Order,Family,Genus,Species,Private_notes,Notes,user_id) 
VALUES($Universe,$Weight,$Color,$Size,$Height,$Maximum_speed,$Reproduction_age,'$Type_of_creature','$Tags','$Name','$Description','$Notable_features','$Materials','$Vestigial_features','$Shape','$Strongest_sense','$Aggressiveness','$Method_of_attack','$Methods_of_defense','$Strengths','$Weaknesses','$Sounds','$Spoils','$Conditions','$Weakest_sense','$Herding_patterns','$Prey','$Predators','$Competitors','$Migratory_patterns','$Food_sources','$Habitats','$Preferred_habitat','$Similar_creatures','$Symbolisms','$Related_creatures','$Ancestors','$Evolutionary_drive','$Tradeoffs','$Predictions','$Mortality_rate','$Offspring_care','$Requirements','$Mating_ritual','$Reproduction','$Reproduction_frequency','$Parental_instincts','$Variations','$Phylum','$Class','$Order','$Family','$Genus','$Species','$Private_notes','$Notes',$user_id)"; 


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

function deleteCreature($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM creatures WHERE id = $id; ";

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

function updateCreature($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Weight = $data->Weight == null ? "NULL" : $data->Weight; 
	$Color = $data->Color == null ? "NULL" : $data->Color; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Height = $data->Height == null ? "NULL" : $data->Height; 
	$Maximum_speed = $data->Maximum_speed == null ? "NULL" : $data->Maximum_speed; 
	$Reproduction_age = $data->Reproduction_age == null ? "NULL" : $data->Reproduction_age; 
	$Type_of_creature = $data->Type_of_creature == null ? NULL : $data->Type_of_creature; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Notable_features = $data->Notable_features == null ? NULL : $data->Notable_features; 
	$Materials = $data->Materials == null ? NULL : $data->Materials; 
	$Vestigial_features = $data->Vestigial_features == null ? NULL : $data->Vestigial_features; 
	$Shape = $data->Shape == null ? NULL : $data->Shape; 
	$Strongest_sense = $data->Strongest_sense == null ? NULL : $data->Strongest_sense; 
	$Aggressiveness = $data->Aggressiveness == null ? NULL : $data->Aggressiveness; 
	$Method_of_attack = $data->Method_of_attack == null ? NULL : $data->Method_of_attack; 
	$Methods_of_defense = $data->Methods_of_defense == null ? NULL : $data->Methods_of_defense; 
	$Strengths = $data->Strengths == null ? NULL : $data->Strengths; 
	$Weaknesses = $data->Weaknesses == null ? NULL : $data->Weaknesses; 
	$Sounds = $data->Sounds == null ? NULL : $data->Sounds; 
	$Spoils = $data->Spoils == null ? NULL : $data->Spoils; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Weakest_sense = $data->Weakest_sense == null ? NULL : $data->Weakest_sense; 
	$Herding_patterns = $data->Herding_patterns == null ? NULL : $data->Herding_patterns; 
	$Prey = $data->Prey == null ? NULL : $data->Prey; 
	$Predators = $data->Predators == null ? NULL : $data->Predators; 
	$Competitors = $data->Competitors == null ? NULL : $data->Competitors; 
	$Migratory_patterns = $data->Migratory_patterns == null ? NULL : $data->Migratory_patterns; 
	$Food_sources = $data->Food_sources == null ? NULL : $data->Food_sources; 
	$Habitats = $data->Habitats == null ? NULL : $data->Habitats; 
	$Preferred_habitat = $data->Preferred_habitat == null ? NULL : $data->Preferred_habitat; 
	$Similar_creatures = $data->Similar_creatures == null ? NULL : $data->Similar_creatures; 
	$Symbolisms = $data->Symbolisms == null ? NULL : $data->Symbolisms; 
	$Related_creatures = $data->Related_creatures == null ? NULL : $data->Related_creatures; 
	$Ancestors = $data->Ancestors == null ? NULL : $data->Ancestors; 
	$Evolutionary_drive = $data->Evolutionary_drive == null ? NULL : $data->Evolutionary_drive; 
	$Tradeoffs = $data->Tradeoffs == null ? NULL : $data->Tradeoffs; 
	$Predictions = $data->Predictions == null ? NULL : $data->Predictions; 
	$Mortality_rate = $data->Mortality_rate == null ? NULL : $data->Mortality_rate; 
	$Offspring_care = $data->Offspring_care == null ? NULL : $data->Offspring_care; 
	$Requirements = $data->Requirements == null ? NULL : $data->Requirements; 
	$Mating_ritual = $data->Mating_ritual == null ? NULL : $data->Mating_ritual; 
	$Reproduction = $data->Reproduction == null ? NULL : $data->Reproduction; 
	$Reproduction_frequency = $data->Reproduction_frequency == null ? NULL : $data->Reproduction_frequency; 
	$Parental_instincts = $data->Parental_instincts == null ? NULL : $data->Parental_instincts; 
	$Variations = $data->Variations == null ? NULL : $data->Variations; 
	$Phylum = $data->Phylum == null ? NULL : $data->Phylum; 
	$Class = $data->Class == null ? NULL : $data->Class; 
	$Order = $data->Order == null ? NULL : $data->Order; 
	$Family = $data->Family == null ? NULL : $data->Family; 
	$Genus = $data->Genus == null ? NULL : $data->Genus; 
	$Species = $data->Species == null ? NULL : $data->Species; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE creatures SET Universe = $Universe,Weight = $Weight,Color = $Color,Size = $Size,Height = $Height,Maximum_speed = $Maximum_speed,Reproduction_age = $Reproduction_age
,Type_of_creature = '$Type_of_creature',Tags = '$Tags',Name = '$Name',Description = '$Description',Notable_features = '$Notable_features',Materials = '$Materials',Vestigial_features = '$Vestigial_features',Shape = '$Shape',Strongest_sense = '$Strongest_sense',Aggressiveness = '$Aggressiveness',Method_of_attack = '$Method_of_attack',Methods_of_defense = '$Methods_of_defense',Strengths = '$Strengths',Weaknesses = '$Weaknesses',Sounds = '$Sounds',Spoils = '$Spoils',Conditions = '$Conditions',Weakest_sense = '$Weakest_sense',Herding_patterns = '$Herding_patterns',Prey = '$Prey',Predators = '$Predators',Competitors = '$Competitors',Migratory_patterns = '$Migratory_patterns',Food_sources = '$Food_sources',Habitats = '$Habitats',Preferred_habitat = '$Preferred_habitat',Similar_creatures = '$Similar_creatures',Symbolisms = '$Symbolisms',Related_creatures = '$Related_creatures',Ancestors = '$Ancestors',Evolutionary_drive = '$Evolutionary_drive',Tradeoffs = '$Tradeoffs',Predictions = '$Predictions',Mortality_rate = '$Mortality_rate',Offspring_care = '$Offspring_care',Requirements = '$Requirements',Mating_ritual = '$Mating_ritual',Reproduction = '$Reproduction',Reproduction_frequency = '$Reproduction_frequency',Parental_instincts = '$Parental_instincts',Variations = '$Variations',Phylum = '$Phylum',Class = '$Class',Order = '$Order',Family = '$Family',Genus = '$Genus',Species = '$Species',Private_notes = '$Private_notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllDeities(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM deities Where user_id = '$user_id'";

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

function getDeities(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM deities Where user_id = '$user_id' and id = '$id'";

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

function addDeitie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Height = $data->Height == null ? "NULL" : $data->Height; 
	$Weight = $data->Weight == null ? "NULL" : $data->Weight; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Physical_Description = $data->Physical_Description == null ? NULL : $data->Physical_Description; 
	$Children = $data->Children == null ? NULL : $data->Children; 
	$Parents = $data->Parents == null ? NULL : $data->Parents; 
	$Partners = $data->Partners == null ? NULL : $data->Partners; 
	$Siblings = $data->Siblings == null ? NULL : $data->Siblings; 
	$Floras = $data->Floras == null ? NULL : $data->Floras; 
	$Relics = $data->Relics == null ? NULL : $data->Relics; 
	$Religions = $data->Religions == null ? NULL : $data->Religions; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Elements = $data->Elements == null ? NULL : $data->Elements; 
	$Symbols = $data->Symbols == null ? NULL : $data->Symbols; 
	$Abilities = $data->Abilities == null ? NULL : $data->Abilities; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Strengths = $data->Strengths == null ? NULL : $data->Strengths; 
	$Weaknesses = $data->Weaknesses == null ? NULL : $data->Weaknesses; 
	$Human_Interaction = $data->Human_Interaction == null ? NULL : $data->Human_Interaction; 
	$Related_towns = $data->Related_towns == null ? NULL : $data->Related_towns; 
	$Related_races = $data->Related_races == null ? NULL : $data->Related_races; 
	$Related_landmarks = $data->Related_landmarks == null ? NULL : $data->Related_landmarks; 
	$Prayers = $data->Prayers == null ? NULL : $data->Prayers; 
	$Rituals = $data->Rituals == null ? NULL : $data->Rituals; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Family_History = $data->Family_History == null ? NULL : $data->Family_History; 
	$Notable_Events = $data->Notable_Events == null ? NULL : $data->Notable_Events; 
	$Life_Story = $data->Life_Story == null ? NULL : $data->Life_Story; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO deities(Universe,Height,Weight,Tags,Name,Description,Other_Names,Physical_Description,Children,Parents,Partners,Siblings,Floras,Relics,Religions,Creatures,Elements,Symbols,Abilities,Conditions,Strengths,Weaknesses,Human_Interaction,Related_towns,Related_races,Related_landmarks,Prayers,Rituals,Traditions,Family_History,Notable_Events,Life_Story,Private_Notes,Notes,user_id) 
VALUES($Universe,$Height,$Weight,'$Tags','$Name','$Description','$Other_Names','$Physical_Description','$Children','$Parents','$Partners','$Siblings','$Floras','$Relics','$Religions','$Creatures','$Elements','$Symbols','$Abilities','$Conditions','$Strengths','$Weaknesses','$Human_Interaction','$Related_towns','$Related_races','$Related_landmarks','$Prayers','$Rituals','$Traditions','$Family_History','$Notable_Events','$Life_Story','$Private_Notes','$Notes',$user_id)"; 


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

function deleteDeitie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM deities WHERE id = $id; ";

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

function updateDeitie($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Height = $data->Height == null ? "NULL" : $data->Height; 
	$Weight = $data->Weight == null ? "NULL" : $data->Weight; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Physical_Description = $data->Physical_Description == null ? NULL : $data->Physical_Description; 
	$Children = $data->Children == null ? NULL : $data->Children; 
	$Parents = $data->Parents == null ? NULL : $data->Parents; 
	$Partners = $data->Partners == null ? NULL : $data->Partners; 
	$Siblings = $data->Siblings == null ? NULL : $data->Siblings; 
	$Floras = $data->Floras == null ? NULL : $data->Floras; 
	$Relics = $data->Relics == null ? NULL : $data->Relics; 
	$Religions = $data->Religions == null ? NULL : $data->Religions; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Elements = $data->Elements == null ? NULL : $data->Elements; 
	$Symbols = $data->Symbols == null ? NULL : $data->Symbols; 
	$Abilities = $data->Abilities == null ? NULL : $data->Abilities; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Strengths = $data->Strengths == null ? NULL : $data->Strengths; 
	$Weaknesses = $data->Weaknesses == null ? NULL : $data->Weaknesses; 
	$Human_Interaction = $data->Human_Interaction == null ? NULL : $data->Human_Interaction; 
	$Related_towns = $data->Related_towns == null ? NULL : $data->Related_towns; 
	$Related_races = $data->Related_races == null ? NULL : $data->Related_races; 
	$Related_landmarks = $data->Related_landmarks == null ? NULL : $data->Related_landmarks; 
	$Prayers = $data->Prayers == null ? NULL : $data->Prayers; 
	$Rituals = $data->Rituals == null ? NULL : $data->Rituals; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Family_History = $data->Family_History == null ? NULL : $data->Family_History; 
	$Notable_Events = $data->Notable_Events == null ? NULL : $data->Notable_Events; 
	$Life_Story = $data->Life_Story == null ? NULL : $data->Life_Story; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE deities SET Universe = $Universe,Height = $Height,Weight = $Weight
,Tags = '$Tags',Name = '$Name',Description = '$Description',Other_Names = '$Other_Names',Physical_Description = '$Physical_Description',Children = '$Children',Parents = '$Parents',Partners = '$Partners',Siblings = '$Siblings',Floras = '$Floras',Relics = '$Relics',Religions = '$Religions',Creatures = '$Creatures',Elements = '$Elements',Symbols = '$Symbols',Abilities = '$Abilities',Conditions = '$Conditions',Strengths = '$Strengths',Weaknesses = '$Weaknesses',Human_Interaction = '$Human_Interaction',Related_towns = '$Related_towns',Related_races = '$Related_races',Related_landmarks = '$Related_landmarks',Prayers = '$Prayers',Rituals = '$Rituals',Traditions = '$Traditions',Family_History = '$Family_History',Notable_Events = '$Notable_Events',Life_Story = '$Life_Story',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllFloras(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM floras Where user_id = '$user_id'";

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

function getFloras(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM floras Where user_id = '$user_id' and id = '$id'";

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

function addFlora($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Order = $data->Order == null ? NULL : $data->Order; 
	$Related_flora = $data->Related_flora == null ? NULL : $data->Related_flora; 
	$Genus = $data->Genus == null ? NULL : $data->Genus; 
	$Family = $data->Family == null ? NULL : $data->Family; 
	$Size = $data->Size == null ? NULL : $data->Size; 
	$Smell = $data->Smell == null ? NULL : $data->Smell; 
	$Taste = $data->Taste == null ? NULL : $data->Taste; 
	$Colorings = $data->Colorings == null ? NULL : $data->Colorings; 
	$Fruits = $data->Fruits == null ? NULL : $data->Fruits; 
	$Magical_effects = $data->Magical_effects == null ? NULL : $data->Magical_effects; 
	$Material_uses = $data->Material_uses == null ? NULL : $data->Material_uses; 
	$Medicinal_purposes = $data->Medicinal_purposes == null ? NULL : $data->Medicinal_purposes; 
	$Berries = $data->Berries == null ? NULL : $data->Berries; 
	$Nuts = $data->Nuts == null ? NULL : $data->Nuts; 
	$Seeds = $data->Seeds == null ? NULL : $data->Seeds; 
	$Seasonality = $data->Seasonality == null ? NULL : $data->Seasonality; 
	$Locations = $data->Locations == null ? NULL : $data->Locations; 
	$Reproduction = $data->Reproduction == null ? NULL : $data->Reproduction; 
	$Eaten_by = $data->Eaten_by == null ? NULL : $data->Eaten_by; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO floras(Universe,Tags,Name,Other_Names,Description,Order,Related_flora,Genus,Family,Size,Smell,Taste,Colorings,Fruits,Magical_effects,Material_uses,Medicinal_purposes,Berries,Nuts,Seeds,Seasonality,Locations,Reproduction,Eaten_by,Notes,Private_Notes,user_id) 
VALUES($Universe,'$Tags','$Name','$Other_Names','$Description','$Order','$Related_flora','$Genus','$Family','$Size','$Smell','$Taste','$Colorings','$Fruits','$Magical_effects','$Material_uses','$Medicinal_purposes','$Berries','$Nuts','$Seeds','$Seasonality','$Locations','$Reproduction','$Eaten_by','$Notes','$Private_Notes',$user_id)"; 


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

function deleteFlora($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM floras WHERE id = $id; ";

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

function updateFlora($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Order = $data->Order == null ? NULL : $data->Order; 
	$Related_flora = $data->Related_flora == null ? NULL : $data->Related_flora; 
	$Genus = $data->Genus == null ? NULL : $data->Genus; 
	$Family = $data->Family == null ? NULL : $data->Family; 
	$Size = $data->Size == null ? NULL : $data->Size; 
	$Smell = $data->Smell == null ? NULL : $data->Smell; 
	$Taste = $data->Taste == null ? NULL : $data->Taste; 
	$Colorings = $data->Colorings == null ? NULL : $data->Colorings; 
	$Fruits = $data->Fruits == null ? NULL : $data->Fruits; 
	$Magical_effects = $data->Magical_effects == null ? NULL : $data->Magical_effects; 
	$Material_uses = $data->Material_uses == null ? NULL : $data->Material_uses; 
	$Medicinal_purposes = $data->Medicinal_purposes == null ? NULL : $data->Medicinal_purposes; 
	$Berries = $data->Berries == null ? NULL : $data->Berries; 
	$Nuts = $data->Nuts == null ? NULL : $data->Nuts; 
	$Seeds = $data->Seeds == null ? NULL : $data->Seeds; 
	$Seasonality = $data->Seasonality == null ? NULL : $data->Seasonality; 
	$Locations = $data->Locations == null ? NULL : $data->Locations; 
	$Reproduction = $data->Reproduction == null ? NULL : $data->Reproduction; 
	$Eaten_by = $data->Eaten_by == null ? NULL : $data->Eaten_by; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE floras SET Universe = $Universe
,Tags = '$Tags',Name = '$Name',Other_Names = '$Other_Names',Description = '$Description',Order = '$Order',Related_flora = '$Related_flora',Genus = '$Genus',Family = '$Family',Size = '$Size',Smell = '$Smell',Taste = '$Taste',Colorings = '$Colorings',Fruits = '$Fruits',Magical_effects = '$Magical_effects',Material_uses = '$Material_uses',Medicinal_purposes = '$Medicinal_purposes',Berries = '$Berries',Nuts = '$Nuts',Seeds = '$Seeds',Seasonality = '$Seasonality',Locations = '$Locations',Reproduction = '$Reproduction',Eaten_by = '$Eaten_by',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllFoods(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM foods Where user_id = '$user_id'";

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

function getFoods(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM foods Where user_id = '$user_id' and id = '$id'";

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

function addFood($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Type_of_food = $data->Type_of_food == null ? NULL : $data->Type_of_food; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Color = $data->Color == null ? NULL : $data->Color; 
	$Variations = $data->Variations == null ? NULL : $data->Variations; 
	$Smell = $data->Smell == null ? NULL : $data->Smell; 
	$Ingredients = $data->Ingredients == null ? NULL : $data->Ingredients; 
	$Preparation = $data->Preparation == null ? NULL : $data->Preparation; 
	$Cooking_method = $data->Cooking_method == null ? NULL : $data->Cooking_method; 
	$Spices = $data->Spices == null ? NULL : $data->Spices; 
	$Yield = $data->Yield == null ? NULL : $data->Yield; 
	$Shelf_life = $data->Shelf_life == null ? NULL : $data->Shelf_life; 
	$Rarity = $data->Rarity == null ? NULL : $data->Rarity; 
	$Sold_by = $data->Sold_by == null ? NULL : $data->Sold_by; 
	$Cost = $data->Cost == null ? NULL : $data->Cost; 
	$Flavor = $data->Flavor == null ? NULL : $data->Flavor; 
	$Meal = $data->Meal == null ? NULL : $data->Meal; 
	$Serving = $data->Serving == null ? NULL : $data->Serving; 
	$Utensils_needed = $data->Utensils_needed == null ? NULL : $data->Utensils_needed; 
	$Texture = $data->Texture == null ? NULL : $data->Texture; 
	$Scent = $data->Scent == null ? NULL : $data->Scent; 
	$Side_effects = $data->Side_effects == null ? NULL : $data->Side_effects; 
	$Nutrition = $data->Nutrition == null ? NULL : $data->Nutrition; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Reputation = $data->Reputation == null ? NULL : $data->Reputation; 
	$Place_of_origin = $data->Place_of_origin == null ? NULL : $data->Place_of_origin; 
	$Origin_story = $data->Origin_story == null ? NULL : $data->Origin_story; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Symbolisms = $data->Symbolisms == null ? NULL : $data->Symbolisms; 
	$Related_foods = $data->Related_foods == null ? NULL : $data->Related_foods; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO foods(Universe,Size,Type_of_food,Other_Names,Tags,Name,Description,Color,Variations,Smell,Ingredients,Preparation,Cooking_method,Spices,Yield,Shelf_life,Rarity,Sold_by,Cost,Flavor,Meal,Serving,Utensils_needed,Texture,Scent,Side_effects,Nutrition,Conditions,Reputation,Place_of_origin,Origin_story,Traditions,Symbolisms,Related_foods,Notes,Private_Notes,user_id) 
VALUES($Universe,$Size,'$Type_of_food','$Other_Names','$Tags','$Name','$Description','$Color','$Variations','$Smell','$Ingredients','$Preparation','$Cooking_method','$Spices','$Yield','$Shelf_life','$Rarity','$Sold_by','$Cost','$Flavor','$Meal','$Serving','$Utensils_needed','$Texture','$Scent','$Side_effects','$Nutrition','$Conditions','$Reputation','$Place_of_origin','$Origin_story','$Traditions','$Symbolisms','$Related_foods','$Notes','$Private_Notes',$user_id)"; 


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

function deleteFood($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM foods WHERE id = $id; ";

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

function updateFood($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Type_of_food = $data->Type_of_food == null ? NULL : $data->Type_of_food; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Color = $data->Color == null ? NULL : $data->Color; 
	$Variations = $data->Variations == null ? NULL : $data->Variations; 
	$Smell = $data->Smell == null ? NULL : $data->Smell; 
	$Ingredients = $data->Ingredients == null ? NULL : $data->Ingredients; 
	$Preparation = $data->Preparation == null ? NULL : $data->Preparation; 
	$Cooking_method = $data->Cooking_method == null ? NULL : $data->Cooking_method; 
	$Spices = $data->Spices == null ? NULL : $data->Spices; 
	$Yield = $data->Yield == null ? NULL : $data->Yield; 
	$Shelf_life = $data->Shelf_life == null ? NULL : $data->Shelf_life; 
	$Rarity = $data->Rarity == null ? NULL : $data->Rarity; 
	$Sold_by = $data->Sold_by == null ? NULL : $data->Sold_by; 
	$Cost = $data->Cost == null ? NULL : $data->Cost; 
	$Flavor = $data->Flavor == null ? NULL : $data->Flavor; 
	$Meal = $data->Meal == null ? NULL : $data->Meal; 
	$Serving = $data->Serving == null ? NULL : $data->Serving; 
	$Utensils_needed = $data->Utensils_needed == null ? NULL : $data->Utensils_needed; 
	$Texture = $data->Texture == null ? NULL : $data->Texture; 
	$Scent = $data->Scent == null ? NULL : $data->Scent; 
	$Side_effects = $data->Side_effects == null ? NULL : $data->Side_effects; 
	$Nutrition = $data->Nutrition == null ? NULL : $data->Nutrition; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Reputation = $data->Reputation == null ? NULL : $data->Reputation; 
	$Place_of_origin = $data->Place_of_origin == null ? NULL : $data->Place_of_origin; 
	$Origin_story = $data->Origin_story == null ? NULL : $data->Origin_story; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Symbolisms = $data->Symbolisms == null ? NULL : $data->Symbolisms; 
	$Related_foods = $data->Related_foods == null ? NULL : $data->Related_foods; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE foods SET Universe = $Universe,Size = $Size
,Type_of_food = '$Type_of_food',Other_Names = '$Other_Names',Tags = '$Tags',Name = '$Name',Description = '$Description',Color = '$Color',Variations = '$Variations',Smell = '$Smell',Ingredients = '$Ingredients',Preparation = '$Preparation',Cooking_method = '$Cooking_method',Spices = '$Spices',Yield = '$Yield',Shelf_life = '$Shelf_life',Rarity = '$Rarity',Sold_by = '$Sold_by',Cost = '$Cost',Flavor = '$Flavor',Meal = '$Meal',Serving = '$Serving',Utensils_needed = '$Utensils_needed',Texture = '$Texture',Scent = '$Scent',Side_effects = '$Side_effects',Nutrition = '$Nutrition',Conditions = '$Conditions',Reputation = '$Reputation',Place_of_origin = '$Place_of_origin',Origin_story = '$Origin_story',Traditions = '$Traditions',Symbolisms = '$Symbolisms',Related_foods = '$Related_foods',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllGovernments(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM governments Where user_id = '$user_id'";

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

function getGovernments(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM governments Where user_id = '$user_id' and id = '$id'";

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

function addGovernment($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Checks_And_Balances = $data->Checks_And_Balances == null ? NULL : $data->Checks_And_Balances; 
	$Jobs = $data->Jobs == null ? NULL : $data->Jobs; 
	$Type_Of_Government = $data->Type_Of_Government == null ? NULL : $data->Type_Of_Government; 
	$Power_Structure = $data->Power_Structure == null ? NULL : $data->Power_Structure; 
	$Power_Source = $data->Power_Source == null ? NULL : $data->Power_Source; 
	$Privacy_Ideologies = $data->Privacy_Ideologies == null ? NULL : $data->Privacy_Ideologies; 
	$Sociopolitical = $data->Sociopolitical == null ? NULL : $data->Sociopolitical; 
	$Socioeconomical = $data->Socioeconomical == null ? NULL : $data->Socioeconomical; 
	$Geocultural = $data->Geocultural == null ? NULL : $data->Geocultural; 
	$Laws = $data->Laws == null ? NULL : $data->Laws; 
	$Immigration = $data->Immigration == null ? NULL : $data->Immigration; 
	$Term_Lengths = $data->Term_Lengths == null ? NULL : $data->Term_Lengths; 
	$Electoral_Process = $data->Electoral_Process == null ? NULL : $data->Electoral_Process; 
	$Criminal_System = $data->Criminal_System == null ? NULL : $data->Criminal_System; 
	$International_Relations = $data->International_Relations == null ? NULL : $data->International_Relations; 
	$Civilian_Life = $data->Civilian_Life == null ? NULL : $data->Civilian_Life; 
	$Approval_Ratings = $data->Approval_Ratings == null ? NULL : $data->Approval_Ratings; 
	$Space_Program = $data->Space_Program == null ? NULL : $data->Space_Program; 
	$Leaders = $data->Leaders == null ? NULL : $data->Leaders; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Political_figures = $data->Political_figures == null ? NULL : $data->Political_figures; 
	$Military = $data->Military == null ? NULL : $data->Military; 
	$Navy = $data->Navy == null ? NULL : $data->Navy; 
	$Airforce = $data->Airforce == null ? NULL : $data->Airforce; 
	$Notable_Wars = $data->Notable_Wars == null ? NULL : $data->Notable_Wars; 
	$Founding_Story = $data->Founding_Story == null ? NULL : $data->Founding_Story; 
	$Flag_Design_Story = $data->Flag_Design_Story == null ? NULL : $data->Flag_Design_Story; 
	$Holidays = $data->Holidays == null ? NULL : $data->Holidays; 
	$Vehicles = $data->Vehicles == null ? NULL : $data->Vehicles; 
	$Items = $data->Items == null ? NULL : $data->Items; 
	$Technologies = $data->Technologies == null ? NULL : $data->Technologies; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO governments(Universe,Name,Description,Tags,Checks_And_Balances,Jobs,Type_Of_Government,Power_Structure,Power_Source,Privacy_Ideologies,Sociopolitical,Socioeconomical,Geocultural,Laws,Immigration,Term_Lengths,Electoral_Process,Criminal_System,International_Relations,Civilian_Life,Approval_Ratings,Space_Program,Leaders,Groups,Political_figures,Military,Navy,Airforce,Notable_Wars,Founding_Story,Flag_Design_Story,Holidays,Vehicles,Items,Technologies,Creatures,Notes,Private_Notes,user_id) 
VALUES($Universe,'$Name','$Description','$Tags','$Checks_And_Balances','$Jobs','$Type_Of_Government','$Power_Structure','$Power_Source','$Privacy_Ideologies','$Sociopolitical','$Socioeconomical','$Geocultural','$Laws','$Immigration','$Term_Lengths','$Electoral_Process','$Criminal_System','$International_Relations','$Civilian_Life','$Approval_Ratings','$Space_Program','$Leaders','$Groups','$Political_figures','$Military','$Navy','$Airforce','$Notable_Wars','$Founding_Story','$Flag_Design_Story','$Holidays','$Vehicles','$Items','$Technologies','$Creatures','$Notes','$Private_Notes',$user_id)"; 


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

function deleteGovernment($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM governments WHERE id = $id; ";

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

function updateGovernment($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Checks_And_Balances = $data->Checks_And_Balances == null ? NULL : $data->Checks_And_Balances; 
	$Jobs = $data->Jobs == null ? NULL : $data->Jobs; 
	$Type_Of_Government = $data->Type_Of_Government == null ? NULL : $data->Type_Of_Government; 
	$Power_Structure = $data->Power_Structure == null ? NULL : $data->Power_Structure; 
	$Power_Source = $data->Power_Source == null ? NULL : $data->Power_Source; 
	$Privacy_Ideologies = $data->Privacy_Ideologies == null ? NULL : $data->Privacy_Ideologies; 
	$Sociopolitical = $data->Sociopolitical == null ? NULL : $data->Sociopolitical; 
	$Socioeconomical = $data->Socioeconomical == null ? NULL : $data->Socioeconomical; 
	$Geocultural = $data->Geocultural == null ? NULL : $data->Geocultural; 
	$Laws = $data->Laws == null ? NULL : $data->Laws; 
	$Immigration = $data->Immigration == null ? NULL : $data->Immigration; 
	$Term_Lengths = $data->Term_Lengths == null ? NULL : $data->Term_Lengths; 
	$Electoral_Process = $data->Electoral_Process == null ? NULL : $data->Electoral_Process; 
	$Criminal_System = $data->Criminal_System == null ? NULL : $data->Criminal_System; 
	$International_Relations = $data->International_Relations == null ? NULL : $data->International_Relations; 
	$Civilian_Life = $data->Civilian_Life == null ? NULL : $data->Civilian_Life; 
	$Approval_Ratings = $data->Approval_Ratings == null ? NULL : $data->Approval_Ratings; 
	$Space_Program = $data->Space_Program == null ? NULL : $data->Space_Program; 
	$Leaders = $data->Leaders == null ? NULL : $data->Leaders; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Political_figures = $data->Political_figures == null ? NULL : $data->Political_figures; 
	$Military = $data->Military == null ? NULL : $data->Military; 
	$Navy = $data->Navy == null ? NULL : $data->Navy; 
	$Airforce = $data->Airforce == null ? NULL : $data->Airforce; 
	$Notable_Wars = $data->Notable_Wars == null ? NULL : $data->Notable_Wars; 
	$Founding_Story = $data->Founding_Story == null ? NULL : $data->Founding_Story; 
	$Flag_Design_Story = $data->Flag_Design_Story == null ? NULL : $data->Flag_Design_Story; 
	$Holidays = $data->Holidays == null ? NULL : $data->Holidays; 
	$Vehicles = $data->Vehicles == null ? NULL : $data->Vehicles; 
	$Items = $data->Items == null ? NULL : $data->Items; 
	$Technologies = $data->Technologies == null ? NULL : $data->Technologies; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE governments SET Universe = $Universe
,Name = '$Name',Description = '$Description',Tags = '$Tags',Checks_And_Balances = '$Checks_And_Balances',Jobs = '$Jobs',Type_Of_Government = '$Type_Of_Government',Power_Structure = '$Power_Structure',Power_Source = '$Power_Source',Privacy_Ideologies = '$Privacy_Ideologies',Sociopolitical = '$Sociopolitical',Socioeconomical = '$Socioeconomical',Geocultural = '$Geocultural',Laws = '$Laws',Immigration = '$Immigration',Term_Lengths = '$Term_Lengths',Electoral_Process = '$Electoral_Process',Criminal_System = '$Criminal_System',International_Relations = '$International_Relations',Civilian_Life = '$Civilian_Life',Approval_Ratings = '$Approval_Ratings',Space_Program = '$Space_Program',Leaders = '$Leaders',Groups = '$Groups',Political_figures = '$Political_figures',Military = '$Military',Navy = '$Navy',Airforce = '$Airforce',Notable_Wars = '$Notable_Wars',Founding_Story = '$Founding_Story',Flag_Design_Story = '$Flag_Design_Story',Holidays = '$Holidays',Vehicles = '$Vehicles',Items = '$Items',Technologies = '$Technologies',Creatures = '$Creatures',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllGroups(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM groups Where user_id = '$user_id'";

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

function getGroups(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM groups Where user_id = '$user_id' and id = '$id'";

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

function addGroup($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Subgroups = $data->Subgroups == null ? NULL : $data->Subgroups; 
	$Supergroups = $data->Supergroups == null ? NULL : $data->Supergroups; 
	$Sistergroups = $data->Sistergroups == null ? NULL : $data->Sistergroups; 
	$Organization_structure = $data->Organization_structure == null ? NULL : $data->Organization_structure; 
	$Leaders = $data->Leaders == null ? NULL : $data->Leaders; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Members = $data->Members == null ? NULL : $data->Members; 
	$Offices = $data->Offices == null ? NULL : $data->Offices; 
	$Locations = $data->Locations == null ? NULL : $data->Locations; 
	$Headquarters = $data->Headquarters == null ? NULL : $data->Headquarters; 
	$Motivations = $data->Motivations == null ? NULL : $data->Motivations; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Risks = $data->Risks == null ? NULL : $data->Risks; 
	$Obstacles = $data->Obstacles == null ? NULL : $data->Obstacles; 
	$Goals = $data->Goals == null ? NULL : $data->Goals; 
	$Clients = $data->Clients == null ? NULL : $data->Clients; 
	$Allies = $data->Allies == null ? NULL : $data->Allies; 
	$Enemies = $data->Enemies == null ? NULL : $data->Enemies; 
	$Rivals = $data->Rivals == null ? NULL : $data->Rivals; 
	$Suppliers = $data->Suppliers == null ? NULL : $data->Suppliers; 
	$Inventory = $data->Inventory == null ? NULL : $data->Inventory; 
	$Equipment = $data->Equipment == null ? NULL : $data->Equipment; 
	$Key_items = $data->Key_items == null ? NULL : $data->Key_items; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO groups(Universe,Tags,Other_Names,Description,Name,Subgroups,Supergroups,Sistergroups,Organization_structure,Leaders,Creatures,Members,Offices,Locations,Headquarters,Motivations,Traditions,Risks,Obstacles,Goals,Clients,Allies,Enemies,Rivals,Suppliers,Inventory,Equipment,Key_items,Notes,Private_notes,user_id) 
VALUES($Universe,'$Tags','$Other_Names','$Description','$Name','$Subgroups','$Supergroups','$Sistergroups','$Organization_structure','$Leaders','$Creatures','$Members','$Offices','$Locations','$Headquarters','$Motivations','$Traditions','$Risks','$Obstacles','$Goals','$Clients','$Allies','$Enemies','$Rivals','$Suppliers','$Inventory','$Equipment','$Key_items','$Notes','$Private_notes',$user_id)"; 


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

function deleteGroup($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM groups WHERE id = $id; ";

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

function updateGroup($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Subgroups = $data->Subgroups == null ? NULL : $data->Subgroups; 
	$Supergroups = $data->Supergroups == null ? NULL : $data->Supergroups; 
	$Sistergroups = $data->Sistergroups == null ? NULL : $data->Sistergroups; 
	$Organization_structure = $data->Organization_structure == null ? NULL : $data->Organization_structure; 
	$Leaders = $data->Leaders == null ? NULL : $data->Leaders; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Members = $data->Members == null ? NULL : $data->Members; 
	$Offices = $data->Offices == null ? NULL : $data->Offices; 
	$Locations = $data->Locations == null ? NULL : $data->Locations; 
	$Headquarters = $data->Headquarters == null ? NULL : $data->Headquarters; 
	$Motivations = $data->Motivations == null ? NULL : $data->Motivations; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Risks = $data->Risks == null ? NULL : $data->Risks; 
	$Obstacles = $data->Obstacles == null ? NULL : $data->Obstacles; 
	$Goals = $data->Goals == null ? NULL : $data->Goals; 
	$Clients = $data->Clients == null ? NULL : $data->Clients; 
	$Allies = $data->Allies == null ? NULL : $data->Allies; 
	$Enemies = $data->Enemies == null ? NULL : $data->Enemies; 
	$Rivals = $data->Rivals == null ? NULL : $data->Rivals; 
	$Suppliers = $data->Suppliers == null ? NULL : $data->Suppliers; 
	$Inventory = $data->Inventory == null ? NULL : $data->Inventory; 
	$Equipment = $data->Equipment == null ? NULL : $data->Equipment; 
	$Key_items = $data->Key_items == null ? NULL : $data->Key_items; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE groups SET Universe = $Universe
,Tags = '$Tags',Other_Names = '$Other_Names',Description = '$Description',Name = '$Name',Subgroups = '$Subgroups',Supergroups = '$Supergroups',Sistergroups = '$Sistergroups',Organization_structure = '$Organization_structure',Leaders = '$Leaders',Creatures = '$Creatures',Members = '$Members',Offices = '$Offices',Locations = '$Locations',Headquarters = '$Headquarters',Motivations = '$Motivations',Traditions = '$Traditions',Risks = '$Risks',Obstacles = '$Obstacles',Goals = '$Goals',Clients = '$Clients',Allies = '$Allies',Enemies = '$Enemies',Rivals = '$Rivals',Suppliers = '$Suppliers',Inventory = '$Inventory',Equipment = '$Equipment',Key_items = '$Key_items',Notes = '$Notes',Private_notes = '$Private_notes'    WHERE id = $id"; 

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

function getAllItems(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM items Where user_id = '$user_id'";

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

function getItems(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM items Where user_id = '$user_id' and id = '$id'";

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

function addItem($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Weight = $data->Weight == null ? "NULL" : $data->Weight; 
	$Year_it_was_made = $data->Year_it_was_made == null ? "NULL" : $data->Year_it_was_made; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Item_Type = $data->Item_Type == null ? NULL : $data->Item_Type; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Materials = $data->Materials == null ? NULL : $data->Materials; 
	$Past_Owners = $data->Past_Owners == null ? NULL : $data->Past_Owners; 
	$Makers = $data->Makers == null ? NULL : $data->Makers; 
	$Current_Owners = $data->Current_Owners == null ? NULL : $data->Current_Owners; 
	$Original_Owners = $data->Original_Owners == null ? NULL : $data->Original_Owners; 
	$Magical_effects = $data->Magical_effects == null ? NULL : $data->Magical_effects; 
	$Magic = $data->Magic == null ? NULL : $data->Magic; 
	$Technical_effects = $data->Technical_effects == null ? NULL : $data->Technical_effects; 
	$Technology = $data->Technology == null ? NULL : $data->Technology; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO items(Universe,Weight,Year_it_was_made,Name,Item_Type,Description,Tags,Materials,Past_Owners,Makers,Current_Owners,Original_Owners,Magical_effects,Magic,Technical_effects,Technology,Private_Notes,Notes,user_id) 
VALUES($Universe,$Weight,$Year_it_was_made,'$Name','$Item_Type','$Description','$Tags','$Materials','$Past_Owners','$Makers','$Current_Owners','$Original_Owners','$Magical_effects','$Magic','$Technical_effects','$Technology','$Private_Notes','$Notes',$user_id)"; 


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

function deleteItem($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM items WHERE id = $id; ";

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

function updateItem($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Weight = $data->Weight == null ? "NULL" : $data->Weight; 
	$Year_it_was_made = $data->Year_it_was_made == null ? "NULL" : $data->Year_it_was_made; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Item_Type = $data->Item_Type == null ? NULL : $data->Item_Type; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Materials = $data->Materials == null ? NULL : $data->Materials; 
	$Past_Owners = $data->Past_Owners == null ? NULL : $data->Past_Owners; 
	$Makers = $data->Makers == null ? NULL : $data->Makers; 
	$Current_Owners = $data->Current_Owners == null ? NULL : $data->Current_Owners; 
	$Original_Owners = $data->Original_Owners == null ? NULL : $data->Original_Owners; 
	$Magical_effects = $data->Magical_effects == null ? NULL : $data->Magical_effects; 
	$Magic = $data->Magic == null ? NULL : $data->Magic; 
	$Technical_effects = $data->Technical_effects == null ? NULL : $data->Technical_effects; 
	$Technology = $data->Technology == null ? NULL : $data->Technology; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE items SET Universe = $Universe,Weight = $Weight,Year_it_was_made = $Year_it_was_made
,Name = '$Name',Item_Type = '$Item_Type',Description = '$Description',Tags = '$Tags',Materials = '$Materials',Past_Owners = '$Past_Owners',Makers = '$Makers',Current_Owners = '$Current_Owners',Original_Owners = '$Original_Owners',Magical_effects = '$Magical_effects',Magic = '$Magic',Technical_effects = '$Technical_effects',Technology = '$Technology',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllJobs(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM jobs Where user_id = '$user_id'";

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

function getJobs(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM jobs Where user_id = '$user_id' and id = '$id'";

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

function addJob($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Work_hours = $data->Work_hours == null ? "NULL" : $data->Work_hours; 
	$Pay_rate = $data->Pay_rate == null ? "NULL" : $data->Pay_rate; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_job = $data->Type_of_job == null ? NULL : $data->Type_of_job; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Experience = $data->Experience == null ? NULL : $data->Experience; 
	$Education = $data->Education == null ? NULL : $data->Education; 
	$Vehicles = $data->Vehicles == null ? NULL : $data->Vehicles; 
	$Training = $data->Training == null ? NULL : $data->Training; 
	$Long_term_risks = $data->Long_term_risks == null ? NULL : $data->Long_term_risks; 
	$Occupational_hazards = $data->Occupational_hazards == null ? NULL : $data->Occupational_hazards; 
	$Time_off = $data->Time_off == null ? NULL : $data->Time_off; 
	$Similar_jobs = $data->Similar_jobs == null ? NULL : $data->Similar_jobs; 
	$Promotions = $data->Promotions == null ? NULL : $data->Promotions; 
	$Specializations = $data->Specializations == null ? NULL : $data->Specializations; 
	$Field = $data->Field == null ? NULL : $data->Field; 
	$Ranks = $data->Ranks == null ? NULL : $data->Ranks; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Job_origin = $data->Job_origin == null ? NULL : $data->Job_origin; 
	$Initial_goal = $data->Initial_goal == null ? NULL : $data->Initial_goal; 
	$Notable_figures = $data->Notable_figures == null ? NULL : $data->Notable_figures; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO jobs(Universe,Work_hours,Pay_rate,Name,Description,Type_of_job,Alternate_names,Tags,Experience,Education,Vehicles,Training,Long_term_risks,Occupational_hazards,Time_off,Similar_jobs,Promotions,Specializations,Field,Ranks,Traditions,Job_origin,Initial_goal,Notable_figures,Notes,Private_Notes,user_id) 
VALUES($Universe,$Work_hours,$Pay_rate,'$Name','$Description','$Type_of_job','$Alternate_names','$Tags','$Experience','$Education','$Vehicles','$Training','$Long_term_risks','$Occupational_hazards','$Time_off','$Similar_jobs','$Promotions','$Specializations','$Field','$Ranks','$Traditions','$Job_origin','$Initial_goal','$Notable_figures','$Notes','$Private_Notes',$user_id)"; 


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

function deleteJob($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM jobs WHERE id = $id; ";

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

function updateJob($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Work_hours = $data->Work_hours == null ? "NULL" : $data->Work_hours; 
	$Pay_rate = $data->Pay_rate == null ? "NULL" : $data->Pay_rate; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_job = $data->Type_of_job == null ? NULL : $data->Type_of_job; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Experience = $data->Experience == null ? NULL : $data->Experience; 
	$Education = $data->Education == null ? NULL : $data->Education; 
	$Vehicles = $data->Vehicles == null ? NULL : $data->Vehicles; 
	$Training = $data->Training == null ? NULL : $data->Training; 
	$Long_term_risks = $data->Long_term_risks == null ? NULL : $data->Long_term_risks; 
	$Occupational_hazards = $data->Occupational_hazards == null ? NULL : $data->Occupational_hazards; 
	$Time_off = $data->Time_off == null ? NULL : $data->Time_off; 
	$Similar_jobs = $data->Similar_jobs == null ? NULL : $data->Similar_jobs; 
	$Promotions = $data->Promotions == null ? NULL : $data->Promotions; 
	$Specializations = $data->Specializations == null ? NULL : $data->Specializations; 
	$Field = $data->Field == null ? NULL : $data->Field; 
	$Ranks = $data->Ranks == null ? NULL : $data->Ranks; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Job_origin = $data->Job_origin == null ? NULL : $data->Job_origin; 
	$Initial_goal = $data->Initial_goal == null ? NULL : $data->Initial_goal; 
	$Notable_figures = $data->Notable_figures == null ? NULL : $data->Notable_figures; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE jobs SET Universe = $Universe,Work_hours = $Work_hours,Pay_rate = $Pay_rate
,Name = '$Name',Description = '$Description',Type_of_job = '$Type_of_job',Alternate_names = '$Alternate_names',Tags = '$Tags',Experience = '$Experience',Education = '$Education',Vehicles = '$Vehicles',Training = '$Training',Long_term_risks = '$Long_term_risks',Occupational_hazards = '$Occupational_hazards',Time_off = '$Time_off',Similar_jobs = '$Similar_jobs',Promotions = '$Promotions',Specializations = '$Specializations',Field = '$Field',Ranks = '$Ranks',Traditions = '$Traditions',Job_origin = '$Job_origin',Initial_goal = '$Initial_goal',Notable_figures = '$Notable_figures',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllLandmarks(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM landmarks Where user_id = '$user_id'";

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

function getLandmarks(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM landmarks Where user_id = '$user_id' and id = '$id'";

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

function addLandmark($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Established_year = $data->Established_year == null ? "NULL" : $data->Established_year; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Type_of_landmark = $data->Type_of_landmark == null ? NULL : $data->Type_of_landmark; 
	$Country = $data->Country == null ? NULL : $data->Country; 
	$Nearby_towns = $data->Nearby_towns == null ? NULL : $data->Nearby_towns; 
	$Colors = $data->Colors == null ? NULL : $data->Colors; 
	$Materials = $data->Materials == null ? NULL : $data->Materials; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Flora = $data->Flora == null ? NULL : $data->Flora; 
	$Creation_story = $data->Creation_story == null ? NULL : $data->Creation_story; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO landmarks(Universe,Size,Established_year,Name,Tags,Description,Other_Names,Type_of_landmark,Country,Nearby_towns,Colors,Materials,Creatures,Flora,Creation_story,Notes,Private_Notes,user_id) 
VALUES($Universe,$Size,$Established_year,'$Name','$Tags','$Description','$Other_Names','$Type_of_landmark','$Country','$Nearby_towns','$Colors','$Materials','$Creatures','$Flora','$Creation_story','$Notes','$Private_Notes',$user_id)"; 


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

function deleteLandmark($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM landmarks WHERE id = $id; ";

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

function updateLandmark($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Established_year = $data->Established_year == null ? "NULL" : $data->Established_year; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Type_of_landmark = $data->Type_of_landmark == null ? NULL : $data->Type_of_landmark; 
	$Country = $data->Country == null ? NULL : $data->Country; 
	$Nearby_towns = $data->Nearby_towns == null ? NULL : $data->Nearby_towns; 
	$Colors = $data->Colors == null ? NULL : $data->Colors; 
	$Materials = $data->Materials == null ? NULL : $data->Materials; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Flora = $data->Flora == null ? NULL : $data->Flora; 
	$Creation_story = $data->Creation_story == null ? NULL : $data->Creation_story; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE landmarks SET Universe = $Universe,Size = $Size,Established_year = $Established_year
,Name = '$Name',Tags = '$Tags',Description = '$Description',Other_Names = '$Other_Names',Type_of_landmark = '$Type_of_landmark',Country = '$Country',Nearby_towns = '$Nearby_towns',Colors = '$Colors',Materials = '$Materials',Creatures = '$Creatures',Flora = '$Flora',Creation_story = '$Creation_story',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllLanguages(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM languages Where user_id = '$user_id'";

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

function getLanguages(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM languages Where user_id = '$user_id' and id = '$id'";

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

function addLanguage($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Typology = $data->Typology == null ? NULL : $data->Typology; 
	$Dialectical_information = $data->Dialectical_information == null ? NULL : $data->Dialectical_information; 
	$Register = $data->Register == null ? NULL : $data->Register; 
	$History = $data->History == null ? NULL : $data->History; 
	$Evolution = $data->Evolution == null ? NULL : $data->Evolution; 
	$Gestures = $data->Gestures == null ? NULL : $data->Gestures; 
	$Phonology = $data->Phonology == null ? NULL : $data->Phonology; 
	$Grammar = $data->Grammar == null ? NULL : $data->Grammar; 
	$Please = $data->Please == null ? NULL : $data->Please; 
	$Trade = $data->Trade == null ? NULL : $data->Trade; 
	$Family = $data->Family == null ? NULL : $data->Family; 
	$Body_parts = $data->Body_parts == null ? NULL : $data->Body_parts; 
	$No_words = $data->No_words == null ? NULL : $data->No_words; 
	$Yes_words = $data->Yes_words == null ? NULL : $data->Yes_words; 
	$Sorry = $data->Sorry == null ? NULL : $data->Sorry; 
	$You_are_welcome = $data->You_are_welcome == null ? NULL : $data->You_are_welcome; 
	$Thank_you = $data->Thank_you == null ? NULL : $data->Thank_you; 
	$Goodbyes = $data->Goodbyes == null ? NULL : $data->Goodbyes; 
	$Greetings = $data->Greetings == null ? NULL : $data->Greetings; 
	$Pronouns = $data->Pronouns == null ? NULL : $data->Pronouns; 
	$Numbers = $data->Numbers == null ? NULL : $data->Numbers; 
	$Quantifiers = $data->Quantifiers == null ? NULL : $data->Quantifiers; 
	$Determiners = $data->Determiners == null ? NULL : $data->Determiners; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO languages(Universe,Tags,Other_Names,Name,Typology,Dialectical_information,Register,History,Evolution,Gestures,Phonology,Grammar,Please,Trade,Family,Body_parts,No_words,Yes_words,Sorry,You_are_welcome,Thank_you,Goodbyes,Greetings,Pronouns,Numbers,Quantifiers,Determiners,Notes,Private_notes,user_id) 
VALUES($Universe,'$Tags','$Other_Names','$Name','$Typology','$Dialectical_information','$Register','$History','$Evolution','$Gestures','$Phonology','$Grammar','$Please','$Trade','$Family','$Body_parts','$No_words','$Yes_words','$Sorry','$You_are_welcome','$Thank_you','$Goodbyes','$Greetings','$Pronouns','$Numbers','$Quantifiers','$Determiners','$Notes','$Private_notes',$user_id)"; 


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

function deleteLanguage($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM languages WHERE id = $id; ";

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

function updateLanguage($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Typology = $data->Typology == null ? NULL : $data->Typology; 
	$Dialectical_information = $data->Dialectical_information == null ? NULL : $data->Dialectical_information; 
	$Register = $data->Register == null ? NULL : $data->Register; 
	$History = $data->History == null ? NULL : $data->History; 
	$Evolution = $data->Evolution == null ? NULL : $data->Evolution; 
	$Gestures = $data->Gestures == null ? NULL : $data->Gestures; 
	$Phonology = $data->Phonology == null ? NULL : $data->Phonology; 
	$Grammar = $data->Grammar == null ? NULL : $data->Grammar; 
	$Please = $data->Please == null ? NULL : $data->Please; 
	$Trade = $data->Trade == null ? NULL : $data->Trade; 
	$Family = $data->Family == null ? NULL : $data->Family; 
	$Body_parts = $data->Body_parts == null ? NULL : $data->Body_parts; 
	$No_words = $data->No_words == null ? NULL : $data->No_words; 
	$Yes_words = $data->Yes_words == null ? NULL : $data->Yes_words; 
	$Sorry = $data->Sorry == null ? NULL : $data->Sorry; 
	$You_are_welcome = $data->You_are_welcome == null ? NULL : $data->You_are_welcome; 
	$Thank_you = $data->Thank_you == null ? NULL : $data->Thank_you; 
	$Goodbyes = $data->Goodbyes == null ? NULL : $data->Goodbyes; 
	$Greetings = $data->Greetings == null ? NULL : $data->Greetings; 
	$Pronouns = $data->Pronouns == null ? NULL : $data->Pronouns; 
	$Numbers = $data->Numbers == null ? NULL : $data->Numbers; 
	$Quantifiers = $data->Quantifiers == null ? NULL : $data->Quantifiers; 
	$Determiners = $data->Determiners == null ? NULL : $data->Determiners; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE languages SET Universe = $Universe
,Tags = '$Tags',Other_Names = '$Other_Names',Name = '$Name',Typology = '$Typology',Dialectical_information = '$Dialectical_information',Register = '$Register',History = '$History',Evolution = '$Evolution',Gestures = '$Gestures',Phonology = '$Phonology',Grammar = '$Grammar',Please = '$Please',Trade = '$Trade',Family = '$Family',Body_parts = '$Body_parts',No_words = '$No_words',Yes_words = '$Yes_words',Sorry = '$Sorry',You_are_welcome = '$You_are_welcome',Thank_you = '$Thank_you',Goodbyes = '$Goodbyes',Greetings = '$Greetings',Pronouns = '$Pronouns',Numbers = '$Numbers',Quantifiers = '$Quantifiers',Determiners = '$Determiners',Notes = '$Notes',Private_notes = '$Private_notes'    WHERE id = $id"; 

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

function getAllLocations(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM locations Where user_id = '$user_id'";

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

function getLocations(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM locations Where user_id = '$user_id' and id = '$id'";

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

function addLocation($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Population = $data->Population == null ? "NULL" : $data->Population; 
	$Area = $data->Area == null ? "NULL" : $data->Area; 
	$Established_Year = $data->Established_Year == null ? "NULL" : $data->Established_Year; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Type = $data->Type == null ? NULL : $data->Type; 
	$Leaders = $data->Leaders == null ? NULL : $data->Leaders; 
	$Language = $data->Language == null ? NULL : $data->Language; 
	$Currency = $data->Currency == null ? NULL : $data->Currency; 
	$Motto = $data->Motto == null ? NULL : $data->Motto; 
	$Sports = $data->Sports == null ? NULL : $data->Sports; 
	$Laws = $data->Laws == null ? NULL : $data->Laws; 
	$Spoken_Languages = $data->Spoken_Languages == null ? NULL : $data->Spoken_Languages; 
	$Largest_cities = $data->Largest_cities == null ? NULL : $data->Largest_cities; 
	$Notable_cities = $data->Notable_cities == null ? NULL : $data->Notable_cities; 
	$Capital_cities = $data->Capital_cities == null ? NULL : $data->Capital_cities; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Crops = $data->Crops == null ? NULL : $data->Crops; 
	$Located_at = $data->Located_at == null ? NULL : $data->Located_at; 
	$Climate = $data->Climate == null ? NULL : $data->Climate; 
	$Notable_Wars = $data->Notable_Wars == null ? NULL : $data->Notable_Wars; 
	$Founding_Story = $data->Founding_Story == null ? NULL : $data->Founding_Story; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO locations(Universe,Population,Area,Established_Year,Description,Tags,Name,Type,Leaders,Language,Currency,Motto,Sports,Laws,Spoken_Languages,Largest_cities,Notable_cities,Capital_cities,Landmarks,Crops,Located_at,Climate,Notable_Wars,Founding_Story,Notes,Private_Notes,user_id) 
VALUES($Universe,$Population,$Area,$Established_Year,'$Description','$Tags','$Name','$Type','$Leaders','$Language','$Currency','$Motto','$Sports','$Laws','$Spoken_Languages','$Largest_cities','$Notable_cities','$Capital_cities','$Landmarks','$Crops','$Located_at','$Climate','$Notable_Wars','$Founding_Story','$Notes','$Private_Notes',$user_id)"; 


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

function deleteLocation($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM locations WHERE id = $id; ";

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

function updateLocation($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Population = $data->Population == null ? "NULL" : $data->Population; 
	$Area = $data->Area == null ? "NULL" : $data->Area; 
	$Established_Year = $data->Established_Year == null ? "NULL" : $data->Established_Year; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Type = $data->Type == null ? NULL : $data->Type; 
	$Leaders = $data->Leaders == null ? NULL : $data->Leaders; 
	$Language = $data->Language == null ? NULL : $data->Language; 
	$Currency = $data->Currency == null ? NULL : $data->Currency; 
	$Motto = $data->Motto == null ? NULL : $data->Motto; 
	$Sports = $data->Sports == null ? NULL : $data->Sports; 
	$Laws = $data->Laws == null ? NULL : $data->Laws; 
	$Spoken_Languages = $data->Spoken_Languages == null ? NULL : $data->Spoken_Languages; 
	$Largest_cities = $data->Largest_cities == null ? NULL : $data->Largest_cities; 
	$Notable_cities = $data->Notable_cities == null ? NULL : $data->Notable_cities; 
	$Capital_cities = $data->Capital_cities == null ? NULL : $data->Capital_cities; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Crops = $data->Crops == null ? NULL : $data->Crops; 
	$Located_at = $data->Located_at == null ? NULL : $data->Located_at; 
	$Climate = $data->Climate == null ? NULL : $data->Climate; 
	$Notable_Wars = $data->Notable_Wars == null ? NULL : $data->Notable_Wars; 
	$Founding_Story = $data->Founding_Story == null ? NULL : $data->Founding_Story; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE locations SET Universe = $Universe,Population = $Population,Area = $Area,Established_Year = $Established_Year
,Description = '$Description',Tags = '$Tags',Name = '$Name',Type = '$Type',Leaders = '$Leaders',Language = '$Language',Currency = '$Currency',Motto = '$Motto',Sports = '$Sports',Laws = '$Laws',Spoken_Languages = '$Spoken_Languages',Largest_cities = '$Largest_cities',Notable_cities = '$Notable_cities',Capital_cities = '$Capital_cities',Landmarks = '$Landmarks',Crops = '$Crops',Located_at = '$Located_at',Climate = '$Climate',Notable_Wars = '$Notable_Wars',Founding_Story = '$Founding_Story',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllLores(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM lores Where user_id = '$user_id'";

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

function getLores(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM lores Where user_id = '$user_id' and id = '$id'";

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

function addLore($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Summary = $data->Summary == null ? NULL : $data->Summary; 
	$Type = $data->Type == null ? NULL : $data->Type; 
	$Tone = $data->Tone == null ? NULL : $data->Tone; 
	$Full_text = $data->Full_text == null ? NULL : $data->Full_text; 
	$Dialect = $data->Dialect == null ? NULL : $data->Dialect; 
	$Structure = $data->Structure == null ? NULL : $data->Structure; 
	$Genre = $data->Genre == null ? NULL : $data->Genre; 
	$Buildings = $data->Buildings == null ? NULL : $data->Buildings; 
	$Time_period = $data->Time_period == null ? NULL : $data->Time_period; 
	$Planets = $data->Planets == null ? NULL : $data->Planets; 
	$Continents = $data->Continents == null ? NULL : $data->Continents; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Towns = $data->Towns == null ? NULL : $data->Towns; 
	$Schools = $data->Schools == null ? NULL : $data->Schools; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Sports = $data->Sports == null ? NULL : $data->Sports; 
	$Foods = $data->Foods == null ? NULL : $data->Foods; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Governments = $data->Governments == null ? NULL : $data->Governments; 
	$Magic = $data->Magic == null ? NULL : $data->Magic; 
	$Religions = $data->Religions == null ? NULL : $data->Religions; 
	$Races = $data->Races == null ? NULL : $data->Races; 
	$Vehicles = $data->Vehicles == null ? NULL : $data->Vehicles; 
	$Technologies = $data->Technologies == null ? NULL : $data->Technologies; 
	$Jobs = $data->Jobs == null ? NULL : $data->Jobs; 
	$Floras = $data->Floras == null ? NULL : $data->Floras; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Deities = $data->Deities == null ? NULL : $data->Deities; 
	$Characters = $data->Characters == null ? NULL : $data->Characters; 
	$Subjects = $data->Subjects == null ? NULL : $data->Subjects; 
	$Believers = $data->Believers == null ? NULL : $data->Believers; 
	$Hoaxes = $data->Hoaxes == null ? NULL : $data->Hoaxes; 
	$True_parts = $data->True_parts == null ? NULL : $data->True_parts; 
	$False_parts = $data->False_parts == null ? NULL : $data->False_parts; 
	$Believability = $data->Believability == null ? NULL : $data->Believability; 
	$Morals = $data->Morals == null ? NULL : $data->Morals; 
	$Symbolisms = $data->Symbolisms == null ? NULL : $data->Symbolisms; 
	$Motivations = $data->Motivations == null ? NULL : $data->Motivations; 
	$Created_phrases = $data->Created_phrases == null ? NULL : $data->Created_phrases; 
	$Reception = $data->Reception == null ? NULL : $data->Reception; 
	$Criticism = $data->Criticism == null ? NULL : $data->Criticism; 
	$Media_adaptations = $data->Media_adaptations == null ? NULL : $data->Media_adaptations; 
	$Interpretations = $data->Interpretations == null ? NULL : $data->Interpretations; 
	$Impact = $data->Impact == null ? NULL : $data->Impact; 
	$Created_traditions = $data->Created_traditions == null ? NULL : $data->Created_traditions; 
	$Influence_on_modern_times = $data->Influence_on_modern_times == null ? NULL : $data->Influence_on_modern_times; 
	$Original_telling = $data->Original_telling == null ? NULL : $data->Original_telling; 
	$Inspirations = $data->Inspirations == null ? NULL : $data->Inspirations; 
	$Original_author = $data->Original_author == null ? NULL : $data->Original_author; 
	$Original_languages = $data->Original_languages == null ? NULL : $data->Original_languages; 
	$Source = $data->Source == null ? NULL : $data->Source; 
	$Date_recorded = $data->Date_recorded == null ? NULL : $data->Date_recorded; 
	$Background_information = $data->Background_information == null ? NULL : $data->Background_information; 
	$Propagation_method = $data->Propagation_method == null ? NULL : $data->Propagation_method; 
	$Historical_context = $data->Historical_context == null ? NULL : $data->Historical_context; 
	$Important_translations = $data->Important_translations == null ? NULL : $data->Important_translations; 
	$Evolution_over_time = $data->Evolution_over_time == null ? NULL : $data->Evolution_over_time; 
	$Geographical_variations = $data->Geographical_variations == null ? NULL : $data->Geographical_variations; 
	$Related_lores = $data->Related_lores == null ? NULL : $data->Related_lores; 
	$Variations = $data->Variations == null ? NULL : $data->Variations; 
	$Translation_variations = $data->Translation_variations == null ? NULL : $data->Translation_variations; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO lores(Universe,Tags,Name,Summary,Type,Tone,Full_text,Dialect,Structure,Genre,Buildings,Time_period,Planets,Continents,Countries,Landmarks,Towns,Schools,Conditions,Sports,Foods,Traditions,Groups,Governments,Magic,Religions,Races,Vehicles,Technologies,Jobs,Floras,Creatures,Deities,Characters,Subjects,Believers,Hoaxes,True_parts,False_parts,Believability,Morals,Symbolisms,Motivations,Created_phrases,Reception,Criticism,Media_adaptations,Interpretations,Impact,Created_traditions,Influence_on_modern_times,Original_telling,Inspirations,Original_author,Original_languages,Source,Date_recorded,Background_information,Propagation_method,Historical_context,Important_translations,Evolution_over_time,Geographical_variations,Related_lores,Variations,Translation_variations,Private_Notes,Notes,user_id) 
VALUES($Universe,'$Tags','$Name','$Summary','$Type','$Tone','$Full_text','$Dialect','$Structure','$Genre','$Buildings','$Time_period','$Planets','$Continents','$Countries','$Landmarks','$Towns','$Schools','$Conditions','$Sports','$Foods','$Traditions','$Groups','$Governments','$Magic','$Religions','$Races','$Vehicles','$Technologies','$Jobs','$Floras','$Creatures','$Deities','$Characters','$Subjects','$Believers','$Hoaxes','$True_parts','$False_parts','$Believability','$Morals','$Symbolisms','$Motivations','$Created_phrases','$Reception','$Criticism','$Media_adaptations','$Interpretations','$Impact','$Created_traditions','$Influence_on_modern_times','$Original_telling','$Inspirations','$Original_author','$Original_languages','$Source','$Date_recorded','$Background_information','$Propagation_method','$Historical_context','$Important_translations','$Evolution_over_time','$Geographical_variations','$Related_lores','$Variations','$Translation_variations','$Private_Notes','$Notes',$user_id)"; 


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

function deleteLore($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM lores WHERE id = $id; ";

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

function updateLore($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Summary = $data->Summary == null ? NULL : $data->Summary; 
	$Type = $data->Type == null ? NULL : $data->Type; 
	$Tone = $data->Tone == null ? NULL : $data->Tone; 
	$Full_text = $data->Full_text == null ? NULL : $data->Full_text; 
	$Dialect = $data->Dialect == null ? NULL : $data->Dialect; 
	$Structure = $data->Structure == null ? NULL : $data->Structure; 
	$Genre = $data->Genre == null ? NULL : $data->Genre; 
	$Buildings = $data->Buildings == null ? NULL : $data->Buildings; 
	$Time_period = $data->Time_period == null ? NULL : $data->Time_period; 
	$Planets = $data->Planets == null ? NULL : $data->Planets; 
	$Continents = $data->Continents == null ? NULL : $data->Continents; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Towns = $data->Towns == null ? NULL : $data->Towns; 
	$Schools = $data->Schools == null ? NULL : $data->Schools; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Sports = $data->Sports == null ? NULL : $data->Sports; 
	$Foods = $data->Foods == null ? NULL : $data->Foods; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Governments = $data->Governments == null ? NULL : $data->Governments; 
	$Magic = $data->Magic == null ? NULL : $data->Magic; 
	$Religions = $data->Religions == null ? NULL : $data->Religions; 
	$Races = $data->Races == null ? NULL : $data->Races; 
	$Vehicles = $data->Vehicles == null ? NULL : $data->Vehicles; 
	$Technologies = $data->Technologies == null ? NULL : $data->Technologies; 
	$Jobs = $data->Jobs == null ? NULL : $data->Jobs; 
	$Floras = $data->Floras == null ? NULL : $data->Floras; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Deities = $data->Deities == null ? NULL : $data->Deities; 
	$Characters = $data->Characters == null ? NULL : $data->Characters; 
	$Subjects = $data->Subjects == null ? NULL : $data->Subjects; 
	$Believers = $data->Believers == null ? NULL : $data->Believers; 
	$Hoaxes = $data->Hoaxes == null ? NULL : $data->Hoaxes; 
	$True_parts = $data->True_parts == null ? NULL : $data->True_parts; 
	$False_parts = $data->False_parts == null ? NULL : $data->False_parts; 
	$Believability = $data->Believability == null ? NULL : $data->Believability; 
	$Morals = $data->Morals == null ? NULL : $data->Morals; 
	$Symbolisms = $data->Symbolisms == null ? NULL : $data->Symbolisms; 
	$Motivations = $data->Motivations == null ? NULL : $data->Motivations; 
	$Created_phrases = $data->Created_phrases == null ? NULL : $data->Created_phrases; 
	$Reception = $data->Reception == null ? NULL : $data->Reception; 
	$Criticism = $data->Criticism == null ? NULL : $data->Criticism; 
	$Media_adaptations = $data->Media_adaptations == null ? NULL : $data->Media_adaptations; 
	$Interpretations = $data->Interpretations == null ? NULL : $data->Interpretations; 
	$Impact = $data->Impact == null ? NULL : $data->Impact; 
	$Created_traditions = $data->Created_traditions == null ? NULL : $data->Created_traditions; 
	$Influence_on_modern_times = $data->Influence_on_modern_times == null ? NULL : $data->Influence_on_modern_times; 
	$Original_telling = $data->Original_telling == null ? NULL : $data->Original_telling; 
	$Inspirations = $data->Inspirations == null ? NULL : $data->Inspirations; 
	$Original_author = $data->Original_author == null ? NULL : $data->Original_author; 
	$Original_languages = $data->Original_languages == null ? NULL : $data->Original_languages; 
	$Source = $data->Source == null ? NULL : $data->Source; 
	$Date_recorded = $data->Date_recorded == null ? NULL : $data->Date_recorded; 
	$Background_information = $data->Background_information == null ? NULL : $data->Background_information; 
	$Propagation_method = $data->Propagation_method == null ? NULL : $data->Propagation_method; 
	$Historical_context = $data->Historical_context == null ? NULL : $data->Historical_context; 
	$Important_translations = $data->Important_translations == null ? NULL : $data->Important_translations; 
	$Evolution_over_time = $data->Evolution_over_time == null ? NULL : $data->Evolution_over_time; 
	$Geographical_variations = $data->Geographical_variations == null ? NULL : $data->Geographical_variations; 
	$Related_lores = $data->Related_lores == null ? NULL : $data->Related_lores; 
	$Variations = $data->Variations == null ? NULL : $data->Variations; 
	$Translation_variations = $data->Translation_variations == null ? NULL : $data->Translation_variations; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE lores SET Universe = $Universe
,Tags = '$Tags',Name = '$Name',Summary = '$Summary',Type = '$Type',Tone = '$Tone',Full_text = '$Full_text',Dialect = '$Dialect',Structure = '$Structure',Genre = '$Genre',Buildings = '$Buildings',Time_period = '$Time_period',Planets = '$Planets',Continents = '$Continents',Countries = '$Countries',Landmarks = '$Landmarks',Towns = '$Towns',Schools = '$Schools',Conditions = '$Conditions',Sports = '$Sports',Foods = '$Foods',Traditions = '$Traditions',Groups = '$Groups',Governments = '$Governments',Magic = '$Magic',Religions = '$Religions',Races = '$Races',Vehicles = '$Vehicles',Technologies = '$Technologies',Jobs = '$Jobs',Floras = '$Floras',Creatures = '$Creatures',Deities = '$Deities',Characters = '$Characters',Subjects = '$Subjects',Believers = '$Believers',Hoaxes = '$Hoaxes',True_parts = '$True_parts',False_parts = '$False_parts',Believability = '$Believability',Morals = '$Morals',Symbolisms = '$Symbolisms',Motivations = '$Motivations',Created_phrases = '$Created_phrases',Reception = '$Reception',Criticism = '$Criticism',Media_adaptations = '$Media_adaptations',Interpretations = '$Interpretations',Impact = '$Impact',Created_traditions = '$Created_traditions',Influence_on_modern_times = '$Influence_on_modern_times',Original_telling = '$Original_telling',Inspirations = '$Inspirations',Original_author = '$Original_author',Original_languages = '$Original_languages',Source = '$Source',Date_recorded = '$Date_recorded',Background_information = '$Background_information',Propagation_method = '$Propagation_method',Historical_context = '$Historical_context',Important_translations = '$Important_translations',Evolution_over_time = '$Evolution_over_time',Geographical_variations = '$Geographical_variations',Related_lores = '$Related_lores',Variations = '$Variations',Translation_variations = '$Translation_variations',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllMagics(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM magics Where user_id = '$user_id'";

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

function getMagics(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM magics Where user_id = '$user_id' and id = '$id'";

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

function addMagic($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Scale = $data->Scale == null ? "NULL" : $data->Scale; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_magic = $data->Type_of_magic == null ? NULL : $data->Type_of_magic; 
	$Effects = $data->Effects == null ? NULL : $data->Effects; 
	$Visuals = $data->Visuals == null ? NULL : $data->Visuals; 
	$Aftereffects = $data->Aftereffects == null ? NULL : $data->Aftereffects; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Negative_effects = $data->Negative_effects == null ? NULL : $data->Negative_effects; 
	$Neutral_effects = $data->Neutral_effects == null ? NULL : $data->Neutral_effects; 
	$Positive_effects = $data->Positive_effects == null ? NULL : $data->Positive_effects; 
	$Deities = $data->Deities == null ? NULL : $data->Deities; 
	$Element = $data->Element == null ? NULL : $data->Element; 
	$Materials_required = $data->Materials_required == null ? NULL : $data->Materials_required; 
	$Skills_required = $data->Skills_required == null ? NULL : $data->Skills_required; 
	$Education = $data->Education == null ? NULL : $data->Education; 
	$Resource_costs = $data->Resource_costs == null ? NULL : $data->Resource_costs; 
	$Limitations = $data->Limitations == null ? NULL : $data->Limitations; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO magics(Universe,Scale,Tags,Name,Description,Type_of_magic,Effects,Visuals,Aftereffects,Conditions,Negative_effects,Neutral_effects,Positive_effects,Deities,Element,Materials_required,Skills_required,Education,Resource_costs,Limitations,Private_notes,Notes,user_id) 
VALUES($Universe,$Scale,'$Tags','$Name','$Description','$Type_of_magic','$Effects','$Visuals','$Aftereffects','$Conditions','$Negative_effects','$Neutral_effects','$Positive_effects','$Deities','$Element','$Materials_required','$Skills_required','$Education','$Resource_costs','$Limitations','$Private_notes','$Notes',$user_id)"; 


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

function deleteMagic($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM magics WHERE id = $id; ";

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

function updateMagic($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Scale = $data->Scale == null ? "NULL" : $data->Scale; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_magic = $data->Type_of_magic == null ? NULL : $data->Type_of_magic; 
	$Effects = $data->Effects == null ? NULL : $data->Effects; 
	$Visuals = $data->Visuals == null ? NULL : $data->Visuals; 
	$Aftereffects = $data->Aftereffects == null ? NULL : $data->Aftereffects; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Negative_effects = $data->Negative_effects == null ? NULL : $data->Negative_effects; 
	$Neutral_effects = $data->Neutral_effects == null ? NULL : $data->Neutral_effects; 
	$Positive_effects = $data->Positive_effects == null ? NULL : $data->Positive_effects; 
	$Deities = $data->Deities == null ? NULL : $data->Deities; 
	$Element = $data->Element == null ? NULL : $data->Element; 
	$Materials_required = $data->Materials_required == null ? NULL : $data->Materials_required; 
	$Skills_required = $data->Skills_required == null ? NULL : $data->Skills_required; 
	$Education = $data->Education == null ? NULL : $data->Education; 
	$Resource_costs = $data->Resource_costs == null ? NULL : $data->Resource_costs; 
	$Limitations = $data->Limitations == null ? NULL : $data->Limitations; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE magics SET Universe = $Universe,Scale = $Scale
,Tags = '$Tags',Name = '$Name',Description = '$Description',Type_of_magic = '$Type_of_magic',Effects = '$Effects',Visuals = '$Visuals',Aftereffects = '$Aftereffects',Conditions = '$Conditions',Negative_effects = '$Negative_effects',Neutral_effects = '$Neutral_effects',Positive_effects = '$Positive_effects',Deities = '$Deities',Element = '$Element',Materials_required = '$Materials_required',Skills_required = '$Skills_required',Education = '$Education',Resource_costs = '$Resource_costs',Limitations = '$Limitations',Private_notes = '$Private_notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllOrganizations(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM organizations Where user_id = '$user_id'";

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

function getOrganizations(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM organizations Where user_id = '$user_id' and id = '$id'";

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

function addOrganization($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Formation_year = $data->Formation_year == null ? "NULL" : $data->Formation_year; 
	$Closure_year = $data->Closure_year == null ? "NULL" : $data->Closure_year; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_organization = $data->Type_of_organization == null ? NULL : $data->Type_of_organization; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Owner = $data->Owner == null ? NULL : $data->Owner; 
	$Members = $data->Members == null ? NULL : $data->Members; 
	$Purpose = $data->Purpose == null ? NULL : $data->Purpose; 
	$Services = $data->Services == null ? NULL : $data->Services; 
	$Sub_organizations = $data->Sub_organizations == null ? NULL : $data->Sub_organizations; 
	$Super_organizations = $data->Super_organizations == null ? NULL : $data->Super_organizations; 
	$Sister_organizations = $data->Sister_organizations == null ? NULL : $data->Sister_organizations; 
	$Organization_structure = $data->Organization_structure == null ? NULL : $data->Organization_structure; 
	$Rival_organizations = $data->Rival_organizations == null ? NULL : $data->Rival_organizations; 
	$Address = $data->Address == null ? NULL : $data->Address; 
	$Offices = $data->Offices == null ? NULL : $data->Offices; 
	$Locations = $data->Locations == null ? NULL : $data->Locations; 
	$Headquarters = $data->Headquarters == null ? NULL : $data->Headquarters; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO organizations(Universe,Formation_year,Closure_year,Name,Description,Type_of_organization,Alternate_names,Tags,Owner,Members,Purpose,Services,Sub_organizations,Super_organizations,Sister_organizations,Organization_structure,Rival_organizations,Address,Offices,Locations,Headquarters,Notes,Private_Notes,user_id) 
VALUES($Universe,$Formation_year,$Closure_year,'$Name','$Description','$Type_of_organization','$Alternate_names','$Tags','$Owner','$Members','$Purpose','$Services','$Sub_organizations','$Super_organizations','$Sister_organizations','$Organization_structure','$Rival_organizations','$Address','$Offices','$Locations','$Headquarters','$Notes','$Private_Notes',$user_id)"; 


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

function deleteOrganization($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM organizations WHERE id = $id; ";

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

function updateOrganization($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Formation_year = $data->Formation_year == null ? "NULL" : $data->Formation_year; 
	$Closure_year = $data->Closure_year == null ? "NULL" : $data->Closure_year; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_organization = $data->Type_of_organization == null ? NULL : $data->Type_of_organization; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Owner = $data->Owner == null ? NULL : $data->Owner; 
	$Members = $data->Members == null ? NULL : $data->Members; 
	$Purpose = $data->Purpose == null ? NULL : $data->Purpose; 
	$Services = $data->Services == null ? NULL : $data->Services; 
	$Sub_organizations = $data->Sub_organizations == null ? NULL : $data->Sub_organizations; 
	$Super_organizations = $data->Super_organizations == null ? NULL : $data->Super_organizations; 
	$Sister_organizations = $data->Sister_organizations == null ? NULL : $data->Sister_organizations; 
	$Organization_structure = $data->Organization_structure == null ? NULL : $data->Organization_structure; 
	$Rival_organizations = $data->Rival_organizations == null ? NULL : $data->Rival_organizations; 
	$Address = $data->Address == null ? NULL : $data->Address; 
	$Offices = $data->Offices == null ? NULL : $data->Offices; 
	$Locations = $data->Locations == null ? NULL : $data->Locations; 
	$Headquarters = $data->Headquarters == null ? NULL : $data->Headquarters; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE organizations SET Universe = $Universe,Formation_year = $Formation_year,Closure_year = $Closure_year
,Name = '$Name',Description = '$Description',Type_of_organization = '$Type_of_organization',Alternate_names = '$Alternate_names',Tags = '$Tags',Owner = '$Owner',Members = '$Members',Purpose = '$Purpose',Services = '$Services',Sub_organizations = '$Sub_organizations',Super_organizations = '$Super_organizations',Sister_organizations = '$Sister_organizations',Organization_structure = '$Organization_structure',Rival_organizations = '$Rival_organizations',Address = '$Address',Offices = '$Offices',Locations = '$Locations',Headquarters = '$Headquarters',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllPlanets(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM planets Where user_id = '$user_id'";

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

function getPlanets(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM planets Where user_id = '$user_id' and id = '$id'";

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

function addPlanet($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Water_Content = $data->Water_Content == null ? "NULL" : $data->Water_Content; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Surface = $data->Surface == null ? "NULL" : $data->Surface; 
	$Temperature = $data->Temperature == null ? "NULL" : $data->Temperature; 
	$Length_Of_Night = $data->Length_Of_Night == null ? "NULL" : $data->Length_Of_Night; 
	$Length_Of_Day = $data->Length_Of_Day == null ? "NULL" : $data->Length_Of_Day; 
	$Population = $data->Population == null ? "NULL" : $data->Population; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Weather = $data->Weather == null ? NULL : $data->Weather; 
	$Natural_Resources = $data->Natural_Resources == null ? NULL : $data->Natural_Resources; 
	$Continents = $data->Continents == null ? NULL : $data->Continents; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Locations = $data->Locations == null ? NULL : $data->Locations; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Climate = $data->Climate == null ? NULL : $data->Climate; 
	$Atmosphere = $data->Atmosphere == null ? NULL : $data->Atmosphere; 
	$Seasons = $data->Seasons == null ? NULL : $data->Seasons; 
	$Natural_diasters = $data->Natural_diasters == null ? NULL : $data->Natural_diasters; 
	$Calendar_System = $data->Calendar_System == null ? NULL : $data->Calendar_System; 
	$Day_sky = $data->Day_sky == null ? NULL : $data->Day_sky; 
	$Night_sky = $data->Night_sky == null ? NULL : $data->Night_sky; 
	$Towns = $data->Towns == null ? NULL : $data->Towns; 
	$Races = $data->Races == null ? NULL : $data->Races; 
	$Flora = $data->Flora == null ? NULL : $data->Flora; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Religions = $data->Religions == null ? NULL : $data->Religions; 
	$Deities = $data->Deities == null ? NULL : $data->Deities; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Languages = $data->Languages == null ? NULL : $data->Languages; 
	$Visible_Constellations = $data->Visible_Constellations == null ? NULL : $data->Visible_Constellations; 
	$Suns = $data->Suns == null ? NULL : $data->Suns; 
	$Moons = $data->Moons == null ? NULL : $data->Moons; 
	$Orbit = $data->Orbit == null ? NULL : $data->Orbit; 
	$Nearby_planets = $data->Nearby_planets == null ? NULL : $data->Nearby_planets; 
	$First_Inhabitants_Story = $data->First_Inhabitants_Story == null ? NULL : $data->First_Inhabitants_Story; 
	$World_History = $data->World_History == null ? NULL : $data->World_History; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO planets(Universe,Water_Content,Size,Surface,Temperature,Length_Of_Night,Length_Of_Day,Population,Description,Tags,Name,Weather,Natural_Resources,Continents,Countries,Locations,Landmarks,Climate,Atmosphere,Seasons,Natural_diasters,Calendar_System,Day_sky,Night_sky,Towns,Races,Flora,Creatures,Religions,Deities,Groups,Languages,Visible_Constellations,Suns,Moons,Orbit,Nearby_planets,First_Inhabitants_Story,World_History,Private_Notes,Notes,user_id) 
VALUES($Universe,$Water_Content,$Size,$Surface,$Temperature,$Length_Of_Night,$Length_Of_Day,$Population,'$Description','$Tags','$Name','$Weather','$Natural_Resources','$Continents','$Countries','$Locations','$Landmarks','$Climate','$Atmosphere','$Seasons','$Natural_diasters','$Calendar_System','$Day_sky','$Night_sky','$Towns','$Races','$Flora','$Creatures','$Religions','$Deities','$Groups','$Languages','$Visible_Constellations','$Suns','$Moons','$Orbit','$Nearby_planets','$First_Inhabitants_Story','$World_History','$Private_Notes','$Notes',$user_id)"; 


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

function deletePlanet($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM planets WHERE id = $id; ";

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

function updatePlanet($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Water_Content = $data->Water_Content == null ? "NULL" : $data->Water_Content; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Surface = $data->Surface == null ? "NULL" : $data->Surface; 
	$Temperature = $data->Temperature == null ? "NULL" : $data->Temperature; 
	$Length_Of_Night = $data->Length_Of_Night == null ? "NULL" : $data->Length_Of_Night; 
	$Length_Of_Day = $data->Length_Of_Day == null ? "NULL" : $data->Length_Of_Day; 
	$Population = $data->Population == null ? "NULL" : $data->Population; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Weather = $data->Weather == null ? NULL : $data->Weather; 
	$Natural_Resources = $data->Natural_Resources == null ? NULL : $data->Natural_Resources; 
	$Continents = $data->Continents == null ? NULL : $data->Continents; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Locations = $data->Locations == null ? NULL : $data->Locations; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Climate = $data->Climate == null ? NULL : $data->Climate; 
	$Atmosphere = $data->Atmosphere == null ? NULL : $data->Atmosphere; 
	$Seasons = $data->Seasons == null ? NULL : $data->Seasons; 
	$Natural_diasters = $data->Natural_diasters == null ? NULL : $data->Natural_diasters; 
	$Calendar_System = $data->Calendar_System == null ? NULL : $data->Calendar_System; 
	$Day_sky = $data->Day_sky == null ? NULL : $data->Day_sky; 
	$Night_sky = $data->Night_sky == null ? NULL : $data->Night_sky; 
	$Towns = $data->Towns == null ? NULL : $data->Towns; 
	$Races = $data->Races == null ? NULL : $data->Races; 
	$Flora = $data->Flora == null ? NULL : $data->Flora; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Religions = $data->Religions == null ? NULL : $data->Religions; 
	$Deities = $data->Deities == null ? NULL : $data->Deities; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Languages = $data->Languages == null ? NULL : $data->Languages; 
	$Visible_Constellations = $data->Visible_Constellations == null ? NULL : $data->Visible_Constellations; 
	$Suns = $data->Suns == null ? NULL : $data->Suns; 
	$Moons = $data->Moons == null ? NULL : $data->Moons; 
	$Orbit = $data->Orbit == null ? NULL : $data->Orbit; 
	$Nearby_planets = $data->Nearby_planets == null ? NULL : $data->Nearby_planets; 
	$First_Inhabitants_Story = $data->First_Inhabitants_Story == null ? NULL : $data->First_Inhabitants_Story; 
	$World_History = $data->World_History == null ? NULL : $data->World_History; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE planets SET Universe = $Universe,Water_Content = $Water_Content,Size = $Size,Surface = $Surface,Temperature = $Temperature,Length_Of_Night = $Length_Of_Night,Length_Of_Day = $Length_Of_Day,Population = $Population
,Description = '$Description',Tags = '$Tags',Name = '$Name',Weather = '$Weather',Natural_Resources = '$Natural_Resources',Continents = '$Continents',Countries = '$Countries',Locations = '$Locations',Landmarks = '$Landmarks',Climate = '$Climate',Atmosphere = '$Atmosphere',Seasons = '$Seasons',Natural_diasters = '$Natural_diasters',Calendar_System = '$Calendar_System',Day_sky = '$Day_sky',Night_sky = '$Night_sky',Towns = '$Towns',Races = '$Races',Flora = '$Flora',Creatures = '$Creatures',Religions = '$Religions',Deities = '$Deities',Groups = '$Groups',Languages = '$Languages',Visible_Constellations = '$Visible_Constellations',Suns = '$Suns',Moons = '$Moons',Orbit = '$Orbit',Nearby_planets = '$Nearby_planets',First_Inhabitants_Story = '$First_Inhabitants_Story',World_History = '$World_History',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllRaces(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM races Where user_id = '$user_id'";

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

function getRaces(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM races Where user_id = '$user_id' and id = '$id'";

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

function addRace($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$General_weight = $data->General_weight == null ? "NULL" : $data->General_weight; 
	$Body_shape = $data->Body_shape == null ? "NULL" : $data->Body_shape; 
	$General_height = $data->General_height == null ? "NULL" : $data->General_height; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Notable_features = $data->Notable_features == null ? NULL : $data->Notable_features; 
	$Physical_variance = $data->Physical_variance == null ? NULL : $data->Physical_variance; 
	$Typical_clothing = $data->Typical_clothing == null ? NULL : $data->Typical_clothing; 
	$Skin_colors = $data->Skin_colors == null ? NULL : $data->Skin_colors; 
	$Weaknesses = $data->Weaknesses == null ? NULL : $data->Weaknesses; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Strengths = $data->Strengths == null ? NULL : $data->Strengths; 
	$Favorite_foods = $data->Favorite_foods == null ? NULL : $data->Favorite_foods; 
	$Famous_figures = $data->Famous_figures == null ? NULL : $data->Famous_figures; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Beliefs = $data->Beliefs == null ? NULL : $data->Beliefs; 
	$Governments = $data->Governments == null ? NULL : $data->Governments; 
	$Technologies = $data->Technologies == null ? NULL : $data->Technologies; 
	$Occupations = $data->Occupations == null ? NULL : $data->Occupations; 
	$Economics = $data->Economics == null ? NULL : $data->Economics; 
	$Notable_events = $data->Notable_events == null ? NULL : $data->Notable_events; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO races(Universe,General_weight,Body_shape,General_height,Other_Names,Tags,Description,Name,Notable_features,Physical_variance,Typical_clothing,Skin_colors,Weaknesses,Conditions,Strengths,Favorite_foods,Famous_figures,Traditions,Beliefs,Governments,Technologies,Occupations,Economics,Notable_events,Notes,Private_notes,user_id) 
VALUES($Universe,$General_weight,$Body_shape,$General_height,'$Other_Names','$Tags','$Description','$Name','$Notable_features','$Physical_variance','$Typical_clothing','$Skin_colors','$Weaknesses','$Conditions','$Strengths','$Favorite_foods','$Famous_figures','$Traditions','$Beliefs','$Governments','$Technologies','$Occupations','$Economics','$Notable_events','$Notes','$Private_notes',$user_id)"; 


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

function deleteRace($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM races WHERE id = $id; ";

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

function updateRace($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$General_weight = $data->General_weight == null ? "NULL" : $data->General_weight; 
	$Body_shape = $data->Body_shape == null ? "NULL" : $data->Body_shape; 
	$General_height = $data->General_height == null ? "NULL" : $data->General_height; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Notable_features = $data->Notable_features == null ? NULL : $data->Notable_features; 
	$Physical_variance = $data->Physical_variance == null ? NULL : $data->Physical_variance; 
	$Typical_clothing = $data->Typical_clothing == null ? NULL : $data->Typical_clothing; 
	$Skin_colors = $data->Skin_colors == null ? NULL : $data->Skin_colors; 
	$Weaknesses = $data->Weaknesses == null ? NULL : $data->Weaknesses; 
	$Conditions = $data->Conditions == null ? NULL : $data->Conditions; 
	$Strengths = $data->Strengths == null ? NULL : $data->Strengths; 
	$Favorite_foods = $data->Favorite_foods == null ? NULL : $data->Favorite_foods; 
	$Famous_figures = $data->Famous_figures == null ? NULL : $data->Famous_figures; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Beliefs = $data->Beliefs == null ? NULL : $data->Beliefs; 
	$Governments = $data->Governments == null ? NULL : $data->Governments; 
	$Technologies = $data->Technologies == null ? NULL : $data->Technologies; 
	$Occupations = $data->Occupations == null ? NULL : $data->Occupations; 
	$Economics = $data->Economics == null ? NULL : $data->Economics; 
	$Notable_events = $data->Notable_events == null ? NULL : $data->Notable_events; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE races SET Universe = $Universe,General_weight = $General_weight,Body_shape = $Body_shape,General_height = $General_height
,Other_Names = '$Other_Names',Tags = '$Tags',Description = '$Description',Name = '$Name',Notable_features = '$Notable_features',Physical_variance = '$Physical_variance',Typical_clothing = '$Typical_clothing',Skin_colors = '$Skin_colors',Weaknesses = '$Weaknesses',Conditions = '$Conditions',Strengths = '$Strengths',Favorite_foods = '$Favorite_foods',Famous_figures = '$Famous_figures',Traditions = '$Traditions',Beliefs = '$Beliefs',Governments = '$Governments',Technologies = '$Technologies',Occupations = '$Occupations',Economics = '$Economics',Notable_events = '$Notable_events',Notes = '$Notes',Private_notes = '$Private_notes'    WHERE id = $id"; 

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

function getAllReligions(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM religions Where user_id = '$user_id'";

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

function getReligions(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM religions Where user_id = '$user_id' and id = '$id'";

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

function addReligion($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Notable_figures = $data->Notable_figures == null ? NULL : $data->Notable_figures; 
	$Origin_story = $data->Origin_story == null ? NULL : $data->Origin_story; 
	$Artifacts = $data->Artifacts == null ? NULL : $data->Artifacts; 
	$Places_of_worship = $data->Places_of_worship == null ? NULL : $data->Places_of_worship; 
	$Vision_of_paradise = $data->Vision_of_paradise == null ? NULL : $data->Vision_of_paradise; 
	$Obligations = $data->Obligations == null ? NULL : $data->Obligations; 
	$Worship_services = $data->Worship_services == null ? NULL : $data->Worship_services; 
	$Prophecies = $data->Prophecies == null ? NULL : $data->Prophecies; 
	$Teachings = $data->Teachings == null ? NULL : $data->Teachings; 
	$Deities = $data->Deities == null ? NULL : $data->Deities; 
	$Initiation_process = $data->Initiation_process == null ? NULL : $data->Initiation_process; 
	$Rituals = $data->Rituals == null ? NULL : $data->Rituals; 
	$Holidays = $data->Holidays == null ? NULL : $data->Holidays; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Practicing_locations = $data->Practicing_locations == null ? NULL : $data->Practicing_locations; 
	$Practicing_races = $data->Practicing_races == null ? NULL : $data->Practicing_races; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO religions(Universe,Tags,Name,Description,Other_Names,Notable_figures,Origin_story,Artifacts,Places_of_worship,Vision_of_paradise,Obligations,Worship_services,Prophecies,Teachings,Deities,Initiation_process,Rituals,Holidays,Traditions,Practicing_locations,Practicing_races,Private_notes,Notes,user_id) 
VALUES($Universe,'$Tags','$Name','$Description','$Other_Names','$Notable_figures','$Origin_story','$Artifacts','$Places_of_worship','$Vision_of_paradise','$Obligations','$Worship_services','$Prophecies','$Teachings','$Deities','$Initiation_process','$Rituals','$Holidays','$Traditions','$Practicing_locations','$Practicing_races','$Private_notes','$Notes',$user_id)"; 


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

function deleteReligion($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM religions WHERE id = $id; ";

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

function updateReligion($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Notable_figures = $data->Notable_figures == null ? NULL : $data->Notable_figures; 
	$Origin_story = $data->Origin_story == null ? NULL : $data->Origin_story; 
	$Artifacts = $data->Artifacts == null ? NULL : $data->Artifacts; 
	$Places_of_worship = $data->Places_of_worship == null ? NULL : $data->Places_of_worship; 
	$Vision_of_paradise = $data->Vision_of_paradise == null ? NULL : $data->Vision_of_paradise; 
	$Obligations = $data->Obligations == null ? NULL : $data->Obligations; 
	$Worship_services = $data->Worship_services == null ? NULL : $data->Worship_services; 
	$Prophecies = $data->Prophecies == null ? NULL : $data->Prophecies; 
	$Teachings = $data->Teachings == null ? NULL : $data->Teachings; 
	$Deities = $data->Deities == null ? NULL : $data->Deities; 
	$Initiation_process = $data->Initiation_process == null ? NULL : $data->Initiation_process; 
	$Rituals = $data->Rituals == null ? NULL : $data->Rituals; 
	$Holidays = $data->Holidays == null ? NULL : $data->Holidays; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Practicing_locations = $data->Practicing_locations == null ? NULL : $data->Practicing_locations; 
	$Practicing_races = $data->Practicing_races == null ? NULL : $data->Practicing_races; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE religions SET Universe = $Universe
,Tags = '$Tags',Name = '$Name',Description = '$Description',Other_Names = '$Other_Names',Notable_figures = '$Notable_figures',Origin_story = '$Origin_story',Artifacts = '$Artifacts',Places_of_worship = '$Places_of_worship',Vision_of_paradise = '$Vision_of_paradise',Obligations = '$Obligations',Worship_services = '$Worship_services',Prophecies = '$Prophecies',Teachings = '$Teachings',Deities = '$Deities',Initiation_process = '$Initiation_process',Rituals = '$Rituals',Holidays = '$Holidays',Traditions = '$Traditions',Practicing_locations = '$Practicing_locations',Practicing_races = '$Practicing_races',Private_notes = '$Private_notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllScenes(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM scenes Where user_id = '$user_id'";

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

function getScenes(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM scenes Where user_id = '$user_id' and id = '$id'";

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

function addScene($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Summary = $data->Summary == null ? NULL : $data->Summary; 
	$Items_in_scene = $data->Items_in_scene == null ? NULL : $data->Items_in_scene; 
	$Locations_in_scene = $data->Locations_in_scene == null ? NULL : $data->Locations_in_scene; 
	$Characters_in_scene = $data->Characters_in_scene == null ? NULL : $data->Characters_in_scene; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Results = $data->Results == null ? NULL : $data->Results; 
	$What_caused_this = $data->What_caused_this == null ? NULL : $data->What_caused_this; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO scenes(Universe,Tags,Name,Summary,Items_in_scene,Locations_in_scene,Characters_in_scene,Description,Results,What_caused_this,Notes,Private_notes,user_id) 
VALUES($Universe,'$Tags','$Name','$Summary','$Items_in_scene','$Locations_in_scene','$Characters_in_scene','$Description','$Results','$What_caused_this','$Notes','$Private_notes',$user_id)"; 


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

function deleteScene($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM scenes WHERE id = $id; ";

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

function updateScene($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Summary = $data->Summary == null ? NULL : $data->Summary; 
	$Items_in_scene = $data->Items_in_scene == null ? NULL : $data->Items_in_scene; 
	$Locations_in_scene = $data->Locations_in_scene == null ? NULL : $data->Locations_in_scene; 
	$Characters_in_scene = $data->Characters_in_scene == null ? NULL : $data->Characters_in_scene; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Results = $data->Results == null ? NULL : $data->Results; 
	$What_caused_this = $data->What_caused_this == null ? NULL : $data->What_caused_this; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE scenes SET Universe = $Universe
,Tags = '$Tags',Name = '$Name',Summary = '$Summary',Items_in_scene = '$Items_in_scene',Locations_in_scene = '$Locations_in_scene',Characters_in_scene = '$Characters_in_scene',Description = '$Description',Results = '$Results',What_caused_this = '$What_caused_this',Notes = '$Notes',Private_notes = '$Private_notes'    WHERE id = $id"; 

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

function getAllSports(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM sports Where user_id = '$user_id'";

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

function getSports(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM sports Where user_id = '$user_id' and id = '$id'";

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

function addSport($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Number_of_players = $data->Number_of_players == null ? "NULL" : $data->Number_of_players; 
	$Positions = $data->Positions == null ? "NULL" : $data->Positions; 
	$Game_time = $data->Game_time == null ? "NULL" : $data->Game_time; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Nicknames = $data->Nicknames == null ? NULL : $data->Nicknames; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$How_to_win = $data->How_to_win == null ? NULL : $data->How_to_win; 
	$Penalties = $data->Penalties == null ? NULL : $data->Penalties; 
	$Scoring = $data->Scoring == null ? NULL : $data->Scoring; 
	$Equipment = $data->Equipment == null ? NULL : $data->Equipment; 
	$Play_area = $data->Play_area == null ? NULL : $data->Play_area; 
	$Most_important_muscles = $data->Most_important_muscles == null ? NULL : $data->Most_important_muscles; 
	$Common_injuries = $data->Common_injuries == null ? NULL : $data->Common_injuries; 
	$Strategies = $data->Strategies == null ? NULL : $data->Strategies; 
	$Rules = $data->Rules == null ? NULL : $data->Rules; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Teams = $data->Teams == null ? NULL : $data->Teams; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Players = $data->Players == null ? NULL : $data->Players; 
	$Popularity = $data->Popularity == null ? NULL : $data->Popularity; 
	$Merchandise = $data->Merchandise == null ? NULL : $data->Merchandise; 
	$Uniforms = $data->Uniforms == null ? NULL : $data->Uniforms; 
	$Famous_games = $data->Famous_games == null ? NULL : $data->Famous_games; 
	$Evolution = $data->Evolution == null ? NULL : $data->Evolution; 
	$Creators = $data->Creators == null ? NULL : $data->Creators; 
	$Origin_story = $data->Origin_story == null ? NULL : $data->Origin_story; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO sports(Universe,Number_of_players,Positions,Game_time,Tags,Nicknames,Description,Name,How_to_win,Penalties,Scoring,Equipment,Play_area,Most_important_muscles,Common_injuries,Strategies,Rules,Traditions,Teams,Countries,Players,Popularity,Merchandise,Uniforms,Famous_games,Evolution,Creators,Origin_story,Private_Notes,Notes,user_id) 
VALUES($Universe,$Number_of_players,$Positions,$Game_time,'$Tags','$Nicknames','$Description','$Name','$How_to_win','$Penalties','$Scoring','$Equipment','$Play_area','$Most_important_muscles','$Common_injuries','$Strategies','$Rules','$Traditions','$Teams','$Countries','$Players','$Popularity','$Merchandise','$Uniforms','$Famous_games','$Evolution','$Creators','$Origin_story','$Private_Notes','$Notes',$user_id)"; 


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

function deleteSport($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM sports WHERE id = $id; ";

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

function updateSport($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Number_of_players = $data->Number_of_players == null ? "NULL" : $data->Number_of_players; 
	$Positions = $data->Positions == null ? "NULL" : $data->Positions; 
	$Game_time = $data->Game_time == null ? "NULL" : $data->Game_time; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Nicknames = $data->Nicknames == null ? NULL : $data->Nicknames; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$How_to_win = $data->How_to_win == null ? NULL : $data->How_to_win; 
	$Penalties = $data->Penalties == null ? NULL : $data->Penalties; 
	$Scoring = $data->Scoring == null ? NULL : $data->Scoring; 
	$Equipment = $data->Equipment == null ? NULL : $data->Equipment; 
	$Play_area = $data->Play_area == null ? NULL : $data->Play_area; 
	$Most_important_muscles = $data->Most_important_muscles == null ? NULL : $data->Most_important_muscles; 
	$Common_injuries = $data->Common_injuries == null ? NULL : $data->Common_injuries; 
	$Strategies = $data->Strategies == null ? NULL : $data->Strategies; 
	$Rules = $data->Rules == null ? NULL : $data->Rules; 
	$Traditions = $data->Traditions == null ? NULL : $data->Traditions; 
	$Teams = $data->Teams == null ? NULL : $data->Teams; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Players = $data->Players == null ? NULL : $data->Players; 
	$Popularity = $data->Popularity == null ? NULL : $data->Popularity; 
	$Merchandise = $data->Merchandise == null ? NULL : $data->Merchandise; 
	$Uniforms = $data->Uniforms == null ? NULL : $data->Uniforms; 
	$Famous_games = $data->Famous_games == null ? NULL : $data->Famous_games; 
	$Evolution = $data->Evolution == null ? NULL : $data->Evolution; 
	$Creators = $data->Creators == null ? NULL : $data->Creators; 
	$Origin_story = $data->Origin_story == null ? NULL : $data->Origin_story; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE sports SET Universe = $Universe,Number_of_players = $Number_of_players,Positions = $Positions,Game_time = $Game_time
,Tags = '$Tags',Nicknames = '$Nicknames',Description = '$Description',Name = '$Name',How_to_win = '$How_to_win',Penalties = '$Penalties',Scoring = '$Scoring',Equipment = '$Equipment',Play_area = '$Play_area',Most_important_muscles = '$Most_important_muscles',Common_injuries = '$Common_injuries',Strategies = '$Strategies',Rules = '$Rules',Traditions = '$Traditions',Teams = '$Teams',Countries = '$Countries',Players = '$Players',Popularity = '$Popularity',Merchandise = '$Merchandise',Uniforms = '$Uniforms',Famous_games = '$Famous_games',Evolution = '$Evolution',Creators = '$Creators',Origin_story = '$Origin_story',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllTechnologies(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM technologies Where user_id = '$user_id'";

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

function getTechnologies(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM technologies Where user_id = '$user_id' and id = '$id'";

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

function addTechnologie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Cost = $data->Cost == null ? "NULL" : $data->Cost; 
	$Weight = $data->Weight == null ? "NULL" : $data->Weight; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Sales_Process = $data->Sales_Process == null ? NULL : $data->Sales_Process; 
	$Materials = $data->Materials == null ? NULL : $data->Materials; 
	$Manufacturing_Process = $data->Manufacturing_Process == null ? NULL : $data->Manufacturing_Process; 
	$Planets = $data->Planets == null ? NULL : $data->Planets; 
	$Rarity = $data->Rarity == null ? NULL : $data->Rarity; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Towns = $data->Towns == null ? NULL : $data->Towns; 
	$Characters = $data->Characters == null ? NULL : $data->Characters; 
	$Magic_effects = $data->Magic_effects == null ? NULL : $data->Magic_effects; 
	$Resources_Used = $data->Resources_Used == null ? NULL : $data->Resources_Used; 
	$How_It_Works = $data->How_It_Works == null ? NULL : $data->How_It_Works; 
	$Purpose = $data->Purpose == null ? NULL : $data->Purpose; 
	$Physical_Description = $data->Physical_Description == null ? NULL : $data->Physical_Description; 
	$Colors = $data->Colors == null ? NULL : $data->Colors; 
	$Related_technologies = $data->Related_technologies == null ? NULL : $data->Related_technologies; 
	$Parent_technologies = $data->Parent_technologies == null ? NULL : $data->Parent_technologies; 
	$Child_technologies = $data->Child_technologies == null ? NULL : $data->Child_technologies; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO technologies(Universe,Cost,Weight,Size,Tags,Name,Description,Other_Names,Sales_Process,Materials,Manufacturing_Process,Planets,Rarity,Creatures,`Groups`,Countries,Towns,Characters,Magic_effects,Resources_Used,How_It_Works,Purpose,Physical_Description,Colors,Related_technologies,Parent_technologies,Child_technologies,Notes,Private_Notes,user_id) 
VALUES($Universe,$Cost,$Weight,$Size,'$Tags','$Name','$Description','$Other_Names','$Sales_Process','$Materials','$Manufacturing_Process','$Planets','$Rarity','$Creatures','$Groups','$Countries','$Towns','$Characters','$Magic_effects','$Resources_Used','$How_It_Works','$Purpose','$Physical_Description','$Colors','$Related_technologies','$Parent_technologies','$Child_technologies','$Notes','$Private_Notes',$user_id)"; 


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

function deleteTechnologie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM technologies WHERE id = $id; ";

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

function updateTechnologie($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Cost = $data->Cost == null ? "NULL" : $data->Cost; 
	$Weight = $data->Weight == null ? "NULL" : $data->Weight; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_Names = $data->Other_Names == null ? NULL : $data->Other_Names; 
	$Sales_Process = $data->Sales_Process == null ? NULL : $data->Sales_Process; 
	$Materials = $data->Materials == null ? NULL : $data->Materials; 
	$Manufacturing_Process = $data->Manufacturing_Process == null ? NULL : $data->Manufacturing_Process; 
	$Planets = $data->Planets == null ? NULL : $data->Planets; 
	$Rarity = $data->Rarity == null ? NULL : $data->Rarity; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Towns = $data->Towns == null ? NULL : $data->Towns; 
	$Characters = $data->Characters == null ? NULL : $data->Characters; 
	$Magic_effects = $data->Magic_effects == null ? NULL : $data->Magic_effects; 
	$Resources_Used = $data->Resources_Used == null ? NULL : $data->Resources_Used; 
	$How_It_Works = $data->How_It_Works == null ? NULL : $data->How_It_Works; 
	$Purpose = $data->Purpose == null ? NULL : $data->Purpose; 
	$Physical_Description = $data->Physical_Description == null ? NULL : $data->Physical_Description; 
	$Colors = $data->Colors == null ? NULL : $data->Colors; 
	$Related_technologies = $data->Related_technologies == null ? NULL : $data->Related_technologies; 
	$Parent_technologies = $data->Parent_technologies == null ? NULL : $data->Parent_technologies; 
	$Child_technologies = $data->Child_technologies == null ? NULL : $data->Child_technologies; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE technologies SET Universe = $Universe,Cost = $Cost,Weight = $Weight,Size = $Size
,Tags = '$Tags',Name = '$Name',Description = '$Description',Other_Names = '$Other_Names',Sales_Process = '$Sales_Process',Materials = '$Materials',Manufacturing_Process = '$Manufacturing_Process',Planets = '$Planets',Rarity = '$Rarity',Creatures = '$Creatures',Groups = '$Groups',Countries = '$Countries',Towns = '$Towns',Characters = '$Characters',Magic_effects = '$Magic_effects',Resources_Used = '$Resources_Used',How_It_Works = '$How_It_Works',Purpose = '$Purpose',Physical_Description = '$Physical_Description',Colors = '$Colors',Related_technologies = '$Related_technologies',Parent_technologies = '$Parent_technologies',Child_technologies = '$Child_technologies',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllTimelines(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM timelines Where user_id = '$user_id'";

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

function getTimelines(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM timelines Where user_id = '$user_id' and id = '$id'";

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

function addTimeline($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe_id = $data->Universe_id == null ? "NULL" : $data->Universe_id; 
	$User_id = $data->User_id == null ? "NULL" : $data->User_id; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Page_type = $data->Page_type == null ? NULL : $data->Page_type; 
	$Deleted_at = $data->Deleted_at == null ? NULL : $data->Deleted_at; 
	$Archived_at = $data->Archived_at == null ? NULL : $data->Archived_at; 
	$Created_at = $data->Created_at == null ? NULL : $data->Created_at; 
	$Updated_at = $data->Updated_at == null ? NULL : $data->Updated_at; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Subtitle = $data->Subtitle == null ? NULL : $data->Subtitle; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 


    $sql = "INSERT INTO timelines(Universe_id,User_id,Name,Page_type,Deleted_at,Archived_at,Created_at,Updated_at,Description,Subtitle,Notes,Private_notes) 
VALUES($Universe_id,$User_id,'$Name','$Page_type','$Deleted_at','$Archived_at','$Created_at','$Updated_at','$Description','$Subtitle','$Notes','$Private_notes')"; 


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

function deleteTimeline($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM timelines WHERE id = $id; ";

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

function updateTimeline($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe_id = $data->Universe_id == null ? "NULL" : $data->Universe_id; 
	$User_id = $data->User_id == null ? "NULL" : $data->User_id; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Page_type = $data->Page_type == null ? NULL : $data->Page_type; 
	$Deleted_at = $data->Deleted_at == null ? NULL : $data->Deleted_at; 
	$Archived_at = $data->Archived_at == null ? NULL : $data->Archived_at; 
	$Created_at = $data->Created_at == null ? NULL : $data->Created_at; 
	$Updated_at = $data->Updated_at == null ? NULL : $data->Updated_at; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Subtitle = $data->Subtitle == null ? NULL : $data->Subtitle; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_notes = $data->Private_notes == null ? NULL : $data->Private_notes; 


    $sql = "UPDATE timelines SET Universe_id = $Universe_id,User_id = $User_id
,Name = '$Name',Page_type = '$Page_type',Deleted_at = '$Deleted_at',Archived_at = '$Archived_at',Created_at = '$Created_at',Updated_at = '$Updated_at',Description = '$Description',Subtitle = '$Subtitle',Notes = '$Notes',Private_notes = '$Private_notes'    WHERE id = $id"; 

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

function getAllTowns(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM towns Where user_id = '$user_id'";

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

function getTowns(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM towns Where user_id = '$user_id' and id = '$id'";

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

function addTown($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Country = $data->Country == null ? "NULL" : $data->Country; 
	$Citizens = $data->Citizens == null ? "NULL" : $data->Citizens; 
	$Buildings = $data->Buildings == null ? "NULL" : $data->Buildings; 
	$Neighborhoods = $data->Neighborhoods == null ? "NULL" : $data->Neighborhoods; 
	$Established_year = $data->Established_year == null ? "NULL" : $data->Established_year; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_names = $data->Other_names == null ? NULL : $data->Other_names; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Busy_areas = $data->Busy_areas == null ? NULL : $data->Busy_areas; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Laws = $data->Laws == null ? NULL : $data->Laws; 
	$Languages = $data->Languages == null ? NULL : $data->Languages; 
	$Flora = $data->Flora == null ? NULL : $data->Flora; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Politics = $data->Politics == null ? NULL : $data->Politics; 
	$Sports = $data->Sports == null ? NULL : $data->Sports; 
	$Founding_story = $data->Founding_story == null ? NULL : $data->Founding_story; 
	$Food_sources = $data->Food_sources == null ? NULL : $data->Food_sources; 
	$Waste = $data->Waste == null ? NULL : $data->Waste; 
	$Energy_sources = $data->Energy_sources == null ? NULL : $data->Energy_sources; 
	$Recycling = $data->Recycling == null ? NULL : $data->Recycling; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO towns(Universe,Country,Citizens,Buildings,Neighborhoods,Established_year,Tags,Name,Description,Other_names,Groups,Busy_areas,Landmarks,Laws,Languages,Flora,Creatures,Politics,Sports,Founding_story,Food_sources,Waste,Energy_sources,Recycling,Notes,Private_Notes,user_id) 
VALUES($Universe,$Country,$Citizens,$Buildings,$Neighborhoods,$Established_year,'$Tags','$Name','$Description','$Other_names','$Groups','$Busy_areas','$Landmarks','$Laws','$Languages','$Flora','$Creatures','$Politics','$Sports','$Founding_story','$Food_sources','$Waste','$Energy_sources','$Recycling','$Notes','$Private_Notes',$user_id)"; 


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

function deleteTown($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM towns WHERE id = $id; ";

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

function updateTown($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Country = $data->Country == null ? "NULL" : $data->Country; 
	$Citizens = $data->Citizens == null ? "NULL" : $data->Citizens; 
	$Buildings = $data->Buildings == null ? "NULL" : $data->Buildings; 
	$Neighborhoods = $data->Neighborhoods == null ? "NULL" : $data->Neighborhoods; 
	$Established_year = $data->Established_year == null ? "NULL" : $data->Established_year; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Other_names = $data->Other_names == null ? NULL : $data->Other_names; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Busy_areas = $data->Busy_areas == null ? NULL : $data->Busy_areas; 
	$Landmarks = $data->Landmarks == null ? NULL : $data->Landmarks; 
	$Laws = $data->Laws == null ? NULL : $data->Laws; 
	$Languages = $data->Languages == null ? NULL : $data->Languages; 
	$Flora = $data->Flora == null ? NULL : $data->Flora; 
	$Creatures = $data->Creatures == null ? NULL : $data->Creatures; 
	$Politics = $data->Politics == null ? NULL : $data->Politics; 
	$Sports = $data->Sports == null ? NULL : $data->Sports; 
	$Founding_story = $data->Founding_story == null ? NULL : $data->Founding_story; 
	$Food_sources = $data->Food_sources == null ? NULL : $data->Food_sources; 
	$Waste = $data->Waste == null ? NULL : $data->Waste; 
	$Energy_sources = $data->Energy_sources == null ? NULL : $data->Energy_sources; 
	$Recycling = $data->Recycling == null ? NULL : $data->Recycling; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE towns SET Universe = $Universe,Country = $Country,Citizens = $Citizens,Buildings = $Buildings,Neighborhoods = $Neighborhoods,Established_year = $Established_year
,Tags = '$Tags',Name = '$Name',Description = '$Description',Other_names = '$Other_names',Groups = '$Groups',Busy_areas = '$Busy_areas',Landmarks = '$Landmarks',Laws = '$Laws',Languages = '$Languages',Flora = '$Flora',Creatures = '$Creatures',Politics = '$Politics',Sports = '$Sports',Founding_story = '$Founding_story',Food_sources = '$Food_sources',Waste = '$Waste',Energy_sources = '$Energy_sources',Recycling = '$Recycling',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllTraditions(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM traditions Where user_id = '$user_id'";

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

function getTraditions(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM traditions Where user_id = '$user_id' and id = '$id'";

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

function addTradition($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_tradition = $data->Type_of_tradition == null ? NULL : $data->Type_of_tradition; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Dates = $data->Dates == null ? NULL : $data->Dates; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Towns = $data->Towns == null ? NULL : $data->Towns; 
	$Gifts = $data->Gifts == null ? NULL : $data->Gifts; 
	$Food = $data->Food == null ? NULL : $data->Food; 
	$Symbolism = $data->Symbolism == null ? NULL : $data->Symbolism; 
	$Games = $data->Games == null ? NULL : $data->Games; 
	$Activities = $data->Activities == null ? NULL : $data->Activities; 
	$Etymology = $data->Etymology == null ? NULL : $data->Etymology; 
	$Origin = $data->Origin == null ? NULL : $data->Origin; 
	$Significance = $data->Significance == null ? NULL : $data->Significance; 
	$Religions = $data->Religions == null ? NULL : $data->Religions; 
	$Notable_events = $data->Notable_events == null ? NULL : $data->Notable_events; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO traditions(Universe,Alternate_names,Tags,Name,Description,Type_of_tradition,Countries,Dates,Groups,Towns,Gifts,Food,Symbolism,Games,Activities,Etymology,Origin,Significance,Religions,Notable_events,Notes,Private_Notes,user_id) 
VALUES($Universe,'$Alternate_names','$Tags','$Name','$Description','$Type_of_tradition','$Countries','$Dates','$Groups','$Towns','$Gifts','$Food','$Symbolism','$Games','$Activities','$Etymology','$Origin','$Significance','$Religions','$Notable_events','$Notes','$Private_Notes',$user_id)"; 


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

function deleteTradition($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM traditions WHERE id = $id; ";

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

function updateTradition($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Type_of_tradition = $data->Type_of_tradition == null ? NULL : $data->Type_of_tradition; 
	$Countries = $data->Countries == null ? NULL : $data->Countries; 
	$Dates = $data->Dates == null ? NULL : $data->Dates; 
	$Groups = $data->Groups == null ? NULL : $data->Groups; 
	$Towns = $data->Towns == null ? NULL : $data->Towns; 
	$Gifts = $data->Gifts == null ? NULL : $data->Gifts; 
	$Food = $data->Food == null ? NULL : $data->Food; 
	$Symbolism = $data->Symbolism == null ? NULL : $data->Symbolism; 
	$Games = $data->Games == null ? NULL : $data->Games; 
	$Activities = $data->Activities == null ? NULL : $data->Activities; 
	$Etymology = $data->Etymology == null ? NULL : $data->Etymology; 
	$Origin = $data->Origin == null ? NULL : $data->Origin; 
	$Significance = $data->Significance == null ? NULL : $data->Significance; 
	$Religions = $data->Religions == null ? NULL : $data->Religions; 
	$Notable_events = $data->Notable_events == null ? NULL : $data->Notable_events; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE traditions SET Universe = $Universe
,Alternate_names = '$Alternate_names',Tags = '$Tags',Name = '$Name',Description = '$Description',Type_of_tradition = '$Type_of_tradition',Countries = '$Countries',Dates = '$Dates',Groups = '$Groups',Towns = '$Towns',Gifts = '$Gifts',Food = '$Food',Symbolism = '$Symbolism',Games = '$Games',Activities = '$Activities',Etymology = '$Etymology',Origin = '$Origin',Significance = '$Significance',Religions = '$Religions',Notable_events = '$Notable_events',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllUniverses(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM universes Where user_id = '$user_id'";

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

function getUniverses(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM universes Where user_id = '$user_id' and id = '$id'";

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

function addUniverse($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$privacy = $data->privacy == null ? "NULL" : $data->privacy; 
	$favorite = $data->favorite == null ? "NULL" : $data->favorite; 
	$name = $data->name == null ? NULL : $data->name; 
	$description = $data->description == null ? NULL : $data->description; 
	$history = $data->history == null ? NULL : $data->history; 
	$notes = $data->notes == null ? NULL : $data->notes; 
	$private_notes = $data->private_notes == null ? NULL : $data->private_notes; 
	$laws_of_physics = $data->laws_of_physics == null ? NULL : $data->laws_of_physics; 
	$magic_system = $data->magic_system == null ? NULL : $data->magic_system; 
	$technology = $data->technology == null ? NULL : $data->technology; 
	$genre = $data->genre == null ? NULL : $data->genre; 
	$page_type = $data->page_type == null ? NULL : $data->page_type; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO universes(privacy,favorite,name,description,history,notes,private_notes,laws_of_physics,magic_system,technology,genre,page_type,user_id) 
VALUES($privacy,$favorite,'$name','$description','$history','$notes','$private_notes','$laws_of_physics','$magic_system','$technology','$genre','$page_type',$user_id)"; 


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

function deleteUniverse($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM universes WHERE id = $id; ";

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

function updateUniverse($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$privacy = $data->privacy == null ? "NULL" : $data->privacy; 
	$favorite = $data->favorite == null ? "NULL" : $data->favorite; 
	$name = $data->name == null ? NULL : $data->name; 
	$description = $data->description == null ? NULL : $data->description; 
	$history = $data->history == null ? NULL : $data->history; 
	$notes = $data->notes == null ? NULL : $data->notes; 
	$private_notes = $data->private_notes == null ? NULL : $data->private_notes; 
	$laws_of_physics = $data->laws_of_physics == null ? NULL : $data->laws_of_physics; 
	$magic_system = $data->magic_system == null ? NULL : $data->magic_system; 
	$technology = $data->technology == null ? NULL : $data->technology; 
	$genre = $data->genre == null ? NULL : $data->genre; 
	$page_type = $data->page_type == null ? NULL : $data->page_type; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE universes SET privacy = $privacy,favorite = $favorite
,name = '$name',description = '$description',history = '$history',notes = '$notes',private_notes = '$private_notes',laws_of_physics = '$laws_of_physics',magic_system = '$magic_system',technology = '$technology',genre = '$genre',page_type = '$page_type'    WHERE id = $id"; 

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

function getAllVehicles(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM vehicles Where user_id = '$user_id'";

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

function getVehicles(){
    $user_id = $_GET['user_id'];
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM vehicles Where user_id = '$user_id' and id = '$id'";

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

function addVehicle($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Dimensions = $data->Dimensions == null ? "NULL" : $data->Dimensions; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Doors = $data->Doors == null ? "NULL" : $data->Doors; 
	$Windows = $data->Windows == null ? "NULL" : $data->Windows; 
	$Fuel = $data->Fuel == null ? "NULL" : $data->Fuel; 
	$Speed = $data->Speed == null ? "NULL" : $data->Speed; 
	$Costs = $data->Costs == null ? "NULL" : $data->Costs; 
	$Weight = $data->Weight == null ? "NULL" : $data->Weight; 
	$Country = $data->Country == null ? "NULL" : $data->Country; 
	$Type_of_vehicle = $data->Type_of_vehicle == null ? NULL : $data->Type_of_vehicle; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Materials = $data->Materials == null ? NULL : $data->Materials; 
	$Designer = $data->Designer == null ? NULL : $data->Designer; 
	$Colors = $data->Colors == null ? NULL : $data->Colors; 
	$Distance = $data->Distance == null ? NULL : $data->Distance; 
	$Features = $data->Features == null ? NULL : $data->Features; 
	$Safety = $data->Safety == null ? NULL : $data->Safety; 
	$Variants = $data->Variants == null ? NULL : $data->Variants; 
	$Manufacturer = $data->Manufacturer == null ? NULL : $data->Manufacturer; 
	$Owner = $data->Owner == null ? NULL : $data->Owner; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "INSERT INTO vehicles(Universe,Dimensions,Size,Doors,Windows,Fuel,Speed,Costs,Weight,Country,Type_of_vehicle,Alternate_names,Tags,Name,Description,Materials,Designer,Colors,Distance,Features,Safety,Variants,Manufacturer,Owner,Notes,Private_Notes,user_id) 
VALUES($Universe,$Dimensions,$Size,$Doors,$Windows,$Fuel,$Speed,$Costs,$Weight,$Country,'$Type_of_vehicle','$Alternate_names','$Tags','$Name','$Description','$Materials','$Designer','$Colors','$Distance','$Features','$Safety','$Variants','$Manufacturer','$Owner','$Notes','$Private_Notes',$user_id)"; 


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

function deleteVehicle($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM vehicles WHERE id = $id; ";

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

function updateVehicle($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Universe = $data->Universe == null ? "NULL" : $data->Universe; 
	$Dimensions = $data->Dimensions == null ? "NULL" : $data->Dimensions; 
	$Size = $data->Size == null ? "NULL" : $data->Size; 
	$Doors = $data->Doors == null ? "NULL" : $data->Doors; 
	$Windows = $data->Windows == null ? "NULL" : $data->Windows; 
	$Fuel = $data->Fuel == null ? "NULL" : $data->Fuel; 
	$Speed = $data->Speed == null ? "NULL" : $data->Speed; 
	$Costs = $data->Costs == null ? "NULL" : $data->Costs; 
	$Weight = $data->Weight == null ? "NULL" : $data->Weight; 
	$Country = $data->Country == null ? "NULL" : $data->Country; 
	$Type_of_vehicle = $data->Type_of_vehicle == null ? NULL : $data->Type_of_vehicle; 
	$Alternate_names = $data->Alternate_names == null ? NULL : $data->Alternate_names; 
	$Tags = $data->Tags == null ? NULL : $data->Tags; 
	$Name = $data->Name == null ? NULL : $data->Name; 
	$Description = $data->Description == null ? NULL : $data->Description; 
	$Materials = $data->Materials == null ? NULL : $data->Materials; 
	$Designer = $data->Designer == null ? NULL : $data->Designer; 
	$Colors = $data->Colors == null ? NULL : $data->Colors; 
	$Distance = $data->Distance == null ? NULL : $data->Distance; 
	$Features = $data->Features == null ? NULL : $data->Features; 
	$Safety = $data->Safety == null ? NULL : $data->Safety; 
	$Variants = $data->Variants == null ? NULL : $data->Variants; 
	$Manufacturer = $data->Manufacturer == null ? NULL : $data->Manufacturer; 
	$Owner = $data->Owner == null ? NULL : $data->Owner; 
	$Notes = $data->Notes == null ? NULL : $data->Notes; 
	$Private_Notes = $data->Private_Notes == null ? NULL : $data->Private_Notes; 
	$user_id = $data->user_id == null ? "NULL" : $data->user_id; 


    $sql = "UPDATE vehicles SET Universe = $Universe,Dimensions = $Dimensions,Size = $Size,Doors = $Doors,Windows = $Windows,Fuel = $Fuel,Speed = $Speed,Costs = $Costs,Weight = $Weight,Country = $Country
,Type_of_vehicle = '$Type_of_vehicle',Alternate_names = '$Alternate_names',Tags = '$Tags',Name = '$Name',Description = '$Description',Materials = '$Materials',Designer = '$Designer',Colors = '$Colors',Distance = '$Distance',Features = '$Features',Safety = '$Safety',Variants = '$Variants',Manufacturer = '$Manufacturer',Owner = '$Owner',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	if ($procedureName == "addContentChangeEvent") {
		addContentChangeEvent($data);
	}

    if($procedureName == "saveData"){
        saveData($data);
    }

	if ($procedureName == "addBuilding") {
		addBuilding($data);
	}

	if ($procedureName == "updateBuilding") {
		updateBuilding($data);
	}

	if ($procedureName == "deleteBuilding") {
		deleteBuilding($data);
	}

	if ($procedureName == "addCharacter") {
		addCharacter($data);
	}

	if ($procedureName == "updateCharacter") {
		updateCharacter($data);
	}

	if ($procedureName == "deleteCharacter") {
		deleteCharacter($data);
	}

	if ($procedureName == "addCondition") {
		addCondition($data);
	}

	if ($procedureName == "updateCondition") {
		updateCondition($data);
	}

	if ($procedureName == "deleteCondition") {
		deleteCondition($data);
	}

	if ($procedureName == "addContinent") {
		addContinent($data);
	}

	if ($procedureName == "updateContinent") {
		updateContinent($data);
	}

	if ($procedureName == "deleteContinent") {
		deleteContinent($data);
	}

	if ($procedureName == "addCountrie") {
		addCountrie($data);
	}

	if ($procedureName == "updateCountrie") {
		updateCountrie($data);
	}

	if ($procedureName == "deleteCountrie") {
		deleteCountrie($data);
	}

	if ($procedureName == "addCreature") {
		addCreature($data);
	}

	if ($procedureName == "updateCreature") {
		updateCreature($data);
	}

	if ($procedureName == "deleteCreature") {
		deleteCreature($data);
	}

	if ($procedureName == "addDeitie") {
		addDeitie($data);
	}

	if ($procedureName == "updateDeitie") {
		updateDeitie($data);
	}

	if ($procedureName == "deleteDeitie") {
		deleteDeitie($data);
	}

	if ($procedureName == "addFlora") {
		addFlora($data);
	}

	if ($procedureName == "updateFlora") {
		updateFlora($data);
	}

	if ($procedureName == "deleteFlora") {
		deleteFlora($data);
	}

	if ($procedureName == "addFood") {
		addFood($data);
	}

	if ($procedureName == "updateFood") {
		updateFood($data);
	}

	if ($procedureName == "deleteFood") {
		deleteFood($data);
	}

	if ($procedureName == "addGovernment") {
		addGovernment($data);
	}

	if ($procedureName == "updateGovernment") {
		updateGovernment($data);
	}

	if ($procedureName == "deleteGovernment") {
		deleteGovernment($data);
	}

	if ($procedureName == "addGroup") {
		addGroup($data);
	}

	if ($procedureName == "updateGroup") {
		updateGroup($data);
	}

	if ($procedureName == "deleteGroup") {
		deleteGroup($data);
	}

	if ($procedureName == "addItem") {
		addItem($data);
	}

	if ($procedureName == "updateItem") {
		updateItem($data);
	}

	if ($procedureName == "deleteItem") {
		deleteItem($data);
	}

	if ($procedureName == "addJob") {
		addJob($data);
	}

	if ($procedureName == "updateJob") {
		updateJob($data);
	}

	if ($procedureName == "deleteJob") {
		deleteJob($data);
	}

	if ($procedureName == "addLandmark") {
		addLandmark($data);
	}

	if ($procedureName == "updateLandmark") {
		updateLandmark($data);
	}

	if ($procedureName == "deleteLandmark") {
		deleteLandmark($data);
	}

	if ($procedureName == "addLanguage") {
		addLanguage($data);
	}

	if ($procedureName == "updateLanguage") {
		updateLanguage($data);
	}

	if ($procedureName == "deleteLanguage") {
		deleteLanguage($data);
	}

	if ($procedureName == "addLocation") {
		addLocation($data);
	}

	if ($procedureName == "updateLocation") {
		updateLocation($data);
	}

	if ($procedureName == "deleteLocation") {
		deleteLocation($data);
	}

	if ($procedureName == "addLore") {
		addLore($data);
	}

	if ($procedureName == "updateLore") {
		updateLore($data);
	}

	if ($procedureName == "deleteLore") {
		deleteLore($data);
	}

	if ($procedureName == "addMagic") {
		addMagic($data);
	}

	if ($procedureName == "updateMagic") {
		updateMagic($data);
	}

	if ($procedureName == "deleteMagic") {
		deleteMagic($data);
	}

	if ($procedureName == "addOrganization") {
		addOrganization($data);
	}

	if ($procedureName == "updateOrganization") {
		updateOrganization($data);
	}

	if ($procedureName == "deleteOrganization") {
		deleteOrganization($data);
	}

	if ($procedureName == "addPlanet") {
		addPlanet($data);
	}

	if ($procedureName == "updatePlanet") {
		updatePlanet($data);
	}

	if ($procedureName == "deletePlanet") {
		deletePlanet($data);
	}

	if ($procedureName == "addRace") {
		addRace($data);
	}

	if ($procedureName == "updateRace") {
		updateRace($data);
	}

	if ($procedureName == "deleteRace") {
		deleteRace($data);
	}

	if ($procedureName == "addReligion") {
		addReligion($data);
	}

	if ($procedureName == "updateReligion") {
		updateReligion($data);
	}

	if ($procedureName == "deleteReligion") {
		deleteReligion($data);
	}

	if ($procedureName == "addScene") {
		addScene($data);
	}

	if ($procedureName == "updateScene") {
		updateScene($data);
	}

	if ($procedureName == "deleteScene") {
		deleteScene($data);
	}

	if ($procedureName == "addSport") {
		addSport($data);
	}

	if ($procedureName == "updateSport") {
		updateSport($data);
	}

	if ($procedureName == "deleteSport") {
		deleteSport($data);
	}

	if ($procedureName == "addTechnologie") {
		addTechnologie($data);
	}

	if ($procedureName == "updateTechnologie") {
		updateTechnologie($data);
	}

	if ($procedureName == "deleteTechnologie") {
		deleteTechnologie($data);
	}

	if ($procedureName == "addTimelineevententitie") {
		addTimelineevententitie($data);
	}

	if ($procedureName == "updateTimelineevententitie") {
		updateTimelineevententitie($data);
	}

	if ($procedureName == "deleteTimelineevententitie") {
		deleteTimelineevententitie($data);
	}

	if ($procedureName == "addTimelineevent") {
		addTimelineevent($data);
	}

	if ($procedureName == "updateTimelineevent") {
		updateTimelineevent($data);
	}

	if ($procedureName == "deleteTimelineevent") {
		deleteTimelineevent($data);
	}

	if ($procedureName == "addTimeline") {
		addTimeline($data);
	}

	if ($procedureName == "updateTimeline") {
		updateTimeline($data);
	}

	if ($procedureName == "deleteTimeline") {
		deleteTimeline($data);
	}

	if ($procedureName == "addTown") {
		addTown($data);
	}

	if ($procedureName == "updateTown") {
		updateTown($data);
	}

	if ($procedureName == "deleteTown") {
		deleteTown($data);
	}

	if ($procedureName == "addTradition") {
		addTradition($data);
	}

	if ($procedureName == "updateTradition") {
		updateTradition($data);
	}

	if ($procedureName == "deleteTradition") {
		deleteTradition($data);
	}

	if ($procedureName == "addUniverse") {
		addUniverse($data);
	}

	if ($procedureName == "updateUniverse") {
		updateUniverse($data);
	}

	if ($procedureName == "deleteUniverse") {
		deleteUniverse($data);
	}

	if ($procedureName == "addVehicle") {
		addVehicle($data);
	}

	if ($procedureName == "updateVehicle") {
		updateVehicle($data);
	}

	if ($procedureName == "deleteVehicle") {
		deleteVehicle($data);
	}


	mysqli_close($link);
	echo json_encode($response);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$procedureName = $_GET['procedureName'];
    
	if ($procedureName == "getChangelogforContent") {
		getChangelogforContent();
	}

	if ($procedureName == "getAllContentDataForUser") {
		getAllContentDataForUser();
	}

	if ($procedureName == "getAllContentTypeDataForUser") {
		getAllContentTypeDataForUser();
	}

	if ($procedureName == "getContentDetailsFromTypeID") {
		getContentDetailsFromTypeID();
	}

	if ($procedureName == "getAllBuildings") {
		getAllBuildings();
	}

	if ($procedureName == "getBuildings") {
		getBuildings();
	}

	if ($procedureName == "getAllCharacters") {
		getAllCharacters();
	}

	if ($procedureName == "getCharacters") {
		getCharacters();
	}

	if ($procedureName == "getAllConditions") {
		getAllConditions();
	}

	if ($procedureName == "getConditions") {
		getConditions();
	}

	if ($procedureName == "getAllContentChangeEvents") {
		getAllContentChangeEvents();
	}

	if ($procedureName == "getContentChangeEvents") {
		getContentChangeEvents();
	}

	if ($procedureName == "getAllContinents") {
		getAllContinents();
	}

	if ($procedureName == "getContinents") {
		getContinents();
	}

	if ($procedureName == "getAllCountries") {
		getAllCountries();
	}

	if ($procedureName == "getCountries") {
		getCountries();
	}

	if ($procedureName == "getAllCreatures") {
		getAllCreatures();
	}

	if ($procedureName == "getCreatures") {
		getCreatures();
	}

	if ($procedureName == "getAllDeities") {
		getAllDeities();
	}

	if ($procedureName == "getDeities") {
		getDeities();
	}

	if ($procedureName == "getAllFloras") {
		getAllFloras();
	}

	if ($procedureName == "getFloras") {
		getFloras();
	}

	if ($procedureName == "getAllFoods") {
		getAllFoods();
	}

	if ($procedureName == "getFoods") {
		getFoods();
	}

	if ($procedureName == "getAllGovernments") {
		getAllGovernments();
	}

	if ($procedureName == "getGovernments") {
		getGovernments();
	}

	if ($procedureName == "getAllGroups") {
		getAllGroups();
	}

	if ($procedureName == "getGroups") {
		getGroups();
	}

	if ($procedureName == "getAllItems") {
		getAllItems();
	}

	if ($procedureName == "getItems") {
		getItems();
	}

	if ($procedureName == "getAllJobs") {
		getAllJobs();
	}

	if ($procedureName == "getJobs") {
		getJobs();
	}

	if ($procedureName == "getAllLandmarks") {
		getAllLandmarks();
	}

	if ($procedureName == "getLandmarks") {
		getLandmarks();
	}

	if ($procedureName == "getAllLanguages") {
		getAllLanguages();
	}

	if ($procedureName == "getLanguages") {
		getLanguages();
	}

	if ($procedureName == "getAllLocations") {
		getAllLocations();
	}

	if ($procedureName == "getLocations") {
		getLocations();
	}

	if ($procedureName == "getAllLores") {
		getAllLores();
	}

	if ($procedureName == "getLores") {
		getLores();
	}

	if ($procedureName == "getAllMagics") {
		getAllMagics();
	}

	if ($procedureName == "getMagics") {
		getMagics();
	}

	if ($procedureName == "getAllOrganizations") {
		getAllOrganizations();
	}

	if ($procedureName == "getOrganizations") {
		getOrganizations();
	}

	if ($procedureName == "getAllPlanets") {
		getAllPlanets();
	}

	if ($procedureName == "getPlanets") {
		getPlanets();
	}

	if ($procedureName == "getAllRaces") {
		getAllRaces();
	}

	if ($procedureName == "getRaces") {
		getRaces();
	}

	if ($procedureName == "getAllReligions") {
		getAllReligions();
	}

	if ($procedureName == "getReligions") {
		getReligions();
	}

	if ($procedureName == "getAllScenes") {
		getAllScenes();
	}

	if ($procedureName == "getScenes") {
		getScenes();
	}

	if ($procedureName == "getAllSports") {
		getAllSports();
	}

	if ($procedureName == "getSports") {
		getSports();
	}

	if ($procedureName == "getAllTechnologies") {
		getAllTechnologies();
	}

	if ($procedureName == "getTechnologies") {
		getTechnologies();
	}

	if ($procedureName == "getAllTimelineEventEntities") {
		getAllTimelineEventEntities();
	}

	if ($procedureName == "getTimelineEventEntities") {
		getTimelineEventEntities();
	}

	if ($procedureName == "getAllTimelineEvents") {
		getAllTimelineEvents();
	}

	if ($procedureName == "getTimelineEvents") {
		getTimelineEvents();
	}

	if ($procedureName == "getAllTimelines") {
		getAllTimelines();
	}

	if ($procedureName == "getTimelines") {
		getTimelines();
	}

	if ($procedureName == "getAllTowns") {
		getAllTowns();
	}

	if ($procedureName == "getTowns") {
		getTowns();
	}

	if ($procedureName == "getAllTraditions") {
		getAllTraditions();
	}

	if ($procedureName == "getTraditions") {
		getTraditions();
	}

	if ($procedureName == "getAllUniverses") {
		getAllUniverses();
	}

	if ($procedureName == "getUniverses") {
		getUniverses();
	}

	if ($procedureName == "getAllVehicles") {
		getAllVehicles();
	}

	if ($procedureName == "getVehicles") {
		getVehicles();
	}


	// Close connection
	mysqli_close($link);
	echo json_encode($response->data);
}


?>
