<?php

declare(strict_types=1);

namespace App\Handler;

use PDO;
use PDOException;

class PDOHandler
{
    public function create(): PDO
    {
        try {
            $conn = new PDO("mysql:host=127.0.0.1:3329;dbname=MitfahrerDB", 'root', '');
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return $conn;
    }
}
