<?php
namespace App\Models;

class Booking extends Model {
    public function findAll() {
        $stmt = $this->pdo->query('SELECT * FROM bookings');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM bookings WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO bookings (user_id, room_id, check_in, check_out, total_price, status) VALUES (:user_id, :room_id, :check_in, :check_out, :total_price, :status)');
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare('UPDATE bookings SET status = :status WHERE id = :id');
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM bookings WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function findByUser($userId) {
        $stmt = $this->pdo->prepare('SELECT * FROM bookings WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}