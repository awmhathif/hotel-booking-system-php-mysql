<?php
session_start();

// Hotel details shown on every page
define("HOTEL_NAME", "Halden Hotel");
define("HOTEL_ADDRESS", "12 Lake Road, Halden");
define("HOTEL_PHONE", "+1 555 0100");

// Connect to MySQL (XAMPP: user root, no password)
$conn = new mysqli("localhost", "root", "", "hotel_db");
$conn->set_charset("utf8mb4");

// Today's date from MySQL, so PHP and MySQL agree on the date
$today = $conn->query("SELECT CURDATE()")->fetch_row()[0];
