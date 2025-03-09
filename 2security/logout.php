<?php
// logout.php

session_start();

// Destroy the session and clear cookies
session_unset();
session_destroy();
setcookie('dark_mode', '', time() - 3600, "/");
setcookie('last_login', '', time() - 3600, "/");

header("Location: login.php");
exit();
?>