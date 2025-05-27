<?php

namespace App\Models;

use PDO;
use App\Core\Database;

class Teacher {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($search = '', $category = '') {
        $stmt = $this->db->query("SELECT * FROM teachers ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM teachers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO teachers (teacher_name, category, subject, experience, bio, image, is_active) 
                VALUES (:teacher_name, :category, :subject, :experience, :bio, :image, :is_active)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'teacher_name' => $data['teacher_name'],
            'category' => $data['category'],
            'subject' => $data['subject'],
            'experience' => $data['experience'],
            'bio' => $data['bio'],
            'image' => $data['image'],
            'is_active' => $data['is_active']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE teachers SET 
                teacher_name = :teacher_name,
                category = :category,
                subject = :subject,
                experience = :experience,
                bio = :bio,
                is_active = :is_active";
        if ($data['image'] !== null) {
            $sql .= ", image = :image";
        }
        $sql .= " WHERE id = :id";
        $params = [
            'id' => $id,
            'teacher_name' => $data['teacher_name'],
            'category' => $data['category'],
            'subject' => $data['subject'],
            'experience' => $data['experience'],
            'bio' => $data['bio'],
            'is_active' => $data['is_active']
        ];
        if ($data['image'] !== null) {
            $params['image'] = $data['image'];
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM teachers WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getRecent($limit = 5) {
        $limit = (int)$limit;
        $stmt = $this->db->prepare("SELECT * FROM teachers ORDER BY created_at DESC LIMIT $limit");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
