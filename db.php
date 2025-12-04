<?php

require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

$serviceAccountPath = __DIR__ . '/firebase_key.json';

// CEK FILE SERVICE ACCOUNT
if (!file_exists($serviceAccountPath)) {
    die("Error: File firebase_key.json tidak ditemukan.");
}

try {
    $factory = (new Factory)
        ->withServiceAccount($serviceAccountPath)
        // ✅ URL DATABASE SUDAH BENAR SESUAI REGION
        ->withDatabaseUri('https://carikost-id-f46dc-default-rtdb.asia-southeast1.firebasedatabase.app');

    $database = $factory->createDatabase();

} catch (Exception $e) {
    die("Firebase Connection Error: " . $e->getMessage());
}

?>
