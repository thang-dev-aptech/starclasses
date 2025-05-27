<?php

namespace App\Models;

use App\Core\BaseModel;

class ConsultRequest extends BaseModel {
    protected $table = 'enquiries';
    
    public function getLatestRequests($limit = 10) {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->query($sql, ['limit' => $limit]);
        return $stmt->fetchAll();
    }
    
    public function search($query) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE firstname LIKE :query 
                OR lastname LIKE :query 
                OR email LIKE :query 
                OR phone LIKE :query 
                ORDER BY created_at DESC";
                
        $stmt = $this->db->query($sql, ['query' => "%$query%"]);
        return $stmt->fetchAll();
    }
    
    public function getByDateRange($startDate, $endDate) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE created_at BETWEEN :start_date AND :end_date 
                ORDER BY created_at DESC";
                
        $stmt = $this->db->query($sql, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        return $stmt->fetchAll();
    }

    public function getRecent($limit = 5) {
        $limit = (int)$limit;
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT $limit";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getAll($filter = []) {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        if (isset($filter['status'])) {
            $sql .= " WHERE status = :status";
            $params['status'] = $filter['status'];
        }
        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
}
