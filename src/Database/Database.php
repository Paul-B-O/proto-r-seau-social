<?php

namespace Database;

use Exception;
use PDO;

class Database
{
    private PDO $pdo;

    public function __construct(
        string $host,
        string $dbname,
        string $username,
        string $password,
        ?string $sqlFile = null,
        string $charset = 'utf8mb4'
    ) {
        $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

        $this->pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        if ($sqlFile !== null) {
            $this->executeSqlFile($sqlFile);
        }
    }

    private function executeSqlFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new Exception("Fichier SQL introuvable : {$filePath}");
        }

        $sql = file_get_contents($filePath);

        if ($sql === false) {
            throw new Exception("Impossible de lire le fichier SQL.");
        }

        try {
            $this->pdo->beginTransaction();

            // exec permet d'exécuter plusieurs requêtes SQL d'un coup
            $this->pdo->exec($sql);

            $this->pdo->commit();
        } catch (Exception $e) {
//            $this->pdo->rollBack();
//            throw new Exception("Erreur lors de l'exécution du fichier SQL : " . $e->getMessage());
        }
    }

    public function select(string $query, array $params = []): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function insert(string $query, array $params = []): int
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(string $query, array $params = []): int
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount();
    }


    public function delete(string $query, array $params = []): int
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount();
    }


    public function execute(string $query, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute($params);
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}