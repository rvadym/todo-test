<?php
// NOT USED YET
// https://stackoverflow.com/questions/34778241/how-to-deal-with-callbacks-having-dot-in-the-url-with-built-in-php-server

if (preg_match('/\.(?:png|jpg|jpeg|gif|json)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // serve the requested resource as-is.
} else {
    echo "<p>Welcome to PHP</p>";
}
