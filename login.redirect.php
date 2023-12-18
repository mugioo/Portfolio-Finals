<?php
include "conn.php";
session_start();

$username  = $_POST['username'];
$password  = $_POST['password'];

$sql = sqlsrv_query($conn,"SELECT * FROM admin WHERE admin_username = '$username' and admin_password = '$password'");
$get_row = sqlsrv_fetch_array($sql);

if(is_array($get_row))
{
  $_SESSION['username'] = $get_row['admin_username'];
  $_SESSION['password'] = $get_row['admin_password'];
  $_SESSION['admin_name'] = $get_row['admin_name'];
  header("Location:main/main.view.php");
} else
{
  echo '<script>
  alert("Incorrect Credentials, Please Try Again.");
  window.location="index.php";
  </script>';
}
?>