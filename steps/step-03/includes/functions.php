<?php
// Small functions used on many pages.

// ===== Showing things =====

// Makes text safe to put in HTML (stops people adding their own HTML or scripts)
function e($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, "UTF-8");
}

function money($amount)
{
    return "$" . number_format((float) $amount, 2);
}

// plural(1, "night") = "1 night", plural(3, "night") = "3 nights"
function plural($count, $word)
{
    return $count . " " . $word . ($count == 1 ? "" : "s");
}

// ===== Moving between pages =====

function go($page)
{
    header("Location: " . $page);
    exit;
}

// A message shown once on the next page ("Booking sent" etc.)
function flash($text)
{
    $_SESSION["flash"] = $text;
}

function take_flash()
{
    $text = $_SESSION["flash"] ?? "";
    unset($_SESSION["flash"]);
    return $text;
}

// ===== Forms =====

// Hidden code in every form, so other websites can't send forms as you
function csrf_field()
{
    if (empty($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(16));
    }
    return '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
}

function check_csrf()
{
    if (!hash_equals($_SESSION["csrf"] ?? "", $_POST["csrf"] ?? "")) {
        exit("This form has expired. Go back, reload the page and try again.");
    }
}
