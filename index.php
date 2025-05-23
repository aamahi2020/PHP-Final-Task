<?php
session_start();

// Always redirect to signup page first
header("Location: signup.php");
exit();
