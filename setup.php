<?php
//**********************************//
// I've thrown this install script  //
// together really fast so I don't  //
// know if this will work or not.?  //
//**********************************//
// I'm really not that good at PDO  //
// so I hope this will work...      //
//**********************************//

// Edit this to connect to your DB
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "dbname";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL stuff, still working on it
    $sql = 

    // use exec() because no results are returned
    $conn->exec($sql);
    echo "The install was successfully, please delete this file as it is now a security risk.";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>
