<?php

declare(strict_types=1);

namespace App\Handler;

use PDO;
use PDOException;

class PDOHandler
{
    public function handle(): PDO
    {
        try {
            $conn = new PDO("mysql:host=localhost;dbname=MitfahrerDB", 'root', '');
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return $conn;
    }
}
