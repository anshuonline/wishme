<?php
require 'db.php';
$pdo->exec('TRUNCATE TABLE wishes;');
echo 'Table cleared.';
?>
