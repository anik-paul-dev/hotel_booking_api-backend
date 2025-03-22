<?php
namespace App\Models;

class Settings extends Model {
    public function getSettings() {
        $stmt = $this->pdo->query('SELECT * FROM settings LIMIT 1');
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateSettings($data) {
        $stmt = $this->pdo->prepare('UPDATE settings SET website_title = :website_title, about_us = :about_us, shutdown = :shutdown, address = :address, email = :email, google_map_iframe = :google_map_iframe WHERE id = 1');
        $stmt->execute($data);
    }

    public function getTeamMembers() {
        $stmt = $this->pdo->query('SELECT * FROM team_members');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTeamMember($data) {
        $stmt = $this->pdo->prepare('INSERT INTO team_members (name, job_title, image) VALUES (:name, :job_title, :image)');
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function updateTeamMember($id, $data) {
        $stmt = $this->pdo->prepare('UPDATE team_members SET name = :name, job_title = :job_title, image = :image WHERE id = :id');
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function deleteTeamMember($id) {
        $stmt = $this->pdo->prepare('DELETE FROM team_members WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}