<?php
$token = bin2hex(random_bytes(8));
$rol = base64_encode('cliente');
$uid = bin2hex(random_bytes(4));

$url = "https://incargo365.com/access?t=$token&role=$rol&u=$uid";

echo "<h2>🔗 URL generada:</h2>";
echo "<p><a href=\"$url\" target=\"_blank\">$url</a></p>";
