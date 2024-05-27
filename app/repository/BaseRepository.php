<?php

namespace leanphp\app\Repository;

use leanphp\core\DBDriver;
use PDO;
use PDOException;
use Exception;

abstract class BaseRepository {
    protected $db;
    protected $table;

    public function __construct($table) {
        $this->db = DBDriver::getInstance()->getConnection();
        $this->table = $table;
    }

    public function save(array $data): bool {
        try {
            $fields = implode(',', array_keys($data));
            $placeholders = implode(',', array_map(fn($field) => ":$field", array_keys($data)));
            $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            throw new Exception("Database error while saving data: " . $e->getMessage());
        }
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function findBy(array $criteria): ?array {
        $conditions = implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($criteria)));
        $sql = "SELECT * FROM {$this->table} WHERE $conditions";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($criteria);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function update(int $id, array $data): bool {
        $data['id'] = $id;
        $fields = implode(',', array_map(fn($field) => "$field = :$field", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $fields WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}