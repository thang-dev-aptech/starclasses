<?php

namespace App\Models;

use App\Core\BaseModel;

class Course extends BaseModel {
    protected $table = 'courses';

    // Lấy tất cả khóa học
    public function getAll($search = '', $category = '') {
        $sql = "SELECT * FROM {$this->table} WHERE 1";
        $params = [];
        if (!empty($search)) {
            $sql .= " AND course_name LIKE :search";
            $params['search'] = "%$search%";
        }
        if (!empty($category) && $category !== 'all') {
            $sql .= " AND category = :category";
            $params['category'] = $category;
        }
        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    // Lấy chi tiết một khóa học
    public function getById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->query($sql, ['id' => $id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log("Get Course Error: " . $e->getMessage());
            throw new \Exception('Failed to get course');
        }
    }

    // Thêm mới khóa học
    public function create($data) {
        try {
            $columns = implode(', ', array_keys($data));
            $values = ':' . implode(', :', array_keys($data));
            
            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
            $this->db->query($sql, $data);
            
            return $this->db->getConnection()->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Create Course Error: " . $e->getMessage());
            throw new \Exception('Failed to create course');
        }
    }

    // Cập nhật khóa học
    public function update($id, $data) {
        try {
            $setClause = implode(', ', array_map(function($key) {
                return "$key = :$key";
            }, array_keys($data)));
            
            $data['id'] = $id;
            $sql = "UPDATE {$this->table} SET $setClause WHERE id = :id";
            
            return $this->db->query($sql, $data);
        } catch (\PDOException $e) {
            error_log("Update Course Error: " . $e->getMessage());
            throw new \Exception('Failed to update course');
        }
    }

    // Xóa khóa học
    public function delete($id) {
        try {
            // Xóa bản ghi trước
            $this->db->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
            // Đẩy các id phía sau lên
            $this->db->query('UPDATE courses SET id = id - 1 WHERE id > ?', [$id]);
            $maxId = $this->db->query('SELECT MAX(id) as max_id FROM courses')->fetch()['max_id'] ?? 0;
            $this->db->query('ALTER TABLE courses AUTO_INCREMENT = ' . ((int)$maxId + 1));
            return true;
        } catch (\Exception $e) {
            error_log("Delete Course Error: " . $e->getMessage());
            throw new \Exception('Failed to delete course');
        }
    }

    // Tìm kiếm một khóa học theo tên
    public function getByName($name) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE course_name = :name LIMIT 1";
            $stmt = $this->db->query($sql, ['name' => $name]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log("Get Course by Name Error: " . $e->getMessage());
            throw new \Exception('Failed to get course by name');
        }
    }

    // Get recent courses
    public function getRecent($limit = 5) {
        try {
            $limit = (int)$limit;
            $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT $limit";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Get Recent Courses Error: " . $e->getMessage());
            throw new \Exception('Failed to get recent courses');
        }
    }

}
