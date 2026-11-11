<?php
require "includes/db.php";
require "includes/functions.php";

$pageTitle = "Welcome";
$types = $conn->query("SELECT * FROM room_types ORDER BY price")->fetch_all(MYSQLI_ASSOC);

require "includes/header.php";
?>

<section class="hero">
    <img src="images/site/hero.jpg" alt="The hotel by the lake">
    <div class="hero-text">
        <h1>A quiet hotel by the lake</h1>
        <p>Choose your dates and send a booking request in a minute.</p>
    </div>
</section>

<h2>Our rooms</h2>
<div class="cards">
    <?php foreach ($types as $type): ?>
        <a class="card" href="room.php?id=<?php echo $type["id"]; ?>">
            <img src="images/rooms/<?php echo e($type["photo"]); ?>" alt="<?php echo e($type["name"]); ?>">
            <h3><?php echo e($type["name"]); ?></h3>
            <p><?php echo money($type["price"]); ?> a night · up to <?php echo plural($type["max_guests"], "guest"); ?></p>
        </a>
    <?php endforeach; ?>
</div>

<?php require "includes/footer.php"; ?>
