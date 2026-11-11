<?php
require "includes/db.php";
require "includes/functions.php";

$pageTitle = "Contact";
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$message = trim($_POST["message"] ?? "");
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    check_csrf();

    if ($name === "" || mb_strlen($name) > 80) {
        $errors["name"] = "Enter your name.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Enter a real email address.";
    }
    if (mb_strlen($message) < 5 || mb_strlen($message) > 2000) {
        $errors["message"] = "Write a message (5 to 2000 characters).";
    }

    if (!$errors) {
        $sql = "INSERT INTO messages (name, email, message) VALUES (?, ?, ?)";
        $conn->execute_query($sql, [$name, $email, $message]);
        flash("Thanks! Your message is sent.");
        go("contact.php");
    }
}

require "includes/header.php";
?>

<h1>Contact us</h1>
<p>Call <?php echo HOTEL_PHONE; ?> or send us a message.</p>

<form class="box narrow" method="post">
    <?php echo csrf_field(); ?>
    <label>Name <input name="name" value="<?php echo e($name); ?>" required></label>
    <?php if (isset($errors["name"])): ?><p class="error"><?php echo $errors["name"]; ?></p><?php endif; ?>

    <label>Email <input type="email" name="email" value="<?php echo e($email); ?>" required></label>
    <?php if (isset($errors["email"])): ?><p class="error"><?php echo $errors["email"]; ?></p><?php endif; ?>

    <label>Message <textarea name="message" rows="5" required><?php echo e($message); ?></textarea></label>
    <?php if (isset($errors["message"])): ?><p class="error"><?php echo $errors["message"]; ?></p><?php endif; ?>

    <button class="btn">Send message</button>
</form>

<?php require "includes/footer.php"; ?>
