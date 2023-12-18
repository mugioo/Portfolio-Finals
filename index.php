<?php
include "conn.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Manager | Login</title>
    <link rel="icon" href="images/favicon-32x32.png">
    <!------BOOTSTRAP CSS & JS------->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <!---------ANIMATE.CSS----------->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!------- BOOTSTRAP ICONS ------->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!--------LOCAL CSS & JS--------->
    <link rel="stylesheet" href="login.style.css">
</head>
<body>
    <div class="container animate__animated animate__fadeIn">
        <h1><span class="btn-label"><i class="bi bi-lightning-charge-fill"></i></span> Inventory Manager</h1>
        <h6> an Inventory Management System </h6>
      <form action="login.redirect.php" method="post">
        <div class="form-floating mb-2">
        <input type="text" id="floatingInput" placeholder="Enter Username:" name="username" class="form-control">
            <label for="floatingInput"> Username </label>
        </div>
        <div class="form-floating mb-2">
        <input type="password" id="floatingInput" placeholder="Enter Password:" name="password" class="form-control">
            <label for="floatingInput"> Password </label>
        </div>
        <div class="d-grid gap-2">
            <input type="submit" value="Login" name="login" class="butt_neeco_green">
        </div>
      </form>
      <br>
    </div>
</body>
</html>