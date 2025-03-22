<?php
namespace App\Models;

class Feature extends Model {
    public function findAll() {
        $stmt = $this->pdo->query('SELECT * FROM features');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO features (name) VALUES (:name)');
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare('UPDATE features SET name = :name WHERE id = :id');
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM features WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}