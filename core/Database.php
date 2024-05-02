<?php

namespace Core;

class Database
{
    protected string $host;
    protected string $db_name;
    protected string $user;
    protected string $password;
    protected string $charset;

    protected Dotenv $dotenv;
    protected ?\PDO $database = null;

    public function __construct()
    {
        $this->dotenv = new Dotenv(Define::ENV_FILE);
        $this->host = $this->dotenv->get('DB_HOST');
        $this->db_name = $this->dotenv->get('DB_NAME');
        $this->user = $this->dotenv->get('DB_USER');
        $this->password = $this->dotenv->get('DB_PASSWORD');
        $this->charset = $this->dotenv->get('DB_CHARSET');
    }

    public function getTable(string $table): ?string
    {
        $tables = $this->getTables();

        if (in_array($table, $tables)) {
            return $table;
        }

        return null;
    }

    public function getTables(): array
    {
        $statement = $this->getConnection()->query("SHOW TABLES FROM {$this->db_name}");
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getColumn(string $tableName, string $columnName): bool
    {
        $statement = $this->getConnection()->prepare("DESCRIBE `$tableName`");
        $statement->execute();
        $columns = $statement->fetchAll(\PDO::FETCH_COLUMN);
        return in_array($columnName, $columns);
    }

    public function getColumns(string $tableName): array
    {
        $statement = $this->getConnection()->prepare("DESCRIBE `$tableName`");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getConnection(): \PDO
    {
        if ($this->database === null) {
            try {
                $this->database = new \PDO("mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}", $this->user, $this->password);
                $this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->database->exec("SET NAMES {$this->charset}");
            } catch (\PDOException $e) {
                throw new \Exception("Erreur de connexion à la base de données: " . $e->getMessage());
            }
        }
        return $this->database;
    }

    public function executeSQL(string $file): void
    {
        $content = file_get_contents($file);

        if (file_exists(Define::DIRECTORIES['migrations'] . 'get_started.sql')) {
            $content .= file_get_contents(Define::DIRECTORIES['migrations'] . 'get_started.sql');
        }

        $conn = $this->getConnection();

        $conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, 0);
        $conn->beginTransaction();

        try {
            $success = $conn->exec($content);
            $conn->commit();
            if ($success === false) {
                throw new \Exception("Error importing $file");
            }
        } catch (\PDOException $e) {
            $conn->rollBack();
            throw new \Exception("Error executing SQL command: " . $e->getMessage() . ". File: $file");
        }
    }
}
