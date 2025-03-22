<?php
namespace App\Models;

class Facility extends Model {
    public function findAll() {
        $stmt = $this->pdo->query('SELECT * FROM facilities');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO facilities (name, description, image) VALUES (:name, :description, :image)');
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare('UPDATE facilities SET name = :name, description = :description, image = :image WHERE id = :id');
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM facilities WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}