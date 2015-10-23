<?php
session_start();

if(isset($_SESSION['client_id'])) {
	session_destroy();

    $response = array();
    $response['message'] = "LOGGED OUT";
    die(json_encode($response));
} else {
	$error = array();
    $error['message'] = "Unable to log out!";
    die(json_encode($error));
}
?>