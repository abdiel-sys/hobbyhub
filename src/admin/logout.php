<?php
require_once "../config/user_functions.php";
session_start();

logoutUser();
session_destroy();

header("Location: login.php");
exit;
