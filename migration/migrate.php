<?php
require '../vendor/autoload.php';
use RedBeanPHP\R as R;


// Setup RedBean
    R::setup('sqlite:../db/sqlite.db');



// Create a migrations table if it doesn't exist
    R::exec("CREATE TABLE IF NOT EXISTS migrations (filename TEXT PRIMARY KEY, applied_at DATETIME)");



// Scan for migration files
    $files = glob(__DIR__ . '/*.up.sql');
    sort($files); // Ensure files are in order (001_, 002_, etc.)


// Load applied migrations
    $applied = R::getAll("SELECT filename FROM migrations");
    $applied = array_map(function($migration) {
        return basename($migration['filename']);
    }, $applied);




// Apply migrations
    foreach ($files as $file) {
        $name = basename($file);
        if (in_array($name, $applied)) {
            echo "Skipping applied migration: $name\n";
            continue;
        }

        echo "Applying migration: $name\n";
        $sql = file_get_contents($file);
        R::exec($sql); // Execute the migration

        // Insert the applied migration into the table
        R::exec("INSERT INTO migrations (filename, applied_at) VALUES (?, ?)", [$name, date('Y-m-d H:i:s')]);
    }

// Rollback migrations
    $files_down = glob(__DIR__ . '/*.down.sql');
    foreach ($files_down as $file) {
        $name = basename($file);
        if (!in_array($name, $applied)) {
            echo "Skipping rollback for migration: $name\n";
            continue;
        }

        echo "Rolling back migration: $name\n";
        $sql = file_get_contents($file);
        R::exec($sql); // Execute the rollback

        // Remove the migration from the applied table
        R::exec("DELETE FROM migrations WHERE filename = ?", [$name]);
    }


echo "Migrations completed.\n";