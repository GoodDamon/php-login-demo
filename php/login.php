<?php 
// A little housekeeping.
session_start();
date_default_timezone_set('America/Los_Angeles');

if(isset($_GET['mode'])) {
    // Client is checking login status. This is a bit of a dirty hack.
    if(isset($_SESSION['client_id'])) {
        // We already have an active session. Do nothing else.
        $data = array();
        $data['message'] = "SESSION ACTIVE";
        die(json_encode($data));
    } else {
        // There is no active session. Let the client know.
        $data = array();
        $data['message'] = "SESSION INACTIVE";
        die(json_encode($data));
    }
}

if(!empty($_REQUEST)) {

    // Load up the database
    include("config.php");
    include("opendb.php");

    $user_name = $_REQUEST['user_name'];
    $user_pass = $_REQUEST['user_pass'];

    $SQL = "SELECT COUNT(*)
            AS total
            FROM client
            WHERE user_name = \"$user_name\"";

    $result = mysql_query($SQL) or die("Couldn't execute query: \n$SQL\n\n".mysql_error());
    $data=mysql_fetch_assoc($result);

    // Make sure exactly one row is returned. If not, we'll throw an invalid user error.
    if($data['total'] != 1) {
        $error = array();
        $error['message'] = "Invalid user";
        $error['matching'] = $data['total'];
        session_destroy();
        mysql_close($conn);
        die(json_encode($error));
    }

    // If we're here, we got exactly one row. Proceeding...
    $SQL = "SELECT client_id, user_name, password_hash
            FROM client 
            WHERE user_name = \"$user_name\""; 

    $result = mysql_query($SQL) or die("Couldn't execute query: \n$SQL\n\n".mysql_error());
    $data = mysql_fetch_assoc($result);

    if($user_pass === $data['password_hash']) {

        /* This is dirty and should NEVER be done in production! But for test purposes, if you've
         * added an unhashed password manually to the table, this will hash it for you. In a prod
         * environment, you should also have an admin console for creating passwords that will
         * hash them as they're added. */
        $hash = password_hash($user_pass, PASSWORD_DEFAULT);
        $SQL = "UPDATE client
                SET password_hash=\"$hash\" WHERE user_name=\"$user_name\"";

        mysql_query($SQL) or die("Couldn't execute query: \n$SQL\n\n".mysql_error());

        /* Proceeding as if we haven't just done something dirty and awful requires us to manually
         * set $data['password_hash'] to the hash we just created. Again, never do this in prod! */

        $data['password_hash'] = $hash;
    }

    // If this is not the first time logging in, we'll verify the password.
    if (password_verify($user_pass, $data['password_hash'])) {

        // Update last successful login field.
        $dateTime = date("Y-m-d H:i:s");
        $SQL = "UPDATE client
                SET last_login=\"$dateTime\" WHERE user_name=\"$user_name\"";

        mysql_query($SQL) or die("Couldn't execute query: \n$SQL\n\n".mysql_error());

        // If we've made it this far, it's time to create some session information for the user.
        $_SESSION['client_id'] = $data['client_id'];
        $_SESSION['last_login'] = $dateTime;

        $response = array();
        $response['message'] = "SESSION CREATED";
        $response['user_name'] = $user_name;
        mysql_close($conn);
        die(json_encode($response));

    } else {
        $error = array();
        $error['message'] = "Invalid password";
        session_destroy();
        mysql_close($conn);
        die(json_encode($error));
    }
}
mysql_close($conn); // Should never get to this point, but if it does, be nice and close the connection.