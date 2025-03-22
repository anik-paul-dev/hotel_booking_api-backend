<?php
namespace App\Models;

class Review extends Model {
    public function findAll() {
        $stmt = $this->pdo->query('SELECT * FROM reviews');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM reviews WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO reviews (room_id, user_id, rating, review, date) VALUES (:room_id, :user_id, :rating, :review, :date)');
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare('UPDATE reviews SET rating = :rating, review = :review WHERE id = :id');
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM reviews WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}