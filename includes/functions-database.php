<?php
/**
 * Database Helper Functions
 * Provides database connection access
 */

/**
 * Get database connection instance
 * 
 * @return mysqli Database connection object
 */
function db()
{
    global $db;
    return $db;
}