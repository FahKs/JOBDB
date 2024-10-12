<?php

session_start();


// delete $_SESSION['profile'] variable
$_SESSION['profile'] = null;

session_unset();

session_destroy();

header('Location: ../login.php');