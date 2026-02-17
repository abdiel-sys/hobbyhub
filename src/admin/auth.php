<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: /errors/401.php");
    exit;
}