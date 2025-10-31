<?php
/**
 * Initialization File
 * Loads required files and sets up the application environment
 */

// Start session for application state management
session_start();

// Set timezone for date/time operations
date_default_timezone_set('Asia/Tehran');

// Load required files
require_once('includes/jdf.php');              // Jalali date functions
require_once('includes/config.php');            // Database configuration
require_once('includes/database.php');          // Database connection
require_once('includes/functions-database.php'); // Database helper functions
require_once('includes/functions.php');         // Application helper functions

// Debug: Uncomment to test database query
// $query = mysqli_query( db(), 'SELECT * FROM `products`' );
// print_r( $query );

// Include form processing handler
include('includes/form-process.php');