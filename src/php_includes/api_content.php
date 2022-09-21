<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once "config.php";
require_once "responseClass.php";
require "logWriter.php";

$response = new dbResponse;
$log = new logWriter;

function getContentDetailsFromTypeID(){
    $contentType = $_GET['contentType'];
    $id = $_GET['id'];

    global $response;
    global $log;
    global $link;

    $sql = "SELECT id, Name as content_name FROM $contentType Where id = '$id'";

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

	$Name = trim($data->Name);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Type_of_building = trim($data->Type_of_building);
	$Alternate_names = trim($data->Alternate_names);
	$Tags = trim($data->Tags);
	$Capacity = trim($data->Capacity);
	$Price = trim($data->Price);
	$Owner = trim($data->Owner);
	$Tenants = trim($data->Tenants);
	$Affiliation = trim($data->Affiliation);
	$Facade = trim($data->Facade);
	$Floor_count = trim($data->Floor_count);
	$Dimensions = trim($data->Dimensions);
	$Architectural_style = trim($data->Architectural_style);
	$Permits = trim($data->Permits);
	$Purpose = trim($data->Purpose);
	$Address = trim($data->Address);
	$Architect = trim($data->Architect);
	$Developer = trim($data->Developer);
	$Notable_events = trim($data->Notable_events);
	$Constructed_year = trim($data->Constructed_year);
	$Construction_cost = trim($data->Construction_cost);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO buildings(Name,Universe,Description,Type_of_building,Alternate_names,Tags,Capacity,Price,Owner,Tenants,Affiliation,Facade,Floor_count,Dimensions,Architectural_style,Permits,Purpose,Address,Architect,Developer,Notable_events,Constructed_year,Construction_cost,Notes,Private_Notes) 
VALUES('$Name','$Universe','$Description','$Type_of_building','$Alternate_names','$Tags','$Capacity','$Price','$Owner','$Tenants','$Affiliation','$Facade','$Floor_count','$Dimensions','$Architectural_style','$Permits','$Purpose','$Address','$Architect','$Developer','$Notable_events','$Constructed_year','$Construction_cost','$Notes','$Private_Notes')"; 


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

	$Name = trim($data->Name);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Type_of_building = trim($data->Type_of_building);
	$Alternate_names = trim($data->Alternate_names);
	$Tags = trim($data->Tags);
	$Capacity = trim($data->Capacity);
	$Price = trim($data->Price);
	$Owner = trim($data->Owner);
	$Tenants = trim($data->Tenants);
	$Affiliation = trim($data->Affiliation);
	$Facade = trim($data->Facade);
	$Floor_count = trim($data->Floor_count);
	$Dimensions = trim($data->Dimensions);
	$Architectural_style = trim($data->Architectural_style);
	$Permits = trim($data->Permits);
	$Purpose = trim($data->Purpose);
	$Address = trim($data->Address);
	$Architect = trim($data->Architect);
	$Developer = trim($data->Developer);
	$Notable_events = trim($data->Notable_events);
	$Constructed_year = trim($data->Constructed_year);
	$Construction_cost = trim($data->Construction_cost);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE buildings SET 
Name = '$Name',Universe = '$Universe',Description = '$Description',Type_of_building = '$Type_of_building',Alternate_names = '$Alternate_names',Tags = '$Tags',Capacity = '$Capacity',Price = '$Price',Owner = '$Owner',Tenants = '$Tenants',Affiliation = '$Affiliation',Facade = '$Facade',Floor_count = '$Floor_count',Dimensions = '$Dimensions',Architectural_style = '$Architectural_style',Permits = '$Permits',Purpose = '$Purpose',Address = '$Address',Architect = '$Architect',Developer = '$Developer',Notable_events = '$Notable_events',Constructed_year = '$Constructed_year',Construction_cost = '$Construction_cost',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	$Name = trim($data->Name);
	$Role = trim($data->Role);
	$Gender = trim($data->Gender);
	$Age = trim($data->Age);
	$Height = trim($data->Height);
	$Weight = trim($data->Weight);
	$Haircolor = trim($data->Haircolor);
	$Hairstyle = trim($data->Hairstyle);
	$Facialhair = trim($data->Facialhair);
	$Eyecolor = trim($data->Eyecolor);
	$Race = trim($data->Race);
	$Skintone = trim($data->Skintone);
	$Bodytype = trim($data->Bodytype);
	$Identmarks = trim($data->Identmarks);
	$Religion = trim($data->Religion);
	$Politics = trim($data->Politics);
	$Prejudices = trim($data->Prejudices);
	$Occupation = trim($data->Occupation);
	$Pets = trim($data->Pets);
	$Mannerisms = trim($data->Mannerisms);
	$Birthday = trim($data->Birthday);
	$Birthplace = trim($data->Birthplace);
	$Education = trim($data->Education);
	$Background = trim($data->Background);
	$Fave_color = trim($data->Fave_color);
	$Fave_food = trim($data->Fave_food);
	$Fave_possession = trim($data->Fave_possession);
	$Fave_weapon = trim($data->Fave_weapon);
	$Fave_animal = trim($data->Fave_animal);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);
	$Universe = trim($data->Universe);
	$Privacy = trim($data->Privacy);
	$Aliases = trim($data->Aliases);
	$Motivations = trim($data->Motivations);
	$Flaws = trim($data->Flaws);
	$Talents = trim($data->Talents);
	$Hobbies = trim($data->Hobbies);
	$Personality_type = trim($data->Personality_type);
	$Favorite = trim($data->Favorite);


    $sql = "INSERT INTO characters(Name,Role,Gender,Age,Height,Weight,Haircolor,Hairstyle,Facialhair,Eyecolor,Race,Skintone,Bodytype,Identmarks,Religion,Politics,Prejudices,Occupation,Pets,Mannerisms,Birthday,Birthplace,Education,Background,Fave_color,Fave_food,Fave_possession,Fave_weapon,Fave_animal,Notes,Private_notes,Universe,Privacy,Aliases,Motivations,Flaws,Talents,Hobbies,Personality_type,Favorite) 
VALUES('$Name','$Role','$Gender','$Age','$Height','$Weight','$Haircolor','$Hairstyle','$Facialhair','$Eyecolor','$Race','$Skintone','$Bodytype','$Identmarks','$Religion','$Politics','$Prejudices','$Occupation','$Pets','$Mannerisms','$Birthday','$Birthplace','$Education','$Background','$Fave_color','$Fave_food','$Fave_possession','$Fave_weapon','$Fave_animal','$Notes','$Private_notes','$Universe','$Privacy','$Aliases','$Motivations','$Flaws','$Talents','$Hobbies','$Personality_type','$Favorite')"; 


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

	$Name = trim($data->Name);
	$Role = trim($data->Role);
	$Gender = trim($data->Gender);
	$Age = trim($data->Age);
	$Height = trim($data->Height);
	$Weight = trim($data->Weight);
	$Haircolor = trim($data->Haircolor);
	$Hairstyle = trim($data->Hairstyle);
	$Facialhair = trim($data->Facialhair);
	$Eyecolor = trim($data->Eyecolor);
	$Race = trim($data->Race);
	$Skintone = trim($data->Skintone);
	$Bodytype = trim($data->Bodytype);
	$Identmarks = trim($data->Identmarks);
	$Religion = trim($data->Religion);
	$Politics = trim($data->Politics);
	$Prejudices = trim($data->Prejudices);
	$Occupation = trim($data->Occupation);
	$Pets = trim($data->Pets);
	$Mannerisms = trim($data->Mannerisms);
	$Birthday = trim($data->Birthday);
	$Birthplace = trim($data->Birthplace);
	$Education = trim($data->Education);
	$Background = trim($data->Background);
	$Fave_color = trim($data->Fave_color);
	$Fave_food = trim($data->Fave_food);
	$Fave_possession = trim($data->Fave_possession);
	$Fave_weapon = trim($data->Fave_weapon);
	$Fave_animal = trim($data->Fave_animal);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);
	$Universe = trim($data->Universe);
	$Privacy = trim($data->Privacy);
	$Aliases = trim($data->Aliases);
	$Motivations = trim($data->Motivations);
	$Flaws = trim($data->Flaws);
	$Talents = trim($data->Talents);
	$Hobbies = trim($data->Hobbies);
	$Personality_type = trim($data->Personality_type);
	$Favorite = trim($data->Favorite);


    $sql = "UPDATE characters SET 
Name = '$Name',Role = '$Role',Gender = '$Gender',Age = '$Age',Height = '$Height',Weight = '$Weight',Haircolor = '$Haircolor',Hairstyle = '$Hairstyle',Facialhair = '$Facialhair',Eyecolor = '$Eyecolor',Race = '$Race',Skintone = '$Skintone',Bodytype = '$Bodytype',Identmarks = '$Identmarks',Religion = '$Religion',Politics = '$Politics',Prejudices = '$Prejudices',Occupation = '$Occupation',Pets = '$Pets',Mannerisms = '$Mannerisms',Birthday = '$Birthday',Birthplace = '$Birthplace',Education = '$Education',Background = '$Background',Fave_color = '$Fave_color',Fave_food = '$Fave_food',Fave_possession = '$Fave_possession',Fave_weapon = '$Fave_weapon',Fave_animal = '$Fave_animal',Notes = '$Notes',Private_notes = '$Private_notes',Universe = '$Universe',Privacy = '$Privacy',Aliases = '$Aliases',Motivations = '$Motivations',Flaws = '$Flaws',Talents = '$Talents',Hobbies = '$Hobbies',Personality_type = '$Personality_type',Favorite = '$Favorite'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Type_of_condition = trim($data->Type_of_condition);
	$Alternate_names = trim($data->Alternate_names);
	$Transmission = trim($data->Transmission);
	$Genetic_factors = trim($data->Genetic_factors);
	$Environmental_factors = trim($data->Environmental_factors);
	$Lifestyle_factors = trim($data->Lifestyle_factors);
	$Epidemiology = trim($data->Epidemiology);
	$Duration = trim($data->Duration);
	$Variations = trim($data->Variations);
	$Prognosis = trim($data->Prognosis);
	$Symptoms = trim($data->Symptoms);
	$Mental_effects = trim($data->Mental_effects);
	$Visual_effects = trim($data->Visual_effects);
	$Prevention = trim($data->Prevention);
	$Treatment = trim($data->Treatment);
	$Medication = trim($data->Medication);
	$Immunization = trim($data->Immunization);
	$Diagnostic_method = trim($data->Diagnostic_method);
	$Symbolism = trim($data->Symbolism);
	$Specialty_Field = trim($data->Specialty_Field);
	$Rarity = trim($data->Rarity);
	$Evolution = trim($data->Evolution);
	$Origin = trim($data->Origin);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "INSERT INTO conditions(Tags,Name,Universe,Description,Type_of_condition,Alternate_names,Transmission,Genetic_factors,Environmental_factors,Lifestyle_factors,Epidemiology,Duration,Variations,Prognosis,Symptoms,Mental_effects,Visual_effects,Prevention,Treatment,Medication,Immunization,Diagnostic_method,Symbolism,Specialty_Field,Rarity,Evolution,Origin,Private_Notes,Notes) 
VALUES('$Tags','$Name','$Universe','$Description','$Type_of_condition','$Alternate_names','$Transmission','$Genetic_factors','$Environmental_factors','$Lifestyle_factors','$Epidemiology','$Duration','$Variations','$Prognosis','$Symptoms','$Mental_effects','$Visual_effects','$Prevention','$Treatment','$Medication','$Immunization','$Diagnostic_method','$Symbolism','$Specialty_Field','$Rarity','$Evolution','$Origin','$Private_Notes','$Notes')"; 


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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Type_of_condition = trim($data->Type_of_condition);
	$Alternate_names = trim($data->Alternate_names);
	$Transmission = trim($data->Transmission);
	$Genetic_factors = trim($data->Genetic_factors);
	$Environmental_factors = trim($data->Environmental_factors);
	$Lifestyle_factors = trim($data->Lifestyle_factors);
	$Epidemiology = trim($data->Epidemiology);
	$Duration = trim($data->Duration);
	$Variations = trim($data->Variations);
	$Prognosis = trim($data->Prognosis);
	$Symptoms = trim($data->Symptoms);
	$Mental_effects = trim($data->Mental_effects);
	$Visual_effects = trim($data->Visual_effects);
	$Prevention = trim($data->Prevention);
	$Treatment = trim($data->Treatment);
	$Medication = trim($data->Medication);
	$Immunization = trim($data->Immunization);
	$Diagnostic_method = trim($data->Diagnostic_method);
	$Symbolism = trim($data->Symbolism);
	$Specialty_Field = trim($data->Specialty_Field);
	$Rarity = trim($data->Rarity);
	$Evolution = trim($data->Evolution);
	$Origin = trim($data->Origin);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "UPDATE conditions SET 
Tags = '$Tags',Name = '$Name',Universe = '$Universe',Description = '$Description',Type_of_condition = '$Type_of_condition',Alternate_names = '$Alternate_names',Transmission = '$Transmission',Genetic_factors = '$Genetic_factors',Environmental_factors = '$Environmental_factors',Lifestyle_factors = '$Lifestyle_factors',Epidemiology = '$Epidemiology',Duration = '$Duration',Variations = '$Variations',Prognosis = '$Prognosis',Symptoms = '$Symptoms',Mental_effects = '$Mental_effects',Visual_effects = '$Visual_effects',Prevention = '$Prevention',Treatment = '$Treatment',Medication = '$Medication',Immunization = '$Immunization',Diagnostic_method = '$Diagnostic_method',Symbolism = '$Symbolism',Specialty_Field = '$Specialty_Field',Rarity = '$Rarity',Evolution = '$Evolution',Origin = '$Origin',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

function getAllContentBlobObject(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM content_blob_object ";

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

function getContentBlobObject(){


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM content_blob_object ";

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

function addContentBlobObject($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$object_id = trim($data->object_id);
	$object_name = trim($data->object_name);
	$object_type = trim($data->object_type);
	$object_size = trim($data->object_size);
	$object_blob = trim($data->object_blob);


    $sql = "INSERT INTO content_blob_object(object_id,object_name,object_type,object_size,object_blob) 
VALUES('$object_id','$object_name','$object_type','$object_size','$object_blob')"; 


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

function deleteContentBlobObject($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM content_blob_object WHERE id = $id; ";

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

function updateContentBlobObject($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$object_id = trim($data->object_id);
	$object_name = trim($data->object_name);
	$object_type = trim($data->object_type);
	$object_size = trim($data->object_size);
	$object_blob = trim($data->object_blob);


    $sql = "UPDATE content_blob_object SET 
object_id = '$object_id',object_name = '$object_name',object_type = '$object_type',object_size = '$object_size',object_blob = '$object_blob'    WHERE id = $id"; 

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

function getAllContentChangeEvents(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM content_change_events Where user_id = '$user_id'";

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

function getContentChangeEvents(){
    $user_id = $_GET['user_id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM content_change_events Where user_id = '$user_id'";

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

function addContentchangeevent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$changed_fields = trim($data->changed_fields);
	$content_id = trim($data->content_id);
	$content_type = trim($data->content_type);
	$action = trim($data->action);


    $sql = "INSERT INTO content_change_events(changed_fields,content_id,content_type,action) 
VALUES('$changed_fields','$content_id','$content_type','$action')"; 


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

function deleteContentchangeevent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM content_change_events WHERE id = $id; ";

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

function updateContentchangeevent($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$changed_fields = trim($data->changed_fields);
	$content_id = trim($data->content_id);
	$content_type = trim($data->content_type);
	$action = trim($data->action);


    $sql = "UPDATE content_change_events SET 
changed_fields = '$changed_fields',content_id = '$content_id',content_type = '$content_type',action = '$action'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Name = trim($data->Name);
	$Other_Names = trim($data->Other_Names);
	$Local_name = trim($data->Local_name);
	$Regional_disadvantages = trim($data->Regional_disadvantages);
	$Regional_advantages = trim($data->Regional_advantages);
	$Landmarks = trim($data->Landmarks);
	$Bodies_of_water = trim($data->Bodies_of_water);
	$Mineralogy = trim($data->Mineralogy);
	$Topography = trim($data->Topography);
	$Population = trim($data->Population);
	$Shape = trim($data->Shape);
	$Area = trim($data->Area);
	$Popular_foods = trim($data->Popular_foods);
	$Governments = trim($data->Governments);
	$Traditions = trim($data->Traditions);
	$Languages = trim($data->Languages);
	$Countries = trim($data->Countries);
	$Reputation = trim($data->Reputation);
	$Architecture = trim($data->Architecture);
	$Tourism = trim($data->Tourism);
	$Economy = trim($data->Economy);
	$Politics = trim($data->Politics);
	$Demonym = trim($data->Demonym);
	$Floras = trim($data->Floras);
	$Creatures = trim($data->Creatures);
	$Crops = trim($data->Crops);
	$Natural_disasters = trim($data->Natural_disasters);
	$Winds = trim($data->Winds);
	$Precipitation = trim($data->Precipitation);
	$Humidity = trim($data->Humidity);
	$Seasons = trim($data->Seasons);
	$Temperature = trim($data->Temperature);
	$Ruins = trim($data->Ruins);
	$Wars = trim($data->Wars);
	$Discovery = trim($data->Discovery);
	$Formation = trim($data->Formation);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "INSERT INTO continents(Tags,Universe,Description,Name,Other_Names,Local_name,Regional_disadvantages,Regional_advantages,Landmarks,Bodies_of_water,Mineralogy,Topography,Population,Shape,Area,Popular_foods,Governments,Traditions,Languages,Countries,Reputation,Architecture,Tourism,Economy,Politics,Demonym,Floras,Creatures,Crops,Natural_disasters,Winds,Precipitation,Humidity,Seasons,Temperature,Ruins,Wars,Discovery,Formation,Private_Notes,Notes) 
VALUES('$Tags','$Universe','$Description','$Name','$Other_Names','$Local_name','$Regional_disadvantages','$Regional_advantages','$Landmarks','$Bodies_of_water','$Mineralogy','$Topography','$Population','$Shape','$Area','$Popular_foods','$Governments','$Traditions','$Languages','$Countries','$Reputation','$Architecture','$Tourism','$Economy','$Politics','$Demonym','$Floras','$Creatures','$Crops','$Natural_disasters','$Winds','$Precipitation','$Humidity','$Seasons','$Temperature','$Ruins','$Wars','$Discovery','$Formation','$Private_Notes','$Notes')"; 


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

	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Name = trim($data->Name);
	$Other_Names = trim($data->Other_Names);
	$Local_name = trim($data->Local_name);
	$Regional_disadvantages = trim($data->Regional_disadvantages);
	$Regional_advantages = trim($data->Regional_advantages);
	$Landmarks = trim($data->Landmarks);
	$Bodies_of_water = trim($data->Bodies_of_water);
	$Mineralogy = trim($data->Mineralogy);
	$Topography = trim($data->Topography);
	$Population = trim($data->Population);
	$Shape = trim($data->Shape);
	$Area = trim($data->Area);
	$Popular_foods = trim($data->Popular_foods);
	$Governments = trim($data->Governments);
	$Traditions = trim($data->Traditions);
	$Languages = trim($data->Languages);
	$Countries = trim($data->Countries);
	$Reputation = trim($data->Reputation);
	$Architecture = trim($data->Architecture);
	$Tourism = trim($data->Tourism);
	$Economy = trim($data->Economy);
	$Politics = trim($data->Politics);
	$Demonym = trim($data->Demonym);
	$Floras = trim($data->Floras);
	$Creatures = trim($data->Creatures);
	$Crops = trim($data->Crops);
	$Natural_disasters = trim($data->Natural_disasters);
	$Winds = trim($data->Winds);
	$Precipitation = trim($data->Precipitation);
	$Humidity = trim($data->Humidity);
	$Seasons = trim($data->Seasons);
	$Temperature = trim($data->Temperature);
	$Ruins = trim($data->Ruins);
	$Wars = trim($data->Wars);
	$Discovery = trim($data->Discovery);
	$Formation = trim($data->Formation);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "UPDATE continents SET 
Tags = '$Tags',Universe = '$Universe',Description = '$Description',Name = '$Name',Other_Names = '$Other_Names',Local_name = '$Local_name',Regional_disadvantages = '$Regional_disadvantages',Regional_advantages = '$Regional_advantages',Landmarks = '$Landmarks',Bodies_of_water = '$Bodies_of_water',Mineralogy = '$Mineralogy',Topography = '$Topography',Population = '$Population',Shape = '$Shape',Area = '$Area',Popular_foods = '$Popular_foods',Governments = '$Governments',Traditions = '$Traditions',Languages = '$Languages',Countries = '$Countries',Reputation = '$Reputation',Architecture = '$Architecture',Tourism = '$Tourism',Economy = '$Economy',Politics = '$Politics',Demonym = '$Demonym',Floras = '$Floras',Creatures = '$Creatures',Crops = '$Crops',Natural_disasters = '$Natural_disasters',Winds = '$Winds',Precipitation = '$Precipitation',Humidity = '$Humidity',Seasons = '$Seasons',Temperature = '$Temperature',Ruins = '$Ruins',Wars = '$Wars',Discovery = '$Discovery',Formation = '$Formation',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Landmarks = trim($data->Landmarks);
	$Locations = trim($data->Locations);
	$Towns = trim($data->Towns);
	$Bordering_countries = trim($data->Bordering_countries);
	$Education = trim($data->Education);
	$Governments = trim($data->Governments);
	$Religions = trim($data->Religions);
	$Languages = trim($data->Languages);
	$Sports = trim($data->Sports);
	$Architecture = trim($data->Architecture);
	$Music = trim($data->Music);
	$Pop_culture = trim($data->Pop_culture);
	$Laws = trim($data->Laws);
	$Currency = trim($data->Currency);
	$Social_hierarchy = trim($data->Social_hierarchy);
	$Population = trim($data->Population);
	$Area = trim($data->Area);
	$Crops = trim($data->Crops);
	$Climate = trim($data->Climate);
	$Creatures = trim($data->Creatures);
	$Flora = trim($data->Flora);
	$Established_year = trim($data->Established_year);
	$Notable_wars = trim($data->Notable_wars);
	$Founding_story = trim($data->Founding_story);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "INSERT INTO countries(Tags,Name,Description,Other_Names,Universe,Landmarks,Locations,Towns,Bordering_countries,Education,Governments,Religions,Languages,Sports,Architecture,Music,Pop_culture,Laws,Currency,Social_hierarchy,Population,Area,Crops,Climate,Creatures,Flora,Established_year,Notable_wars,Founding_story,Private_Notes,Notes) 
VALUES('$Tags','$Name','$Description','$Other_Names','$Universe','$Landmarks','$Locations','$Towns','$Bordering_countries','$Education','$Governments','$Religions','$Languages','$Sports','$Architecture','$Music','$Pop_culture','$Laws','$Currency','$Social_hierarchy','$Population','$Area','$Crops','$Climate','$Creatures','$Flora','$Established_year','$Notable_wars','$Founding_story','$Private_Notes','$Notes')"; 


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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Landmarks = trim($data->Landmarks);
	$Locations = trim($data->Locations);
	$Towns = trim($data->Towns);
	$Bordering_countries = trim($data->Bordering_countries);
	$Education = trim($data->Education);
	$Governments = trim($data->Governments);
	$Religions = trim($data->Religions);
	$Languages = trim($data->Languages);
	$Sports = trim($data->Sports);
	$Architecture = trim($data->Architecture);
	$Music = trim($data->Music);
	$Pop_culture = trim($data->Pop_culture);
	$Laws = trim($data->Laws);
	$Currency = trim($data->Currency);
	$Social_hierarchy = trim($data->Social_hierarchy);
	$Population = trim($data->Population);
	$Area = trim($data->Area);
	$Crops = trim($data->Crops);
	$Climate = trim($data->Climate);
	$Creatures = trim($data->Creatures);
	$Flora = trim($data->Flora);
	$Established_year = trim($data->Established_year);
	$Notable_wars = trim($data->Notable_wars);
	$Founding_story = trim($data->Founding_story);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "UPDATE countries SET 
Tags = '$Tags',Name = '$Name',Description = '$Description',Other_Names = '$Other_Names',Universe = '$Universe',Landmarks = '$Landmarks',Locations = '$Locations',Towns = '$Towns',Bordering_countries = '$Bordering_countries',Education = '$Education',Governments = '$Governments',Religions = '$Religions',Languages = '$Languages',Sports = '$Sports',Architecture = '$Architecture',Music = '$Music',Pop_culture = '$Pop_culture',Laws = '$Laws',Currency = '$Currency',Social_hierarchy = '$Social_hierarchy',Population = '$Population',Area = '$Area',Crops = '$Crops',Climate = '$Climate',Creatures = '$Creatures',Flora = '$Flora',Established_year = '$Established_year',Notable_wars = '$Notable_wars',Founding_story = '$Founding_story',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

	$Type_of_creature = trim($data->Type_of_creature);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Weight = trim($data->Weight);
	$Notable_features = trim($data->Notable_features);
	$Materials = trim($data->Materials);
	$Vestigial_features = trim($data->Vestigial_features);
	$Color = trim($data->Color);
	$Shape = trim($data->Shape);
	$Size = trim($data->Size);
	$Height = trim($data->Height);
	$Strongest_sense = trim($data->Strongest_sense);
	$Aggressiveness = trim($data->Aggressiveness);
	$Method_of_attack = trim($data->Method_of_attack);
	$Methods_of_defense = trim($data->Methods_of_defense);
	$Maximum_speed = trim($data->Maximum_speed);
	$Strengths = trim($data->Strengths);
	$Weaknesses = trim($data->Weaknesses);
	$Sounds = trim($data->Sounds);
	$Spoils = trim($data->Spoils);
	$Conditions = trim($data->Conditions);
	$Weakest_sense = trim($data->Weakest_sense);
	$Herding_patterns = trim($data->Herding_patterns);
	$Prey = trim($data->Prey);
	$Predators = trim($data->Predators);
	$Competitors = trim($data->Competitors);
	$Migratory_patterns = trim($data->Migratory_patterns);
	$Food_sources = trim($data->Food_sources);
	$Habitats = trim($data->Habitats);
	$Preferred_habitat = trim($data->Preferred_habitat);
	$Similar_creatures = trim($data->Similar_creatures);
	$Symbolisms = trim($data->Symbolisms);
	$Related_creatures = trim($data->Related_creatures);
	$Ancestors = trim($data->Ancestors);
	$Evolutionary_drive = trim($data->Evolutionary_drive);
	$Tradeoffs = trim($data->Tradeoffs);
	$Predictions = trim($data->Predictions);
	$Mortality_rate = trim($data->Mortality_rate);
	$Offspring_care = trim($data->Offspring_care);
	$Reproduction_age = trim($data->Reproduction_age);
	$Requirements = trim($data->Requirements);
	$Mating_ritual = trim($data->Mating_ritual);
	$Reproduction = trim($data->Reproduction);
	$Reproduction_frequency = trim($data->Reproduction_frequency);
	$Parental_instincts = trim($data->Parental_instincts);
	$Variations = trim($data->Variations);
	$Phylum = trim($data->Phylum);
	$Class = trim($data->Class);
	$Order = trim($data->Order);
	$Family = trim($data->Family);
	$Genus = trim($data->Genus);
	$Species = trim($data->Species);
	$Private_notes = trim($data->Private_notes);
	$Notes = trim($data->Notes);


    $sql = "INSERT INTO creatures(Type_of_creature,Universe,Tags,Name,Description,Weight,Notable_features,Materials,Vestigial_features,Color,Shape,Size,Height,Strongest_sense,Aggressiveness,Method_of_attack,Methods_of_defense,Maximum_speed,Strengths,Weaknesses,Sounds,Spoils,Conditions,Weakest_sense,Herding_patterns,Prey,Predators,Competitors,Migratory_patterns,Food_sources,Habitats,Preferred_habitat,Similar_creatures,Symbolisms,Related_creatures,Ancestors,Evolutionary_drive,Tradeoffs,Predictions,Mortality_rate,Offspring_care,Reproduction_age,Requirements,Mating_ritual,Reproduction,Reproduction_frequency,Parental_instincts,Variations,Phylum,Class,Order,Family,Genus,Species,Private_notes,Notes) 
VALUES('$Type_of_creature','$Universe','$Tags','$Name','$Description','$Weight','$Notable_features','$Materials','$Vestigial_features','$Color','$Shape','$Size','$Height','$Strongest_sense','$Aggressiveness','$Method_of_attack','$Methods_of_defense','$Maximum_speed','$Strengths','$Weaknesses','$Sounds','$Spoils','$Conditions','$Weakest_sense','$Herding_patterns','$Prey','$Predators','$Competitors','$Migratory_patterns','$Food_sources','$Habitats','$Preferred_habitat','$Similar_creatures','$Symbolisms','$Related_creatures','$Ancestors','$Evolutionary_drive','$Tradeoffs','$Predictions','$Mortality_rate','$Offspring_care','$Reproduction_age','$Requirements','$Mating_ritual','$Reproduction','$Reproduction_frequency','$Parental_instincts','$Variations','$Phylum','$Class','$Order','$Family','$Genus','$Species','$Private_notes','$Notes')"; 


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

	$Type_of_creature = trim($data->Type_of_creature);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Weight = trim($data->Weight);
	$Notable_features = trim($data->Notable_features);
	$Materials = trim($data->Materials);
	$Vestigial_features = trim($data->Vestigial_features);
	$Color = trim($data->Color);
	$Shape = trim($data->Shape);
	$Size = trim($data->Size);
	$Height = trim($data->Height);
	$Strongest_sense = trim($data->Strongest_sense);
	$Aggressiveness = trim($data->Aggressiveness);
	$Method_of_attack = trim($data->Method_of_attack);
	$Methods_of_defense = trim($data->Methods_of_defense);
	$Maximum_speed = trim($data->Maximum_speed);
	$Strengths = trim($data->Strengths);
	$Weaknesses = trim($data->Weaknesses);
	$Sounds = trim($data->Sounds);
	$Spoils = trim($data->Spoils);
	$Conditions = trim($data->Conditions);
	$Weakest_sense = trim($data->Weakest_sense);
	$Herding_patterns = trim($data->Herding_patterns);
	$Prey = trim($data->Prey);
	$Predators = trim($data->Predators);
	$Competitors = trim($data->Competitors);
	$Migratory_patterns = trim($data->Migratory_patterns);
	$Food_sources = trim($data->Food_sources);
	$Habitats = trim($data->Habitats);
	$Preferred_habitat = trim($data->Preferred_habitat);
	$Similar_creatures = trim($data->Similar_creatures);
	$Symbolisms = trim($data->Symbolisms);
	$Related_creatures = trim($data->Related_creatures);
	$Ancestors = trim($data->Ancestors);
	$Evolutionary_drive = trim($data->Evolutionary_drive);
	$Tradeoffs = trim($data->Tradeoffs);
	$Predictions = trim($data->Predictions);
	$Mortality_rate = trim($data->Mortality_rate);
	$Offspring_care = trim($data->Offspring_care);
	$Reproduction_age = trim($data->Reproduction_age);
	$Requirements = trim($data->Requirements);
	$Mating_ritual = trim($data->Mating_ritual);
	$Reproduction = trim($data->Reproduction);
	$Reproduction_frequency = trim($data->Reproduction_frequency);
	$Parental_instincts = trim($data->Parental_instincts);
	$Variations = trim($data->Variations);
	$Phylum = trim($data->Phylum);
	$Class = trim($data->Class);
	$Order = trim($data->Order);
	$Family = trim($data->Family);
	$Genus = trim($data->Genus);
	$Species = trim($data->Species);
	$Private_notes = trim($data->Private_notes);
	$Notes = trim($data->Notes);


    $sql = "UPDATE creatures SET 
Type_of_creature = '$Type_of_creature',Universe = '$Universe',Tags = '$Tags',Name = '$Name',Description = '$Description',Weight = '$Weight',Notable_features = '$Notable_features',Materials = '$Materials',Vestigial_features = '$Vestigial_features',Color = '$Color',Shape = '$Shape',Size = '$Size',Height = '$Height',Strongest_sense = '$Strongest_sense',Aggressiveness = '$Aggressiveness',Method_of_attack = '$Method_of_attack',Methods_of_defense = '$Methods_of_defense',Maximum_speed = '$Maximum_speed',Strengths = '$Strengths',Weaknesses = '$Weaknesses',Sounds = '$Sounds',Spoils = '$Spoils',Conditions = '$Conditions',Weakest_sense = '$Weakest_sense',Herding_patterns = '$Herding_patterns',Prey = '$Prey',Predators = '$Predators',Competitors = '$Competitors',Migratory_patterns = '$Migratory_patterns',Food_sources = '$Food_sources',Habitats = '$Habitats',Preferred_habitat = '$Preferred_habitat',Similar_creatures = '$Similar_creatures',Symbolisms = '$Symbolisms',Related_creatures = '$Related_creatures',Ancestors = '$Ancestors',Evolutionary_drive = '$Evolutionary_drive',Tradeoffs = '$Tradeoffs',Predictions = '$Predictions',Mortality_rate = '$Mortality_rate',Offspring_care = '$Offspring_care',Reproduction_age = '$Reproduction_age',Requirements = '$Requirements',Mating_ritual = '$Mating_ritual',Reproduction = '$Reproduction',Reproduction_frequency = '$Reproduction_frequency',Parental_instincts = '$Parental_instincts',Variations = '$Variations',Phylum = '$Phylum',Class = '$Class',Order = '$Order',Family = '$Family',Genus = '$Genus',Species = '$Species',Private_notes = '$Private_notes',Notes = '$Notes'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Height = trim($data->Height);
	$Physical_Description = trim($data->Physical_Description);
	$Weight = trim($data->Weight);
	$Children = trim($data->Children);
	$Parents = trim($data->Parents);
	$Partners = trim($data->Partners);
	$Siblings = trim($data->Siblings);
	$Floras = trim($data->Floras);
	$Relics = trim($data->Relics);
	$Religions = trim($data->Religions);
	$Creatures = trim($data->Creatures);
	$Elements = trim($data->Elements);
	$Symbols = trim($data->Symbols);
	$Abilities = trim($data->Abilities);
	$Conditions = trim($data->Conditions);
	$Strengths = trim($data->Strengths);
	$Weaknesses = trim($data->Weaknesses);
	$Human_Interaction = trim($data->Human_Interaction);
	$Related_towns = trim($data->Related_towns);
	$Related_races = trim($data->Related_races);
	$Related_landmarks = trim($data->Related_landmarks);
	$Prayers = trim($data->Prayers);
	$Rituals = trim($data->Rituals);
	$Traditions = trim($data->Traditions);
	$Family_History = trim($data->Family_History);
	$Notable_Events = trim($data->Notable_Events);
	$Life_Story = trim($data->Life_Story);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "INSERT INTO deities(Tags,Name,Description,Other_Names,Universe,Height,Physical_Description,Weight,Children,Parents,Partners,Siblings,Floras,Relics,Religions,Creatures,Elements,Symbols,Abilities,Conditions,Strengths,Weaknesses,Human_Interaction,Related_towns,Related_races,Related_landmarks,Prayers,Rituals,Traditions,Family_History,Notable_Events,Life_Story,Private_Notes,Notes) 
VALUES('$Tags','$Name','$Description','$Other_Names','$Universe','$Height','$Physical_Description','$Weight','$Children','$Parents','$Partners','$Siblings','$Floras','$Relics','$Religions','$Creatures','$Elements','$Symbols','$Abilities','$Conditions','$Strengths','$Weaknesses','$Human_Interaction','$Related_towns','$Related_races','$Related_landmarks','$Prayers','$Rituals','$Traditions','$Family_History','$Notable_Events','$Life_Story','$Private_Notes','$Notes')"; 


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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Height = trim($data->Height);
	$Physical_Description = trim($data->Physical_Description);
	$Weight = trim($data->Weight);
	$Children = trim($data->Children);
	$Parents = trim($data->Parents);
	$Partners = trim($data->Partners);
	$Siblings = trim($data->Siblings);
	$Floras = trim($data->Floras);
	$Relics = trim($data->Relics);
	$Religions = trim($data->Religions);
	$Creatures = trim($data->Creatures);
	$Elements = trim($data->Elements);
	$Symbols = trim($data->Symbols);
	$Abilities = trim($data->Abilities);
	$Conditions = trim($data->Conditions);
	$Strengths = trim($data->Strengths);
	$Weaknesses = trim($data->Weaknesses);
	$Human_Interaction = trim($data->Human_Interaction);
	$Related_towns = trim($data->Related_towns);
	$Related_races = trim($data->Related_races);
	$Related_landmarks = trim($data->Related_landmarks);
	$Prayers = trim($data->Prayers);
	$Rituals = trim($data->Rituals);
	$Traditions = trim($data->Traditions);
	$Family_History = trim($data->Family_History);
	$Notable_Events = trim($data->Notable_Events);
	$Life_Story = trim($data->Life_Story);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "UPDATE deities SET 
Tags = '$Tags',Name = '$Name',Description = '$Description',Other_Names = '$Other_Names',Universe = '$Universe',Height = '$Height',Physical_Description = '$Physical_Description',Weight = '$Weight',Children = '$Children',Parents = '$Parents',Partners = '$Partners',Siblings = '$Siblings',Floras = '$Floras',Relics = '$Relics',Religions = '$Religions',Creatures = '$Creatures',Elements = '$Elements',Symbols = '$Symbols',Abilities = '$Abilities',Conditions = '$Conditions',Strengths = '$Strengths',Weaknesses = '$Weaknesses',Human_Interaction = '$Human_Interaction',Related_towns = '$Related_towns',Related_races = '$Related_races',Related_landmarks = '$Related_landmarks',Prayers = '$Prayers',Rituals = '$Rituals',Traditions = '$Traditions',Family_History = '$Family_History',Notable_Events = '$Notable_Events',Life_Story = '$Life_Story',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$Name = trim($data->Name);
	$Other_Names = trim($data->Other_Names);
	$Description = trim($data->Description);
	$Order = trim($data->Order);
	$Related_flora = trim($data->Related_flora);
	$Genus = trim($data->Genus);
	$Family = trim($data->Family);
	$Size = trim($data->Size);
	$Smell = trim($data->Smell);
	$Taste = trim($data->Taste);
	$Colorings = trim($data->Colorings);
	$Fruits = trim($data->Fruits);
	$Magical_effects = trim($data->Magical_effects);
	$Material_uses = trim($data->Material_uses);
	$Medicinal_purposes = trim($data->Medicinal_purposes);
	$Berries = trim($data->Berries);
	$Nuts = trim($data->Nuts);
	$Seeds = trim($data->Seeds);
	$Seasonality = trim($data->Seasonality);
	$Locations = trim($data->Locations);
	$Reproduction = trim($data->Reproduction);
	$Eaten_by = trim($data->Eaten_by);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO floras(Tags,Universe,Name,Other_Names,Description,Order,Related_flora,Genus,Family,Size,Smell,Taste,Colorings,Fruits,Magical_effects,Material_uses,Medicinal_purposes,Berries,Nuts,Seeds,Seasonality,Locations,Reproduction,Eaten_by,Notes,Private_Notes) 
VALUES('$Tags','$Universe','$Name','$Other_Names','$Description','$Order','$Related_flora','$Genus','$Family','$Size','$Smell','$Taste','$Colorings','$Fruits','$Magical_effects','$Material_uses','$Medicinal_purposes','$Berries','$Nuts','$Seeds','$Seasonality','$Locations','$Reproduction','$Eaten_by','$Notes','$Private_Notes')"; 


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

	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$Name = trim($data->Name);
	$Other_Names = trim($data->Other_Names);
	$Description = trim($data->Description);
	$Order = trim($data->Order);
	$Related_flora = trim($data->Related_flora);
	$Genus = trim($data->Genus);
	$Family = trim($data->Family);
	$Size = trim($data->Size);
	$Smell = trim($data->Smell);
	$Taste = trim($data->Taste);
	$Colorings = trim($data->Colorings);
	$Fruits = trim($data->Fruits);
	$Magical_effects = trim($data->Magical_effects);
	$Material_uses = trim($data->Material_uses);
	$Medicinal_purposes = trim($data->Medicinal_purposes);
	$Berries = trim($data->Berries);
	$Nuts = trim($data->Nuts);
	$Seeds = trim($data->Seeds);
	$Seasonality = trim($data->Seasonality);
	$Locations = trim($data->Locations);
	$Reproduction = trim($data->Reproduction);
	$Eaten_by = trim($data->Eaten_by);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE floras SET 
Tags = '$Tags',Universe = '$Universe',Name = '$Name',Other_Names = '$Other_Names',Description = '$Description',Order = '$Order',Related_flora = '$Related_flora',Genus = '$Genus',Family = '$Family',Size = '$Size',Smell = '$Smell',Taste = '$Taste',Colorings = '$Colorings',Fruits = '$Fruits',Magical_effects = '$Magical_effects',Material_uses = '$Material_uses',Medicinal_purposes = '$Medicinal_purposes',Berries = '$Berries',Nuts = '$Nuts',Seeds = '$Seeds',Seasonality = '$Seasonality',Locations = '$Locations',Reproduction = '$Reproduction',Eaten_by = '$Eaten_by',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	$Type_of_food = trim($data->Type_of_food);
	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Color = trim($data->Color);
	$Size = trim($data->Size);
	$Variations = trim($data->Variations);
	$Smell = trim($data->Smell);
	$Ingredients = trim($data->Ingredients);
	$Preparation = trim($data->Preparation);
	$Cooking_method = trim($data->Cooking_method);
	$Spices = trim($data->Spices);
	$Yield = trim($data->Yield);
	$Shelf_life = trim($data->Shelf_life);
	$Rarity = trim($data->Rarity);
	$Sold_by = trim($data->Sold_by);
	$Cost = trim($data->Cost);
	$Flavor = trim($data->Flavor);
	$Meal = trim($data->Meal);
	$Serving = trim($data->Serving);
	$Utensils_needed = trim($data->Utensils_needed);
	$Texture = trim($data->Texture);
	$Scent = trim($data->Scent);
	$Side_effects = trim($data->Side_effects);
	$Nutrition = trim($data->Nutrition);
	$Conditions = trim($data->Conditions);
	$Reputation = trim($data->Reputation);
	$Place_of_origin = trim($data->Place_of_origin);
	$Origin_story = trim($data->Origin_story);
	$Traditions = trim($data->Traditions);
	$Symbolisms = trim($data->Symbolisms);
	$Related_foods = trim($data->Related_foods);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO foods(Type_of_food,Other_Names,Universe,Tags,Name,Description,Color,Size,Variations,Smell,Ingredients,Preparation,Cooking_method,Spices,Yield,Shelf_life,Rarity,Sold_by,Cost,Flavor,Meal,Serving,Utensils_needed,Texture,Scent,Side_effects,Nutrition,Conditions,Reputation,Place_of_origin,Origin_story,Traditions,Symbolisms,Related_foods,Notes,Private_Notes) 
VALUES('$Type_of_food','$Other_Names','$Universe','$Tags','$Name','$Description','$Color','$Size','$Variations','$Smell','$Ingredients','$Preparation','$Cooking_method','$Spices','$Yield','$Shelf_life','$Rarity','$Sold_by','$Cost','$Flavor','$Meal','$Serving','$Utensils_needed','$Texture','$Scent','$Side_effects','$Nutrition','$Conditions','$Reputation','$Place_of_origin','$Origin_story','$Traditions','$Symbolisms','$Related_foods','$Notes','$Private_Notes')"; 


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

	$Type_of_food = trim($data->Type_of_food);
	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Color = trim($data->Color);
	$Size = trim($data->Size);
	$Variations = trim($data->Variations);
	$Smell = trim($data->Smell);
	$Ingredients = trim($data->Ingredients);
	$Preparation = trim($data->Preparation);
	$Cooking_method = trim($data->Cooking_method);
	$Spices = trim($data->Spices);
	$Yield = trim($data->Yield);
	$Shelf_life = trim($data->Shelf_life);
	$Rarity = trim($data->Rarity);
	$Sold_by = trim($data->Sold_by);
	$Cost = trim($data->Cost);
	$Flavor = trim($data->Flavor);
	$Meal = trim($data->Meal);
	$Serving = trim($data->Serving);
	$Utensils_needed = trim($data->Utensils_needed);
	$Texture = trim($data->Texture);
	$Scent = trim($data->Scent);
	$Side_effects = trim($data->Side_effects);
	$Nutrition = trim($data->Nutrition);
	$Conditions = trim($data->Conditions);
	$Reputation = trim($data->Reputation);
	$Place_of_origin = trim($data->Place_of_origin);
	$Origin_story = trim($data->Origin_story);
	$Traditions = trim($data->Traditions);
	$Symbolisms = trim($data->Symbolisms);
	$Related_foods = trim($data->Related_foods);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE foods SET 
Type_of_food = '$Type_of_food',Other_Names = '$Other_Names',Universe = '$Universe',Tags = '$Tags',Name = '$Name',Description = '$Description',Color = '$Color',Size = '$Size',Variations = '$Variations',Smell = '$Smell',Ingredients = '$Ingredients',Preparation = '$Preparation',Cooking_method = '$Cooking_method',Spices = '$Spices',Yield = '$Yield',Shelf_life = '$Shelf_life',Rarity = '$Rarity',Sold_by = '$Sold_by',Cost = '$Cost',Flavor = '$Flavor',Meal = '$Meal',Serving = '$Serving',Utensils_needed = '$Utensils_needed',Texture = '$Texture',Scent = '$Scent',Side_effects = '$Side_effects',Nutrition = '$Nutrition',Conditions = '$Conditions',Reputation = '$Reputation',Place_of_origin = '$Place_of_origin',Origin_story = '$Origin_story',Traditions = '$Traditions',Symbolisms = '$Symbolisms',Related_foods = '$Related_foods',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Checks_And_Balances = trim($data->Checks_And_Balances);
	$Jobs = trim($data->Jobs);
	$Type_Of_Government = trim($data->Type_Of_Government);
	$Power_Structure = trim($data->Power_Structure);
	$Power_Source = trim($data->Power_Source);
	$Privacy_Ideologies = trim($data->Privacy_Ideologies);
	$Sociopolitical = trim($data->Sociopolitical);
	$Socioeconomical = trim($data->Socioeconomical);
	$Geocultural = trim($data->Geocultural);
	$Laws = trim($data->Laws);
	$Immigration = trim($data->Immigration);
	$Term_Lengths = trim($data->Term_Lengths);
	$Electoral_Process = trim($data->Electoral_Process);
	$Criminal_System = trim($data->Criminal_System);
	$International_Relations = trim($data->International_Relations);
	$Civilian_Life = trim($data->Civilian_Life);
	$Approval_Ratings = trim($data->Approval_Ratings);
	$Space_Program = trim($data->Space_Program);
	$Leaders = trim($data->Leaders);
	$Groups = trim($data->Groups);
	$Political_figures = trim($data->Political_figures);
	$Military = trim($data->Military);
	$Navy = trim($data->Navy);
	$Airforce = trim($data->Airforce);
	$Notable_Wars = trim($data->Notable_Wars);
	$Founding_Story = trim($data->Founding_Story);
	$Flag_Design_Story = trim($data->Flag_Design_Story);
	$Holidays = trim($data->Holidays);
	$Vehicles = trim($data->Vehicles);
	$Items = trim($data->Items);
	$Technologies = trim($data->Technologies);
	$Creatures = trim($data->Creatures);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO governments(Name,Description,Universe,Tags,Checks_And_Balances,Jobs,Type_Of_Government,Power_Structure,Power_Source,Privacy_Ideologies,Sociopolitical,Socioeconomical,Geocultural,Laws,Immigration,Term_Lengths,Electoral_Process,Criminal_System,International_Relations,Civilian_Life,Approval_Ratings,Space_Program,Leaders,Groups,Political_figures,Military,Navy,Airforce,Notable_Wars,Founding_Story,Flag_Design_Story,Holidays,Vehicles,Items,Technologies,Creatures,Notes,Private_Notes) 
VALUES('$Name','$Description','$Universe','$Tags','$Checks_And_Balances','$Jobs','$Type_Of_Government','$Power_Structure','$Power_Source','$Privacy_Ideologies','$Sociopolitical','$Socioeconomical','$Geocultural','$Laws','$Immigration','$Term_Lengths','$Electoral_Process','$Criminal_System','$International_Relations','$Civilian_Life','$Approval_Ratings','$Space_Program','$Leaders','$Groups','$Political_figures','$Military','$Navy','$Airforce','$Notable_Wars','$Founding_Story','$Flag_Design_Story','$Holidays','$Vehicles','$Items','$Technologies','$Creatures','$Notes','$Private_Notes')"; 


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

	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Checks_And_Balances = trim($data->Checks_And_Balances);
	$Jobs = trim($data->Jobs);
	$Type_Of_Government = trim($data->Type_Of_Government);
	$Power_Structure = trim($data->Power_Structure);
	$Power_Source = trim($data->Power_Source);
	$Privacy_Ideologies = trim($data->Privacy_Ideologies);
	$Sociopolitical = trim($data->Sociopolitical);
	$Socioeconomical = trim($data->Socioeconomical);
	$Geocultural = trim($data->Geocultural);
	$Laws = trim($data->Laws);
	$Immigration = trim($data->Immigration);
	$Term_Lengths = trim($data->Term_Lengths);
	$Electoral_Process = trim($data->Electoral_Process);
	$Criminal_System = trim($data->Criminal_System);
	$International_Relations = trim($data->International_Relations);
	$Civilian_Life = trim($data->Civilian_Life);
	$Approval_Ratings = trim($data->Approval_Ratings);
	$Space_Program = trim($data->Space_Program);
	$Leaders = trim($data->Leaders);
	$Groups = trim($data->Groups);
	$Political_figures = trim($data->Political_figures);
	$Military = trim($data->Military);
	$Navy = trim($data->Navy);
	$Airforce = trim($data->Airforce);
	$Notable_Wars = trim($data->Notable_Wars);
	$Founding_Story = trim($data->Founding_Story);
	$Flag_Design_Story = trim($data->Flag_Design_Story);
	$Holidays = trim($data->Holidays);
	$Vehicles = trim($data->Vehicles);
	$Items = trim($data->Items);
	$Technologies = trim($data->Technologies);
	$Creatures = trim($data->Creatures);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE governments SET 
Name = '$Name',Description = '$Description',Universe = '$Universe',Tags = '$Tags',Checks_And_Balances = '$Checks_And_Balances',Jobs = '$Jobs',Type_Of_Government = '$Type_Of_Government',Power_Structure = '$Power_Structure',Power_Source = '$Power_Source',Privacy_Ideologies = '$Privacy_Ideologies',Sociopolitical = '$Sociopolitical',Socioeconomical = '$Socioeconomical',Geocultural = '$Geocultural',Laws = '$Laws',Immigration = '$Immigration',Term_Lengths = '$Term_Lengths',Electoral_Process = '$Electoral_Process',Criminal_System = '$Criminal_System',International_Relations = '$International_Relations',Civilian_Life = '$Civilian_Life',Approval_Ratings = '$Approval_Ratings',Space_Program = '$Space_Program',Leaders = '$Leaders',Groups = '$Groups',Political_figures = '$Political_figures',Military = '$Military',Navy = '$Navy',Airforce = '$Airforce',Notable_Wars = '$Notable_Wars',Founding_Story = '$Founding_Story',Flag_Design_Story = '$Flag_Design_Story',Holidays = '$Holidays',Vehicles = '$Vehicles',Items = '$Items',Technologies = '$Technologies',Creatures = '$Creatures',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$Other_Names = trim($data->Other_Names);
	$Description = trim($data->Description);
	$Name = trim($data->Name);
	$Subgroups = trim($data->Subgroups);
	$Supergroups = trim($data->Supergroups);
	$Sistergroups = trim($data->Sistergroups);
	$Organization_structure = trim($data->Organization_structure);
	$Leaders = trim($data->Leaders);
	$Creatures = trim($data->Creatures);
	$Members = trim($data->Members);
	$Offices = trim($data->Offices);
	$Locations = trim($data->Locations);
	$Headquarters = trim($data->Headquarters);
	$Motivations = trim($data->Motivations);
	$Traditions = trim($data->Traditions);
	$Risks = trim($data->Risks);
	$Obstacles = trim($data->Obstacles);
	$Goals = trim($data->Goals);
	$Clients = trim($data->Clients);
	$Allies = trim($data->Allies);
	$Enemies = trim($data->Enemies);
	$Rivals = trim($data->Rivals);
	$Suppliers = trim($data->Suppliers);
	$Inventory = trim($data->Inventory);
	$Equipment = trim($data->Equipment);
	$Key_items = trim($data->Key_items);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);


    $sql = "INSERT INTO groups(Tags,Universe,Other_Names,Description,Name,Subgroups,Supergroups,Sistergroups,Organization_structure,Leaders,Creatures,Members,Offices,Locations,Headquarters,Motivations,Traditions,Risks,Obstacles,Goals,Clients,Allies,Enemies,Rivals,Suppliers,Inventory,Equipment,Key_items,Notes,Private_notes) 
VALUES('$Tags','$Universe','$Other_Names','$Description','$Name','$Subgroups','$Supergroups','$Sistergroups','$Organization_structure','$Leaders','$Creatures','$Members','$Offices','$Locations','$Headquarters','$Motivations','$Traditions','$Risks','$Obstacles','$Goals','$Clients','$Allies','$Enemies','$Rivals','$Suppliers','$Inventory','$Equipment','$Key_items','$Notes','$Private_notes')"; 


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

	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$Other_Names = trim($data->Other_Names);
	$Description = trim($data->Description);
	$Name = trim($data->Name);
	$Subgroups = trim($data->Subgroups);
	$Supergroups = trim($data->Supergroups);
	$Sistergroups = trim($data->Sistergroups);
	$Organization_structure = trim($data->Organization_structure);
	$Leaders = trim($data->Leaders);
	$Creatures = trim($data->Creatures);
	$Members = trim($data->Members);
	$Offices = trim($data->Offices);
	$Locations = trim($data->Locations);
	$Headquarters = trim($data->Headquarters);
	$Motivations = trim($data->Motivations);
	$Traditions = trim($data->Traditions);
	$Risks = trim($data->Risks);
	$Obstacles = trim($data->Obstacles);
	$Goals = trim($data->Goals);
	$Clients = trim($data->Clients);
	$Allies = trim($data->Allies);
	$Enemies = trim($data->Enemies);
	$Rivals = trim($data->Rivals);
	$Suppliers = trim($data->Suppliers);
	$Inventory = trim($data->Inventory);
	$Equipment = trim($data->Equipment);
	$Key_items = trim($data->Key_items);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);


    $sql = "UPDATE groups SET 
Tags = '$Tags',Universe = '$Universe',Other_Names = '$Other_Names',Description = '$Description',Name = '$Name',Subgroups = '$Subgroups',Supergroups = '$Supergroups',Sistergroups = '$Sistergroups',Organization_structure = '$Organization_structure',Leaders = '$Leaders',Creatures = '$Creatures',Members = '$Members',Offices = '$Offices',Locations = '$Locations',Headquarters = '$Headquarters',Motivations = '$Motivations',Traditions = '$Traditions',Risks = '$Risks',Obstacles = '$Obstacles',Goals = '$Goals',Clients = '$Clients',Allies = '$Allies',Enemies = '$Enemies',Rivals = '$Rivals',Suppliers = '$Suppliers',Inventory = '$Inventory',Equipment = '$Equipment',Key_items = '$Key_items',Notes = '$Notes',Private_notes = '$Private_notes'    WHERE id = $id"; 

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

	$Name = trim($data->Name);
	$Item_Type = trim($data->Item_Type);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Tags = trim($data->Tags);
	$Weight = trim($data->Weight);
	$Materials = trim($data->Materials);
	$Past_Owners = trim($data->Past_Owners);
	$Year_it_was_made = trim($data->Year_it_was_made);
	$Makers = trim($data->Makers);
	$Current_Owners = trim($data->Current_Owners);
	$Original_Owners = trim($data->Original_Owners);
	$Magical_effects = trim($data->Magical_effects);
	$Magic = trim($data->Magic);
	$Technical_effects = trim($data->Technical_effects);
	$Technology = trim($data->Technology);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "INSERT INTO items(Name,Item_Type,Universe,Description,Tags,Weight,Materials,Past_Owners,Year_it_was_made,Makers,Current_Owners,Original_Owners,Magical_effects,Magic,Technical_effects,Technology,Private_Notes,Notes) 
VALUES('$Name','$Item_Type','$Universe','$Description','$Tags','$Weight','$Materials','$Past_Owners','$Year_it_was_made','$Makers','$Current_Owners','$Original_Owners','$Magical_effects','$Magic','$Technical_effects','$Technology','$Private_Notes','$Notes')"; 


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

	$Name = trim($data->Name);
	$Item_Type = trim($data->Item_Type);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Tags = trim($data->Tags);
	$Weight = trim($data->Weight);
	$Materials = trim($data->Materials);
	$Past_Owners = trim($data->Past_Owners);
	$Year_it_was_made = trim($data->Year_it_was_made);
	$Makers = trim($data->Makers);
	$Current_Owners = trim($data->Current_Owners);
	$Original_Owners = trim($data->Original_Owners);
	$Magical_effects = trim($data->Magical_effects);
	$Magic = trim($data->Magic);
	$Technical_effects = trim($data->Technical_effects);
	$Technology = trim($data->Technology);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "UPDATE items SET 
Name = '$Name',Item_Type = '$Item_Type',Universe = '$Universe',Description = '$Description',Tags = '$Tags',Weight = '$Weight',Materials = '$Materials',Past_Owners = '$Past_Owners',Year_it_was_made = '$Year_it_was_made',Makers = '$Makers',Current_Owners = '$Current_Owners',Original_Owners = '$Original_Owners',Magical_effects = '$Magical_effects',Magic = '$Magic',Technical_effects = '$Technical_effects',Technology = '$Technology',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

	$Name = trim($data->Name);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Type_of_job = trim($data->Type_of_job);
	$Alternate_names = trim($data->Alternate_names);
	$Tags = trim($data->Tags);
	$Experience = trim($data->Experience);
	$Education = trim($data->Education);
	$Work_hours = trim($data->Work_hours);
	$Vehicles = trim($data->Vehicles);
	$Training = trim($data->Training);
	$Long_term_risks = trim($data->Long_term_risks);
	$Occupational_hazards = trim($data->Occupational_hazards);
	$Pay_rate = trim($data->Pay_rate);
	$Time_off = trim($data->Time_off);
	$Similar_jobs = trim($data->Similar_jobs);
	$Promotions = trim($data->Promotions);
	$Specializations = trim($data->Specializations);
	$Field = trim($data->Field);
	$Ranks = trim($data->Ranks);
	$Traditions = trim($data->Traditions);
	$Job_origin = trim($data->Job_origin);
	$Initial_goal = trim($data->Initial_goal);
	$Notable_figures = trim($data->Notable_figures);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO jobs(Name,Universe,Description,Type_of_job,Alternate_names,Tags,Experience,Education,Work_hours,Vehicles,Training,Long_term_risks,Occupational_hazards,Pay_rate,Time_off,Similar_jobs,Promotions,Specializations,Field,Ranks,Traditions,Job_origin,Initial_goal,Notable_figures,Notes,Private_Notes) 
VALUES('$Name','$Universe','$Description','$Type_of_job','$Alternate_names','$Tags','$Experience','$Education','$Work_hours','$Vehicles','$Training','$Long_term_risks','$Occupational_hazards','$Pay_rate','$Time_off','$Similar_jobs','$Promotions','$Specializations','$Field','$Ranks','$Traditions','$Job_origin','$Initial_goal','$Notable_figures','$Notes','$Private_Notes')"; 


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

	$Name = trim($data->Name);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Type_of_job = trim($data->Type_of_job);
	$Alternate_names = trim($data->Alternate_names);
	$Tags = trim($data->Tags);
	$Experience = trim($data->Experience);
	$Education = trim($data->Education);
	$Work_hours = trim($data->Work_hours);
	$Vehicles = trim($data->Vehicles);
	$Training = trim($data->Training);
	$Long_term_risks = trim($data->Long_term_risks);
	$Occupational_hazards = trim($data->Occupational_hazards);
	$Pay_rate = trim($data->Pay_rate);
	$Time_off = trim($data->Time_off);
	$Similar_jobs = trim($data->Similar_jobs);
	$Promotions = trim($data->Promotions);
	$Specializations = trim($data->Specializations);
	$Field = trim($data->Field);
	$Ranks = trim($data->Ranks);
	$Traditions = trim($data->Traditions);
	$Job_origin = trim($data->Job_origin);
	$Initial_goal = trim($data->Initial_goal);
	$Notable_figures = trim($data->Notable_figures);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE jobs SET 
Name = '$Name',Universe = '$Universe',Description = '$Description',Type_of_job = '$Type_of_job',Alternate_names = '$Alternate_names',Tags = '$Tags',Experience = '$Experience',Education = '$Education',Work_hours = '$Work_hours',Vehicles = '$Vehicles',Training = '$Training',Long_term_risks = '$Long_term_risks',Occupational_hazards = '$Occupational_hazards',Pay_rate = '$Pay_rate',Time_off = '$Time_off',Similar_jobs = '$Similar_jobs',Promotions = '$Promotions',Specializations = '$Specializations',Field = '$Field',Ranks = '$Ranks',Traditions = '$Traditions',Job_origin = '$Job_origin',Initial_goal = '$Initial_goal',Notable_figures = '$Notable_figures',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	$Name = trim($data->Name);
	$Tags = trim($data->Tags);
	$Description = trim($data->Description);
	$Other_Names = trim($data->Other_Names);
	$Type_of_landmark = trim($data->Type_of_landmark);
	$Universe = trim($data->Universe);
	$Country = trim($data->Country);
	$Nearby_towns = trim($data->Nearby_towns);
	$Size = trim($data->Size);
	$Colors = trim($data->Colors);
	$Materials = trim($data->Materials);
	$Creatures = trim($data->Creatures);
	$Flora = trim($data->Flora);
	$Creation_story = trim($data->Creation_story);
	$Established_year = trim($data->Established_year);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO landmarks(Name,Tags,Description,Other_Names,Type_of_landmark,Universe,Country,Nearby_towns,Size,Colors,Materials,Creatures,Flora,Creation_story,Established_year,Notes,Private_Notes) 
VALUES('$Name','$Tags','$Description','$Other_Names','$Type_of_landmark','$Universe','$Country','$Nearby_towns','$Size','$Colors','$Materials','$Creatures','$Flora','$Creation_story','$Established_year','$Notes','$Private_Notes')"; 


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

	$Name = trim($data->Name);
	$Tags = trim($data->Tags);
	$Description = trim($data->Description);
	$Other_Names = trim($data->Other_Names);
	$Type_of_landmark = trim($data->Type_of_landmark);
	$Universe = trim($data->Universe);
	$Country = trim($data->Country);
	$Nearby_towns = trim($data->Nearby_towns);
	$Size = trim($data->Size);
	$Colors = trim($data->Colors);
	$Materials = trim($data->Materials);
	$Creatures = trim($data->Creatures);
	$Flora = trim($data->Flora);
	$Creation_story = trim($data->Creation_story);
	$Established_year = trim($data->Established_year);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE landmarks SET 
Name = '$Name',Tags = '$Tags',Description = '$Description',Other_Names = '$Other_Names',Type_of_landmark = '$Type_of_landmark',Universe = '$Universe',Country = '$Country',Nearby_towns = '$Nearby_towns',Size = '$Size',Colors = '$Colors',Materials = '$Materials',Creatures = '$Creatures',Flora = '$Flora',Creation_story = '$Creation_story',Established_year = '$Established_year',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Other_Names = trim($data->Other_Names);
	$Name = trim($data->Name);
	$Typology = trim($data->Typology);
	$Dialectical_information = trim($data->Dialectical_information);
	$Register = trim($data->Register);
	$History = trim($data->History);
	$Evolution = trim($data->Evolution);
	$Gestures = trim($data->Gestures);
	$Phonology = trim($data->Phonology);
	$Grammar = trim($data->Grammar);
	$Please = trim($data->Please);
	$Trade = trim($data->Trade);
	$Family = trim($data->Family);
	$Body_parts = trim($data->Body_parts);
	$No_words = trim($data->No_words);
	$Yes_words = trim($data->Yes_words);
	$Sorry = trim($data->Sorry);
	$You_are_welcome = trim($data->You_are_welcome);
	$Thank_you = trim($data->Thank_you);
	$Goodbyes = trim($data->Goodbyes);
	$Greetings = trim($data->Greetings);
	$Pronouns = trim($data->Pronouns);
	$Numbers = trim($data->Numbers);
	$Quantifiers = trim($data->Quantifiers);
	$Determiners = trim($data->Determiners);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);


    $sql = "INSERT INTO languages(Universe,Tags,Other_Names,Name,Typology,Dialectical_information,Register,History,Evolution,Gestures,Phonology,Grammar,Please,Trade,Family,Body_parts,No_words,Yes_words,Sorry,You_are_welcome,Thank_you,Goodbyes,Greetings,Pronouns,Numbers,Quantifiers,Determiners,Notes,Private_notes) 
VALUES('$Universe','$Tags','$Other_Names','$Name','$Typology','$Dialectical_information','$Register','$History','$Evolution','$Gestures','$Phonology','$Grammar','$Please','$Trade','$Family','$Body_parts','$No_words','$Yes_words','$Sorry','$You_are_welcome','$Thank_you','$Goodbyes','$Greetings','$Pronouns','$Numbers','$Quantifiers','$Determiners','$Notes','$Private_notes')"; 


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

	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Other_Names = trim($data->Other_Names);
	$Name = trim($data->Name);
	$Typology = trim($data->Typology);
	$Dialectical_information = trim($data->Dialectical_information);
	$Register = trim($data->Register);
	$History = trim($data->History);
	$Evolution = trim($data->Evolution);
	$Gestures = trim($data->Gestures);
	$Phonology = trim($data->Phonology);
	$Grammar = trim($data->Grammar);
	$Please = trim($data->Please);
	$Trade = trim($data->Trade);
	$Family = trim($data->Family);
	$Body_parts = trim($data->Body_parts);
	$No_words = trim($data->No_words);
	$Yes_words = trim($data->Yes_words);
	$Sorry = trim($data->Sorry);
	$You_are_welcome = trim($data->You_are_welcome);
	$Thank_you = trim($data->Thank_you);
	$Goodbyes = trim($data->Goodbyes);
	$Greetings = trim($data->Greetings);
	$Pronouns = trim($data->Pronouns);
	$Numbers = trim($data->Numbers);
	$Quantifiers = trim($data->Quantifiers);
	$Determiners = trim($data->Determiners);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);


    $sql = "UPDATE languages SET 
Universe = '$Universe',Tags = '$Tags',Other_Names = '$Other_Names',Name = '$Name',Typology = '$Typology',Dialectical_information = '$Dialectical_information',Register = '$Register',History = '$History',Evolution = '$Evolution',Gestures = '$Gestures',Phonology = '$Phonology',Grammar = '$Grammar',Please = '$Please',Trade = '$Trade',Family = '$Family',Body_parts = '$Body_parts',No_words = '$No_words',Yes_words = '$Yes_words',Sorry = '$Sorry',You_are_welcome = '$You_are_welcome',Thank_you = '$Thank_you',Goodbyes = '$Goodbyes',Greetings = '$Greetings',Pronouns = '$Pronouns',Numbers = '$Numbers',Quantifiers = '$Quantifiers',Determiners = '$Determiners',Notes = '$Notes',Private_notes = '$Private_notes'    WHERE id = $id"; 

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

	$Description = trim($data->Description);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Type = trim($data->Type);
	$Leaders = trim($data->Leaders);
	$Language = trim($data->Language);
	$Population = trim($data->Population);
	$Currency = trim($data->Currency);
	$Motto = trim($data->Motto);
	$Sports = trim($data->Sports);
	$Laws = trim($data->Laws);
	$Spoken_Languages = trim($data->Spoken_Languages);
	$Largest_cities = trim($data->Largest_cities);
	$Notable_cities = trim($data->Notable_cities);
	$Capital_cities = trim($data->Capital_cities);
	$Landmarks = trim($data->Landmarks);
	$Area = trim($data->Area);
	$Crops = trim($data->Crops);
	$Located_at = trim($data->Located_at);
	$Climate = trim($data->Climate);
	$Notable_Wars = trim($data->Notable_Wars);
	$Founding_Story = trim($data->Founding_Story);
	$Established_Year = trim($data->Established_Year);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO locations(Description,Universe,Tags,Name,Type,Leaders,Language,Population,Currency,Motto,Sports,Laws,Spoken_Languages,Largest_cities,Notable_cities,Capital_cities,Landmarks,Area,Crops,Located_at,Climate,Notable_Wars,Founding_Story,Established_Year,Notes,Private_Notes) 
VALUES('$Description','$Universe','$Tags','$Name','$Type','$Leaders','$Language','$Population','$Currency','$Motto','$Sports','$Laws','$Spoken_Languages','$Largest_cities','$Notable_cities','$Capital_cities','$Landmarks','$Area','$Crops','$Located_at','$Climate','$Notable_Wars','$Founding_Story','$Established_Year','$Notes','$Private_Notes')"; 


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

	$Description = trim($data->Description);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Type = trim($data->Type);
	$Leaders = trim($data->Leaders);
	$Language = trim($data->Language);
	$Population = trim($data->Population);
	$Currency = trim($data->Currency);
	$Motto = trim($data->Motto);
	$Sports = trim($data->Sports);
	$Laws = trim($data->Laws);
	$Spoken_Languages = trim($data->Spoken_Languages);
	$Largest_cities = trim($data->Largest_cities);
	$Notable_cities = trim($data->Notable_cities);
	$Capital_cities = trim($data->Capital_cities);
	$Landmarks = trim($data->Landmarks);
	$Area = trim($data->Area);
	$Crops = trim($data->Crops);
	$Located_at = trim($data->Located_at);
	$Climate = trim($data->Climate);
	$Notable_Wars = trim($data->Notable_Wars);
	$Founding_Story = trim($data->Founding_Story);
	$Established_Year = trim($data->Established_Year);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE locations SET 
Description = '$Description',Universe = '$Universe',Tags = '$Tags',Name = '$Name',Type = '$Type',Leaders = '$Leaders',Language = '$Language',Population = '$Population',Currency = '$Currency',Motto = '$Motto',Sports = '$Sports',Laws = '$Laws',Spoken_Languages = '$Spoken_Languages',Largest_cities = '$Largest_cities',Notable_cities = '$Notable_cities',Capital_cities = '$Capital_cities',Landmarks = '$Landmarks',Area = '$Area',Crops = '$Crops',Located_at = '$Located_at',Climate = '$Climate',Notable_Wars = '$Notable_Wars',Founding_Story = '$Founding_Story',Established_Year = '$Established_Year',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Summary = trim($data->Summary);
	$Type = trim($data->Type);
	$Universe = trim($data->Universe);
	$Tone = trim($data->Tone);
	$Full_text = trim($data->Full_text);
	$Dialect = trim($data->Dialect);
	$Structure = trim($data->Structure);
	$Genre = trim($data->Genre);
	$Buildings = trim($data->Buildings);
	$Time_period = trim($data->Time_period);
	$Planets = trim($data->Planets);
	$Continents = trim($data->Continents);
	$Countries = trim($data->Countries);
	$Landmarks = trim($data->Landmarks);
	$Towns = trim($data->Towns);
	$Schools = trim($data->Schools);
	$Conditions = trim($data->Conditions);
	$Sports = trim($data->Sports);
	$Foods = trim($data->Foods);
	$Traditions = trim($data->Traditions);
	$Groups = trim($data->Groups);
	$Governments = trim($data->Governments);
	$Magic = trim($data->Magic);
	$Religions = trim($data->Religions);
	$Races = trim($data->Races);
	$Vehicles = trim($data->Vehicles);
	$Technologies = trim($data->Technologies);
	$Jobs = trim($data->Jobs);
	$Floras = trim($data->Floras);
	$Creatures = trim($data->Creatures);
	$Deities = trim($data->Deities);
	$Characters = trim($data->Characters);
	$Subjects = trim($data->Subjects);
	$Believers = trim($data->Believers);
	$Hoaxes = trim($data->Hoaxes);
	$True_parts = trim($data->True_parts);
	$False_parts = trim($data->False_parts);
	$Believability = trim($data->Believability);
	$Morals = trim($data->Morals);
	$Symbolisms = trim($data->Symbolisms);
	$Motivations = trim($data->Motivations);
	$Created_phrases = trim($data->Created_phrases);
	$Reception = trim($data->Reception);
	$Criticism = trim($data->Criticism);
	$Media_adaptations = trim($data->Media_adaptations);
	$Interpretations = trim($data->Interpretations);
	$Impact = trim($data->Impact);
	$Created_traditions = trim($data->Created_traditions);
	$Influence_on_modern_times = trim($data->Influence_on_modern_times);
	$Original_telling = trim($data->Original_telling);
	$Inspirations = trim($data->Inspirations);
	$Original_author = trim($data->Original_author);
	$Original_languages = trim($data->Original_languages);
	$Source = trim($data->Source);
	$Date_recorded = trim($data->Date_recorded);
	$Background_information = trim($data->Background_information);
	$Propagation_method = trim($data->Propagation_method);
	$Historical_context = trim($data->Historical_context);
	$Important_translations = trim($data->Important_translations);
	$Evolution_over_time = trim($data->Evolution_over_time);
	$Geographical_variations = trim($data->Geographical_variations);
	$Related_lores = trim($data->Related_lores);
	$Variations = trim($data->Variations);
	$Translation_variations = trim($data->Translation_variations);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "INSERT INTO lores(Tags,Name,Summary,Type,Universe,Tone,Full_text,Dialect,Structure,Genre,Buildings,Time_period,Planets,Continents,Countries,Landmarks,Towns,Schools,Conditions,Sports,Foods,Traditions,Groups,Governments,Magic,Religions,Races,Vehicles,Technologies,Jobs,Floras,Creatures,Deities,Characters,Subjects,Believers,Hoaxes,True_parts,False_parts,Believability,Morals,Symbolisms,Motivations,Created_phrases,Reception,Criticism,Media_adaptations,Interpretations,Impact,Created_traditions,Influence_on_modern_times,Original_telling,Inspirations,Original_author,Original_languages,Source,Date_recorded,Background_information,Propagation_method,Historical_context,Important_translations,Evolution_over_time,Geographical_variations,Related_lores,Variations,Translation_variations,Private_Notes,Notes) 
VALUES('$Tags','$Name','$Summary','$Type','$Universe','$Tone','$Full_text','$Dialect','$Structure','$Genre','$Buildings','$Time_period','$Planets','$Continents','$Countries','$Landmarks','$Towns','$Schools','$Conditions','$Sports','$Foods','$Traditions','$Groups','$Governments','$Magic','$Religions','$Races','$Vehicles','$Technologies','$Jobs','$Floras','$Creatures','$Deities','$Characters','$Subjects','$Believers','$Hoaxes','$True_parts','$False_parts','$Believability','$Morals','$Symbolisms','$Motivations','$Created_phrases','$Reception','$Criticism','$Media_adaptations','$Interpretations','$Impact','$Created_traditions','$Influence_on_modern_times','$Original_telling','$Inspirations','$Original_author','$Original_languages','$Source','$Date_recorded','$Background_information','$Propagation_method','$Historical_context','$Important_translations','$Evolution_over_time','$Geographical_variations','$Related_lores','$Variations','$Translation_variations','$Private_Notes','$Notes')"; 


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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Summary = trim($data->Summary);
	$Type = trim($data->Type);
	$Universe = trim($data->Universe);
	$Tone = trim($data->Tone);
	$Full_text = trim($data->Full_text);
	$Dialect = trim($data->Dialect);
	$Structure = trim($data->Structure);
	$Genre = trim($data->Genre);
	$Buildings = trim($data->Buildings);
	$Time_period = trim($data->Time_period);
	$Planets = trim($data->Planets);
	$Continents = trim($data->Continents);
	$Countries = trim($data->Countries);
	$Landmarks = trim($data->Landmarks);
	$Towns = trim($data->Towns);
	$Schools = trim($data->Schools);
	$Conditions = trim($data->Conditions);
	$Sports = trim($data->Sports);
	$Foods = trim($data->Foods);
	$Traditions = trim($data->Traditions);
	$Groups = trim($data->Groups);
	$Governments = trim($data->Governments);
	$Magic = trim($data->Magic);
	$Religions = trim($data->Religions);
	$Races = trim($data->Races);
	$Vehicles = trim($data->Vehicles);
	$Technologies = trim($data->Technologies);
	$Jobs = trim($data->Jobs);
	$Floras = trim($data->Floras);
	$Creatures = trim($data->Creatures);
	$Deities = trim($data->Deities);
	$Characters = trim($data->Characters);
	$Subjects = trim($data->Subjects);
	$Believers = trim($data->Believers);
	$Hoaxes = trim($data->Hoaxes);
	$True_parts = trim($data->True_parts);
	$False_parts = trim($data->False_parts);
	$Believability = trim($data->Believability);
	$Morals = trim($data->Morals);
	$Symbolisms = trim($data->Symbolisms);
	$Motivations = trim($data->Motivations);
	$Created_phrases = trim($data->Created_phrases);
	$Reception = trim($data->Reception);
	$Criticism = trim($data->Criticism);
	$Media_adaptations = trim($data->Media_adaptations);
	$Interpretations = trim($data->Interpretations);
	$Impact = trim($data->Impact);
	$Created_traditions = trim($data->Created_traditions);
	$Influence_on_modern_times = trim($data->Influence_on_modern_times);
	$Original_telling = trim($data->Original_telling);
	$Inspirations = trim($data->Inspirations);
	$Original_author = trim($data->Original_author);
	$Original_languages = trim($data->Original_languages);
	$Source = trim($data->Source);
	$Date_recorded = trim($data->Date_recorded);
	$Background_information = trim($data->Background_information);
	$Propagation_method = trim($data->Propagation_method);
	$Historical_context = trim($data->Historical_context);
	$Important_translations = trim($data->Important_translations);
	$Evolution_over_time = trim($data->Evolution_over_time);
	$Geographical_variations = trim($data->Geographical_variations);
	$Related_lores = trim($data->Related_lores);
	$Variations = trim($data->Variations);
	$Translation_variations = trim($data->Translation_variations);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "UPDATE lores SET 
Tags = '$Tags',Name = '$Name',Summary = '$Summary',Type = '$Type',Universe = '$Universe',Tone = '$Tone',Full_text = '$Full_text',Dialect = '$Dialect',Structure = '$Structure',Genre = '$Genre',Buildings = '$Buildings',Time_period = '$Time_period',Planets = '$Planets',Continents = '$Continents',Countries = '$Countries',Landmarks = '$Landmarks',Towns = '$Towns',Schools = '$Schools',Conditions = '$Conditions',Sports = '$Sports',Foods = '$Foods',Traditions = '$Traditions',Groups = '$Groups',Governments = '$Governments',Magic = '$Magic',Religions = '$Religions',Races = '$Races',Vehicles = '$Vehicles',Technologies = '$Technologies',Jobs = '$Jobs',Floras = '$Floras',Creatures = '$Creatures',Deities = '$Deities',Characters = '$Characters',Subjects = '$Subjects',Believers = '$Believers',Hoaxes = '$Hoaxes',True_parts = '$True_parts',False_parts = '$False_parts',Believability = '$Believability',Morals = '$Morals',Symbolisms = '$Symbolisms',Motivations = '$Motivations',Created_phrases = '$Created_phrases',Reception = '$Reception',Criticism = '$Criticism',Media_adaptations = '$Media_adaptations',Interpretations = '$Interpretations',Impact = '$Impact',Created_traditions = '$Created_traditions',Influence_on_modern_times = '$Influence_on_modern_times',Original_telling = '$Original_telling',Inspirations = '$Inspirations',Original_author = '$Original_author',Original_languages = '$Original_languages',Source = '$Source',Date_recorded = '$Date_recorded',Background_information = '$Background_information',Propagation_method = '$Propagation_method',Historical_context = '$Historical_context',Important_translations = '$Important_translations',Evolution_over_time = '$Evolution_over_time',Geographical_variations = '$Geographical_variations',Related_lores = '$Related_lores',Variations = '$Variations',Translation_variations = '$Translation_variations',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Type_of_magic = trim($data->Type_of_magic);
	$Universe = trim($data->Universe);
	$Effects = trim($data->Effects);
	$Visuals = trim($data->Visuals);
	$Aftereffects = trim($data->Aftereffects);
	$Conditions = trim($data->Conditions);
	$Scale = trim($data->Scale);
	$Negative_effects = trim($data->Negative_effects);
	$Neutral_effects = trim($data->Neutral_effects);
	$Positive_effects = trim($data->Positive_effects);
	$Deities = trim($data->Deities);
	$Element = trim($data->Element);
	$Materials_required = trim($data->Materials_required);
	$Skills_required = trim($data->Skills_required);
	$Education = trim($data->Education);
	$Resource_costs = trim($data->Resource_costs);
	$Limitations = trim($data->Limitations);
	$Private_notes = trim($data->Private_notes);
	$Notes = trim($data->Notes);


    $sql = "INSERT INTO magics(Tags,Name,Description,Type_of_magic,Universe,Effects,Visuals,Aftereffects,Conditions,Scale,Negative_effects,Neutral_effects,Positive_effects,Deities,Element,Materials_required,Skills_required,Education,Resource_costs,Limitations,Private_notes,Notes) 
VALUES('$Tags','$Name','$Description','$Type_of_magic','$Universe','$Effects','$Visuals','$Aftereffects','$Conditions','$Scale','$Negative_effects','$Neutral_effects','$Positive_effects','$Deities','$Element','$Materials_required','$Skills_required','$Education','$Resource_costs','$Limitations','$Private_notes','$Notes')"; 


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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Type_of_magic = trim($data->Type_of_magic);
	$Universe = trim($data->Universe);
	$Effects = trim($data->Effects);
	$Visuals = trim($data->Visuals);
	$Aftereffects = trim($data->Aftereffects);
	$Conditions = trim($data->Conditions);
	$Scale = trim($data->Scale);
	$Negative_effects = trim($data->Negative_effects);
	$Neutral_effects = trim($data->Neutral_effects);
	$Positive_effects = trim($data->Positive_effects);
	$Deities = trim($data->Deities);
	$Element = trim($data->Element);
	$Materials_required = trim($data->Materials_required);
	$Skills_required = trim($data->Skills_required);
	$Education = trim($data->Education);
	$Resource_costs = trim($data->Resource_costs);
	$Limitations = trim($data->Limitations);
	$Private_notes = trim($data->Private_notes);
	$Notes = trim($data->Notes);


    $sql = "UPDATE magics SET 
Tags = '$Tags',Name = '$Name',Description = '$Description',Type_of_magic = '$Type_of_magic',Universe = '$Universe',Effects = '$Effects',Visuals = '$Visuals',Aftereffects = '$Aftereffects',Conditions = '$Conditions',Scale = '$Scale',Negative_effects = '$Negative_effects',Neutral_effects = '$Neutral_effects',Positive_effects = '$Positive_effects',Deities = '$Deities',Element = '$Element',Materials_required = '$Materials_required',Skills_required = '$Skills_required',Education = '$Education',Resource_costs = '$Resource_costs',Limitations = '$Limitations',Private_notes = '$Private_notes',Notes = '$Notes'    WHERE id = $id"; 

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

	$Name = trim($data->Name);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Type_of_organization = trim($data->Type_of_organization);
	$Alternate_names = trim($data->Alternate_names);
	$Tags = trim($data->Tags);
	$Owner = trim($data->Owner);
	$Members = trim($data->Members);
	$Purpose = trim($data->Purpose);
	$Services = trim($data->Services);
	$Sub_organizations = trim($data->Sub_organizations);
	$Super_organizations = trim($data->Super_organizations);
	$Sister_organizations = trim($data->Sister_organizations);
	$Organization_structure = trim($data->Organization_structure);
	$Rival_organizations = trim($data->Rival_organizations);
	$Address = trim($data->Address);
	$Offices = trim($data->Offices);
	$Locations = trim($data->Locations);
	$Headquarters = trim($data->Headquarters);
	$Formation_year = trim($data->Formation_year);
	$Closure_year = trim($data->Closure_year);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO organizations(Name,Universe,Description,Type_of_organization,Alternate_names,Tags,Owner,Members,Purpose,Services,Sub_organizations,Super_organizations,Sister_organizations,Organization_structure,Rival_organizations,Address,Offices,Locations,Headquarters,Formation_year,Closure_year,Notes,Private_Notes) 
VALUES('$Name','$Universe','$Description','$Type_of_organization','$Alternate_names','$Tags','$Owner','$Members','$Purpose','$Services','$Sub_organizations','$Super_organizations','$Sister_organizations','$Organization_structure','$Rival_organizations','$Address','$Offices','$Locations','$Headquarters','$Formation_year','$Closure_year','$Notes','$Private_Notes')"; 


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

	$Name = trim($data->Name);
	$Universe = trim($data->Universe);
	$Description = trim($data->Description);
	$Type_of_organization = trim($data->Type_of_organization);
	$Alternate_names = trim($data->Alternate_names);
	$Tags = trim($data->Tags);
	$Owner = trim($data->Owner);
	$Members = trim($data->Members);
	$Purpose = trim($data->Purpose);
	$Services = trim($data->Services);
	$Sub_organizations = trim($data->Sub_organizations);
	$Super_organizations = trim($data->Super_organizations);
	$Sister_organizations = trim($data->Sister_organizations);
	$Organization_structure = trim($data->Organization_structure);
	$Rival_organizations = trim($data->Rival_organizations);
	$Address = trim($data->Address);
	$Offices = trim($data->Offices);
	$Locations = trim($data->Locations);
	$Headquarters = trim($data->Headquarters);
	$Formation_year = trim($data->Formation_year);
	$Closure_year = trim($data->Closure_year);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE organizations SET 
Name = '$Name',Universe = '$Universe',Description = '$Description',Type_of_organization = '$Type_of_organization',Alternate_names = '$Alternate_names',Tags = '$Tags',Owner = '$Owner',Members = '$Members',Purpose = '$Purpose',Services = '$Services',Sub_organizations = '$Sub_organizations',Super_organizations = '$Super_organizations',Sister_organizations = '$Sister_organizations',Organization_structure = '$Organization_structure',Rival_organizations = '$Rival_organizations',Address = '$Address',Offices = '$Offices',Locations = '$Locations',Headquarters = '$Headquarters',Formation_year = '$Formation_year',Closure_year = '$Closure_year',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	$Description = trim($data->Description);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Weather = trim($data->Weather);
	$Water_Content = trim($data->Water_Content);
	$Natural_Resources = trim($data->Natural_Resources);
	$Continents = trim($data->Continents);
	$Countries = trim($data->Countries);
	$Locations = trim($data->Locations);
	$Landmarks = trim($data->Landmarks);
	$Size = trim($data->Size);
	$Surface = trim($data->Surface);
	$Climate = trim($data->Climate);
	$Atmosphere = trim($data->Atmosphere);
	$Seasons = trim($data->Seasons);
	$Temperature = trim($data->Temperature);
	$Natural_diasters = trim($data->Natural_diasters);
	$Calendar_System = trim($data->Calendar_System);
	$Day_sky = trim($data->Day_sky);
	$Night_sky = trim($data->Night_sky);
	$Length_Of_Night = trim($data->Length_Of_Night);
	$Length_Of_Day = trim($data->Length_Of_Day);
	$Towns = trim($data->Towns);
	$Population = trim($data->Population);
	$Races = trim($data->Races);
	$Flora = trim($data->Flora);
	$Creatures = trim($data->Creatures);
	$Religions = trim($data->Religions);
	$Deities = trim($data->Deities);
	$Groups = trim($data->Groups);
	$Languages = trim($data->Languages);
	$Visible_Constellations = trim($data->Visible_Constellations);
	$Suns = trim($data->Suns);
	$Moons = trim($data->Moons);
	$Orbit = trim($data->Orbit);
	$Nearby_planets = trim($data->Nearby_planets);
	$First_Inhabitants_Story = trim($data->First_Inhabitants_Story);
	$World_History = trim($data->World_History);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "INSERT INTO planets(Description,Universe,Tags,Name,Weather,Water_Content,Natural_Resources,Continents,Countries,Locations,Landmarks,Size,Surface,Climate,Atmosphere,Seasons,Temperature,Natural_diasters,Calendar_System,Day_sky,Night_sky,Length_Of_Night,Length_Of_Day,Towns,Population,Races,Flora,Creatures,Religions,Deities,Groups,Languages,Visible_Constellations,Suns,Moons,Orbit,Nearby_planets,First_Inhabitants_Story,World_History,Private_Notes,Notes) 
VALUES('$Description','$Universe','$Tags','$Name','$Weather','$Water_Content','$Natural_Resources','$Continents','$Countries','$Locations','$Landmarks','$Size','$Surface','$Climate','$Atmosphere','$Seasons','$Temperature','$Natural_diasters','$Calendar_System','$Day_sky','$Night_sky','$Length_Of_Night','$Length_Of_Day','$Towns','$Population','$Races','$Flora','$Creatures','$Religions','$Deities','$Groups','$Languages','$Visible_Constellations','$Suns','$Moons','$Orbit','$Nearby_planets','$First_Inhabitants_Story','$World_History','$Private_Notes','$Notes')"; 


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

	$Description = trim($data->Description);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Weather = trim($data->Weather);
	$Water_Content = trim($data->Water_Content);
	$Natural_Resources = trim($data->Natural_Resources);
	$Continents = trim($data->Continents);
	$Countries = trim($data->Countries);
	$Locations = trim($data->Locations);
	$Landmarks = trim($data->Landmarks);
	$Size = trim($data->Size);
	$Surface = trim($data->Surface);
	$Climate = trim($data->Climate);
	$Atmosphere = trim($data->Atmosphere);
	$Seasons = trim($data->Seasons);
	$Temperature = trim($data->Temperature);
	$Natural_diasters = trim($data->Natural_diasters);
	$Calendar_System = trim($data->Calendar_System);
	$Day_sky = trim($data->Day_sky);
	$Night_sky = trim($data->Night_sky);
	$Length_Of_Night = trim($data->Length_Of_Night);
	$Length_Of_Day = trim($data->Length_Of_Day);
	$Towns = trim($data->Towns);
	$Population = trim($data->Population);
	$Races = trim($data->Races);
	$Flora = trim($data->Flora);
	$Creatures = trim($data->Creatures);
	$Religions = trim($data->Religions);
	$Deities = trim($data->Deities);
	$Groups = trim($data->Groups);
	$Languages = trim($data->Languages);
	$Visible_Constellations = trim($data->Visible_Constellations);
	$Suns = trim($data->Suns);
	$Moons = trim($data->Moons);
	$Orbit = trim($data->Orbit);
	$Nearby_planets = trim($data->Nearby_planets);
	$First_Inhabitants_Story = trim($data->First_Inhabitants_Story);
	$World_History = trim($data->World_History);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "UPDATE planets SET 
Description = '$Description',Universe = '$Universe',Tags = '$Tags',Name = '$Name',Weather = '$Weather',Water_Content = '$Water_Content',Natural_Resources = '$Natural_Resources',Continents = '$Continents',Countries = '$Countries',Locations = '$Locations',Landmarks = '$Landmarks',Size = '$Size',Surface = '$Surface',Climate = '$Climate',Atmosphere = '$Atmosphere',Seasons = '$Seasons',Temperature = '$Temperature',Natural_diasters = '$Natural_diasters',Calendar_System = '$Calendar_System',Day_sky = '$Day_sky',Night_sky = '$Night_sky',Length_Of_Night = '$Length_Of_Night',Length_Of_Day = '$Length_Of_Day',Towns = '$Towns',Population = '$Population',Races = '$Races',Flora = '$Flora',Creatures = '$Creatures',Religions = '$Religions',Deities = '$Deities',Groups = '$Groups',Languages = '$Languages',Visible_Constellations = '$Visible_Constellations',Suns = '$Suns',Moons = '$Moons',Orbit = '$Orbit',Nearby_planets = '$Nearby_planets',First_Inhabitants_Story = '$First_Inhabitants_Story',World_History = '$World_History',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Description = trim($data->Description);
	$Name = trim($data->Name);
	$General_weight = trim($data->General_weight);
	$Notable_features = trim($data->Notable_features);
	$Physical_variance = trim($data->Physical_variance);
	$Typical_clothing = trim($data->Typical_clothing);
	$Body_shape = trim($data->Body_shape);
	$Skin_colors = trim($data->Skin_colors);
	$General_height = trim($data->General_height);
	$Weaknesses = trim($data->Weaknesses);
	$Conditions = trim($data->Conditions);
	$Strengths = trim($data->Strengths);
	$Favorite_foods = trim($data->Favorite_foods);
	$Famous_figures = trim($data->Famous_figures);
	$Traditions = trim($data->Traditions);
	$Beliefs = trim($data->Beliefs);
	$Governments = trim($data->Governments);
	$Technologies = trim($data->Technologies);
	$Occupations = trim($data->Occupations);
	$Economics = trim($data->Economics);
	$Notable_events = trim($data->Notable_events);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);


    $sql = "INSERT INTO races(Other_Names,Universe,Tags,Description,Name,General_weight,Notable_features,Physical_variance,Typical_clothing,Body_shape,Skin_colors,General_height,Weaknesses,Conditions,Strengths,Favorite_foods,Famous_figures,Traditions,Beliefs,Governments,Technologies,Occupations,Economics,Notable_events,Notes,Private_notes) 
VALUES('$Other_Names','$Universe','$Tags','$Description','$Name','$General_weight','$Notable_features','$Physical_variance','$Typical_clothing','$Body_shape','$Skin_colors','$General_height','$Weaknesses','$Conditions','$Strengths','$Favorite_foods','$Famous_figures','$Traditions','$Beliefs','$Governments','$Technologies','$Occupations','$Economics','$Notable_events','$Notes','$Private_notes')"; 


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

	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Description = trim($data->Description);
	$Name = trim($data->Name);
	$General_weight = trim($data->General_weight);
	$Notable_features = trim($data->Notable_features);
	$Physical_variance = trim($data->Physical_variance);
	$Typical_clothing = trim($data->Typical_clothing);
	$Body_shape = trim($data->Body_shape);
	$Skin_colors = trim($data->Skin_colors);
	$General_height = trim($data->General_height);
	$Weaknesses = trim($data->Weaknesses);
	$Conditions = trim($data->Conditions);
	$Strengths = trim($data->Strengths);
	$Favorite_foods = trim($data->Favorite_foods);
	$Famous_figures = trim($data->Famous_figures);
	$Traditions = trim($data->Traditions);
	$Beliefs = trim($data->Beliefs);
	$Governments = trim($data->Governments);
	$Technologies = trim($data->Technologies);
	$Occupations = trim($data->Occupations);
	$Economics = trim($data->Economics);
	$Notable_events = trim($data->Notable_events);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);


    $sql = "UPDATE races SET 
Other_Names = '$Other_Names',Universe = '$Universe',Tags = '$Tags',Description = '$Description',Name = '$Name',General_weight = '$General_weight',Notable_features = '$Notable_features',Physical_variance = '$Physical_variance',Typical_clothing = '$Typical_clothing',Body_shape = '$Body_shape',Skin_colors = '$Skin_colors',General_height = '$General_height',Weaknesses = '$Weaknesses',Conditions = '$Conditions',Strengths = '$Strengths',Favorite_foods = '$Favorite_foods',Famous_figures = '$Famous_figures',Traditions = '$Traditions',Beliefs = '$Beliefs',Governments = '$Governments',Technologies = '$Technologies',Occupations = '$Occupations',Economics = '$Economics',Notable_events = '$Notable_events',Notes = '$Notes',Private_notes = '$Private_notes'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Notable_figures = trim($data->Notable_figures);
	$Origin_story = trim($data->Origin_story);
	$Artifacts = trim($data->Artifacts);
	$Places_of_worship = trim($data->Places_of_worship);
	$Vision_of_paradise = trim($data->Vision_of_paradise);
	$Obligations = trim($data->Obligations);
	$Worship_services = trim($data->Worship_services);
	$Prophecies = trim($data->Prophecies);
	$Teachings = trim($data->Teachings);
	$Deities = trim($data->Deities);
	$Initiation_process = trim($data->Initiation_process);
	$Rituals = trim($data->Rituals);
	$Holidays = trim($data->Holidays);
	$Traditions = trim($data->Traditions);
	$Practicing_locations = trim($data->Practicing_locations);
	$Practicing_races = trim($data->Practicing_races);
	$Private_notes = trim($data->Private_notes);
	$Notes = trim($data->Notes);


    $sql = "INSERT INTO religions(Tags,Name,Description,Other_Names,Universe,Notable_figures,Origin_story,Artifacts,Places_of_worship,Vision_of_paradise,Obligations,Worship_services,Prophecies,Teachings,Deities,Initiation_process,Rituals,Holidays,Traditions,Practicing_locations,Practicing_races,Private_notes,Notes) 
VALUES('$Tags','$Name','$Description','$Other_Names','$Universe','$Notable_figures','$Origin_story','$Artifacts','$Places_of_worship','$Vision_of_paradise','$Obligations','$Worship_services','$Prophecies','$Teachings','$Deities','$Initiation_process','$Rituals','$Holidays','$Traditions','$Practicing_locations','$Practicing_races','$Private_notes','$Notes')"; 


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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Notable_figures = trim($data->Notable_figures);
	$Origin_story = trim($data->Origin_story);
	$Artifacts = trim($data->Artifacts);
	$Places_of_worship = trim($data->Places_of_worship);
	$Vision_of_paradise = trim($data->Vision_of_paradise);
	$Obligations = trim($data->Obligations);
	$Worship_services = trim($data->Worship_services);
	$Prophecies = trim($data->Prophecies);
	$Teachings = trim($data->Teachings);
	$Deities = trim($data->Deities);
	$Initiation_process = trim($data->Initiation_process);
	$Rituals = trim($data->Rituals);
	$Holidays = trim($data->Holidays);
	$Traditions = trim($data->Traditions);
	$Practicing_locations = trim($data->Practicing_locations);
	$Practicing_races = trim($data->Practicing_races);
	$Private_notes = trim($data->Private_notes);
	$Notes = trim($data->Notes);


    $sql = "UPDATE religions SET 
Tags = '$Tags',Name = '$Name',Description = '$Description',Other_Names = '$Other_Names',Universe = '$Universe',Notable_figures = '$Notable_figures',Origin_story = '$Origin_story',Artifacts = '$Artifacts',Places_of_worship = '$Places_of_worship',Vision_of_paradise = '$Vision_of_paradise',Obligations = '$Obligations',Worship_services = '$Worship_services',Prophecies = '$Prophecies',Teachings = '$Teachings',Deities = '$Deities',Initiation_process = '$Initiation_process',Rituals = '$Rituals',Holidays = '$Holidays',Traditions = '$Traditions',Practicing_locations = '$Practicing_locations',Practicing_races = '$Practicing_races',Private_notes = '$Private_notes',Notes = '$Notes'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Summary = trim($data->Summary);
	$Universe = trim($data->Universe);
	$Items_in_scene = trim($data->Items_in_scene);
	$Locations_in_scene = trim($data->Locations_in_scene);
	$Characters_in_scene = trim($data->Characters_in_scene);
	$Description = trim($data->Description);
	$Results = trim($data->Results);
	$What_caused_this = trim($data->What_caused_this);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);


    $sql = "INSERT INTO scenes(Tags,Name,Summary,Universe,Items_in_scene,Locations_in_scene,Characters_in_scene,Description,Results,What_caused_this,Notes,Private_notes) 
VALUES('$Tags','$Name','$Summary','$Universe','$Items_in_scene','$Locations_in_scene','$Characters_in_scene','$Description','$Results','$What_caused_this','$Notes','$Private_notes')"; 


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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Summary = trim($data->Summary);
	$Universe = trim($data->Universe);
	$Items_in_scene = trim($data->Items_in_scene);
	$Locations_in_scene = trim($data->Locations_in_scene);
	$Characters_in_scene = trim($data->Characters_in_scene);
	$Description = trim($data->Description);
	$Results = trim($data->Results);
	$What_caused_this = trim($data->What_caused_this);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);


    $sql = "UPDATE scenes SET 
Tags = '$Tags',Name = '$Name',Summary = '$Summary',Universe = '$Universe',Items_in_scene = '$Items_in_scene',Locations_in_scene = '$Locations_in_scene',Characters_in_scene = '$Characters_in_scene',Description = '$Description',Results = '$Results',What_caused_this = '$What_caused_this',Notes = '$Notes',Private_notes = '$Private_notes'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$Nicknames = trim($data->Nicknames);
	$Description = trim($data->Description);
	$Name = trim($data->Name);
	$How_to_win = trim($data->How_to_win);
	$Penalties = trim($data->Penalties);
	$Scoring = trim($data->Scoring);
	$Number_of_players = trim($data->Number_of_players);
	$Equipment = trim($data->Equipment);
	$Play_area = trim($data->Play_area);
	$Most_important_muscles = trim($data->Most_important_muscles);
	$Common_injuries = trim($data->Common_injuries);
	$Strategies = trim($data->Strategies);
	$Positions = trim($data->Positions);
	$Game_time = trim($data->Game_time);
	$Rules = trim($data->Rules);
	$Traditions = trim($data->Traditions);
	$Teams = trim($data->Teams);
	$Countries = trim($data->Countries);
	$Players = trim($data->Players);
	$Popularity = trim($data->Popularity);
	$Merchandise = trim($data->Merchandise);
	$Uniforms = trim($data->Uniforms);
	$Famous_games = trim($data->Famous_games);
	$Evolution = trim($data->Evolution);
	$Creators = trim($data->Creators);
	$Origin_story = trim($data->Origin_story);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "INSERT INTO sports(Tags,Universe,Nicknames,Description,Name,How_to_win,Penalties,Scoring,Number_of_players,Equipment,Play_area,Most_important_muscles,Common_injuries,Strategies,Positions,Game_time,Rules,Traditions,Teams,Countries,Players,Popularity,Merchandise,Uniforms,Famous_games,Evolution,Creators,Origin_story,Private_Notes,Notes) 
VALUES('$Tags','$Universe','$Nicknames','$Description','$Name','$How_to_win','$Penalties','$Scoring','$Number_of_players','$Equipment','$Play_area','$Most_important_muscles','$Common_injuries','$Strategies','$Positions','$Game_time','$Rules','$Traditions','$Teams','$Countries','$Players','$Popularity','$Merchandise','$Uniforms','$Famous_games','$Evolution','$Creators','$Origin_story','$Private_Notes','$Notes')"; 


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

	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$Nicknames = trim($data->Nicknames);
	$Description = trim($data->Description);
	$Name = trim($data->Name);
	$How_to_win = trim($data->How_to_win);
	$Penalties = trim($data->Penalties);
	$Scoring = trim($data->Scoring);
	$Number_of_players = trim($data->Number_of_players);
	$Equipment = trim($data->Equipment);
	$Play_area = trim($data->Play_area);
	$Most_important_muscles = trim($data->Most_important_muscles);
	$Common_injuries = trim($data->Common_injuries);
	$Strategies = trim($data->Strategies);
	$Positions = trim($data->Positions);
	$Game_time = trim($data->Game_time);
	$Rules = trim($data->Rules);
	$Traditions = trim($data->Traditions);
	$Teams = trim($data->Teams);
	$Countries = trim($data->Countries);
	$Players = trim($data->Players);
	$Popularity = trim($data->Popularity);
	$Merchandise = trim($data->Merchandise);
	$Uniforms = trim($data->Uniforms);
	$Famous_games = trim($data->Famous_games);
	$Evolution = trim($data->Evolution);
	$Creators = trim($data->Creators);
	$Origin_story = trim($data->Origin_story);
	$Private_Notes = trim($data->Private_Notes);
	$Notes = trim($data->Notes);


    $sql = "UPDATE sports SET 
Tags = '$Tags',Universe = '$Universe',Nicknames = '$Nicknames',Description = '$Description',Name = '$Name',How_to_win = '$How_to_win',Penalties = '$Penalties',Scoring = '$Scoring',Number_of_players = '$Number_of_players',Equipment = '$Equipment',Play_area = '$Play_area',Most_important_muscles = '$Most_important_muscles',Common_injuries = '$Common_injuries',Strategies = '$Strategies',Positions = '$Positions',Game_time = '$Game_time',Rules = '$Rules',Traditions = '$Traditions',Teams = '$Teams',Countries = '$Countries',Players = '$Players',Popularity = '$Popularity',Merchandise = '$Merchandise',Uniforms = '$Uniforms',Famous_games = '$Famous_games',Evolution = '$Evolution',Creators = '$Creators',Origin_story = '$Origin_story',Private_Notes = '$Private_Notes',Notes = '$Notes'    WHERE id = $id"; 

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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Sales_Process = trim($data->Sales_Process);
	$Materials = trim($data->Materials);
	$Manufacturing_Process = trim($data->Manufacturing_Process);
	$Cost = trim($data->Cost);
	$Planets = trim($data->Planets);
	$Rarity = trim($data->Rarity);
	$Creatures = trim($data->Creatures);
	$Groups = trim($data->Groups);
	$Countries = trim($data->Countries);
	$Towns = trim($data->Towns);
	$Characters = trim($data->Characters);
	$Magic_effects = trim($data->Magic_effects);
	$Resources_Used = trim($data->Resources_Used);
	$How_It_Works = trim($data->How_It_Works);
	$Purpose = trim($data->Purpose);
	$Weight = trim($data->Weight);
	$Physical_Description = trim($data->Physical_Description);
	$Size = trim($data->Size);
	$Colors = trim($data->Colors);
	$Related_technologies = trim($data->Related_technologies);
	$Parent_technologies = trim($data->Parent_technologies);
	$Child_technologies = trim($data->Child_technologies);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO technologies(Tags,Name,Description,Other_Names,Universe,Sales_Process,Materials,Manufacturing_Process,Cost,Planets,Rarity,Creatures,Groups,Countries,Towns,Characters,Magic_effects,Resources_Used,How_It_Works,Purpose,Weight,Physical_Description,Size,Colors,Related_technologies,Parent_technologies,Child_technologies,Notes,Private_Notes) 
VALUES('$Tags','$Name','$Description','$Other_Names','$Universe','$Sales_Process','$Materials','$Manufacturing_Process','$Cost','$Planets','$Rarity','$Creatures','$Groups','$Countries','$Towns','$Characters','$Magic_effects','$Resources_Used','$How_It_Works','$Purpose','$Weight','$Physical_Description','$Size','$Colors','$Related_technologies','$Parent_technologies','$Child_technologies','$Notes','$Private_Notes')"; 


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

	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Other_Names = trim($data->Other_Names);
	$Universe = trim($data->Universe);
	$Sales_Process = trim($data->Sales_Process);
	$Materials = trim($data->Materials);
	$Manufacturing_Process = trim($data->Manufacturing_Process);
	$Cost = trim($data->Cost);
	$Planets = trim($data->Planets);
	$Rarity = trim($data->Rarity);
	$Creatures = trim($data->Creatures);
	$Groups = trim($data->Groups);
	$Countries = trim($data->Countries);
	$Towns = trim($data->Towns);
	$Characters = trim($data->Characters);
	$Magic_effects = trim($data->Magic_effects);
	$Resources_Used = trim($data->Resources_Used);
	$How_It_Works = trim($data->How_It_Works);
	$Purpose = trim($data->Purpose);
	$Weight = trim($data->Weight);
	$Physical_Description = trim($data->Physical_Description);
	$Size = trim($data->Size);
	$Colors = trim($data->Colors);
	$Related_technologies = trim($data->Related_technologies);
	$Parent_technologies = trim($data->Parent_technologies);
	$Child_technologies = trim($data->Child_technologies);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE technologies SET 
Tags = '$Tags',Name = '$Name',Description = '$Description',Other_Names = '$Other_Names',Universe = '$Universe',Sales_Process = '$Sales_Process',Materials = '$Materials',Manufacturing_Process = '$Manufacturing_Process',Cost = '$Cost',Planets = '$Planets',Rarity = '$Rarity',Creatures = '$Creatures',Groups = '$Groups',Countries = '$Countries',Towns = '$Towns',Characters = '$Characters',Magic_effects = '$Magic_effects',Resources_Used = '$Resources_Used',How_It_Works = '$How_It_Works',Purpose = '$Purpose',Weight = '$Weight',Physical_Description = '$Physical_Description',Size = '$Size',Colors = '$Colors',Related_technologies = '$Related_technologies',Parent_technologies = '$Parent_technologies',Child_technologies = '$Child_technologies',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

function getAllTimelineEventEntities(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM timeline_event_entities ";

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

function getTimelineEventEntities(){
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM timeline_event_entities Where id = '$id'";

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

function addTimelineevententitie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Entity_type = trim($data->Entity_type);
	$Entity_id = trim($data->Entity_id);
	$Timeline_event_id = trim($data->Timeline_event_id);
	$Notes = trim($data->Notes);
	$Created_at = trim($data->Created_at);
	$Updated_at = trim($data->Updated_at);


    $sql = "INSERT INTO timeline_event_entities(Entity_type,Entity_id,Timeline_event_id,Notes,Created_at,Updated_at) 
VALUES('$Entity_type','$Entity_id','$Timeline_event_id','$Notes','$Created_at','$Updated_at')"; 


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

function deleteTimelineevententitie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM timeline_event_entities WHERE id = $id; ";

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

function updateTimelineevententitie($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Entity_type = trim($data->Entity_type);
	$Entity_id = trim($data->Entity_id);
	$Timeline_event_id = trim($data->Timeline_event_id);
	$Notes = trim($data->Notes);
	$Created_at = trim($data->Created_at);
	$Updated_at = trim($data->Updated_at);


    $sql = "UPDATE timeline_event_entities SET 
Entity_type = '$Entity_type',Entity_id = '$Entity_id',Timeline_event_id = '$Timeline_event_id',Notes = '$Notes',Created_at = '$Created_at',Updated_at = '$Updated_at'    WHERE id = $id"; 

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

function getAllTimelineEvents(){
    $user_id = $_GET['user_id']; 
    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM timeline_events ";

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

function getTimelineEvents(){
    $id = $_GET['id'];


    global $response;
    global $log;
    global $link;

    $sql = "SELECT * FROM timeline_events Where id = '$id'";

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

function addTimelineevent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Timeline_id = trim($data->Timeline_id);
	$Time_label = trim($data->Time_label);
	$Title = trim($data->Title);
	$Description = trim($data->Description);
	$Notes = trim($data->Notes);
	$Position = trim($data->Position);
	$Created_at = trim($data->Created_at);
	$Updated_at = trim($data->Updated_at);
	$Deleted_at = trim($data->Deleted_at);


    $sql = "INSERT INTO timeline_events(Timeline_id,Time_label,Title,Description,Notes,Position,Created_at,Updated_at,Deleted_at) 
VALUES('$Timeline_id','$Time_label','$Title','$Description','$Notes','$Position','$Created_at','$Updated_at','$Deleted_at')"; 


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

function deleteTimelineevent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started delete function.");

    $id = trim($data->id);

    $sql = "DELETE FROM timeline_events WHERE id = $id; ";

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

function updateTimelineevent($data){
    global $response;
    global $log;
    global $link;
    $id = $_GET['id']; 

    $log->info("Started update function.");

	$Timeline_id = trim($data->Timeline_id);
	$Time_label = trim($data->Time_label);
	$Title = trim($data->Title);
	$Description = trim($data->Description);
	$Notes = trim($data->Notes);
	$Position = trim($data->Position);
	$Created_at = trim($data->Created_at);
	$Updated_at = trim($data->Updated_at);
	$Deleted_at = trim($data->Deleted_at);


    $sql = "UPDATE timeline_events SET 
Timeline_id = '$Timeline_id',Time_label = '$Time_label',Title = '$Title',Description = '$Description',Notes = '$Notes',Position = '$Position',Created_at = '$Created_at',Updated_at = '$Updated_at',Deleted_at = '$Deleted_at'    WHERE id = $id"; 

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

	$Name = trim($data->Name);
	$Universe_id = trim($data->Universe_id);
	$User_id = trim($data->User_id);
	$Page_type = trim($data->Page_type);
	$Deleted_at = trim($data->Deleted_at);
	$Archived_at = trim($data->Archived_at);
	$Created_at = trim($data->Created_at);
	$Updated_at = trim($data->Updated_at);
	$Description = trim($data->Description);
	$Subtitle = trim($data->Subtitle);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);


    $sql = "INSERT INTO timelines(Name,Universe_id,User_id,Page_type,Deleted_at,Archived_at,Created_at,Updated_at,Description,Subtitle,Notes,Private_notes) 
VALUES('$Name','$Universe_id','$User_id','$Page_type','$Deleted_at','$Archived_at','$Created_at','$Updated_at','$Description','$Subtitle','$Notes','$Private_notes')"; 


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

	$Name = trim($data->Name);
	$Universe_id = trim($data->Universe_id);
	$User_id = trim($data->User_id);
	$Page_type = trim($data->Page_type);
	$Deleted_at = trim($data->Deleted_at);
	$Archived_at = trim($data->Archived_at);
	$Created_at = trim($data->Created_at);
	$Updated_at = trim($data->Updated_at);
	$Description = trim($data->Description);
	$Subtitle = trim($data->Subtitle);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);


    $sql = "UPDATE timelines SET 
Name = '$Name',Universe_id = '$Universe_id',User_id = '$User_id',Page_type = '$Page_type',Deleted_at = '$Deleted_at',Archived_at = '$Archived_at',Created_at = '$Created_at',Updated_at = '$Updated_at',Description = '$Description',Subtitle = '$Subtitle',Notes = '$Notes',Private_notes = '$Private_notes'    WHERE id = $id"; 

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

	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Other_names = trim($data->Other_names);
	$Country = trim($data->Country);
	$Groups = trim($data->Groups);
	$Citizens = trim($data->Citizens);
	$Buildings = trim($data->Buildings);
	$Neighborhoods = trim($data->Neighborhoods);
	$Busy_areas = trim($data->Busy_areas);
	$Landmarks = trim($data->Landmarks);
	$Laws = trim($data->Laws);
	$Languages = trim($data->Languages);
	$Flora = trim($data->Flora);
	$Creatures = trim($data->Creatures);
	$Politics = trim($data->Politics);
	$Sports = trim($data->Sports);
	$Established_year = trim($data->Established_year);
	$Founding_story = trim($data->Founding_story);
	$Food_sources = trim($data->Food_sources);
	$Waste = trim($data->Waste);
	$Energy_sources = trim($data->Energy_sources);
	$Recycling = trim($data->Recycling);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO towns(Universe,Tags,Name,Description,Other_names,Country,Groups,Citizens,Buildings,Neighborhoods,Busy_areas,Landmarks,Laws,Languages,Flora,Creatures,Politics,Sports,Established_year,Founding_story,Food_sources,Waste,Energy_sources,Recycling,Notes,Private_Notes) 
VALUES('$Universe','$Tags','$Name','$Description','$Other_names','$Country','$Groups','$Citizens','$Buildings','$Neighborhoods','$Busy_areas','$Landmarks','$Laws','$Languages','$Flora','$Creatures','$Politics','$Sports','$Established_year','$Founding_story','$Food_sources','$Waste','$Energy_sources','$Recycling','$Notes','$Private_Notes')"; 


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

	$Universe = trim($data->Universe);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Other_names = trim($data->Other_names);
	$Country = trim($data->Country);
	$Groups = trim($data->Groups);
	$Citizens = trim($data->Citizens);
	$Buildings = trim($data->Buildings);
	$Neighborhoods = trim($data->Neighborhoods);
	$Busy_areas = trim($data->Busy_areas);
	$Landmarks = trim($data->Landmarks);
	$Laws = trim($data->Laws);
	$Languages = trim($data->Languages);
	$Flora = trim($data->Flora);
	$Creatures = trim($data->Creatures);
	$Politics = trim($data->Politics);
	$Sports = trim($data->Sports);
	$Established_year = trim($data->Established_year);
	$Founding_story = trim($data->Founding_story);
	$Food_sources = trim($data->Food_sources);
	$Waste = trim($data->Waste);
	$Energy_sources = trim($data->Energy_sources);
	$Recycling = trim($data->Recycling);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE towns SET 
Universe = '$Universe',Tags = '$Tags',Name = '$Name',Description = '$Description',Other_names = '$Other_names',Country = '$Country',Groups = '$Groups',Citizens = '$Citizens',Buildings = '$Buildings',Neighborhoods = '$Neighborhoods',Busy_areas = '$Busy_areas',Landmarks = '$Landmarks',Laws = '$Laws',Languages = '$Languages',Flora = '$Flora',Creatures = '$Creatures',Politics = '$Politics',Sports = '$Sports',Established_year = '$Established_year',Founding_story = '$Founding_story',Food_sources = '$Food_sources',Waste = '$Waste',Energy_sources = '$Energy_sources',Recycling = '$Recycling',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	$Universe = trim($data->Universe);
	$Alternate_names = trim($data->Alternate_names);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Type_of_tradition = trim($data->Type_of_tradition);
	$Countries = trim($data->Countries);
	$Dates = trim($data->Dates);
	$Groups = trim($data->Groups);
	$Towns = trim($data->Towns);
	$Gifts = trim($data->Gifts);
	$Food = trim($data->Food);
	$Symbolism = trim($data->Symbolism);
	$Games = trim($data->Games);
	$Activities = trim($data->Activities);
	$Etymology = trim($data->Etymology);
	$Origin = trim($data->Origin);
	$Significance = trim($data->Significance);
	$Religions = trim($data->Religions);
	$Notable_events = trim($data->Notable_events);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO traditions(Universe,Alternate_names,Tags,Name,Description,Type_of_tradition,Countries,Dates,Groups,Towns,Gifts,Food,Symbolism,Games,Activities,Etymology,Origin,Significance,Religions,Notable_events,Notes,Private_Notes) 
VALUES('$Universe','$Alternate_names','$Tags','$Name','$Description','$Type_of_tradition','$Countries','$Dates','$Groups','$Towns','$Gifts','$Food','$Symbolism','$Games','$Activities','$Etymology','$Origin','$Significance','$Religions','$Notable_events','$Notes','$Private_Notes')"; 


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

	$Universe = trim($data->Universe);
	$Alternate_names = trim($data->Alternate_names);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Type_of_tradition = trim($data->Type_of_tradition);
	$Countries = trim($data->Countries);
	$Dates = trim($data->Dates);
	$Groups = trim($data->Groups);
	$Towns = trim($data->Towns);
	$Gifts = trim($data->Gifts);
	$Food = trim($data->Food);
	$Symbolism = trim($data->Symbolism);
	$Games = trim($data->Games);
	$Activities = trim($data->Activities);
	$Etymology = trim($data->Etymology);
	$Origin = trim($data->Origin);
	$Significance = trim($data->Significance);
	$Religions = trim($data->Religions);
	$Notable_events = trim($data->Notable_events);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE traditions SET 
Universe = '$Universe',Alternate_names = '$Alternate_names',Tags = '$Tags',Name = '$Name',Description = '$Description',Type_of_tradition = '$Type_of_tradition',Countries = '$Countries',Dates = '$Dates',Groups = '$Groups',Towns = '$Towns',Gifts = '$Gifts',Food = '$Food',Symbolism = '$Symbolism',Games = '$Games',Activities = '$Activities',Etymology = '$Etymology',Origin = '$Origin',Significance = '$Significance',Religions = '$Religions',Notable_events = '$Notable_events',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	$name = trim($data->name);
	$description = trim($data->description);
	$history = trim($data->history);
	$notes = trim($data->notes);
	$private_notes = trim($data->private_notes);
	$privacy = trim($data->privacy);
	$laws_of_physics = trim($data->laws_of_physics);
	$magic_system = trim($data->magic_system);
	$technology = trim($data->technology);
	$genre = trim($data->genre);
	$page_type = trim($data->page_type);
	$favorite = trim($data->favorite);


    $sql = "INSERT INTO universes(name,description,history,notes,private_notes,privacy,laws_of_physics,magic_system,technology,genre,page_type,favorite) 
VALUES('$name','$description','$history','$notes','$private_notes','$privacy','$laws_of_physics','$magic_system','$technology','$genre','$page_type','$favorite')"; 


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

	$name = trim($data->name);
	$description = trim($data->description);
	$history = trim($data->history);
	$notes = trim($data->notes);
	$private_notes = trim($data->private_notes);
	$privacy = trim($data->privacy);
	$laws_of_physics = trim($data->laws_of_physics);
	$magic_system = trim($data->magic_system);
	$technology = trim($data->technology);
	$genre = trim($data->genre);
	$page_type = trim($data->page_type);
	$favorite = trim($data->favorite);


    $sql = "UPDATE universes SET 
name = '$name',description = '$description',history = '$history',notes = '$notes',private_notes = '$private_notes',privacy = '$privacy',laws_of_physics = '$laws_of_physics',magic_system = '$magic_system',technology = '$technology',genre = '$genre',page_type = '$page_type',favorite = '$favorite'    WHERE id = $id"; 

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

	$Universe = trim($data->Universe);
	$Type_of_vehicle = trim($data->Type_of_vehicle);
	$Alternate_names = trim($data->Alternate_names);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Dimensions = trim($data->Dimensions);
	$Size = trim($data->Size);
	$Doors = trim($data->Doors);
	$Materials = trim($data->Materials);
	$Designer = trim($data->Designer);
	$Windows = trim($data->Windows);
	$Colors = trim($data->Colors);
	$Distance = trim($data->Distance);
	$Features = trim($data->Features);
	$Safety = trim($data->Safety);
	$Fuel = trim($data->Fuel);
	$Speed = trim($data->Speed);
	$Variants = trim($data->Variants);
	$Manufacturer = trim($data->Manufacturer);
	$Costs = trim($data->Costs);
	$Weight = trim($data->Weight);
	$Country = trim($data->Country);
	$Owner = trim($data->Owner);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "INSERT INTO vehicles(Universe,Type_of_vehicle,Alternate_names,Tags,Name,Description,Dimensions,Size,Doors,Materials,Designer,Windows,Colors,Distance,Features,Safety,Fuel,Speed,Variants,Manufacturer,Costs,Weight,Country,Owner,Notes,Private_Notes) 
VALUES('$Universe','$Type_of_vehicle','$Alternate_names','$Tags','$Name','$Description','$Dimensions','$Size','$Doors','$Materials','$Designer','$Windows','$Colors','$Distance','$Features','$Safety','$Fuel','$Speed','$Variants','$Manufacturer','$Costs','$Weight','$Country','$Owner','$Notes','$Private_Notes')"; 


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

	$Universe = trim($data->Universe);
	$Type_of_vehicle = trim($data->Type_of_vehicle);
	$Alternate_names = trim($data->Alternate_names);
	$Tags = trim($data->Tags);
	$Name = trim($data->Name);
	$Description = trim($data->Description);
	$Dimensions = trim($data->Dimensions);
	$Size = trim($data->Size);
	$Doors = trim($data->Doors);
	$Materials = trim($data->Materials);
	$Designer = trim($data->Designer);
	$Windows = trim($data->Windows);
	$Colors = trim($data->Colors);
	$Distance = trim($data->Distance);
	$Features = trim($data->Features);
	$Safety = trim($data->Safety);
	$Fuel = trim($data->Fuel);
	$Speed = trim($data->Speed);
	$Variants = trim($data->Variants);
	$Manufacturer = trim($data->Manufacturer);
	$Costs = trim($data->Costs);
	$Weight = trim($data->Weight);
	$Country = trim($data->Country);
	$Owner = trim($data->Owner);
	$Notes = trim($data->Notes);
	$Private_Notes = trim($data->Private_Notes);


    $sql = "UPDATE vehicles SET 
Universe = '$Universe',Type_of_vehicle = '$Type_of_vehicle',Alternate_names = '$Alternate_names',Tags = '$Tags',Name = '$Name',Description = '$Description',Dimensions = '$Dimensions',Size = '$Size',Doors = '$Doors',Materials = '$Materials',Designer = '$Designer',Windows = '$Windows',Colors = '$Colors',Distance = '$Distance',Features = '$Features',Safety = '$Safety',Fuel = '$Fuel',Speed = '$Speed',Variants = '$Variants',Manufacturer = '$Manufacturer',Costs = '$Costs',Weight = '$Weight',Country = '$Country',Owner = '$Owner',Notes = '$Notes',Private_Notes = '$Private_Notes'    WHERE id = $id"; 

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

	if ($procedureName == "addContentchangeevent") {
		addContentchangeevent($data);
	}

	if ($procedureName == "updateContentchangeevent") {
		updateContentchangeevent($data);
	}

	if ($procedureName == "deleteContentchangeevent") {
		deleteContentchangeevent($data);
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
