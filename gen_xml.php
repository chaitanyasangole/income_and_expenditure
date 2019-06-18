<?php

require("dbfile.php");// 1) change file name

// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Opens a connection to a MySQL server

$connection=mysqli_connect ('localhost', $username, $password);
if (!$connection) {  die('Not connected : ' . mysqli_error());}

// Set the active MySQL database

$db_selected = mysqli_select_db($connection,$database); // 2) see the syntax 
if (!$db_selected) {
  die ('Can\'t use db : ' . mysqli_error($connection)); // check syntax
}

// Select all the rows in the markers table

$query = "select * from map";// 3) check the table name
$result = mysqli_query($connection,$query); // check syntax
if (!$result) {
  die('Invalid query: ' . mysqli_error($connection));
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each

while ($row = @mysqli_fetch_assoc($result)){
  // Add to XML document node
  $node = $dom->createElement("marker");// 4) check all associative array row with columns of table
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id",$row['id']);
  $newnode->setAttribute("name",$row['name']);
  $newnode->setAttribute("address", $row['address']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
  $newnode->setAttribute("type", $row['type']);
}

echo $dom->saveXML();

?>