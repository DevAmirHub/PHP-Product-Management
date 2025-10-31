<?php
/**
 * Database Connection
 * Establishes connection to MySQL database
 */

mysqli_report(MYSQLI_REPORT_ERROR);

// Connect to database
$db = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Debug: Uncomment to see connection object
// print_r($db);

if( ! $db ){

    // Log database connection error
    $db_error = mysqli_connect_error();
    file_put_contents( 'db-error.txt' ,  'error at ' . jdate('Y-m-d h:i:s ')  . ' => ' . $db_error  . "\n", FILE_APPEND );

    // Display error page if connection fails
    include('error-404.php');
    exit;
}

