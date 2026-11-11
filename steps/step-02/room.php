<?php
require "includes/db.php";
require "includes/functions.php";

$id = (int) ($_GET["id"] ?? 0);
$type = $conn->execute_query("SELECT * FROM room_types WHERE id = ?", [$id])->fetch_assoc();
if (!$type) {
    exit("That room doesn't exist. <a href='index.php'>Back to the home page</a>");
}

$pageTitle = $type["name"];

require "includes/header.php";
?>

<div class="room-page">
    <img class="room-photo" src="images/rooms/<?php echo e($type["photo"]); ?>" alt="<?php echo e($type["name"]); ?>">

    <div>
        <h1><?php echo e($type["name"]); ?></h1>
        <p><?php echo e($type["description"]); ?></p>
        <p><strong><?php echo money($type["price"]); ?></strong> a night · up to <?php echo plural($type["max_guests"], "guest"); ?></p>
    </div>
</div>

<?php require "includes/footer.php"; ?>
