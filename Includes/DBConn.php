<?php


// In this page, we open the connection to the Database
// In this page, we open the connection to the Database
// Our MySQL database (blueprintdb) for the Blueprint Application
// Function to connect to the DB


function connectToDB() {
    // These four parameters must be changed dependent on your MySQL settings
    // $hostdb = 'local.sdipr.net:25565';   // MySQl host
    // $userdb = 'sa';    // MySQL username
    // $passdb = 'pipe';    // MySQL password
    // $namedb = 'rd2012'; // MySQL database name

    $config=parse_ini_file('../config.ini', true);  

    $hostdb = $config["host"];   // MySQL host
    $userdb = $config["username"];    // MySQL username
    $passdb = $config["password"];   // MySQL password
    $namedb = 'rd2012'; // MySQL database name


    $link=mssql_connect($hostdb,$userdb,$passdb);


    if (!$link) {
        // we should have connected, but if any of the above parameters
        // are incorrect or we can't access the DB for some reason,
        // then we will stop execution here
        die('Could not connect.');
    }
    
    return $link;
}
?>
