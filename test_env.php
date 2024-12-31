<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$variables = $dotenv->load(); // Load the variables into $variables array

echo "<pre>";
print_r($variables); // This shows that Dotenv is working
echo "</pre>";

echo "SMTP_HOST (via \$variables): " . $variables['SMTP_HOST'] . "<br>";
echo "SMTP_USER (via \$variables): " . $variables['SMTP_USER'] . "<br>";
echo "SMTP_PASS (via \$variables): " . $variables['SMTP_PASS'] . "<br>";
echo "SMTP_PORT (via \$variables): " . $variables['SMTP_PORT'] . "<br>";
echo "SMTP_SECURE (via \$variables): " . $variables['SMTP_SECURE'] . "<br>";



