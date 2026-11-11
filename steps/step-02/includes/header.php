<?php
// The top of every page. Set $pageTitle before including this file.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($pageTitle); ?> | <?php echo HOTEL_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="site-header">
    <a class="logo" href="index.php"><?php echo HOTEL_NAME; ?></a>
</header>

<main class="container">
