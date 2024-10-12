<?php

if (empty($_SESSION['profile'])) {
    header('Location: ../../login.php');
    exit();
}

// go to login page if user is not admin
if ($_SESSION['profile']['position'] != 'Admin') {
    header('Location: ../401.php');
    exit();
}

