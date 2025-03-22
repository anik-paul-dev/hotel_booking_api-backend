<?php
namespace App\Models;

use PDO;

class Model {
    protected $pdo;

    public function __construct() {
        $config = require __DIR__ . '/../../config/database.php';
        $this->pdo = new PDO($config['dsn'], $config['username'], $config['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}