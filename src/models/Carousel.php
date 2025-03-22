<?php
namespace App\Models;

class Carousel extends Model {
    public function findAll() {
        $stmt = $this->pdo->query('SELECT * FROM carousel');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO carousel (image) VALUES (:image)');
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM carousel WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}