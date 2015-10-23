<?php
session_start();

if(isset($_SESSION['client_id'])) {
	$client_id = $_SESSION['client_id'];

	/* Get all the user info here. In prod, this would probably contain private information.
	 * Since this is just a test system, we're only showing the user's details. */
	include("config.php");
    include("opendb.php");

	$SQL = "SELECT user_name, last_login
            FROM client 
            WHERE client_id = \"$client_id\"";

    $result = mysql_query($SQL) or die("Couldn't execute query: \n$SQL\n\n".mysql_error());
    $data = mysql_fetch_assoc($result);

    $response = array();
    $response['message'] = "User Logged in";
    $response['user_name'] = $data['user_name'];
    $response['last_login'] = $data['last_login'];
    mysql_close($conn);
    die(json_encode($response));
} else {
	$error = array();
    $error['message'] = "Unauthorized";
    die(json_encode($error));
}
?>