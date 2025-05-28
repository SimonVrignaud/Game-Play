<?php

require_once __DIR__ . '/../services/Database.php'; // Assure-toi que ce fichier contient une méthode getPDO()

class Product
{
    // Récupère tous les produits
    public static function getAll()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un produit par son ID
    public static function getById($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crée un nouveau produit
    public static function create($data)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("INSERT INTO products (name, price, description) VALUES (:name, :price, :description)");
        $stmt->execute([
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
        ]);
    }

    // Met à jour un produit
    public static function update($id, $data)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("UPDATE products SET name = :name, price = :price, description = :description WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
        ]);
    }

    // Supprime un produit
    public static function delete($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
