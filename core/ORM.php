<?php

namespace Core;

class ORM extends Database
{
    private function snakeCaseToPascalCase($tableName)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $tableName)));
    }

    public function findOneBy(string $tableName, array $params = []): ?object
    {
        if ($this->getTable($tableName) === null) {
            throw new \Exception(sprintf('Table %s does not exist', $tableName));
        }

        $where = implode(' AND ', array_map(fn ($key) => "$key = :$key", array_keys($params)));

        $sql = "SELECT * FROM $tableName WHERE $where";
        $statement = $this->getConnection()->prepare($sql);

        $statement->execute($params);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$statement || $result === false) {
            throw new \Exception('Error while fetching data');
        }

        if ($result === false) {
            return null;
        }

        $entityClassName = $this->snakeCaseToPascalCase($tableName);
        $className = Define::NAMESPACES['entities'] . $entityClassName;
    
        $object = new $className();

        if ($object === false) {
            throw new \Exception(sprintf('Entity %s does not exist', $className));
        }

        foreach ($result as $key => $value) {
            if (property_exists($object, $key)) {
                $reflection = new \ReflectionProperty($className, $key);
                $reflection->setAccessible(true);
                $reflection->setValue($object, $value);
            }
        }

        return $object;
    }

    public function findAll($tableName): array
    {
        if ($this->getTable($tableName) === null) {
            throw new \Exception(sprintf('Table %s does not exist', $tableName));
        }

        $sql = "SELECT * FROM $tableName";
        $statement = $this->getConnection()->prepare($sql);

        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if ($result === false) {
            return [];
        }

        $entityClassName = $this->snakeCaseToPascalCase($tableName);
        $className = Define::NAMESPACES['entities'] . $entityClassName;

        $objects = [];
        foreach ($result as $row) {
            $object = new $className();
            foreach ($row as $key => $value) {
                $reflection = new \ReflectionProperty($className, $key);
                $reflection->setAccessible(true);
                $reflection->setValue($object, $value);
            }
            $objects[] = $object;
        }

        return $objects;
    }

    public function count(string $tableName, array $params = []): int
    {
        if ($this->getTable($tableName) === null) {
            throw new \Exception(sprintf('Table %s does not exist', $tableName));
        }

        $where = implode(' AND ', array_map(fn ($key) => "$key = :$key", array_keys($params)));

        $sql = "SELECT COUNT(*) FROM $tableName WHERE $where";
        $statement = $this->getConnection()->prepare($sql);

        $statement->execute($params);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($result === false) {
            return 0;
        }

        return (int) $result['COUNT(*)'];
    }

    public function save(string $tableName, object $object): bool
    {
        if ($this->getTable($tableName) === null) {
            throw new \Exception(sprintf('Table %s does not exist', $tableName));
        }

        $columns = [];
        $values = [];

        $columnNames = $this->getColumns($tableName);

        $reflectionClass = new \ReflectionClass($object);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);

        foreach ($columnNames as $columnName) {
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $propertyName = $property->getName();

                if ($columnName === $propertyName) {
                    $columns[] = $propertyName;
                    $values[":$propertyName"] = $property->getValue($object);
                    break;
                }
            }
        }

        $sql = "INSERT INTO $tableName (";
        $sql .= implode(', ', $columns);
        $sql .= ') VALUES (';
        $sql .= implode(', ', array_keys($values));
        $sql .= ')';

        $statement = $this->getConnection()->prepare($sql);
        return $statement->execute($values);
    }

    public function update(string $tableName, $params = []): bool
    {
        if (!is_array($params) && !is_object($params)) {
            throw new \Exception('Params must be an array or an object');
        } else {
            $params = (array) $params;
        }

        if (!isset($params['id'])) {
            throw new \Exception('ID is required for update operation');
        }
        if ($this->getTable($tableName) === null) {
            throw new \Exception(sprintf('Table %s does not exist', $tableName));
        }

        $columns = $this->getColumns($tableName);
        $columns = array_flip($columns);
        $params = array_intersect_key($params, $columns);

        $setColumns = array_map(fn ($key) => "$key = COALESCE(:$key, $key)", array_keys($params));

        $sql = "UPDATE $tableName SET ";
        $sql .= implode(', ', $setColumns);
        $sql .= ' WHERE id = :id';

        $statement = $this->getConnection()->prepare($sql);
        return $statement->execute($params);
    }

    public function delete(string $tableName, array $params = []): bool
    {
        if ($this->getTable($tableName) === null) {
            throw new \Exception(sprintf('Table %s does not exist', $tableName));
        }

        $where = implode(' AND ', array_map(fn ($key) => "$key = :$key", array_keys($params)));

        $sql = "DELETE FROM $tableName WHERE $where";
        $statement = $this->getConnection()->prepare($sql);
        return $statement->execute($params);
    }

    public function read(string $tableName, array $params = []): array
    {
        if ($this->getTable($tableName) === null) {
            throw new \Exception(sprintf('Table %s does not exist', $tableName));
        }

        $sql = "SELECT ";

        if (isset($params['items'])) {
            $sql .= implode(', ', $params['items']);
        } else {
            $sql .= '*';
        }

        $sql .= " FROM $tableName";

        if (isset($params['id'])) {
            $sql .= ' WHERE id = :id';
        }
        if (isset($params['order'])) {
            $sql .= ' ORDER BY ' . $params['order'];
        }
        if (isset($params['limit'])) {
            $sql .= ' LIMIT ' . $params['limit'];
        }

        $statement = $this->getConnection()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
