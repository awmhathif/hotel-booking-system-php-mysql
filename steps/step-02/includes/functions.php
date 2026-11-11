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
