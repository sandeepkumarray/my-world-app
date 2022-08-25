<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

class dbResponse {
    public $success = false;
    public $data;
    public $message = '';
}

?>