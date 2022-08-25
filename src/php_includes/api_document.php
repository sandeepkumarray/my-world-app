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

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $procedureName = $_GET['procedureName'];
	if ($procedureName == "getAllMentions") {
		getAllMentions();
	}
    mysqli_close($link);
	echo json_encode($response->data);
}
?>