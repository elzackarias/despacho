<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
} else {
    session_destroy();
    header("Location: login.php?prev=logout");
    exit();
}