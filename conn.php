<?php
$serverName = "RENDER-LAPPY\SQLEXPRESS";
$database = "inv_db";
$uid = "";
$pass = "";

$connection = [
	"Database" => $database,
	"Uid" => $uid,
	"PWD" => $pass
];

$conn = sqlsrv_connect($serverName,$connection);

if(!$conn){
	die(print_r(sqlsrv_errors(),true));
}else{
	// "Connected Successfully<br>";
}
?>