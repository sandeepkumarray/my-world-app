<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once "config.php";
require_once "responseClass.php";
require "logWriter.php";

$response = new dbResponse;
$log = new logWriter;

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

	$Address = trim($data->Address);
	$Affiliation = trim($data->Affiliation);
	$Alternate_names = trim($data->Alternate_names);
	$Architect = trim($data->Architect);
	$Architectural_style = trim($data->Architectural_style);
	$Capacity = trim($data->Capacity);
	$Constructed_year = trim($data->Constructed_year);
	$Construction_cost = trim($data->Construction_cost);
	$Description = trim($data->Description);
	$Developer = trim($data->Developer);
	$Dimensions = trim($data->Dimensions);
	$Facade = trim($data->Facade);
	$Floor_count = trim($data->Floor_count);
	$Name = trim($data->Name);
	$Notable_events = trim($data->Notable_events);
	$Notes = trim($data->Notes);
	$Owner = trim($data->Owner);
	$Permits = trim($data->Permits);
	$Price = trim($data->Price);
	$Private_Notes = trim($data->Private_Notes);
	$Purpose = trim($data->Purpose);
	$Tags = trim($data->Tags);
	$Tenants = trim($data->Tenants);
	$Type_of_building = trim($data->Type_of_building);
	$Universe = trim($data->Universe);


    $sql = "INSERT INTO buildings(Address,Affiliation,Alternate_names,Architect,Architectural_style,Capacity,Constructed_year,Construction_cost,Description,Developer,Dimensions,Facade,Floor_count,Name,Notable_events,Notes,Owner,Permits,Price,Private_Notes,Purpose,Tags,Tenants,Type_of_building,Universe) 
VALUES('$Address','$Affiliation','$Alternate_names','$Architect','$Architectural_style','$Capacity','$Constructed_year','$Construction_cost','$Description','$Developer','$Dimensions','$Facade','$Floor_count','$Name','$Notable_events','$Notes','$Owner','$Permits','$Price','$Private_Notes','$Purpose','$Tags','$Tenants','$Type_of_building','$Universe')"; 


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

	$Address = trim($data->Address);
	$Affiliation = trim($data->Affiliation);
	$Alternate_names = trim($data->Alternate_names);
	$Architect = trim($data->Architect);
	$Architectural_style = trim($data->Architectural_style);
	$Capacity = trim($data->Capacity);
	$Constructed_year = trim($data->Constructed_year);
	$Construction_cost = trim($data->Construction_cost);
	$Description = trim($data->Description);
	$Developer = trim($data->Developer);
	$Dimensions = trim($data->Dimensions);
	$Facade = trim($data->Facade);
	$Floor_count = trim($data->Floor_count);
	$Name = trim($data->Name);
	$Notable_events = trim($data->Notable_events);
	$Notes = trim($data->Notes);
	$Owner = trim($data->Owner);
	$Permits = trim($data->Permits);
	$Price = trim($data->Price);
	$Private_Notes = trim($data->Private_Notes);
	$Purpose = trim($data->Purpose);
	$Tags = trim($data->Tags);
	$Tenants = trim($data->Tenants);
	$Type_of_building = trim($data->Type_of_building);
	$Universe = trim($data->Universe);


    $sql = "UPDATE buildings SET 
Address = '$Address',Affiliation = '$Affiliation',Alternate_names = '$Alternate_names',Architect = '$Architect',Architectural_style = '$Architectural_style',Capacity = '$Capacity',Constructed_year = '$Constructed_year',Construction_cost = '$Construction_cost',Description = '$Description',Developer = '$Developer',Dimensions = '$Dimensions',Facade = '$Facade',Floor_count = '$Floor_count',Name = '$Name',Notable_events = '$Notable_events',Notes = '$Notes',Owner = '$Owner',Permits = '$Permits',Price = '$Price',Private_Notes = '$Private_Notes',Purpose = '$Purpose',Tags = '$Tags',Tenants = '$Tenants',Type_of_building = '$Type_of_building',Universe = '$Universe'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM characters Where user_id = '$user_id',id = '$id'";

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

function addCharacter($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Age = trim($data->Age);
	$Aliases = trim($data->Aliases);
	$Background = trim($data->Background);
	$Birthday = trim($data->Birthday);
	$Birthplace = trim($data->Birthplace);
	$Bodytype = trim($data->Bodytype);
	$Education = trim($data->Education);
	$Eyecolor = trim($data->Eyecolor);
	$Facialhair = trim($data->Facialhair);
	$Fave_animal = trim($data->Fave_animal);
	$Fave_color = trim($data->Fave_color);
	$Fave_food = trim($data->Fave_food);
	$Fave_possession = trim($data->Fave_possession);
	$Fave_weapon = trim($data->Fave_weapon);
	$Favorite = trim($data->Favorite);
	$Flaws = trim($data->Flaws);
	$Gender = trim($data->Gender);
	$Haircolor = trim($data->Haircolor);
	$Hairstyle = trim($data->Hairstyle);
	$Height = trim($data->Height);
	$Hobbies = trim($data->Hobbies);
	$Identmarks = trim($data->Identmarks);
	$Mannerisms = trim($data->Mannerisms);
	$Motivations = trim($data->Motivations);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Occupation = trim($data->Occupation);
	$Personality_type = trim($data->Personality_type);
	$Pets = trim($data->Pets);
	$Politics = trim($data->Politics);
	$Prejudices = trim($data->Prejudices);
	$Privacy = trim($data->Privacy);
	$Private_notes = trim($data->Private_notes);
	$Race = trim($data->Race);
	$Religion = trim($data->Religion);
	$Role = trim($data->Role);
	$Skintone = trim($data->Skintone);
	$Talents = trim($data->Talents);
	$Universe = trim($data->Universe);
	$Weight = trim($data->Weight);


    $sql = "INSERT INTO characters(Age,Aliases,Background,Birthday,Birthplace,Bodytype,Education,Eyecolor,Facialhair,Fave_animal,Fave_color,Fave_food,Fave_possession,Fave_weapon,Favorite,Flaws,Gender,Haircolor,Hairstyle,Height,Hobbies,Identmarks,Mannerisms,Motivations,Name,Notes,Occupation,Personality_type,Pets,Politics,Prejudices,Privacy,Private_notes,Race,Religion,Role,Skintone,Talents,Universe,Weight) 
VALUES('$Age','$Aliases','$Background','$Birthday','$Birthplace','$Bodytype','$Education','$Eyecolor','$Facialhair','$Fave_animal','$Fave_color','$Fave_food','$Fave_possession','$Fave_weapon','$Favorite','$Flaws','$Gender','$Haircolor','$Hairstyle','$Height','$Hobbies','$Identmarks','$Mannerisms','$Motivations','$Name','$Notes','$Occupation','$Personality_type','$Pets','$Politics','$Prejudices','$Privacy','$Private_notes','$Race','$Religion','$Role','$Skintone','$Talents','$Universe','$Weight')"; 


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

	$Age = trim($data->Age);
	$Aliases = trim($data->Aliases);
	$Background = trim($data->Background);
	$Birthday = trim($data->Birthday);
	$Birthplace = trim($data->Birthplace);
	$Bodytype = trim($data->Bodytype);
	$Education = trim($data->Education);
	$Eyecolor = trim($data->Eyecolor);
	$Facialhair = trim($data->Facialhair);
	$Fave_animal = trim($data->Fave_animal);
	$Fave_color = trim($data->Fave_color);
	$Fave_food = trim($data->Fave_food);
	$Fave_possession = trim($data->Fave_possession);
	$Fave_weapon = trim($data->Fave_weapon);
	$Favorite = trim($data->Favorite);
	$Flaws = trim($data->Flaws);
	$Gender = trim($data->Gender);
	$Haircolor = trim($data->Haircolor);
	$Hairstyle = trim($data->Hairstyle);
	$Height = trim($data->Height);
	$Hobbies = trim($data->Hobbies);
	$Identmarks = trim($data->Identmarks);
	$Mannerisms = trim($data->Mannerisms);
	$Motivations = trim($data->Motivations);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Occupation = trim($data->Occupation);
	$Personality_type = trim($data->Personality_type);
	$Pets = trim($data->Pets);
	$Politics = trim($data->Politics);
	$Prejudices = trim($data->Prejudices);
	$Privacy = trim($data->Privacy);
	$Private_notes = trim($data->Private_notes);
	$Race = trim($data->Race);
	$Religion = trim($data->Religion);
	$Role = trim($data->Role);
	$Skintone = trim($data->Skintone);
	$Talents = trim($data->Talents);
	$Universe = trim($data->Universe);
	$Weight = trim($data->Weight);


    $sql = "UPDATE characters SET 
Age = '$Age',Aliases = '$Aliases',Background = '$Background',Birthday = '$Birthday',Birthplace = '$Birthplace',Bodytype = '$Bodytype',Education = '$Education',Eyecolor = '$Eyecolor',Facialhair = '$Facialhair',Fave_animal = '$Fave_animal',Fave_color = '$Fave_color',Fave_food = '$Fave_food',Fave_possession = '$Fave_possession',Fave_weapon = '$Fave_weapon',Favorite = '$Favorite',Flaws = '$Flaws',Gender = '$Gender',Haircolor = '$Haircolor',Hairstyle = '$Hairstyle',Height = '$Height',Hobbies = '$Hobbies',Identmarks = '$Identmarks',Mannerisms = '$Mannerisms',Motivations = '$Motivations',Name = '$Name',Notes = '$Notes',Occupation = '$Occupation',Personality_type = '$Personality_type',Pets = '$Pets',Politics = '$Politics',Prejudices = '$Prejudices',Privacy = '$Privacy',Private_notes = '$Private_notes',Race = '$Race',Religion = '$Religion',Role = '$Role',Skintone = '$Skintone',Talents = '$Talents',Universe = '$Universe',Weight = '$Weight'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM conditions Where user_id = '$user_id',id = '$id'";

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

function addCondition($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Alternate_names = trim($data->Alternate_names);
	$Description = trim($data->Description);
	$Diagnostic_method = trim($data->Diagnostic_method);
	$Duration = trim($data->Duration);
	$Environmental_factors = trim($data->Environmental_factors);
	$Epidemiology = trim($data->Epidemiology);
	$Evolution = trim($data->Evolution);
	$Genetic_factors = trim($data->Genetic_factors);
	$Immunization = trim($data->Immunization);
	$Lifestyle_factors = trim($data->Lifestyle_factors);
	$Medication = trim($data->Medication);
	$Mental_effects = trim($data->Mental_effects);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Origin = trim($data->Origin);
	$Prevention = trim($data->Prevention);
	$Private_Notes = trim($data->Private_Notes);
	$Prognosis = trim($data->Prognosis);
	$Rarity = trim($data->Rarity);
	$Specialty_Field = trim($data->Specialty_Field);
	$Symbolism = trim($data->Symbolism);
	$Symptoms = trim($data->Symptoms);
	$Tags = trim($data->Tags);
	$Transmission = trim($data->Transmission);
	$Treatment = trim($data->Treatment);
	$Type_of_condition = trim($data->Type_of_condition);
	$Universe = trim($data->Universe);
	$Variations = trim($data->Variations);
	$Visual_effects = trim($data->Visual_effects);


    $sql = "INSERT INTO conditions(Alternate_names,Description,Diagnostic_method,Duration,Environmental_factors,Epidemiology,Evolution,Genetic_factors,Immunization,Lifestyle_factors,Medication,Mental_effects,Name,Notes,Origin,Prevention,Private_Notes,Prognosis,Rarity,Specialty_Field,Symbolism,Symptoms,Tags,Transmission,Treatment,Type_of_condition,Universe,Variations,Visual_effects) 
VALUES('$Alternate_names','$Description','$Diagnostic_method','$Duration','$Environmental_factors','$Epidemiology','$Evolution','$Genetic_factors','$Immunization','$Lifestyle_factors','$Medication','$Mental_effects','$Name','$Notes','$Origin','$Prevention','$Private_Notes','$Prognosis','$Rarity','$Specialty_Field','$Symbolism','$Symptoms','$Tags','$Transmission','$Treatment','$Type_of_condition','$Universe','$Variations','$Visual_effects')"; 


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

	$Alternate_names = trim($data->Alternate_names);
	$Description = trim($data->Description);
	$Diagnostic_method = trim($data->Diagnostic_method);
	$Duration = trim($data->Duration);
	$Environmental_factors = trim($data->Environmental_factors);
	$Epidemiology = trim($data->Epidemiology);
	$Evolution = trim($data->Evolution);
	$Genetic_factors = trim($data->Genetic_factors);
	$Immunization = trim($data->Immunization);
	$Lifestyle_factors = trim($data->Lifestyle_factors);
	$Medication = trim($data->Medication);
	$Mental_effects = trim($data->Mental_effects);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Origin = trim($data->Origin);
	$Prevention = trim($data->Prevention);
	$Private_Notes = trim($data->Private_Notes);
	$Prognosis = trim($data->Prognosis);
	$Rarity = trim($data->Rarity);
	$Specialty_Field = trim($data->Specialty_Field);
	$Symbolism = trim($data->Symbolism);
	$Symptoms = trim($data->Symptoms);
	$Tags = trim($data->Tags);
	$Transmission = trim($data->Transmission);
	$Treatment = trim($data->Treatment);
	$Type_of_condition = trim($data->Type_of_condition);
	$Universe = trim($data->Universe);
	$Variations = trim($data->Variations);
	$Visual_effects = trim($data->Visual_effects);


    $sql = "UPDATE conditions SET 
Alternate_names = '$Alternate_names',Description = '$Description',Diagnostic_method = '$Diagnostic_method',Duration = '$Duration',Environmental_factors = '$Environmental_factors',Epidemiology = '$Epidemiology',Evolution = '$Evolution',Genetic_factors = '$Genetic_factors',Immunization = '$Immunization',Lifestyle_factors = '$Lifestyle_factors',Medication = '$Medication',Mental_effects = '$Mental_effects',Name = '$Name',Notes = '$Notes',Origin = '$Origin',Prevention = '$Prevention',Private_Notes = '$Private_Notes',Prognosis = '$Prognosis',Rarity = '$Rarity',Specialty_Field = '$Specialty_Field',Symbolism = '$Symbolism',Symptoms = '$Symptoms',Tags = '$Tags',Transmission = '$Transmission',Treatment = '$Treatment',Type_of_condition = '$Type_of_condition',Universe = '$Universe',Variations = '$Variations',Visual_effects = '$Visual_effects'    WHERE id = $id"; 

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
    $id = $_GET['id']; 
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

function addContentchangeevent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$action = trim($data->action);
	$changed_fields = trim($data->changed_fields);
	$content_id = trim($data->content_id);
	$content_type = trim($data->content_type);


    $sql = "INSERT INTO content_change_events(action,changed_fields,content_id,content_type) 
VALUES('$action','$changed_fields','$content_id','$content_type')"; 


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

	$action = trim($data->action);
	$changed_fields = trim($data->changed_fields);
	$content_id = trim($data->content_id);
	$content_type = trim($data->content_type);


    $sql = "UPDATE content_change_events SET 
action = '$action',changed_fields = '$changed_fields',content_id = '$content_id',content_type = '$content_type'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM continents Where user_id = '$user_id',id = '$id'";

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

function addContinent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Architecture = trim($data->Architecture);
	$Area = trim($data->Area);
	$Bodies_of_water = trim($data->Bodies_of_water);
	$Countries = trim($data->Countries);
	$Creatures = trim($data->Creatures);
	$Crops = trim($data->Crops);
	$Demonym = trim($data->Demonym);
	$Description = trim($data->Description);
	$Discovery = trim($data->Discovery);
	$Economy = trim($data->Economy);
	$Floras = trim($data->Floras);
	$Formation = trim($data->Formation);
	$Governments = trim($data->Governments);
	$Humidity = trim($data->Humidity);
	$Landmarks = trim($data->Landmarks);
	$Languages = trim($data->Languages);
	$Local_name = trim($data->Local_name);
	$Mineralogy = trim($data->Mineralogy);
	$Name = trim($data->Name);
	$Natural_disasters = trim($data->Natural_disasters);
	$Notes = trim($data->Notes);
	$Other_Names = trim($data->Other_Names);
	$Politics = trim($data->Politics);
	$Popular_foods = trim($data->Popular_foods);
	$Population = trim($data->Population);
	$Precipitation = trim($data->Precipitation);
	$Private_Notes = trim($data->Private_Notes);
	$Regional_advantages = trim($data->Regional_advantages);
	$Regional_disadvantages = trim($data->Regional_disadvantages);
	$Reputation = trim($data->Reputation);
	$Ruins = trim($data->Ruins);
	$Seasons = trim($data->Seasons);
	$Shape = trim($data->Shape);
	$Tags = trim($data->Tags);
	$Temperature = trim($data->Temperature);
	$Topography = trim($data->Topography);
	$Tourism = trim($data->Tourism);
	$Traditions = trim($data->Traditions);
	$Universe = trim($data->Universe);
	$Wars = trim($data->Wars);
	$Winds = trim($data->Winds);


    $sql = "INSERT INTO continents(Architecture,Area,Bodies_of_water,Countries,Creatures,Crops,Demonym,Description,Discovery,Economy,Floras,Formation,Governments,Humidity,Landmarks,Languages,Local_name,Mineralogy,Name,Natural_disasters,Notes,Other_Names,Politics,Popular_foods,Population,Precipitation,Private_Notes,Regional_advantages,Regional_disadvantages,Reputation,Ruins,Seasons,Shape,Tags,Temperature,Topography,Tourism,Traditions,Universe,Wars,Winds) 
VALUES('$Architecture','$Area','$Bodies_of_water','$Countries','$Creatures','$Crops','$Demonym','$Description','$Discovery','$Economy','$Floras','$Formation','$Governments','$Humidity','$Landmarks','$Languages','$Local_name','$Mineralogy','$Name','$Natural_disasters','$Notes','$Other_Names','$Politics','$Popular_foods','$Population','$Precipitation','$Private_Notes','$Regional_advantages','$Regional_disadvantages','$Reputation','$Ruins','$Seasons','$Shape','$Tags','$Temperature','$Topography','$Tourism','$Traditions','$Universe','$Wars','$Winds')"; 


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

	$Architecture = trim($data->Architecture);
	$Area = trim($data->Area);
	$Bodies_of_water = trim($data->Bodies_of_water);
	$Countries = trim($data->Countries);
	$Creatures = trim($data->Creatures);
	$Crops = trim($data->Crops);
	$Demonym = trim($data->Demonym);
	$Description = trim($data->Description);
	$Discovery = trim($data->Discovery);
	$Economy = trim($data->Economy);
	$Floras = trim($data->Floras);
	$Formation = trim($data->Formation);
	$Governments = trim($data->Governments);
	$Humidity = trim($data->Humidity);
	$Landmarks = trim($data->Landmarks);
	$Languages = trim($data->Languages);
	$Local_name = trim($data->Local_name);
	$Mineralogy = trim($data->Mineralogy);
	$Name = trim($data->Name);
	$Natural_disasters = trim($data->Natural_disasters);
	$Notes = trim($data->Notes);
	$Other_Names = trim($data->Other_Names);
	$Politics = trim($data->Politics);
	$Popular_foods = trim($data->Popular_foods);
	$Population = trim($data->Population);
	$Precipitation = trim($data->Precipitation);
	$Private_Notes = trim($data->Private_Notes);
	$Regional_advantages = trim($data->Regional_advantages);
	$Regional_disadvantages = trim($data->Regional_disadvantages);
	$Reputation = trim($data->Reputation);
	$Ruins = trim($data->Ruins);
	$Seasons = trim($data->Seasons);
	$Shape = trim($data->Shape);
	$Tags = trim($data->Tags);
	$Temperature = trim($data->Temperature);
	$Topography = trim($data->Topography);
	$Tourism = trim($data->Tourism);
	$Traditions = trim($data->Traditions);
	$Universe = trim($data->Universe);
	$Wars = trim($data->Wars);
	$Winds = trim($data->Winds);


    $sql = "UPDATE continents SET 
Architecture = '$Architecture',Area = '$Area',Bodies_of_water = '$Bodies_of_water',Countries = '$Countries',Creatures = '$Creatures',Crops = '$Crops',Demonym = '$Demonym',Description = '$Description',Discovery = '$Discovery',Economy = '$Economy',Floras = '$Floras',Formation = '$Formation',Governments = '$Governments',Humidity = '$Humidity',Landmarks = '$Landmarks',Languages = '$Languages',Local_name = '$Local_name',Mineralogy = '$Mineralogy',Name = '$Name',Natural_disasters = '$Natural_disasters',Notes = '$Notes',Other_Names = '$Other_Names',Politics = '$Politics',Popular_foods = '$Popular_foods',Population = '$Population',Precipitation = '$Precipitation',Private_Notes = '$Private_Notes',Regional_advantages = '$Regional_advantages',Regional_disadvantages = '$Regional_disadvantages',Reputation = '$Reputation',Ruins = '$Ruins',Seasons = '$Seasons',Shape = '$Shape',Tags = '$Tags',Temperature = '$Temperature',Topography = '$Topography',Tourism = '$Tourism',Traditions = '$Traditions',Universe = '$Universe',Wars = '$Wars',Winds = '$Winds'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM countries Where user_id = '$user_id',id = '$id'";

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

function addCountrie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Architecture = trim($data->Architecture);
	$Area = trim($data->Area);
	$Bordering_countries = trim($data->Bordering_countries);
	$Climate = trim($data->Climate);
	$Creatures = trim($data->Creatures);
	$Crops = trim($data->Crops);
	$Currency = trim($data->Currency);
	$Description = trim($data->Description);
	$Education = trim($data->Education);
	$Established_year = trim($data->Established_year);
	$Flora = trim($data->Flora);
	$Founding_story = trim($data->Founding_story);
	$Governments = trim($data->Governments);
	$Landmarks = trim($data->Landmarks);
	$Languages = trim($data->Languages);
	$Laws = trim($data->Laws);
	$Locations = trim($data->Locations);
	$Music = trim($data->Music);
	$Name = trim($data->Name);
	$Notable_wars = trim($data->Notable_wars);
	$Notes = trim($data->Notes);
	$Other_Names = trim($data->Other_Names);
	$Pop_culture = trim($data->Pop_culture);
	$Population = trim($data->Population);
	$Private_Notes = trim($data->Private_Notes);
	$Religions = trim($data->Religions);
	$Social_hierarchy = trim($data->Social_hierarchy);
	$Sports = trim($data->Sports);
	$Tags = trim($data->Tags);
	$Towns = trim($data->Towns);
	$Universe = trim($data->Universe);


    $sql = "INSERT INTO countries(Architecture,Area,Bordering_countries,Climate,Creatures,Crops,Currency,Description,Education,Established_year,Flora,Founding_story,Governments,Landmarks,Languages,Laws,Locations,Music,Name,Notable_wars,Notes,Other_Names,Pop_culture,Population,Private_Notes,Religions,Social_hierarchy,Sports,Tags,Towns,Universe) 
VALUES('$Architecture','$Area','$Bordering_countries','$Climate','$Creatures','$Crops','$Currency','$Description','$Education','$Established_year','$Flora','$Founding_story','$Governments','$Landmarks','$Languages','$Laws','$Locations','$Music','$Name','$Notable_wars','$Notes','$Other_Names','$Pop_culture','$Population','$Private_Notes','$Religions','$Social_hierarchy','$Sports','$Tags','$Towns','$Universe')"; 


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

	$Architecture = trim($data->Architecture);
	$Area = trim($data->Area);
	$Bordering_countries = trim($data->Bordering_countries);
	$Climate = trim($data->Climate);
	$Creatures = trim($data->Creatures);
	$Crops = trim($data->Crops);
	$Currency = trim($data->Currency);
	$Description = trim($data->Description);
	$Education = trim($data->Education);
	$Established_year = trim($data->Established_year);
	$Flora = trim($data->Flora);
	$Founding_story = trim($data->Founding_story);
	$Governments = trim($data->Governments);
	$Landmarks = trim($data->Landmarks);
	$Languages = trim($data->Languages);
	$Laws = trim($data->Laws);
	$Locations = trim($data->Locations);
	$Music = trim($data->Music);
	$Name = trim($data->Name);
	$Notable_wars = trim($data->Notable_wars);
	$Notes = trim($data->Notes);
	$Other_Names = trim($data->Other_Names);
	$Pop_culture = trim($data->Pop_culture);
	$Population = trim($data->Population);
	$Private_Notes = trim($data->Private_Notes);
	$Religions = trim($data->Religions);
	$Social_hierarchy = trim($data->Social_hierarchy);
	$Sports = trim($data->Sports);
	$Tags = trim($data->Tags);
	$Towns = trim($data->Towns);
	$Universe = trim($data->Universe);


    $sql = "UPDATE countries SET 
Architecture = '$Architecture',Area = '$Area',Bordering_countries = '$Bordering_countries',Climate = '$Climate',Creatures = '$Creatures',Crops = '$Crops',Currency = '$Currency',Description = '$Description',Education = '$Education',Established_year = '$Established_year',Flora = '$Flora',Founding_story = '$Founding_story',Governments = '$Governments',Landmarks = '$Landmarks',Languages = '$Languages',Laws = '$Laws',Locations = '$Locations',Music = '$Music',Name = '$Name',Notable_wars = '$Notable_wars',Notes = '$Notes',Other_Names = '$Other_Names',Pop_culture = '$Pop_culture',Population = '$Population',Private_Notes = '$Private_Notes',Religions = '$Religions',Social_hierarchy = '$Social_hierarchy',Sports = '$Sports',Tags = '$Tags',Towns = '$Towns',Universe = '$Universe'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM creatures Where user_id = '$user_id',id = '$id'";

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

function addCreature($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Aggressiveness = trim($data->Aggressiveness);
	$Ancestors = trim($data->Ancestors);
	$Class = trim($data->Class);
	$Color = trim($data->Color);
	$Competitors = trim($data->Competitors);
	$Conditions = trim($data->Conditions);
	$Description = trim($data->Description);
	$Evolutionary_drive = trim($data->Evolutionary_drive);
	$Family = trim($data->Family);
	$Food_sources = trim($data->Food_sources);
	$Genus = trim($data->Genus);
	$Habitats = trim($data->Habitats);
	$Height = trim($data->Height);
	$Herding_patterns = trim($data->Herding_patterns);
	$Materials = trim($data->Materials);
	$Mating_ritual = trim($data->Mating_ritual);
	$Maximum_speed = trim($data->Maximum_speed);
	$Method_of_attack = trim($data->Method_of_attack);
	$Methods_of_defense = trim($data->Methods_of_defense);
	$Migratory_patterns = trim($data->Migratory_patterns);
	$Mortality_rate = trim($data->Mortality_rate);
	$Name = trim($data->Name);
	$Notable_features = trim($data->Notable_features);
	$Notes = trim($data->Notes);
	$Offspring_care = trim($data->Offspring_care);
	$Order = trim($data->Order);
	$Parental_instincts = trim($data->Parental_instincts);
	$Phylum = trim($data->Phylum);
	$Predators = trim($data->Predators);
	$Predictions = trim($data->Predictions);
	$Preferred_habitat = trim($data->Preferred_habitat);
	$Prey = trim($data->Prey);
	$Private_notes = trim($data->Private_notes);
	$Related_creatures = trim($data->Related_creatures);
	$Reproduction = trim($data->Reproduction);
	$Reproduction_age = trim($data->Reproduction_age);
	$Reproduction_frequency = trim($data->Reproduction_frequency);
	$Requirements = trim($data->Requirements);
	$Shape = trim($data->Shape);
	$Similar_creatures = trim($data->Similar_creatures);
	$Size = trim($data->Size);
	$Sounds = trim($data->Sounds);
	$Species = trim($data->Species);
	$Spoils = trim($data->Spoils);
	$Strengths = trim($data->Strengths);
	$Strongest_sense = trim($data->Strongest_sense);
	$Symbolisms = trim($data->Symbolisms);
	$Tags = trim($data->Tags);
	$Tradeoffs = trim($data->Tradeoffs);
	$Type_of_creature = trim($data->Type_of_creature);
	$Universe = trim($data->Universe);
	$Variations = trim($data->Variations);
	$Vestigial_features = trim($data->Vestigial_features);
	$Weakest_sense = trim($data->Weakest_sense);
	$Weaknesses = trim($data->Weaknesses);
	$Weight = trim($data->Weight);


    $sql = "INSERT INTO creatures(Aggressiveness,Ancestors,Class,Color,Competitors,Conditions,Description,Evolutionary_drive,Family,Food_sources,Genus,Habitats,Height,Herding_patterns,Materials,Mating_ritual,Maximum_speed,Method_of_attack,Methods_of_defense,Migratory_patterns,Mortality_rate,Name,Notable_features,Notes,Offspring_care,Order,Parental_instincts,Phylum,Predators,Predictions,Preferred_habitat,Prey,Private_notes,Related_creatures,Reproduction,Reproduction_age,Reproduction_frequency,Requirements,Shape,Similar_creatures,Size,Sounds,Species,Spoils,Strengths,Strongest_sense,Symbolisms,Tags,Tradeoffs,Type_of_creature,Universe,Variations,Vestigial_features,Weakest_sense,Weaknesses,Weight) 
VALUES('$Aggressiveness','$Ancestors','$Class','$Color','$Competitors','$Conditions','$Description','$Evolutionary_drive','$Family','$Food_sources','$Genus','$Habitats','$Height','$Herding_patterns','$Materials','$Mating_ritual','$Maximum_speed','$Method_of_attack','$Methods_of_defense','$Migratory_patterns','$Mortality_rate','$Name','$Notable_features','$Notes','$Offspring_care','$Order','$Parental_instincts','$Phylum','$Predators','$Predictions','$Preferred_habitat','$Prey','$Private_notes','$Related_creatures','$Reproduction','$Reproduction_age','$Reproduction_frequency','$Requirements','$Shape','$Similar_creatures','$Size','$Sounds','$Species','$Spoils','$Strengths','$Strongest_sense','$Symbolisms','$Tags','$Tradeoffs','$Type_of_creature','$Universe','$Variations','$Vestigial_features','$Weakest_sense','$Weaknesses','$Weight')"; 


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

	$Aggressiveness = trim($data->Aggressiveness);
	$Ancestors = trim($data->Ancestors);
	$Class = trim($data->Class);
	$Color = trim($data->Color);
	$Competitors = trim($data->Competitors);
	$Conditions = trim($data->Conditions);
	$Description = trim($data->Description);
	$Evolutionary_drive = trim($data->Evolutionary_drive);
	$Family = trim($data->Family);
	$Food_sources = trim($data->Food_sources);
	$Genus = trim($data->Genus);
	$Habitats = trim($data->Habitats);
	$Height = trim($data->Height);
	$Herding_patterns = trim($data->Herding_patterns);
	$Materials = trim($data->Materials);
	$Mating_ritual = trim($data->Mating_ritual);
	$Maximum_speed = trim($data->Maximum_speed);
	$Method_of_attack = trim($data->Method_of_attack);
	$Methods_of_defense = trim($data->Methods_of_defense);
	$Migratory_patterns = trim($data->Migratory_patterns);
	$Mortality_rate = trim($data->Mortality_rate);
	$Name = trim($data->Name);
	$Notable_features = trim($data->Notable_features);
	$Notes = trim($data->Notes);
	$Offspring_care = trim($data->Offspring_care);
	$Order = trim($data->Order);
	$Parental_instincts = trim($data->Parental_instincts);
	$Phylum = trim($data->Phylum);
	$Predators = trim($data->Predators);
	$Predictions = trim($data->Predictions);
	$Preferred_habitat = trim($data->Preferred_habitat);
	$Prey = trim($data->Prey);
	$Private_notes = trim($data->Private_notes);
	$Related_creatures = trim($data->Related_creatures);
	$Reproduction = trim($data->Reproduction);
	$Reproduction_age = trim($data->Reproduction_age);
	$Reproduction_frequency = trim($data->Reproduction_frequency);
	$Requirements = trim($data->Requirements);
	$Shape = trim($data->Shape);
	$Similar_creatures = trim($data->Similar_creatures);
	$Size = trim($data->Size);
	$Sounds = trim($data->Sounds);
	$Species = trim($data->Species);
	$Spoils = trim($data->Spoils);
	$Strengths = trim($data->Strengths);
	$Strongest_sense = trim($data->Strongest_sense);
	$Symbolisms = trim($data->Symbolisms);
	$Tags = trim($data->Tags);
	$Tradeoffs = trim($data->Tradeoffs);
	$Type_of_creature = trim($data->Type_of_creature);
	$Universe = trim($data->Universe);
	$Variations = trim($data->Variations);
	$Vestigial_features = trim($data->Vestigial_features);
	$Weakest_sense = trim($data->Weakest_sense);
	$Weaknesses = trim($data->Weaknesses);
	$Weight = trim($data->Weight);


    $sql = "UPDATE creatures SET 
Aggressiveness = '$Aggressiveness',Ancestors = '$Ancestors',Class = '$Class',Color = '$Color',Competitors = '$Competitors',Conditions = '$Conditions',Description = '$Description',Evolutionary_drive = '$Evolutionary_drive',Family = '$Family',Food_sources = '$Food_sources',Genus = '$Genus',Habitats = '$Habitats',Height = '$Height',Herding_patterns = '$Herding_patterns',Materials = '$Materials',Mating_ritual = '$Mating_ritual',Maximum_speed = '$Maximum_speed',Method_of_attack = '$Method_of_attack',Methods_of_defense = '$Methods_of_defense',Migratory_patterns = '$Migratory_patterns',Mortality_rate = '$Mortality_rate',Name = '$Name',Notable_features = '$Notable_features',Notes = '$Notes',Offspring_care = '$Offspring_care',Order = '$Order',Parental_instincts = '$Parental_instincts',Phylum = '$Phylum',Predators = '$Predators',Predictions = '$Predictions',Preferred_habitat = '$Preferred_habitat',Prey = '$Prey',Private_notes = '$Private_notes',Related_creatures = '$Related_creatures',Reproduction = '$Reproduction',Reproduction_age = '$Reproduction_age',Reproduction_frequency = '$Reproduction_frequency',Requirements = '$Requirements',Shape = '$Shape',Similar_creatures = '$Similar_creatures',Size = '$Size',Sounds = '$Sounds',Species = '$Species',Spoils = '$Spoils',Strengths = '$Strengths',Strongest_sense = '$Strongest_sense',Symbolisms = '$Symbolisms',Tags = '$Tags',Tradeoffs = '$Tradeoffs',Type_of_creature = '$Type_of_creature',Universe = '$Universe',Variations = '$Variations',Vestigial_features = '$Vestigial_features',Weakest_sense = '$Weakest_sense',Weaknesses = '$Weaknesses',Weight = '$Weight'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM deities Where user_id = '$user_id',id = '$id'";

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

function addDeitie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Abilities = trim($data->Abilities);
	$Children = trim($data->Children);
	$Conditions = trim($data->Conditions);
	$Creatures = trim($data->Creatures);
	$Description = trim($data->Description);
	$Elements = trim($data->Elements);
	$Family_History = trim($data->Family_History);
	$Floras = trim($data->Floras);
	$Height = trim($data->Height);
	$Human_Interaction = trim($data->Human_Interaction);
	$Life_Story = trim($data->Life_Story);
	$Name = trim($data->Name);
	$Notable_Events = trim($data->Notable_Events);
	$Notes = trim($data->Notes);
	$Other_Names = trim($data->Other_Names);
	$Parents = trim($data->Parents);
	$Partners = trim($data->Partners);
	$Physical_Description = trim($data->Physical_Description);
	$Prayers = trim($data->Prayers);
	$Private_Notes = trim($data->Private_Notes);
	$Related_landmarks = trim($data->Related_landmarks);
	$Related_races = trim($data->Related_races);
	$Related_towns = trim($data->Related_towns);
	$Relics = trim($data->Relics);
	$Religions = trim($data->Religions);
	$Rituals = trim($data->Rituals);
	$Siblings = trim($data->Siblings);
	$Strengths = trim($data->Strengths);
	$Symbols = trim($data->Symbols);
	$Tags = trim($data->Tags);
	$Traditions = trim($data->Traditions);
	$Universe = trim($data->Universe);
	$Weaknesses = trim($data->Weaknesses);
	$Weight = trim($data->Weight);


    $sql = "INSERT INTO deities(Abilities,Children,Conditions,Creatures,Description,Elements,Family_History,Floras,Height,Human_Interaction,Life_Story,Name,Notable_Events,Notes,Other_Names,Parents,Partners,Physical_Description,Prayers,Private_Notes,Related_landmarks,Related_races,Related_towns,Relics,Religions,Rituals,Siblings,Strengths,Symbols,Tags,Traditions,Universe,Weaknesses,Weight) 
VALUES('$Abilities','$Children','$Conditions','$Creatures','$Description','$Elements','$Family_History','$Floras','$Height','$Human_Interaction','$Life_Story','$Name','$Notable_Events','$Notes','$Other_Names','$Parents','$Partners','$Physical_Description','$Prayers','$Private_Notes','$Related_landmarks','$Related_races','$Related_towns','$Relics','$Religions','$Rituals','$Siblings','$Strengths','$Symbols','$Tags','$Traditions','$Universe','$Weaknesses','$Weight')"; 


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

	$Abilities = trim($data->Abilities);
	$Children = trim($data->Children);
	$Conditions = trim($data->Conditions);
	$Creatures = trim($data->Creatures);
	$Description = trim($data->Description);
	$Elements = trim($data->Elements);
	$Family_History = trim($data->Family_History);
	$Floras = trim($data->Floras);
	$Height = trim($data->Height);
	$Human_Interaction = trim($data->Human_Interaction);
	$Life_Story = trim($data->Life_Story);
	$Name = trim($data->Name);
	$Notable_Events = trim($data->Notable_Events);
	$Notes = trim($data->Notes);
	$Other_Names = trim($data->Other_Names);
	$Parents = trim($data->Parents);
	$Partners = trim($data->Partners);
	$Physical_Description = trim($data->Physical_Description);
	$Prayers = trim($data->Prayers);
	$Private_Notes = trim($data->Private_Notes);
	$Related_landmarks = trim($data->Related_landmarks);
	$Related_races = trim($data->Related_races);
	$Related_towns = trim($data->Related_towns);
	$Relics = trim($data->Relics);
	$Religions = trim($data->Religions);
	$Rituals = trim($data->Rituals);
	$Siblings = trim($data->Siblings);
	$Strengths = trim($data->Strengths);
	$Symbols = trim($data->Symbols);
	$Tags = trim($data->Tags);
	$Traditions = trim($data->Traditions);
	$Universe = trim($data->Universe);
	$Weaknesses = trim($data->Weaknesses);
	$Weight = trim($data->Weight);


    $sql = "UPDATE deities SET 
Abilities = '$Abilities',Children = '$Children',Conditions = '$Conditions',Creatures = '$Creatures',Description = '$Description',Elements = '$Elements',Family_History = '$Family_History',Floras = '$Floras',Height = '$Height',Human_Interaction = '$Human_Interaction',Life_Story = '$Life_Story',Name = '$Name',Notable_Events = '$Notable_Events',Notes = '$Notes',Other_Names = '$Other_Names',Parents = '$Parents',Partners = '$Partners',Physical_Description = '$Physical_Description',Prayers = '$Prayers',Private_Notes = '$Private_Notes',Related_landmarks = '$Related_landmarks',Related_races = '$Related_races',Related_towns = '$Related_towns',Relics = '$Relics',Religions = '$Religions',Rituals = '$Rituals',Siblings = '$Siblings',Strengths = '$Strengths',Symbols = '$Symbols',Tags = '$Tags',Traditions = '$Traditions',Universe = '$Universe',Weaknesses = '$Weaknesses',Weight = '$Weight'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM floras Where user_id = '$user_id',id = '$id'";

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

function addFlora($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Berries = trim($data->Berries);
	$Colorings = trim($data->Colorings);
	$Description = trim($data->Description);
	$Eaten_by = trim($data->Eaten_by);
	$Family = trim($data->Family);
	$Fruits = trim($data->Fruits);
	$Genus = trim($data->Genus);
	$Locations = trim($data->Locations);
	$Magical_effects = trim($data->Magical_effects);
	$Material_uses = trim($data->Material_uses);
	$Medicinal_purposes = trim($data->Medicinal_purposes);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Nuts = trim($data->Nuts);
	$Order = trim($data->Order);
	$Other_Names = trim($data->Other_Names);
	$Private_Notes = trim($data->Private_Notes);
	$Related_flora = trim($data->Related_flora);
	$Reproduction = trim($data->Reproduction);
	$Seasonality = trim($data->Seasonality);
	$Seeds = trim($data->Seeds);
	$Size = trim($data->Size);
	$Smell = trim($data->Smell);
	$Tags = trim($data->Tags);
	$Taste = trim($data->Taste);
	$Universe = trim($data->Universe);


    $sql = "INSERT INTO floras(Berries,Colorings,Description,Eaten_by,Family,Fruits,Genus,Locations,Magical_effects,Material_uses,Medicinal_purposes,Name,Notes,Nuts,Order,Other_Names,Private_Notes,Related_flora,Reproduction,Seasonality,Seeds,Size,Smell,Tags,Taste,Universe) 
VALUES('$Berries','$Colorings','$Description','$Eaten_by','$Family','$Fruits','$Genus','$Locations','$Magical_effects','$Material_uses','$Medicinal_purposes','$Name','$Notes','$Nuts','$Order','$Other_Names','$Private_Notes','$Related_flora','$Reproduction','$Seasonality','$Seeds','$Size','$Smell','$Tags','$Taste','$Universe')"; 


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

	$Berries = trim($data->Berries);
	$Colorings = trim($data->Colorings);
	$Description = trim($data->Description);
	$Eaten_by = trim($data->Eaten_by);
	$Family = trim($data->Family);
	$Fruits = trim($data->Fruits);
	$Genus = trim($data->Genus);
	$Locations = trim($data->Locations);
	$Magical_effects = trim($data->Magical_effects);
	$Material_uses = trim($data->Material_uses);
	$Medicinal_purposes = trim($data->Medicinal_purposes);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Nuts = trim($data->Nuts);
	$Order = trim($data->Order);
	$Other_Names = trim($data->Other_Names);
	$Private_Notes = trim($data->Private_Notes);
	$Related_flora = trim($data->Related_flora);
	$Reproduction = trim($data->Reproduction);
	$Seasonality = trim($data->Seasonality);
	$Seeds = trim($data->Seeds);
	$Size = trim($data->Size);
	$Smell = trim($data->Smell);
	$Tags = trim($data->Tags);
	$Taste = trim($data->Taste);
	$Universe = trim($data->Universe);


    $sql = "UPDATE floras SET 
Berries = '$Berries',Colorings = '$Colorings',Description = '$Description',Eaten_by = '$Eaten_by',Family = '$Family',Fruits = '$Fruits',Genus = '$Genus',Locations = '$Locations',Magical_effects = '$Magical_effects',Material_uses = '$Material_uses',Medicinal_purposes = '$Medicinal_purposes',Name = '$Name',Notes = '$Notes',Nuts = '$Nuts',Order = '$Order',Other_Names = '$Other_Names',Private_Notes = '$Private_Notes',Related_flora = '$Related_flora',Reproduction = '$Reproduction',Seasonality = '$Seasonality',Seeds = '$Seeds',Size = '$Size',Smell = '$Smell',Tags = '$Tags',Taste = '$Taste',Universe = '$Universe'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM foods Where user_id = '$user_id',id = '$id'";

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

function addFood($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Color = trim($data->Color);
	$Conditions = trim($data->Conditions);
	$Cooking_method = trim($data->Cooking_method);
	$Cost = trim($data->Cost);
	$Description = trim($data->Description);
	$Flavor = trim($data->Flavor);
	$Ingredients = trim($data->Ingredients);
	$Meal = trim($data->Meal);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Nutrition = trim($data->Nutrition);
	$Origin_story = trim($data->Origin_story);
	$Other_Names = trim($data->Other_Names);
	$Place_of_origin = trim($data->Place_of_origin);
	$Preparation = trim($data->Preparation);
	$Private_Notes = trim($data->Private_Notes);
	$Rarity = trim($data->Rarity);
	$Related_foods = trim($data->Related_foods);
	$Reputation = trim($data->Reputation);
	$Scent = trim($data->Scent);
	$Serving = trim($data->Serving);
	$Shelf_life = trim($data->Shelf_life);
	$Side_effects = trim($data->Side_effects);
	$Size = trim($data->Size);
	$Smell = trim($data->Smell);
	$Sold_by = trim($data->Sold_by);
	$Spices = trim($data->Spices);
	$Symbolisms = trim($data->Symbolisms);
	$Tags = trim($data->Tags);
	$Texture = trim($data->Texture);
	$Traditions = trim($data->Traditions);
	$Type_of_food = trim($data->Type_of_food);
	$Universe = trim($data->Universe);
	$Utensils_needed = trim($data->Utensils_needed);
	$Variations = trim($data->Variations);
	$Yield = trim($data->Yield);


    $sql = "INSERT INTO foods(Color,Conditions,Cooking_method,Cost,Description,Flavor,Ingredients,Meal,Name,Notes,Nutrition,Origin_story,Other_Names,Place_of_origin,Preparation,Private_Notes,Rarity,Related_foods,Reputation,Scent,Serving,Shelf_life,Side_effects,Size,Smell,Sold_by,Spices,Symbolisms,Tags,Texture,Traditions,Type_of_food,Universe,Utensils_needed,Variations,Yield) 
VALUES('$Color','$Conditions','$Cooking_method','$Cost','$Description','$Flavor','$Ingredients','$Meal','$Name','$Notes','$Nutrition','$Origin_story','$Other_Names','$Place_of_origin','$Preparation','$Private_Notes','$Rarity','$Related_foods','$Reputation','$Scent','$Serving','$Shelf_life','$Side_effects','$Size','$Smell','$Sold_by','$Spices','$Symbolisms','$Tags','$Texture','$Traditions','$Type_of_food','$Universe','$Utensils_needed','$Variations','$Yield')"; 


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

	$Color = trim($data->Color);
	$Conditions = trim($data->Conditions);
	$Cooking_method = trim($data->Cooking_method);
	$Cost = trim($data->Cost);
	$Description = trim($data->Description);
	$Flavor = trim($data->Flavor);
	$Ingredients = trim($data->Ingredients);
	$Meal = trim($data->Meal);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Nutrition = trim($data->Nutrition);
	$Origin_story = trim($data->Origin_story);
	$Other_Names = trim($data->Other_Names);
	$Place_of_origin = trim($data->Place_of_origin);
	$Preparation = trim($data->Preparation);
	$Private_Notes = trim($data->Private_Notes);
	$Rarity = trim($data->Rarity);
	$Related_foods = trim($data->Related_foods);
	$Reputation = trim($data->Reputation);
	$Scent = trim($data->Scent);
	$Serving = trim($data->Serving);
	$Shelf_life = trim($data->Shelf_life);
	$Side_effects = trim($data->Side_effects);
	$Size = trim($data->Size);
	$Smell = trim($data->Smell);
	$Sold_by = trim($data->Sold_by);
	$Spices = trim($data->Spices);
	$Symbolisms = trim($data->Symbolisms);
	$Tags = trim($data->Tags);
	$Texture = trim($data->Texture);
	$Traditions = trim($data->Traditions);
	$Type_of_food = trim($data->Type_of_food);
	$Universe = trim($data->Universe);
	$Utensils_needed = trim($data->Utensils_needed);
	$Variations = trim($data->Variations);
	$Yield = trim($data->Yield);


    $sql = "UPDATE foods SET 
Color = '$Color',Conditions = '$Conditions',Cooking_method = '$Cooking_method',Cost = '$Cost',Description = '$Description',Flavor = '$Flavor',Ingredients = '$Ingredients',Meal = '$Meal',Name = '$Name',Notes = '$Notes',Nutrition = '$Nutrition',Origin_story = '$Origin_story',Other_Names = '$Other_Names',Place_of_origin = '$Place_of_origin',Preparation = '$Preparation',Private_Notes = '$Private_Notes',Rarity = '$Rarity',Related_foods = '$Related_foods',Reputation = '$Reputation',Scent = '$Scent',Serving = '$Serving',Shelf_life = '$Shelf_life',Side_effects = '$Side_effects',Size = '$Size',Smell = '$Smell',Sold_by = '$Sold_by',Spices = '$Spices',Symbolisms = '$Symbolisms',Tags = '$Tags',Texture = '$Texture',Traditions = '$Traditions',Type_of_food = '$Type_of_food',Universe = '$Universe',Utensils_needed = '$Utensils_needed',Variations = '$Variations',Yield = '$Yield'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM governments Where user_id = '$user_id',id = '$id'";

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

function addGovernment($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Airforce = trim($data->Airforce);
	$Approval_Ratings = trim($data->Approval_Ratings);
	$Checks_And_Balances = trim($data->Checks_And_Balances);
	$Civilian_Life = trim($data->Civilian_Life);
	$Creatures = trim($data->Creatures);
	$Criminal_System = trim($data->Criminal_System);
	$Description = trim($data->Description);
	$Electoral_Process = trim($data->Electoral_Process);
	$Flag_Design_Story = trim($data->Flag_Design_Story);
	$Founding_Story = trim($data->Founding_Story);
	$Geocultural = trim($data->Geocultural);
	$Groups = trim($data->Groups);
	$Holidays = trim($data->Holidays);
	$Immigration = trim($data->Immigration);
	$International_Relations = trim($data->International_Relations);
	$Items = trim($data->Items);
	$Jobs = trim($data->Jobs);
	$Laws = trim($data->Laws);
	$Leaders = trim($data->Leaders);
	$Military = trim($data->Military);
	$Name = trim($data->Name);
	$Navy = trim($data->Navy);
	$Notable_Wars = trim($data->Notable_Wars);
	$Notes = trim($data->Notes);
	$Political_figures = trim($data->Political_figures);
	$Power_Source = trim($data->Power_Source);
	$Power_Structure = trim($data->Power_Structure);
	$Privacy_Ideologies = trim($data->Privacy_Ideologies);
	$Private_Notes = trim($data->Private_Notes);
	$Socioeconomical = trim($data->Socioeconomical);
	$Sociopolitical = trim($data->Sociopolitical);
	$Space_Program = trim($data->Space_Program);
	$Tags = trim($data->Tags);
	$Technologies = trim($data->Technologies);
	$Term_Lengths = trim($data->Term_Lengths);
	$Type_Of_Government = trim($data->Type_Of_Government);
	$Universe = trim($data->Universe);
	$Vehicles = trim($data->Vehicles);


    $sql = "INSERT INTO governments(Airforce,Approval_Ratings,Checks_And_Balances,Civilian_Life,Creatures,Criminal_System,Description,Electoral_Process,Flag_Design_Story,Founding_Story,Geocultural,Groups,Holidays,Immigration,International_Relations,Items,Jobs,Laws,Leaders,Military,Name,Navy,Notable_Wars,Notes,Political_figures,Power_Source,Power_Structure,Privacy_Ideologies,Private_Notes,Socioeconomical,Sociopolitical,Space_Program,Tags,Technologies,Term_Lengths,Type_Of_Government,Universe,Vehicles) 
VALUES('$Airforce','$Approval_Ratings','$Checks_And_Balances','$Civilian_Life','$Creatures','$Criminal_System','$Description','$Electoral_Process','$Flag_Design_Story','$Founding_Story','$Geocultural','$Groups','$Holidays','$Immigration','$International_Relations','$Items','$Jobs','$Laws','$Leaders','$Military','$Name','$Navy','$Notable_Wars','$Notes','$Political_figures','$Power_Source','$Power_Structure','$Privacy_Ideologies','$Private_Notes','$Socioeconomical','$Sociopolitical','$Space_Program','$Tags','$Technologies','$Term_Lengths','$Type_Of_Government','$Universe','$Vehicles')"; 


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

	$Airforce = trim($data->Airforce);
	$Approval_Ratings = trim($data->Approval_Ratings);
	$Checks_And_Balances = trim($data->Checks_And_Balances);
	$Civilian_Life = trim($data->Civilian_Life);
	$Creatures = trim($data->Creatures);
	$Criminal_System = trim($data->Criminal_System);
	$Description = trim($data->Description);
	$Electoral_Process = trim($data->Electoral_Process);
	$Flag_Design_Story = trim($data->Flag_Design_Story);
	$Founding_Story = trim($data->Founding_Story);
	$Geocultural = trim($data->Geocultural);
	$Groups = trim($data->Groups);
	$Holidays = trim($data->Holidays);
	$Immigration = trim($data->Immigration);
	$International_Relations = trim($data->International_Relations);
	$Items = trim($data->Items);
	$Jobs = trim($data->Jobs);
	$Laws = trim($data->Laws);
	$Leaders = trim($data->Leaders);
	$Military = trim($data->Military);
	$Name = trim($data->Name);
	$Navy = trim($data->Navy);
	$Notable_Wars = trim($data->Notable_Wars);
	$Notes = trim($data->Notes);
	$Political_figures = trim($data->Political_figures);
	$Power_Source = trim($data->Power_Source);
	$Power_Structure = trim($data->Power_Structure);
	$Privacy_Ideologies = trim($data->Privacy_Ideologies);
	$Private_Notes = trim($data->Private_Notes);
	$Socioeconomical = trim($data->Socioeconomical);
	$Sociopolitical = trim($data->Sociopolitical);
	$Space_Program = trim($data->Space_Program);
	$Tags = trim($data->Tags);
	$Technologies = trim($data->Technologies);
	$Term_Lengths = trim($data->Term_Lengths);
	$Type_Of_Government = trim($data->Type_Of_Government);
	$Universe = trim($data->Universe);
	$Vehicles = trim($data->Vehicles);


    $sql = "UPDATE governments SET 
Airforce = '$Airforce',Approval_Ratings = '$Approval_Ratings',Checks_And_Balances = '$Checks_And_Balances',Civilian_Life = '$Civilian_Life',Creatures = '$Creatures',Criminal_System = '$Criminal_System',Description = '$Description',Electoral_Process = '$Electoral_Process',Flag_Design_Story = '$Flag_Design_Story',Founding_Story = '$Founding_Story',Geocultural = '$Geocultural',Groups = '$Groups',Holidays = '$Holidays',Immigration = '$Immigration',International_Relations = '$International_Relations',Items = '$Items',Jobs = '$Jobs',Laws = '$Laws',Leaders = '$Leaders',Military = '$Military',Name = '$Name',Navy = '$Navy',Notable_Wars = '$Notable_Wars',Notes = '$Notes',Political_figures = '$Political_figures',Power_Source = '$Power_Source',Power_Structure = '$Power_Structure',Privacy_Ideologies = '$Privacy_Ideologies',Private_Notes = '$Private_Notes',Socioeconomical = '$Socioeconomical',Sociopolitical = '$Sociopolitical',Space_Program = '$Space_Program',Tags = '$Tags',Technologies = '$Technologies',Term_Lengths = '$Term_Lengths',Type_Of_Government = '$Type_Of_Government',Universe = '$Universe',Vehicles = '$Vehicles'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM groups Where user_id = '$user_id',id = '$id'";

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

function addGroup($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Allies = trim($data->Allies);
	$Clients = trim($data->Clients);
	$Creatures = trim($data->Creatures);
	$Description = trim($data->Description);
	$Enemies = trim($data->Enemies);
	$Equipment = trim($data->Equipment);
	$Goals = trim($data->Goals);
	$Headquarters = trim($data->Headquarters);
	$Inventory = trim($data->Inventory);
	$Key_items = trim($data->Key_items);
	$Leaders = trim($data->Leaders);
	$Locations = trim($data->Locations);
	$Members = trim($data->Members);
	$Motivations = trim($data->Motivations);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Obstacles = trim($data->Obstacles);
	$Offices = trim($data->Offices);
	$Organization_structure = trim($data->Organization_structure);
	$Other_Names = trim($data->Other_Names);
	$Private_notes = trim($data->Private_notes);
	$Risks = trim($data->Risks);
	$Rivals = trim($data->Rivals);
	$Sistergroups = trim($data->Sistergroups);
	$Subgroups = trim($data->Subgroups);
	$Supergroups = trim($data->Supergroups);
	$Suppliers = trim($data->Suppliers);
	$Tags = trim($data->Tags);
	$Traditions = trim($data->Traditions);
	$Universe = trim($data->Universe);


    $sql = "INSERT INTO groups(Allies,Clients,Creatures,Description,Enemies,Equipment,Goals,Headquarters,Inventory,Key_items,Leaders,Locations,Members,Motivations,Name,Notes,Obstacles,Offices,Organization_structure,Other_Names,Private_notes,Risks,Rivals,Sistergroups,Subgroups,Supergroups,Suppliers,Tags,Traditions,Universe) 
VALUES('$Allies','$Clients','$Creatures','$Description','$Enemies','$Equipment','$Goals','$Headquarters','$Inventory','$Key_items','$Leaders','$Locations','$Members','$Motivations','$Name','$Notes','$Obstacles','$Offices','$Organization_structure','$Other_Names','$Private_notes','$Risks','$Rivals','$Sistergroups','$Subgroups','$Supergroups','$Suppliers','$Tags','$Traditions','$Universe')"; 


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

	$Allies = trim($data->Allies);
	$Clients = trim($data->Clients);
	$Creatures = trim($data->Creatures);
	$Description = trim($data->Description);
	$Enemies = trim($data->Enemies);
	$Equipment = trim($data->Equipment);
	$Goals = trim($data->Goals);
	$Headquarters = trim($data->Headquarters);
	$Inventory = trim($data->Inventory);
	$Key_items = trim($data->Key_items);
	$Leaders = trim($data->Leaders);
	$Locations = trim($data->Locations);
	$Members = trim($data->Members);
	$Motivations = trim($data->Motivations);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Obstacles = trim($data->Obstacles);
	$Offices = trim($data->Offices);
	$Organization_structure = trim($data->Organization_structure);
	$Other_Names = trim($data->Other_Names);
	$Private_notes = trim($data->Private_notes);
	$Risks = trim($data->Risks);
	$Rivals = trim($data->Rivals);
	$Sistergroups = trim($data->Sistergroups);
	$Subgroups = trim($data->Subgroups);
	$Supergroups = trim($data->Supergroups);
	$Suppliers = trim($data->Suppliers);
	$Tags = trim($data->Tags);
	$Traditions = trim($data->Traditions);
	$Universe = trim($data->Universe);


    $sql = "UPDATE groups SET 
Allies = '$Allies',Clients = '$Clients',Creatures = '$Creatures',Description = '$Description',Enemies = '$Enemies',Equipment = '$Equipment',Goals = '$Goals',Headquarters = '$Headquarters',Inventory = '$Inventory',Key_items = '$Key_items',Leaders = '$Leaders',Locations = '$Locations',Members = '$Members',Motivations = '$Motivations',Name = '$Name',Notes = '$Notes',Obstacles = '$Obstacles',Offices = '$Offices',Organization_structure = '$Organization_structure',Other_Names = '$Other_Names',Private_notes = '$Private_notes',Risks = '$Risks',Rivals = '$Rivals',Sistergroups = '$Sistergroups',Subgroups = '$Subgroups',Supergroups = '$Supergroups',Suppliers = '$Suppliers',Tags = '$Tags',Traditions = '$Traditions',Universe = '$Universe'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM items Where user_id = '$user_id',id = '$id'";

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

function addItem($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Current_Owners = trim($data->Current_Owners);
	$Description = trim($data->Description);
	$Item_Type = trim($data->Item_Type);
	$Magic = trim($data->Magic);
	$Magical_effects = trim($data->Magical_effects);
	$Makers = trim($data->Makers);
	$Materials = trim($data->Materials);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Original_Owners = trim($data->Original_Owners);
	$Past_Owners = trim($data->Past_Owners);
	$Private_Notes = trim($data->Private_Notes);
	$Tags = trim($data->Tags);
	$Technical_effects = trim($data->Technical_effects);
	$Technology = trim($data->Technology);
	$Universe = trim($data->Universe);
	$Weight = trim($data->Weight);
	$Year_it_was_made = trim($data->Year_it_was_made);


    $sql = "INSERT INTO items(Current_Owners,Description,Item_Type,Magic,Magical_effects,Makers,Materials,Name,Notes,Original_Owners,Past_Owners,Private_Notes,Tags,Technical_effects,Technology,Universe,Weight,Year_it_was_made) 
VALUES('$Current_Owners','$Description','$Item_Type','$Magic','$Magical_effects','$Makers','$Materials','$Name','$Notes','$Original_Owners','$Past_Owners','$Private_Notes','$Tags','$Technical_effects','$Technology','$Universe','$Weight','$Year_it_was_made')"; 


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

	$Current_Owners = trim($data->Current_Owners);
	$Description = trim($data->Description);
	$Item_Type = trim($data->Item_Type);
	$Magic = trim($data->Magic);
	$Magical_effects = trim($data->Magical_effects);
	$Makers = trim($data->Makers);
	$Materials = trim($data->Materials);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Original_Owners = trim($data->Original_Owners);
	$Past_Owners = trim($data->Past_Owners);
	$Private_Notes = trim($data->Private_Notes);
	$Tags = trim($data->Tags);
	$Technical_effects = trim($data->Technical_effects);
	$Technology = trim($data->Technology);
	$Universe = trim($data->Universe);
	$Weight = trim($data->Weight);
	$Year_it_was_made = trim($data->Year_it_was_made);


    $sql = "UPDATE items SET 
Current_Owners = '$Current_Owners',Description = '$Description',Item_Type = '$Item_Type',Magic = '$Magic',Magical_effects = '$Magical_effects',Makers = '$Makers',Materials = '$Materials',Name = '$Name',Notes = '$Notes',Original_Owners = '$Original_Owners',Past_Owners = '$Past_Owners',Private_Notes = '$Private_Notes',Tags = '$Tags',Technical_effects = '$Technical_effects',Technology = '$Technology',Universe = '$Universe',Weight = '$Weight',Year_it_was_made = '$Year_it_was_made'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM jobs Where user_id = '$user_id',id = '$id'";

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

function addJob($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Alternate_names = trim($data->Alternate_names);
	$Description = trim($data->Description);
	$Education = trim($data->Education);
	$Experience = trim($data->Experience);
	$Field = trim($data->Field);
	$Initial_goal = trim($data->Initial_goal);
	$Job_origin = trim($data->Job_origin);
	$Long_term_risks = trim($data->Long_term_risks);
	$Name = trim($data->Name);
	$Notable_figures = trim($data->Notable_figures);
	$Notes = trim($data->Notes);
	$Occupational_hazards = trim($data->Occupational_hazards);
	$Pay_rate = trim($data->Pay_rate);
	$Private_Notes = trim($data->Private_Notes);
	$Promotions = trim($data->Promotions);
	$Ranks = trim($data->Ranks);
	$Similar_jobs = trim($data->Similar_jobs);
	$Specializations = trim($data->Specializations);
	$Tags = trim($data->Tags);
	$Time_off = trim($data->Time_off);
	$Traditions = trim($data->Traditions);
	$Training = trim($data->Training);
	$Type_of_job = trim($data->Type_of_job);
	$Universe = trim($data->Universe);
	$Vehicles = trim($data->Vehicles);
	$Work_hours = trim($data->Work_hours);


    $sql = "INSERT INTO jobs(Alternate_names,Description,Education,Experience,Field,Initial_goal,Job_origin,Long_term_risks,Name,Notable_figures,Notes,Occupational_hazards,Pay_rate,Private_Notes,Promotions,Ranks,Similar_jobs,Specializations,Tags,Time_off,Traditions,Training,Type_of_job,Universe,Vehicles,Work_hours) 
VALUES('$Alternate_names','$Description','$Education','$Experience','$Field','$Initial_goal','$Job_origin','$Long_term_risks','$Name','$Notable_figures','$Notes','$Occupational_hazards','$Pay_rate','$Private_Notes','$Promotions','$Ranks','$Similar_jobs','$Specializations','$Tags','$Time_off','$Traditions','$Training','$Type_of_job','$Universe','$Vehicles','$Work_hours')"; 


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

	$Alternate_names = trim($data->Alternate_names);
	$Description = trim($data->Description);
	$Education = trim($data->Education);
	$Experience = trim($data->Experience);
	$Field = trim($data->Field);
	$Initial_goal = trim($data->Initial_goal);
	$Job_origin = trim($data->Job_origin);
	$Long_term_risks = trim($data->Long_term_risks);
	$Name = trim($data->Name);
	$Notable_figures = trim($data->Notable_figures);
	$Notes = trim($data->Notes);
	$Occupational_hazards = trim($data->Occupational_hazards);
	$Pay_rate = trim($data->Pay_rate);
	$Private_Notes = trim($data->Private_Notes);
	$Promotions = trim($data->Promotions);
	$Ranks = trim($data->Ranks);
	$Similar_jobs = trim($data->Similar_jobs);
	$Specializations = trim($data->Specializations);
	$Tags = trim($data->Tags);
	$Time_off = trim($data->Time_off);
	$Traditions = trim($data->Traditions);
	$Training = trim($data->Training);
	$Type_of_job = trim($data->Type_of_job);
	$Universe = trim($data->Universe);
	$Vehicles = trim($data->Vehicles);
	$Work_hours = trim($data->Work_hours);


    $sql = "UPDATE jobs SET 
Alternate_names = '$Alternate_names',Description = '$Description',Education = '$Education',Experience = '$Experience',Field = '$Field',Initial_goal = '$Initial_goal',Job_origin = '$Job_origin',Long_term_risks = '$Long_term_risks',Name = '$Name',Notable_figures = '$Notable_figures',Notes = '$Notes',Occupational_hazards = '$Occupational_hazards',Pay_rate = '$Pay_rate',Private_Notes = '$Private_Notes',Promotions = '$Promotions',Ranks = '$Ranks',Similar_jobs = '$Similar_jobs',Specializations = '$Specializations',Tags = '$Tags',Time_off = '$Time_off',Traditions = '$Traditions',Training = '$Training',Type_of_job = '$Type_of_job',Universe = '$Universe',Vehicles = '$Vehicles',Work_hours = '$Work_hours'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM landmarks Where user_id = '$user_id',id = '$id'";

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

function addLandmark($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Colors = trim($data->Colors);
	$Country = trim($data->Country);
	$Creation_story = trim($data->Creation_story);
	$Creatures = trim($data->Creatures);
	$Description = trim($data->Description);
	$Established_year = trim($data->Established_year);
	$Flora = trim($data->Flora);
	$Materials = trim($data->Materials);
	$Name = trim($data->Name);
	$Nearby_towns = trim($data->Nearby_towns);
	$Notes = trim($data->Notes);
	$Other_Names = trim($data->Other_Names);
	$Private_Notes = trim($data->Private_Notes);
	$Size = trim($data->Size);
	$Tags = trim($data->Tags);
	$Type_of_landmark = trim($data->Type_of_landmark);
	$Universe = trim($data->Universe);


    $sql = "INSERT INTO landmarks(Colors,Country,Creation_story,Creatures,Description,Established_year,Flora,Materials,Name,Nearby_towns,Notes,Other_Names,Private_Notes,Size,Tags,Type_of_landmark,Universe) 
VALUES('$Colors','$Country','$Creation_story','$Creatures','$Description','$Established_year','$Flora','$Materials','$Name','$Nearby_towns','$Notes','$Other_Names','$Private_Notes','$Size','$Tags','$Type_of_landmark','$Universe')"; 


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

	$Colors = trim($data->Colors);
	$Country = trim($data->Country);
	$Creation_story = trim($data->Creation_story);
	$Creatures = trim($data->Creatures);
	$Description = trim($data->Description);
	$Established_year = trim($data->Established_year);
	$Flora = trim($data->Flora);
	$Materials = trim($data->Materials);
	$Name = trim($data->Name);
	$Nearby_towns = trim($data->Nearby_towns);
	$Notes = trim($data->Notes);
	$Other_Names = trim($data->Other_Names);
	$Private_Notes = trim($data->Private_Notes);
	$Size = trim($data->Size);
	$Tags = trim($data->Tags);
	$Type_of_landmark = trim($data->Type_of_landmark);
	$Universe = trim($data->Universe);


    $sql = "UPDATE landmarks SET 
Colors = '$Colors',Country = '$Country',Creation_story = '$Creation_story',Creatures = '$Creatures',Description = '$Description',Established_year = '$Established_year',Flora = '$Flora',Materials = '$Materials',Name = '$Name',Nearby_towns = '$Nearby_towns',Notes = '$Notes',Other_Names = '$Other_Names',Private_Notes = '$Private_Notes',Size = '$Size',Tags = '$Tags',Type_of_landmark = '$Type_of_landmark',Universe = '$Universe'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM languages Where user_id = '$user_id',id = '$id'";

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

function addLanguage($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Body_parts = trim($data->Body_parts);
	$Determiners = trim($data->Determiners);
	$Dialectical_information = trim($data->Dialectical_information);
	$Evolution = trim($data->Evolution);
	$Family = trim($data->Family);
	$Gestures = trim($data->Gestures);
	$Goodbyes = trim($data->Goodbyes);
	$Grammar = trim($data->Grammar);
	$Greetings = trim($data->Greetings);
	$History = trim($data->History);
	$Name = trim($data->Name);
	$No_words = trim($data->No_words);
	$Notes = trim($data->Notes);
	$Numbers = trim($data->Numbers);
	$Other_Names = trim($data->Other_Names);
	$Phonology = trim($data->Phonology);
	$Please = trim($data->Please);
	$Private_notes = trim($data->Private_notes);
	$Pronouns = trim($data->Pronouns);
	$Quantifiers = trim($data->Quantifiers);
	$Register = trim($data->Register);
	$Sorry = trim($data->Sorry);
	$Tags = trim($data->Tags);
	$Thank_you = trim($data->Thank_you);
	$Trade = trim($data->Trade);
	$Typology = trim($data->Typology);
	$Universe = trim($data->Universe);
	$Yes_words = trim($data->Yes_words);
	$You_are_welcome = trim($data->You_are_welcome);


    $sql = "INSERT INTO languages(Body_parts,Determiners,Dialectical_information,Evolution,Family,Gestures,Goodbyes,Grammar,Greetings,History,Name,No_words,Notes,Numbers,Other_Names,Phonology,Please,Private_notes,Pronouns,Quantifiers,Register,Sorry,Tags,Thank_you,Trade,Typology,Universe,Yes_words,You_are_welcome) 
VALUES('$Body_parts','$Determiners','$Dialectical_information','$Evolution','$Family','$Gestures','$Goodbyes','$Grammar','$Greetings','$History','$Name','$No_words','$Notes','$Numbers','$Other_Names','$Phonology','$Please','$Private_notes','$Pronouns','$Quantifiers','$Register','$Sorry','$Tags','$Thank_you','$Trade','$Typology','$Universe','$Yes_words','$You_are_welcome')"; 


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

	$Body_parts = trim($data->Body_parts);
	$Determiners = trim($data->Determiners);
	$Dialectical_information = trim($data->Dialectical_information);
	$Evolution = trim($data->Evolution);
	$Family = trim($data->Family);
	$Gestures = trim($data->Gestures);
	$Goodbyes = trim($data->Goodbyes);
	$Grammar = trim($data->Grammar);
	$Greetings = trim($data->Greetings);
	$History = trim($data->History);
	$Name = trim($data->Name);
	$No_words = trim($data->No_words);
	$Notes = trim($data->Notes);
	$Numbers = trim($data->Numbers);
	$Other_Names = trim($data->Other_Names);
	$Phonology = trim($data->Phonology);
	$Please = trim($data->Please);
	$Private_notes = trim($data->Private_notes);
	$Pronouns = trim($data->Pronouns);
	$Quantifiers = trim($data->Quantifiers);
	$Register = trim($data->Register);
	$Sorry = trim($data->Sorry);
	$Tags = trim($data->Tags);
	$Thank_you = trim($data->Thank_you);
	$Trade = trim($data->Trade);
	$Typology = trim($data->Typology);
	$Universe = trim($data->Universe);
	$Yes_words = trim($data->Yes_words);
	$You_are_welcome = trim($data->You_are_welcome);


    $sql = "UPDATE languages SET 
Body_parts = '$Body_parts',Determiners = '$Determiners',Dialectical_information = '$Dialectical_information',Evolution = '$Evolution',Family = '$Family',Gestures = '$Gestures',Goodbyes = '$Goodbyes',Grammar = '$Grammar',Greetings = '$Greetings',History = '$History',Name = '$Name',No_words = '$No_words',Notes = '$Notes',Numbers = '$Numbers',Other_Names = '$Other_Names',Phonology = '$Phonology',Please = '$Please',Private_notes = '$Private_notes',Pronouns = '$Pronouns',Quantifiers = '$Quantifiers',Register = '$Register',Sorry = '$Sorry',Tags = '$Tags',Thank_you = '$Thank_you',Trade = '$Trade',Typology = '$Typology',Universe = '$Universe',Yes_words = '$Yes_words',You_are_welcome = '$You_are_welcome'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM locations Where user_id = '$user_id',id = '$id'";

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

function addLocation($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Area = trim($data->Area);
	$Capital_cities = trim($data->Capital_cities);
	$Climate = trim($data->Climate);
	$Crops = trim($data->Crops);
	$Currency = trim($data->Currency);
	$Description = trim($data->Description);
	$Established_Year = trim($data->Established_Year);
	$Founding_Story = trim($data->Founding_Story);
	$Landmarks = trim($data->Landmarks);
	$Language = trim($data->Language);
	$Largest_cities = trim($data->Largest_cities);
	$Laws = trim($data->Laws);
	$Leaders = trim($data->Leaders);
	$Located_at = trim($data->Located_at);
	$Motto = trim($data->Motto);
	$Name = trim($data->Name);
	$Notable_cities = trim($data->Notable_cities);
	$Notable_Wars = trim($data->Notable_Wars);
	$Notes = trim($data->Notes);
	$Population = trim($data->Population);
	$Private_Notes = trim($data->Private_Notes);
	$Spoken_Languages = trim($data->Spoken_Languages);
	$Sports = trim($data->Sports);
	$Tags = trim($data->Tags);
	$Type = trim($data->Type);
	$Universe = trim($data->Universe);


    $sql = "INSERT INTO locations(Area,Capital_cities,Climate,Crops,Currency,Description,Established_Year,Founding_Story,Landmarks,Language,Largest_cities,Laws,Leaders,Located_at,Motto,Name,Notable_cities,Notable_Wars,Notes,Population,Private_Notes,Spoken_Languages,Sports,Tags,Type,Universe) 
VALUES('$Area','$Capital_cities','$Climate','$Crops','$Currency','$Description','$Established_Year','$Founding_Story','$Landmarks','$Language','$Largest_cities','$Laws','$Leaders','$Located_at','$Motto','$Name','$Notable_cities','$Notable_Wars','$Notes','$Population','$Private_Notes','$Spoken_Languages','$Sports','$Tags','$Type','$Universe')"; 


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

	$Area = trim($data->Area);
	$Capital_cities = trim($data->Capital_cities);
	$Climate = trim($data->Climate);
	$Crops = trim($data->Crops);
	$Currency = trim($data->Currency);
	$Description = trim($data->Description);
	$Established_Year = trim($data->Established_Year);
	$Founding_Story = trim($data->Founding_Story);
	$Landmarks = trim($data->Landmarks);
	$Language = trim($data->Language);
	$Largest_cities = trim($data->Largest_cities);
	$Laws = trim($data->Laws);
	$Leaders = trim($data->Leaders);
	$Located_at = trim($data->Located_at);
	$Motto = trim($data->Motto);
	$Name = trim($data->Name);
	$Notable_cities = trim($data->Notable_cities);
	$Notable_Wars = trim($data->Notable_Wars);
	$Notes = trim($data->Notes);
	$Population = trim($data->Population);
	$Private_Notes = trim($data->Private_Notes);
	$Spoken_Languages = trim($data->Spoken_Languages);
	$Sports = trim($data->Sports);
	$Tags = trim($data->Tags);
	$Type = trim($data->Type);
	$Universe = trim($data->Universe);


    $sql = "UPDATE locations SET 
Area = '$Area',Capital_cities = '$Capital_cities',Climate = '$Climate',Crops = '$Crops',Currency = '$Currency',Description = '$Description',Established_Year = '$Established_Year',Founding_Story = '$Founding_Story',Landmarks = '$Landmarks',Language = '$Language',Largest_cities = '$Largest_cities',Laws = '$Laws',Leaders = '$Leaders',Located_at = '$Located_at',Motto = '$Motto',Name = '$Name',Notable_cities = '$Notable_cities',Notable_Wars = '$Notable_Wars',Notes = '$Notes',Population = '$Population',Private_Notes = '$Private_Notes',Spoken_Languages = '$Spoken_Languages',Sports = '$Sports',Tags = '$Tags',Type = '$Type',Universe = '$Universe'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM lores Where user_id = '$user_id',id = '$id'";

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

function addLore($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Background_information = trim($data->Background_information);
	$Believability = trim($data->Believability);
	$Believers = trim($data->Believers);
	$Buildings = trim($data->Buildings);
	$Characters = trim($data->Characters);
	$Conditions = trim($data->Conditions);
	$Continents = trim($data->Continents);
	$Countries = trim($data->Countries);
	$Created_phrases = trim($data->Created_phrases);
	$Created_traditions = trim($data->Created_traditions);
	$Creatures = trim($data->Creatures);
	$Criticism = trim($data->Criticism);
	$Date_recorded = trim($data->Date_recorded);
	$Deities = trim($data->Deities);
	$Dialect = trim($data->Dialect);
	$Evolution_over_time = trim($data->Evolution_over_time);
	$False_parts = trim($data->False_parts);
	$Floras = trim($data->Floras);
	$Foods = trim($data->Foods);
	$Full_text = trim($data->Full_text);
	$Genre = trim($data->Genre);
	$Geographical_variations = trim($data->Geographical_variations);
	$Governments = trim($data->Governments);
	$Groups = trim($data->Groups);
	$Historical_context = trim($data->Historical_context);
	$Hoaxes = trim($data->Hoaxes);
	$Impact = trim($data->Impact);
	$Important_translations = trim($data->Important_translations);
	$Influence_on_modern_times = trim($data->Influence_on_modern_times);
	$Inspirations = trim($data->Inspirations);
	$Interpretations = trim($data->Interpretations);
	$Jobs = trim($data->Jobs);
	$Landmarks = trim($data->Landmarks);
	$Magic = trim($data->Magic);
	$Media_adaptations = trim($data->Media_adaptations);
	$Morals = trim($data->Morals);
	$Motivations = trim($data->Motivations);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Original_author = trim($data->Original_author);
	$Original_languages = trim($data->Original_languages);
	$Original_telling = trim($data->Original_telling);
	$Planets = trim($data->Planets);
	$Private_Notes = trim($data->Private_Notes);
	$Propagation_method = trim($data->Propagation_method);
	$Races = trim($data->Races);
	$Reception = trim($data->Reception);
	$Related_lores = trim($data->Related_lores);
	$Religions = trim($data->Religions);
	$Schools = trim($data->Schools);
	$Source = trim($data->Source);
	$Sports = trim($data->Sports);
	$Structure = trim($data->Structure);
	$Subjects = trim($data->Subjects);
	$Summary = trim($data->Summary);
	$Symbolisms = trim($data->Symbolisms);
	$Tags = trim($data->Tags);
	$Technologies = trim($data->Technologies);
	$Time_period = trim($data->Time_period);
	$Tone = trim($data->Tone);
	$Towns = trim($data->Towns);
	$Traditions = trim($data->Traditions);
	$Translation_variations = trim($data->Translation_variations);
	$True_parts = trim($data->True_parts);
	$Type = trim($data->Type);
	$Universe = trim($data->Universe);
	$Variations = trim($data->Variations);
	$Vehicles = trim($data->Vehicles);


    $sql = "INSERT INTO lores(Background_information,Believability,Believers,Buildings,Characters,Conditions,Continents,Countries,Created_phrases,Created_traditions,Creatures,Criticism,Date_recorded,Deities,Dialect,Evolution_over_time,False_parts,Floras,Foods,Full_text,Genre,Geographical_variations,Governments,Groups,Historical_context,Hoaxes,Impact,Important_translations,Influence_on_modern_times,Inspirations,Interpretations,Jobs,Landmarks,Magic,Media_adaptations,Morals,Motivations,Name,Notes,Original_author,Original_languages,Original_telling,Planets,Private_Notes,Propagation_method,Races,Reception,Related_lores,Religions,Schools,Source,Sports,Structure,Subjects,Summary,Symbolisms,Tags,Technologies,Time_period,Tone,Towns,Traditions,Translation_variations,True_parts,Type,Universe,Variations,Vehicles) 
VALUES('$Background_information','$Believability','$Believers','$Buildings','$Characters','$Conditions','$Continents','$Countries','$Created_phrases','$Created_traditions','$Creatures','$Criticism','$Date_recorded','$Deities','$Dialect','$Evolution_over_time','$False_parts','$Floras','$Foods','$Full_text','$Genre','$Geographical_variations','$Governments','$Groups','$Historical_context','$Hoaxes','$Impact','$Important_translations','$Influence_on_modern_times','$Inspirations','$Interpretations','$Jobs','$Landmarks','$Magic','$Media_adaptations','$Morals','$Motivations','$Name','$Notes','$Original_author','$Original_languages','$Original_telling','$Planets','$Private_Notes','$Propagation_method','$Races','$Reception','$Related_lores','$Religions','$Schools','$Source','$Sports','$Structure','$Subjects','$Summary','$Symbolisms','$Tags','$Technologies','$Time_period','$Tone','$Towns','$Traditions','$Translation_variations','$True_parts','$Type','$Universe','$Variations','$Vehicles')"; 


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

	$Background_information = trim($data->Background_information);
	$Believability = trim($data->Believability);
	$Believers = trim($data->Believers);
	$Buildings = trim($data->Buildings);
	$Characters = trim($data->Characters);
	$Conditions = trim($data->Conditions);
	$Continents = trim($data->Continents);
	$Countries = trim($data->Countries);
	$Created_phrases = trim($data->Created_phrases);
	$Created_traditions = trim($data->Created_traditions);
	$Creatures = trim($data->Creatures);
	$Criticism = trim($data->Criticism);
	$Date_recorded = trim($data->Date_recorded);
	$Deities = trim($data->Deities);
	$Dialect = trim($data->Dialect);
	$Evolution_over_time = trim($data->Evolution_over_time);
	$False_parts = trim($data->False_parts);
	$Floras = trim($data->Floras);
	$Foods = trim($data->Foods);
	$Full_text = trim($data->Full_text);
	$Genre = trim($data->Genre);
	$Geographical_variations = trim($data->Geographical_variations);
	$Governments = trim($data->Governments);
	$Groups = trim($data->Groups);
	$Historical_context = trim($data->Historical_context);
	$Hoaxes = trim($data->Hoaxes);
	$Impact = trim($data->Impact);
	$Important_translations = trim($data->Important_translations);
	$Influence_on_modern_times = trim($data->Influence_on_modern_times);
	$Inspirations = trim($data->Inspirations);
	$Interpretations = trim($data->Interpretations);
	$Jobs = trim($data->Jobs);
	$Landmarks = trim($data->Landmarks);
	$Magic = trim($data->Magic);
	$Media_adaptations = trim($data->Media_adaptations);
	$Morals = trim($data->Morals);
	$Motivations = trim($data->Motivations);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Original_author = trim($data->Original_author);
	$Original_languages = trim($data->Original_languages);
	$Original_telling = trim($data->Original_telling);
	$Planets = trim($data->Planets);
	$Private_Notes = trim($data->Private_Notes);
	$Propagation_method = trim($data->Propagation_method);
	$Races = trim($data->Races);
	$Reception = trim($data->Reception);
	$Related_lores = trim($data->Related_lores);
	$Religions = trim($data->Religions);
	$Schools = trim($data->Schools);
	$Source = trim($data->Source);
	$Sports = trim($data->Sports);
	$Structure = trim($data->Structure);
	$Subjects = trim($data->Subjects);
	$Summary = trim($data->Summary);
	$Symbolisms = trim($data->Symbolisms);
	$Tags = trim($data->Tags);
	$Technologies = trim($data->Technologies);
	$Time_period = trim($data->Time_period);
	$Tone = trim($data->Tone);
	$Towns = trim($data->Towns);
	$Traditions = trim($data->Traditions);
	$Translation_variations = trim($data->Translation_variations);
	$True_parts = trim($data->True_parts);
	$Type = trim($data->Type);
	$Universe = trim($data->Universe);
	$Variations = trim($data->Variations);
	$Vehicles = trim($data->Vehicles);


    $sql = "UPDATE lores SET 
Background_information = '$Background_information',Believability = '$Believability',Believers = '$Believers',Buildings = '$Buildings',Characters = '$Characters',Conditions = '$Conditions',Continents = '$Continents',Countries = '$Countries',Created_phrases = '$Created_phrases',Created_traditions = '$Created_traditions',Creatures = '$Creatures',Criticism = '$Criticism',Date_recorded = '$Date_recorded',Deities = '$Deities',Dialect = '$Dialect',Evolution_over_time = '$Evolution_over_time',False_parts = '$False_parts',Floras = '$Floras',Foods = '$Foods',Full_text = '$Full_text',Genre = '$Genre',Geographical_variations = '$Geographical_variations',Governments = '$Governments',Groups = '$Groups',Historical_context = '$Historical_context',Hoaxes = '$Hoaxes',Impact = '$Impact',Important_translations = '$Important_translations',Influence_on_modern_times = '$Influence_on_modern_times',Inspirations = '$Inspirations',Interpretations = '$Interpretations',Jobs = '$Jobs',Landmarks = '$Landmarks',Magic = '$Magic',Media_adaptations = '$Media_adaptations',Morals = '$Morals',Motivations = '$Motivations',Name = '$Name',Notes = '$Notes',Original_author = '$Original_author',Original_languages = '$Original_languages',Original_telling = '$Original_telling',Planets = '$Planets',Private_Notes = '$Private_Notes',Propagation_method = '$Propagation_method',Races = '$Races',Reception = '$Reception',Related_lores = '$Related_lores',Religions = '$Religions',Schools = '$Schools',Source = '$Source',Sports = '$Sports',Structure = '$Structure',Subjects = '$Subjects',Summary = '$Summary',Symbolisms = '$Symbolisms',Tags = '$Tags',Technologies = '$Technologies',Time_period = '$Time_period',Tone = '$Tone',Towns = '$Towns',Traditions = '$Traditions',Translation_variations = '$Translation_variations',True_parts = '$True_parts',Type = '$Type',Universe = '$Universe',Variations = '$Variations',Vehicles = '$Vehicles'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM magics Where user_id = '$user_id',id = '$id'";

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

function addMagic($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Aftereffects = trim($data->Aftereffects);
	$Conditions = trim($data->Conditions);
	$Deities = trim($data->Deities);
	$Description = trim($data->Description);
	$Education = trim($data->Education);
	$Effects = trim($data->Effects);
	$Element = trim($data->Element);
	$Limitations = trim($data->Limitations);
	$Materials_required = trim($data->Materials_required);
	$Name = trim($data->Name);
	$Negative_effects = trim($data->Negative_effects);
	$Neutral_effects = trim($data->Neutral_effects);
	$Notes = trim($data->Notes);
	$Positive_effects = trim($data->Positive_effects);
	$Private_notes = trim($data->Private_notes);
	$Resource_costs = trim($data->Resource_costs);
	$Scale = trim($data->Scale);
	$Skills_required = trim($data->Skills_required);
	$Tags = trim($data->Tags);
	$Type_of_magic = trim($data->Type_of_magic);
	$Universe = trim($data->Universe);
	$Visuals = trim($data->Visuals);


    $sql = "INSERT INTO magics(Aftereffects,Conditions,Deities,Description,Education,Effects,Element,Limitations,Materials_required,Name,Negative_effects,Neutral_effects,Notes,Positive_effects,Private_notes,Resource_costs,Scale,Skills_required,Tags,Type_of_magic,Universe,Visuals) 
VALUES('$Aftereffects','$Conditions','$Deities','$Description','$Education','$Effects','$Element','$Limitations','$Materials_required','$Name','$Negative_effects','$Neutral_effects','$Notes','$Positive_effects','$Private_notes','$Resource_costs','$Scale','$Skills_required','$Tags','$Type_of_magic','$Universe','$Visuals')"; 


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

	$Aftereffects = trim($data->Aftereffects);
	$Conditions = trim($data->Conditions);
	$Deities = trim($data->Deities);
	$Description = trim($data->Description);
	$Education = trim($data->Education);
	$Effects = trim($data->Effects);
	$Element = trim($data->Element);
	$Limitations = trim($data->Limitations);
	$Materials_required = trim($data->Materials_required);
	$Name = trim($data->Name);
	$Negative_effects = trim($data->Negative_effects);
	$Neutral_effects = trim($data->Neutral_effects);
	$Notes = trim($data->Notes);
	$Positive_effects = trim($data->Positive_effects);
	$Private_notes = trim($data->Private_notes);
	$Resource_costs = trim($data->Resource_costs);
	$Scale = trim($data->Scale);
	$Skills_required = trim($data->Skills_required);
	$Tags = trim($data->Tags);
	$Type_of_magic = trim($data->Type_of_magic);
	$Universe = trim($data->Universe);
	$Visuals = trim($data->Visuals);


    $sql = "UPDATE magics SET 
Aftereffects = '$Aftereffects',Conditions = '$Conditions',Deities = '$Deities',Description = '$Description',Education = '$Education',Effects = '$Effects',Element = '$Element',Limitations = '$Limitations',Materials_required = '$Materials_required',Name = '$Name',Negative_effects = '$Negative_effects',Neutral_effects = '$Neutral_effects',Notes = '$Notes',Positive_effects = '$Positive_effects',Private_notes = '$Private_notes',Resource_costs = '$Resource_costs',Scale = '$Scale',Skills_required = '$Skills_required',Tags = '$Tags',Type_of_magic = '$Type_of_magic',Universe = '$Universe',Visuals = '$Visuals'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM organizations Where user_id = '$user_id',id = '$id'";

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

function addOrganization($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Address = trim($data->Address);
	$Alternate_names = trim($data->Alternate_names);
	$Closure_year = trim($data->Closure_year);
	$Description = trim($data->Description);
	$Formation_year = trim($data->Formation_year);
	$Headquarters = trim($data->Headquarters);
	$Locations = trim($data->Locations);
	$Members = trim($data->Members);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Offices = trim($data->Offices);
	$Organization_structure = trim($data->Organization_structure);
	$Owner = trim($data->Owner);
	$Private_Notes = trim($data->Private_Notes);
	$Purpose = trim($data->Purpose);
	$Rival_organizations = trim($data->Rival_organizations);
	$Services = trim($data->Services);
	$Sister_organizations = trim($data->Sister_organizations);
	$Sub_organizations = trim($data->Sub_organizations);
	$Super_organizations = trim($data->Super_organizations);
	$Tags = trim($data->Tags);
	$Type_of_organization = trim($data->Type_of_organization);
	$Universe = trim($data->Universe);


    $sql = "INSERT INTO organizations(Address,Alternate_names,Closure_year,Description,Formation_year,Headquarters,Locations,Members,Name,Notes,Offices,Organization_structure,Owner,Private_Notes,Purpose,Rival_organizations,Services,Sister_organizations,Sub_organizations,Super_organizations,Tags,Type_of_organization,Universe) 
VALUES('$Address','$Alternate_names','$Closure_year','$Description','$Formation_year','$Headquarters','$Locations','$Members','$Name','$Notes','$Offices','$Organization_structure','$Owner','$Private_Notes','$Purpose','$Rival_organizations','$Services','$Sister_organizations','$Sub_organizations','$Super_organizations','$Tags','$Type_of_organization','$Universe')"; 


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

	$Address = trim($data->Address);
	$Alternate_names = trim($data->Alternate_names);
	$Closure_year = trim($data->Closure_year);
	$Description = trim($data->Description);
	$Formation_year = trim($data->Formation_year);
	$Headquarters = trim($data->Headquarters);
	$Locations = trim($data->Locations);
	$Members = trim($data->Members);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Offices = trim($data->Offices);
	$Organization_structure = trim($data->Organization_structure);
	$Owner = trim($data->Owner);
	$Private_Notes = trim($data->Private_Notes);
	$Purpose = trim($data->Purpose);
	$Rival_organizations = trim($data->Rival_organizations);
	$Services = trim($data->Services);
	$Sister_organizations = trim($data->Sister_organizations);
	$Sub_organizations = trim($data->Sub_organizations);
	$Super_organizations = trim($data->Super_organizations);
	$Tags = trim($data->Tags);
	$Type_of_organization = trim($data->Type_of_organization);
	$Universe = trim($data->Universe);


    $sql = "UPDATE organizations SET 
Address = '$Address',Alternate_names = '$Alternate_names',Closure_year = '$Closure_year',Description = '$Description',Formation_year = '$Formation_year',Headquarters = '$Headquarters',Locations = '$Locations',Members = '$Members',Name = '$Name',Notes = '$Notes',Offices = '$Offices',Organization_structure = '$Organization_structure',Owner = '$Owner',Private_Notes = '$Private_Notes',Purpose = '$Purpose',Rival_organizations = '$Rival_organizations',Services = '$Services',Sister_organizations = '$Sister_organizations',Sub_organizations = '$Sub_organizations',Super_organizations = '$Super_organizations',Tags = '$Tags',Type_of_organization = '$Type_of_organization',Universe = '$Universe'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM planets Where user_id = '$user_id',id = '$id'";

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

function addPlanet($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Atmosphere = trim($data->Atmosphere);
	$Calendar_System = trim($data->Calendar_System);
	$Climate = trim($data->Climate);
	$Continents = trim($data->Continents);
	$Countries = trim($data->Countries);
	$Creatures = trim($data->Creatures);
	$Day_sky = trim($data->Day_sky);
	$Deities = trim($data->Deities);
	$Description = trim($data->Description);
	$First_Inhabitants_Story = trim($data->First_Inhabitants_Story);
	$Flora = trim($data->Flora);
	$Groups = trim($data->Groups);
	$Landmarks = trim($data->Landmarks);
	$Languages = trim($data->Languages);
	$Length_Of_Day = trim($data->Length_Of_Day);
	$Length_Of_Night = trim($data->Length_Of_Night);
	$Locations = trim($data->Locations);
	$Moons = trim($data->Moons);
	$Name = trim($data->Name);
	$Natural_diasters = trim($data->Natural_diasters);
	$Natural_Resources = trim($data->Natural_Resources);
	$Nearby_planets = trim($data->Nearby_planets);
	$Night_sky = trim($data->Night_sky);
	$Notes = trim($data->Notes);
	$Orbit = trim($data->Orbit);
	$Population = trim($data->Population);
	$Private_Notes = trim($data->Private_Notes);
	$Races = trim($data->Races);
	$Religions = trim($data->Religions);
	$Seasons = trim($data->Seasons);
	$Size = trim($data->Size);
	$Suns = trim($data->Suns);
	$Surface = trim($data->Surface);
	$Tags = trim($data->Tags);
	$Temperature = trim($data->Temperature);
	$Towns = trim($data->Towns);
	$Universe = trim($data->Universe);
	$Visible_Constellations = trim($data->Visible_Constellations);
	$Water_Content = trim($data->Water_Content);
	$Weather = trim($data->Weather);
	$World_History = trim($data->World_History);


    $sql = "INSERT INTO planets(Atmosphere,Calendar_System,Climate,Continents,Countries,Creatures,Day_sky,Deities,Description,First_Inhabitants_Story,Flora,Groups,Landmarks,Languages,Length_Of_Day,Length_Of_Night,Locations,Moons,Name,Natural_diasters,Natural_Resources,Nearby_planets,Night_sky,Notes,Orbit,Population,Private_Notes,Races,Religions,Seasons,Size,Suns,Surface,Tags,Temperature,Towns,Universe,Visible_Constellations,Water_Content,Weather,World_History) 
VALUES('$Atmosphere','$Calendar_System','$Climate','$Continents','$Countries','$Creatures','$Day_sky','$Deities','$Description','$First_Inhabitants_Story','$Flora','$Groups','$Landmarks','$Languages','$Length_Of_Day','$Length_Of_Night','$Locations','$Moons','$Name','$Natural_diasters','$Natural_Resources','$Nearby_planets','$Night_sky','$Notes','$Orbit','$Population','$Private_Notes','$Races','$Religions','$Seasons','$Size','$Suns','$Surface','$Tags','$Temperature','$Towns','$Universe','$Visible_Constellations','$Water_Content','$Weather','$World_History')"; 


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

	$Atmosphere = trim($data->Atmosphere);
	$Calendar_System = trim($data->Calendar_System);
	$Climate = trim($data->Climate);
	$Continents = trim($data->Continents);
	$Countries = trim($data->Countries);
	$Creatures = trim($data->Creatures);
	$Day_sky = trim($data->Day_sky);
	$Deities = trim($data->Deities);
	$Description = trim($data->Description);
	$First_Inhabitants_Story = trim($data->First_Inhabitants_Story);
	$Flora = trim($data->Flora);
	$Groups = trim($data->Groups);
	$Landmarks = trim($data->Landmarks);
	$Languages = trim($data->Languages);
	$Length_Of_Day = trim($data->Length_Of_Day);
	$Length_Of_Night = trim($data->Length_Of_Night);
	$Locations = trim($data->Locations);
	$Moons = trim($data->Moons);
	$Name = trim($data->Name);
	$Natural_diasters = trim($data->Natural_diasters);
	$Natural_Resources = trim($data->Natural_Resources);
	$Nearby_planets = trim($data->Nearby_planets);
	$Night_sky = trim($data->Night_sky);
	$Notes = trim($data->Notes);
	$Orbit = trim($data->Orbit);
	$Population = trim($data->Population);
	$Private_Notes = trim($data->Private_Notes);
	$Races = trim($data->Races);
	$Religions = trim($data->Religions);
	$Seasons = trim($data->Seasons);
	$Size = trim($data->Size);
	$Suns = trim($data->Suns);
	$Surface = trim($data->Surface);
	$Tags = trim($data->Tags);
	$Temperature = trim($data->Temperature);
	$Towns = trim($data->Towns);
	$Universe = trim($data->Universe);
	$Visible_Constellations = trim($data->Visible_Constellations);
	$Water_Content = trim($data->Water_Content);
	$Weather = trim($data->Weather);
	$World_History = trim($data->World_History);


    $sql = "UPDATE planets SET 
Atmosphere = '$Atmosphere',Calendar_System = '$Calendar_System',Climate = '$Climate',Continents = '$Continents',Countries = '$Countries',Creatures = '$Creatures',Day_sky = '$Day_sky',Deities = '$Deities',Description = '$Description',First_Inhabitants_Story = '$First_Inhabitants_Story',Flora = '$Flora',Groups = '$Groups',Landmarks = '$Landmarks',Languages = '$Languages',Length_Of_Day = '$Length_Of_Day',Length_Of_Night = '$Length_Of_Night',Locations = '$Locations',Moons = '$Moons',Name = '$Name',Natural_diasters = '$Natural_diasters',Natural_Resources = '$Natural_Resources',Nearby_planets = '$Nearby_planets',Night_sky = '$Night_sky',Notes = '$Notes',Orbit = '$Orbit',Population = '$Population',Private_Notes = '$Private_Notes',Races = '$Races',Religions = '$Religions',Seasons = '$Seasons',Size = '$Size',Suns = '$Suns',Surface = '$Surface',Tags = '$Tags',Temperature = '$Temperature',Towns = '$Towns',Universe = '$Universe',Visible_Constellations = '$Visible_Constellations',Water_Content = '$Water_Content',Weather = '$Weather',World_History = '$World_History'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM races Where user_id = '$user_id',id = '$id'";

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

function addRace($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Beliefs = trim($data->Beliefs);
	$Body_shape = trim($data->Body_shape);
	$Conditions = trim($data->Conditions);
	$Description = trim($data->Description);
	$Economics = trim($data->Economics);
	$Famous_figures = trim($data->Famous_figures);
	$Favorite_foods = trim($data->Favorite_foods);
	$General_height = trim($data->General_height);
	$General_weight = trim($data->General_weight);
	$Governments = trim($data->Governments);
	$Name = trim($data->Name);
	$Notable_events = trim($data->Notable_events);
	$Notable_features = trim($data->Notable_features);
	$Notes = trim($data->Notes);
	$Occupations = trim($data->Occupations);
	$Other_Names = trim($data->Other_Names);
	$Physical_variance = trim($data->Physical_variance);
	$Private_notes = trim($data->Private_notes);
	$Skin_colors = trim($data->Skin_colors);
	$Strengths = trim($data->Strengths);
	$Tags = trim($data->Tags);
	$Technologies = trim($data->Technologies);
	$Traditions = trim($data->Traditions);
	$Typical_clothing = trim($data->Typical_clothing);
	$Universe = trim($data->Universe);
	$Weaknesses = trim($data->Weaknesses);


    $sql = "INSERT INTO races(Beliefs,Body_shape,Conditions,Description,Economics,Famous_figures,Favorite_foods,General_height,General_weight,Governments,Name,Notable_events,Notable_features,Notes,Occupations,Other_Names,Physical_variance,Private_notes,Skin_colors,Strengths,Tags,Technologies,Traditions,Typical_clothing,Universe,Weaknesses) 
VALUES('$Beliefs','$Body_shape','$Conditions','$Description','$Economics','$Famous_figures','$Favorite_foods','$General_height','$General_weight','$Governments','$Name','$Notable_events','$Notable_features','$Notes','$Occupations','$Other_Names','$Physical_variance','$Private_notes','$Skin_colors','$Strengths','$Tags','$Technologies','$Traditions','$Typical_clothing','$Universe','$Weaknesses')"; 


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

	$Beliefs = trim($data->Beliefs);
	$Body_shape = trim($data->Body_shape);
	$Conditions = trim($data->Conditions);
	$Description = trim($data->Description);
	$Economics = trim($data->Economics);
	$Famous_figures = trim($data->Famous_figures);
	$Favorite_foods = trim($data->Favorite_foods);
	$General_height = trim($data->General_height);
	$General_weight = trim($data->General_weight);
	$Governments = trim($data->Governments);
	$Name = trim($data->Name);
	$Notable_events = trim($data->Notable_events);
	$Notable_features = trim($data->Notable_features);
	$Notes = trim($data->Notes);
	$Occupations = trim($data->Occupations);
	$Other_Names = trim($data->Other_Names);
	$Physical_variance = trim($data->Physical_variance);
	$Private_notes = trim($data->Private_notes);
	$Skin_colors = trim($data->Skin_colors);
	$Strengths = trim($data->Strengths);
	$Tags = trim($data->Tags);
	$Technologies = trim($data->Technologies);
	$Traditions = trim($data->Traditions);
	$Typical_clothing = trim($data->Typical_clothing);
	$Universe = trim($data->Universe);
	$Weaknesses = trim($data->Weaknesses);


    $sql = "UPDATE races SET 
Beliefs = '$Beliefs',Body_shape = '$Body_shape',Conditions = '$Conditions',Description = '$Description',Economics = '$Economics',Famous_figures = '$Famous_figures',Favorite_foods = '$Favorite_foods',General_height = '$General_height',General_weight = '$General_weight',Governments = '$Governments',Name = '$Name',Notable_events = '$Notable_events',Notable_features = '$Notable_features',Notes = '$Notes',Occupations = '$Occupations',Other_Names = '$Other_Names',Physical_variance = '$Physical_variance',Private_notes = '$Private_notes',Skin_colors = '$Skin_colors',Strengths = '$Strengths',Tags = '$Tags',Technologies = '$Technologies',Traditions = '$Traditions',Typical_clothing = '$Typical_clothing',Universe = '$Universe',Weaknesses = '$Weaknesses'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM religions Where user_id = '$user_id',id = '$id'";

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

function addReligion($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Artifacts = trim($data->Artifacts);
	$Deities = trim($data->Deities);
	$Description = trim($data->Description);
	$Holidays = trim($data->Holidays);
	$Initiation_process = trim($data->Initiation_process);
	$Name = trim($data->Name);
	$Notable_figures = trim($data->Notable_figures);
	$Notes = trim($data->Notes);
	$Obligations = trim($data->Obligations);
	$Origin_story = trim($data->Origin_story);
	$Other_Names = trim($data->Other_Names);
	$Places_of_worship = trim($data->Places_of_worship);
	$Practicing_locations = trim($data->Practicing_locations);
	$Practicing_races = trim($data->Practicing_races);
	$Private_notes = trim($data->Private_notes);
	$Prophecies = trim($data->Prophecies);
	$Rituals = trim($data->Rituals);
	$Tags = trim($data->Tags);
	$Teachings = trim($data->Teachings);
	$Traditions = trim($data->Traditions);
	$Universe = trim($data->Universe);
	$Vision_of_paradise = trim($data->Vision_of_paradise);
	$Worship_services = trim($data->Worship_services);


    $sql = "INSERT INTO religions(Artifacts,Deities,Description,Holidays,Initiation_process,Name,Notable_figures,Notes,Obligations,Origin_story,Other_Names,Places_of_worship,Practicing_locations,Practicing_races,Private_notes,Prophecies,Rituals,Tags,Teachings,Traditions,Universe,Vision_of_paradise,Worship_services) 
VALUES('$Artifacts','$Deities','$Description','$Holidays','$Initiation_process','$Name','$Notable_figures','$Notes','$Obligations','$Origin_story','$Other_Names','$Places_of_worship','$Practicing_locations','$Practicing_races','$Private_notes','$Prophecies','$Rituals','$Tags','$Teachings','$Traditions','$Universe','$Vision_of_paradise','$Worship_services')"; 


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

	$Artifacts = trim($data->Artifacts);
	$Deities = trim($data->Deities);
	$Description = trim($data->Description);
	$Holidays = trim($data->Holidays);
	$Initiation_process = trim($data->Initiation_process);
	$Name = trim($data->Name);
	$Notable_figures = trim($data->Notable_figures);
	$Notes = trim($data->Notes);
	$Obligations = trim($data->Obligations);
	$Origin_story = trim($data->Origin_story);
	$Other_Names = trim($data->Other_Names);
	$Places_of_worship = trim($data->Places_of_worship);
	$Practicing_locations = trim($data->Practicing_locations);
	$Practicing_races = trim($data->Practicing_races);
	$Private_notes = trim($data->Private_notes);
	$Prophecies = trim($data->Prophecies);
	$Rituals = trim($data->Rituals);
	$Tags = trim($data->Tags);
	$Teachings = trim($data->Teachings);
	$Traditions = trim($data->Traditions);
	$Universe = trim($data->Universe);
	$Vision_of_paradise = trim($data->Vision_of_paradise);
	$Worship_services = trim($data->Worship_services);


    $sql = "UPDATE religions SET 
Artifacts = '$Artifacts',Deities = '$Deities',Description = '$Description',Holidays = '$Holidays',Initiation_process = '$Initiation_process',Name = '$Name',Notable_figures = '$Notable_figures',Notes = '$Notes',Obligations = '$Obligations',Origin_story = '$Origin_story',Other_Names = '$Other_Names',Places_of_worship = '$Places_of_worship',Practicing_locations = '$Practicing_locations',Practicing_races = '$Practicing_races',Private_notes = '$Private_notes',Prophecies = '$Prophecies',Rituals = '$Rituals',Tags = '$Tags',Teachings = '$Teachings',Traditions = '$Traditions',Universe = '$Universe',Vision_of_paradise = '$Vision_of_paradise',Worship_services = '$Worship_services'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM scenes Where user_id = '$user_id',id = '$id'";

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

function addScene($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Characters_in_scene = trim($data->Characters_in_scene);
	$Description = trim($data->Description);
	$Items_in_scene = trim($data->Items_in_scene);
	$Locations_in_scene = trim($data->Locations_in_scene);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);
	$Results = trim($data->Results);
	$Summary = trim($data->Summary);
	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$What_caused_this = trim($data->What_caused_this);


    $sql = "INSERT INTO scenes(Characters_in_scene,Description,Items_in_scene,Locations_in_scene,Name,Notes,Private_notes,Results,Summary,Tags,Universe,What_caused_this) 
VALUES('$Characters_in_scene','$Description','$Items_in_scene','$Locations_in_scene','$Name','$Notes','$Private_notes','$Results','$Summary','$Tags','$Universe','$What_caused_this')"; 


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

	$Characters_in_scene = trim($data->Characters_in_scene);
	$Description = trim($data->Description);
	$Items_in_scene = trim($data->Items_in_scene);
	$Locations_in_scene = trim($data->Locations_in_scene);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Private_notes = trim($data->Private_notes);
	$Results = trim($data->Results);
	$Summary = trim($data->Summary);
	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$What_caused_this = trim($data->What_caused_this);


    $sql = "UPDATE scenes SET 
Characters_in_scene = '$Characters_in_scene',Description = '$Description',Items_in_scene = '$Items_in_scene',Locations_in_scene = '$Locations_in_scene',Name = '$Name',Notes = '$Notes',Private_notes = '$Private_notes',Results = '$Results',Summary = '$Summary',Tags = '$Tags',Universe = '$Universe',What_caused_this = '$What_caused_this'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM sports Where user_id = '$user_id',id = '$id'";

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

function addSport($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Common_injuries = trim($data->Common_injuries);
	$Countries = trim($data->Countries);
	$Creators = trim($data->Creators);
	$Description = trim($data->Description);
	$Equipment = trim($data->Equipment);
	$Evolution = trim($data->Evolution);
	$Famous_games = trim($data->Famous_games);
	$Game_time = trim($data->Game_time);
	$How_to_win = trim($data->How_to_win);
	$Merchandise = trim($data->Merchandise);
	$Most_important_muscles = trim($data->Most_important_muscles);
	$Name = trim($data->Name);
	$Nicknames = trim($data->Nicknames);
	$Notes = trim($data->Notes);
	$Number_of_players = trim($data->Number_of_players);
	$Origin_story = trim($data->Origin_story);
	$Penalties = trim($data->Penalties);
	$Play_area = trim($data->Play_area);
	$Players = trim($data->Players);
	$Popularity = trim($data->Popularity);
	$Positions = trim($data->Positions);
	$Private_Notes = trim($data->Private_Notes);
	$Rules = trim($data->Rules);
	$Scoring = trim($data->Scoring);
	$Strategies = trim($data->Strategies);
	$Tags = trim($data->Tags);
	$Teams = trim($data->Teams);
	$Traditions = trim($data->Traditions);
	$Uniforms = trim($data->Uniforms);
	$Universe = trim($data->Universe);


    $sql = "INSERT INTO sports(Common_injuries,Countries,Creators,Description,Equipment,Evolution,Famous_games,Game_time,How_to_win,Merchandise,Most_important_muscles,Name,Nicknames,Notes,Number_of_players,Origin_story,Penalties,Play_area,Players,Popularity,Positions,Private_Notes,Rules,Scoring,Strategies,Tags,Teams,Traditions,Uniforms,Universe) 
VALUES('$Common_injuries','$Countries','$Creators','$Description','$Equipment','$Evolution','$Famous_games','$Game_time','$How_to_win','$Merchandise','$Most_important_muscles','$Name','$Nicknames','$Notes','$Number_of_players','$Origin_story','$Penalties','$Play_area','$Players','$Popularity','$Positions','$Private_Notes','$Rules','$Scoring','$Strategies','$Tags','$Teams','$Traditions','$Uniforms','$Universe')"; 


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

	$Common_injuries = trim($data->Common_injuries);
	$Countries = trim($data->Countries);
	$Creators = trim($data->Creators);
	$Description = trim($data->Description);
	$Equipment = trim($data->Equipment);
	$Evolution = trim($data->Evolution);
	$Famous_games = trim($data->Famous_games);
	$Game_time = trim($data->Game_time);
	$How_to_win = trim($data->How_to_win);
	$Merchandise = trim($data->Merchandise);
	$Most_important_muscles = trim($data->Most_important_muscles);
	$Name = trim($data->Name);
	$Nicknames = trim($data->Nicknames);
	$Notes = trim($data->Notes);
	$Number_of_players = trim($data->Number_of_players);
	$Origin_story = trim($data->Origin_story);
	$Penalties = trim($data->Penalties);
	$Play_area = trim($data->Play_area);
	$Players = trim($data->Players);
	$Popularity = trim($data->Popularity);
	$Positions = trim($data->Positions);
	$Private_Notes = trim($data->Private_Notes);
	$Rules = trim($data->Rules);
	$Scoring = trim($data->Scoring);
	$Strategies = trim($data->Strategies);
	$Tags = trim($data->Tags);
	$Teams = trim($data->Teams);
	$Traditions = trim($data->Traditions);
	$Uniforms = trim($data->Uniforms);
	$Universe = trim($data->Universe);


    $sql = "UPDATE sports SET 
Common_injuries = '$Common_injuries',Countries = '$Countries',Creators = '$Creators',Description = '$Description',Equipment = '$Equipment',Evolution = '$Evolution',Famous_games = '$Famous_games',Game_time = '$Game_time',How_to_win = '$How_to_win',Merchandise = '$Merchandise',Most_important_muscles = '$Most_important_muscles',Name = '$Name',Nicknames = '$Nicknames',Notes = '$Notes',Number_of_players = '$Number_of_players',Origin_story = '$Origin_story',Penalties = '$Penalties',Play_area = '$Play_area',Players = '$Players',Popularity = '$Popularity',Positions = '$Positions',Private_Notes = '$Private_Notes',Rules = '$Rules',Scoring = '$Scoring',Strategies = '$Strategies',Tags = '$Tags',Teams = '$Teams',Traditions = '$Traditions',Uniforms = '$Uniforms',Universe = '$Universe'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM technologies Where user_id = '$user_id',id = '$id'";

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

function addTechnologie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Characters = trim($data->Characters);
	$Child_technologies = trim($data->Child_technologies);
	$Colors = trim($data->Colors);
	$Cost = trim($data->Cost);
	$Countries = trim($data->Countries);
	$Creatures = trim($data->Creatures);
	$Description = trim($data->Description);
	$Groups = trim($data->Groups);
	$How_It_Works = trim($data->How_It_Works);
	$Magic_effects = trim($data->Magic_effects);
	$Manufacturing_Process = trim($data->Manufacturing_Process);
	$Materials = trim($data->Materials);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Other_Names = trim($data->Other_Names);
	$Parent_technologies = trim($data->Parent_technologies);
	$Physical_Description = trim($data->Physical_Description);
	$Planets = trim($data->Planets);
	$Private_Notes = trim($data->Private_Notes);
	$Purpose = trim($data->Purpose);
	$Rarity = trim($data->Rarity);
	$Related_technologies = trim($data->Related_technologies);
	$Resources_Used = trim($data->Resources_Used);
	$Sales_Process = trim($data->Sales_Process);
	$Size = trim($data->Size);
	$Tags = trim($data->Tags);
	$Towns = trim($data->Towns);
	$Universe = trim($data->Universe);
	$Weight = trim($data->Weight);


    $sql = "INSERT INTO technologies(Characters,Child_technologies,Colors,Cost,Countries,Creatures,Description,Groups,How_It_Works,Magic_effects,Manufacturing_Process,Materials,Name,Notes,Other_Names,Parent_technologies,Physical_Description,Planets,Private_Notes,Purpose,Rarity,Related_technologies,Resources_Used,Sales_Process,Size,Tags,Towns,Universe,Weight) 
VALUES('$Characters','$Child_technologies','$Colors','$Cost','$Countries','$Creatures','$Description','$Groups','$How_It_Works','$Magic_effects','$Manufacturing_Process','$Materials','$Name','$Notes','$Other_Names','$Parent_technologies','$Physical_Description','$Planets','$Private_Notes','$Purpose','$Rarity','$Related_technologies','$Resources_Used','$Sales_Process','$Size','$Tags','$Towns','$Universe','$Weight')"; 


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

	$Characters = trim($data->Characters);
	$Child_technologies = trim($data->Child_technologies);
	$Colors = trim($data->Colors);
	$Cost = trim($data->Cost);
	$Countries = trim($data->Countries);
	$Creatures = trim($data->Creatures);
	$Description = trim($data->Description);
	$Groups = trim($data->Groups);
	$How_It_Works = trim($data->How_It_Works);
	$Magic_effects = trim($data->Magic_effects);
	$Manufacturing_Process = trim($data->Manufacturing_Process);
	$Materials = trim($data->Materials);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Other_Names = trim($data->Other_Names);
	$Parent_technologies = trim($data->Parent_technologies);
	$Physical_Description = trim($data->Physical_Description);
	$Planets = trim($data->Planets);
	$Private_Notes = trim($data->Private_Notes);
	$Purpose = trim($data->Purpose);
	$Rarity = trim($data->Rarity);
	$Related_technologies = trim($data->Related_technologies);
	$Resources_Used = trim($data->Resources_Used);
	$Sales_Process = trim($data->Sales_Process);
	$Size = trim($data->Size);
	$Tags = trim($data->Tags);
	$Towns = trim($data->Towns);
	$Universe = trim($data->Universe);
	$Weight = trim($data->Weight);


    $sql = "UPDATE technologies SET 
Characters = '$Characters',Child_technologies = '$Child_technologies',Colors = '$Colors',Cost = '$Cost',Countries = '$Countries',Creatures = '$Creatures',Description = '$Description',Groups = '$Groups',How_It_Works = '$How_It_Works',Magic_effects = '$Magic_effects',Manufacturing_Process = '$Manufacturing_Process',Materials = '$Materials',Name = '$Name',Notes = '$Notes',Other_Names = '$Other_Names',Parent_technologies = '$Parent_technologies',Physical_Description = '$Physical_Description',Planets = '$Planets',Private_Notes = '$Private_Notes',Purpose = '$Purpose',Rarity = '$Rarity',Related_technologies = '$Related_technologies',Resources_Used = '$Resources_Used',Sales_Process = '$Sales_Process',Size = '$Size',Tags = '$Tags',Towns = '$Towns',Universe = '$Universe',Weight = '$Weight'    WHERE id = $id"; 

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
    $user_id = $_GET['user_id']; 
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

function addTimelineevententitie($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Created_at = trim($data->Created_at);
	$Entity_id = trim($data->Entity_id);
	$Entity_type = trim($data->Entity_type);
	$Notes = trim($data->Notes);
	$Timeline_event_id = trim($data->Timeline_event_id);
	$Updated_at = trim($data->Updated_at);


    $sql = "INSERT INTO timeline_event_entities(Created_at,Entity_id,Entity_type,Notes,Timeline_event_id,Updated_at) 
VALUES('$Created_at','$Entity_id','$Entity_type','$Notes','$Timeline_event_id','$Updated_at')"; 


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

	$Created_at = trim($data->Created_at);
	$Entity_id = trim($data->Entity_id);
	$Entity_type = trim($data->Entity_type);
	$Notes = trim($data->Notes);
	$Timeline_event_id = trim($data->Timeline_event_id);
	$Updated_at = trim($data->Updated_at);


    $sql = "UPDATE timeline_event_entities SET 
Created_at = '$Created_at',Entity_id = '$Entity_id',Entity_type = '$Entity_type',Notes = '$Notes',Timeline_event_id = '$Timeline_event_id',Updated_at = '$Updated_at'    WHERE id = $id"; 

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
    $user_id = $_GET['user_id']; 
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

function addTimelineevent($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Created_at = trim($data->Created_at);
	$Deleted_at = trim($data->Deleted_at);
	$Description = trim($data->Description);
	$Notes = trim($data->Notes);
	$Position = trim($data->Position);
	$Time_label = trim($data->Time_label);
	$Timeline_id = trim($data->Timeline_id);
	$Title = trim($data->Title);
	$Updated_at = trim($data->Updated_at);


    $sql = "INSERT INTO timeline_events(Created_at,Deleted_at,Description,Notes,Position,Time_label,Timeline_id,Title,Updated_at) 
VALUES('$Created_at','$Deleted_at','$Description','$Notes','$Position','$Time_label','$Timeline_id','$Title','$Updated_at')"; 


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

	$Created_at = trim($data->Created_at);
	$Deleted_at = trim($data->Deleted_at);
	$Description = trim($data->Description);
	$Notes = trim($data->Notes);
	$Position = trim($data->Position);
	$Time_label = trim($data->Time_label);
	$Timeline_id = trim($data->Timeline_id);
	$Title = trim($data->Title);
	$Updated_at = trim($data->Updated_at);


    $sql = "UPDATE timeline_events SET 
Created_at = '$Created_at',Deleted_at = '$Deleted_at',Description = '$Description',Notes = '$Notes',Position = '$Position',Time_label = '$Time_label',Timeline_id = '$Timeline_id',Title = '$Title',Updated_at = '$Updated_at'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM timelines Where user_id = '$user_id',id = '$id'";

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

function addTimeline($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Archived_at = trim($data->Archived_at);
	$Created_at = trim($data->Created_at);
	$Deleted_at = trim($data->Deleted_at);
	$Description = trim($data->Description);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Page_type = trim($data->Page_type);
	$Private_notes = trim($data->Private_notes);
	$Subtitle = trim($data->Subtitle);
	$Universe_id = trim($data->Universe_id);
	$Updated_at = trim($data->Updated_at);
	$User_id = trim($data->User_id);


    $sql = "INSERT INTO timelines(Archived_at,Created_at,Deleted_at,Description,Name,Notes,Page_type,Private_notes,Subtitle,Universe_id,Updated_at,User_id) 
VALUES('$Archived_at','$Created_at','$Deleted_at','$Description','$Name','$Notes','$Page_type','$Private_notes','$Subtitle','$Universe_id','$Updated_at','$User_id')"; 


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

	$Archived_at = trim($data->Archived_at);
	$Created_at = trim($data->Created_at);
	$Deleted_at = trim($data->Deleted_at);
	$Description = trim($data->Description);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Page_type = trim($data->Page_type);
	$Private_notes = trim($data->Private_notes);
	$Subtitle = trim($data->Subtitle);
	$Universe_id = trim($data->Universe_id);
	$Updated_at = trim($data->Updated_at);
	$User_id = trim($data->User_id);


    $sql = "UPDATE timelines SET 
Archived_at = '$Archived_at',Created_at = '$Created_at',Deleted_at = '$Deleted_at',Description = '$Description',Name = '$Name',Notes = '$Notes',Page_type = '$Page_type',Private_notes = '$Private_notes',Subtitle = '$Subtitle',Universe_id = '$Universe_id',Updated_at = '$Updated_at',User_id = '$User_id'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM towns Where user_id = '$user_id',id = '$id'";

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

function addTown($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Buildings = trim($data->Buildings);
	$Busy_areas = trim($data->Busy_areas);
	$Citizens = trim($data->Citizens);
	$Country = trim($data->Country);
	$Creatures = trim($data->Creatures);
	$Description = trim($data->Description);
	$Energy_sources = trim($data->Energy_sources);
	$Established_year = trim($data->Established_year);
	$Flora = trim($data->Flora);
	$Food_sources = trim($data->Food_sources);
	$Founding_story = trim($data->Founding_story);
	$Groups = trim($data->Groups);
	$Landmarks = trim($data->Landmarks);
	$Languages = trim($data->Languages);
	$Laws = trim($data->Laws);
	$Name = trim($data->Name);
	$Neighborhoods = trim($data->Neighborhoods);
	$Notes = trim($data->Notes);
	$Other_names = trim($data->Other_names);
	$Politics = trim($data->Politics);
	$Private_Notes = trim($data->Private_Notes);
	$Recycling = trim($data->Recycling);
	$Sports = trim($data->Sports);
	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$Waste = trim($data->Waste);


    $sql = "INSERT INTO towns(Buildings,Busy_areas,Citizens,Country,Creatures,Description,Energy_sources,Established_year,Flora,Food_sources,Founding_story,Groups,Landmarks,Languages,Laws,Name,Neighborhoods,Notes,Other_names,Politics,Private_Notes,Recycling,Sports,Tags,Universe,Waste) 
VALUES('$Buildings','$Busy_areas','$Citizens','$Country','$Creatures','$Description','$Energy_sources','$Established_year','$Flora','$Food_sources','$Founding_story','$Groups','$Landmarks','$Languages','$Laws','$Name','$Neighborhoods','$Notes','$Other_names','$Politics','$Private_Notes','$Recycling','$Sports','$Tags','$Universe','$Waste')"; 


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

	$Buildings = trim($data->Buildings);
	$Busy_areas = trim($data->Busy_areas);
	$Citizens = trim($data->Citizens);
	$Country = trim($data->Country);
	$Creatures = trim($data->Creatures);
	$Description = trim($data->Description);
	$Energy_sources = trim($data->Energy_sources);
	$Established_year = trim($data->Established_year);
	$Flora = trim($data->Flora);
	$Food_sources = trim($data->Food_sources);
	$Founding_story = trim($data->Founding_story);
	$Groups = trim($data->Groups);
	$Landmarks = trim($data->Landmarks);
	$Languages = trim($data->Languages);
	$Laws = trim($data->Laws);
	$Name = trim($data->Name);
	$Neighborhoods = trim($data->Neighborhoods);
	$Notes = trim($data->Notes);
	$Other_names = trim($data->Other_names);
	$Politics = trim($data->Politics);
	$Private_Notes = trim($data->Private_Notes);
	$Recycling = trim($data->Recycling);
	$Sports = trim($data->Sports);
	$Tags = trim($data->Tags);
	$Universe = trim($data->Universe);
	$Waste = trim($data->Waste);


    $sql = "UPDATE towns SET 
Buildings = '$Buildings',Busy_areas = '$Busy_areas',Citizens = '$Citizens',Country = '$Country',Creatures = '$Creatures',Description = '$Description',Energy_sources = '$Energy_sources',Established_year = '$Established_year',Flora = '$Flora',Food_sources = '$Food_sources',Founding_story = '$Founding_story',Groups = '$Groups',Landmarks = '$Landmarks',Languages = '$Languages',Laws = '$Laws',Name = '$Name',Neighborhoods = '$Neighborhoods',Notes = '$Notes',Other_names = '$Other_names',Politics = '$Politics',Private_Notes = '$Private_Notes',Recycling = '$Recycling',Sports = '$Sports',Tags = '$Tags',Universe = '$Universe',Waste = '$Waste'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM traditions Where user_id = '$user_id',id = '$id'";

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

function addTradition($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Activities = trim($data->Activities);
	$Alternate_names = trim($data->Alternate_names);
	$Countries = trim($data->Countries);
	$Dates = trim($data->Dates);
	$Description = trim($data->Description);
	$Etymology = trim($data->Etymology);
	$Food = trim($data->Food);
	$Games = trim($data->Games);
	$Gifts = trim($data->Gifts);
	$Groups = trim($data->Groups);
	$Name = trim($data->Name);
	$Notable_events = trim($data->Notable_events);
	$Notes = trim($data->Notes);
	$Origin = trim($data->Origin);
	$Private_Notes = trim($data->Private_Notes);
	$Religions = trim($data->Religions);
	$Significance = trim($data->Significance);
	$Symbolism = trim($data->Symbolism);
	$Tags = trim($data->Tags);
	$Towns = trim($data->Towns);
	$Type_of_tradition = trim($data->Type_of_tradition);
	$Universe = trim($data->Universe);


    $sql = "INSERT INTO traditions(Activities,Alternate_names,Countries,Dates,Description,Etymology,Food,Games,Gifts,Groups,Name,Notable_events,Notes,Origin,Private_Notes,Religions,Significance,Symbolism,Tags,Towns,Type_of_tradition,Universe) 
VALUES('$Activities','$Alternate_names','$Countries','$Dates','$Description','$Etymology','$Food','$Games','$Gifts','$Groups','$Name','$Notable_events','$Notes','$Origin','$Private_Notes','$Religions','$Significance','$Symbolism','$Tags','$Towns','$Type_of_tradition','$Universe')"; 


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

	$Activities = trim($data->Activities);
	$Alternate_names = trim($data->Alternate_names);
	$Countries = trim($data->Countries);
	$Dates = trim($data->Dates);
	$Description = trim($data->Description);
	$Etymology = trim($data->Etymology);
	$Food = trim($data->Food);
	$Games = trim($data->Games);
	$Gifts = trim($data->Gifts);
	$Groups = trim($data->Groups);
	$Name = trim($data->Name);
	$Notable_events = trim($data->Notable_events);
	$Notes = trim($data->Notes);
	$Origin = trim($data->Origin);
	$Private_Notes = trim($data->Private_Notes);
	$Religions = trim($data->Religions);
	$Significance = trim($data->Significance);
	$Symbolism = trim($data->Symbolism);
	$Tags = trim($data->Tags);
	$Towns = trim($data->Towns);
	$Type_of_tradition = trim($data->Type_of_tradition);
	$Universe = trim($data->Universe);


    $sql = "UPDATE traditions SET 
Activities = '$Activities',Alternate_names = '$Alternate_names',Countries = '$Countries',Dates = '$Dates',Description = '$Description',Etymology = '$Etymology',Food = '$Food',Games = '$Games',Gifts = '$Gifts',Groups = '$Groups',Name = '$Name',Notable_events = '$Notable_events',Notes = '$Notes',Origin = '$Origin',Private_Notes = '$Private_Notes',Religions = '$Religions',Significance = '$Significance',Symbolism = '$Symbolism',Tags = '$Tags',Towns = '$Towns',Type_of_tradition = '$Type_of_tradition',Universe = '$Universe'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM universes Where user_id = '$user_id',id = '$id'";

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

function addUniverse($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$description = trim($data->description);
	$favorite = trim($data->favorite);
	$genre = trim($data->genre);
	$history = trim($data->history);
	$laws_of_physics = trim($data->laws_of_physics);
	$magic_system = trim($data->magic_system);
	$name = trim($data->name);
	$notes = trim($data->notes);
	$page_type = trim($data->page_type);
	$privacy = trim($data->privacy);
	$private_notes = trim($data->private_notes);
	$technology = trim($data->technology);


    $sql = "INSERT INTO universes(description,favorite,genre,history,laws_of_physics,magic_system,name,notes,page_type,privacy,private_notes,technology) 
VALUES('$description','$favorite','$genre','$history','$laws_of_physics','$magic_system','$name','$notes','$page_type','$privacy','$private_notes','$technology')"; 


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

	$description = trim($data->description);
	$favorite = trim($data->favorite);
	$genre = trim($data->genre);
	$history = trim($data->history);
	$laws_of_physics = trim($data->laws_of_physics);
	$magic_system = trim($data->magic_system);
	$name = trim($data->name);
	$notes = trim($data->notes);
	$page_type = trim($data->page_type);
	$privacy = trim($data->privacy);
	$private_notes = trim($data->private_notes);
	$technology = trim($data->technology);


    $sql = "UPDATE universes SET 
description = '$description',favorite = '$favorite',genre = '$genre',history = '$history',laws_of_physics = '$laws_of_physics',magic_system = '$magic_system',name = '$name',notes = '$notes',page_type = '$page_type',privacy = '$privacy',private_notes = '$private_notes',technology = '$technology'    WHERE id = $id"; 

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

    $sql = "SELECT * FROM vehicles Where user_id = '$user_id',id = '$id'";

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

function addVehicle($data){
    global $response;
    global $log;
    global $link;

    $log->info("Started save function.");

	$Alternate_names = trim($data->Alternate_names);
	$Colors = trim($data->Colors);
	$Costs = trim($data->Costs);
	$Country = trim($data->Country);
	$Description = trim($data->Description);
	$Designer = trim($data->Designer);
	$Dimensions = trim($data->Dimensions);
	$Distance = trim($data->Distance);
	$Doors = trim($data->Doors);
	$Features = trim($data->Features);
	$Fuel = trim($data->Fuel);
	$Manufacturer = trim($data->Manufacturer);
	$Materials = trim($data->Materials);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Owner = trim($data->Owner);
	$Private_Notes = trim($data->Private_Notes);
	$Safety = trim($data->Safety);
	$Size = trim($data->Size);
	$Speed = trim($data->Speed);
	$Tags = trim($data->Tags);
	$Type_of_vehicle = trim($data->Type_of_vehicle);
	$Universe = trim($data->Universe);
	$Variants = trim($data->Variants);
	$Weight = trim($data->Weight);
	$Windows = trim($data->Windows);


    $sql = "INSERT INTO vehicles(Alternate_names,Colors,Costs,Country,Description,Designer,Dimensions,Distance,Doors,Features,Fuel,Manufacturer,Materials,Name,Notes,Owner,Private_Notes,Safety,Size,Speed,Tags,Type_of_vehicle,Universe,Variants,Weight,Windows) 
VALUES('$Alternate_names','$Colors','$Costs','$Country','$Description','$Designer','$Dimensions','$Distance','$Doors','$Features','$Fuel','$Manufacturer','$Materials','$Name','$Notes','$Owner','$Private_Notes','$Safety','$Size','$Speed','$Tags','$Type_of_vehicle','$Universe','$Variants','$Weight','$Windows')"; 


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

	$Alternate_names = trim($data->Alternate_names);
	$Colors = trim($data->Colors);
	$Costs = trim($data->Costs);
	$Country = trim($data->Country);
	$Description = trim($data->Description);
	$Designer = trim($data->Designer);
	$Dimensions = trim($data->Dimensions);
	$Distance = trim($data->Distance);
	$Doors = trim($data->Doors);
	$Features = trim($data->Features);
	$Fuel = trim($data->Fuel);
	$Manufacturer = trim($data->Manufacturer);
	$Materials = trim($data->Materials);
	$Name = trim($data->Name);
	$Notes = trim($data->Notes);
	$Owner = trim($data->Owner);
	$Private_Notes = trim($data->Private_Notes);
	$Safety = trim($data->Safety);
	$Size = trim($data->Size);
	$Speed = trim($data->Speed);
	$Tags = trim($data->Tags);
	$Type_of_vehicle = trim($data->Type_of_vehicle);
	$Universe = trim($data->Universe);
	$Variants = trim($data->Variants);
	$Weight = trim($data->Weight);
	$Windows = trim($data->Windows);


    $sql = "UPDATE vehicles SET 
Alternate_names = '$Alternate_names',Colors = '$Colors',Costs = '$Costs',Country = '$Country',Description = '$Description',Designer = '$Designer',Dimensions = '$Dimensions',Distance = '$Distance',Doors = '$Doors',Features = '$Features',Fuel = '$Fuel',Manufacturer = '$Manufacturer',Materials = '$Materials',Name = '$Name',Notes = '$Notes',Owner = '$Owner',Private_Notes = '$Private_Notes',Safety = '$Safety',Size = '$Size',Speed = '$Speed',Tags = '$Tags',Type_of_vehicle = '$Type_of_vehicle',Universe = '$Universe',Variants = '$Variants',Weight = '$Weight',Windows = '$Windows'    WHERE id = $id"; 

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
