<?php
namespace App\Models;

class User extends Model {
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare('INSERT INTO users (email, password, role, first_name, last_name) VALUES (:email, :password, :role, :first_name, :last_name)');
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function find($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare('UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email WHERE id = :id');
        $data['id'] = $id;
        $stmt->execute($data);
    }
}