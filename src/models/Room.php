<?php
namespace App\Models;

class Room extends Model {
    public function findAll() {
        $stmt = $this->pdo->query('SELECT * FROM rooms');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM rooms WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($params) {
        $query = 'SELECT * FROM rooms WHERE status = "active"';
        $conditions = [];
        $data = [];
        if (!empty($params['name'])) {
            $conditions[] = 'name LIKE :name';
            $data['name'] = '%' . $params['name'] . '%';
        }
        if (!empty($params['min_price'])) {
            $conditions[] = 'price_per_night >= :min_price';
            $data['min_price'] = $params['min_price'];
        }
        if (!empty($params['max_price'])) {
            $conditions[] = 'price_per_night <= :max_price';
            $data['max_price'] = $params['max_price'];
        }
        if ($conditions) {
            $query .= ' AND ' . implode(' AND ', $conditions);
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($data);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO rooms (name, area, price_per_night, adult_max, children_max, description, status) VALUES (:name, :area, :price_per_night, :adult_max, :children_max, :description, :status)');
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare('UPDATE rooms SET name = :name, area = :area, price_per_night = :price_per_night, adult_max = :adult_max, children_max = :children_max, description = :description, status = :status WHERE id = :id');
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM rooms WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}