<?php

namespace Core\Entity;

use Core\Database;
use Core\Define;

class EntityGenerate
{
    protected Database $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->handleGenerateErrors();
    }

    public function getSql(string $entity): string
    {
        $sql = $this->generateSQL($entity);
        return $sql;
    }

    public function generateAllSQL(): string
    {
        $migrationDir = Define::DIRECTORIES['migrations'];
        if (!is_dir($migrationDir)) {
            throw new \Exception("Migrations directory not found: $migrationDir");
        }
    
        $sqlQueries = "";
        foreach (glob(Define::DIRECTORIES['entities'] . '*.php') as $entityFile) {
            $entityName = basename($entityFile, '.php');
            $sql = $this->generateSQL($entityName);
            if ($sql) {
                $sqlQueries .= $sql . "\n";
            } else {
                throw new \Exception("No properties found for $entityName");
            }
        }

        $existingMigrations = glob($migrationDir . '/*.sql');

        foreach ($existingMigrations as $migrationFile) {
            $existingMigrationContent = file_get_contents($migrationFile);
            if ($sqlQueries === $existingMigrationContent) {
                return "No changes detected, no new migration created.";
            }
        }
    
        $migrationFilename = date('Y-m-d-H-i-s') . '.sql';
        $migrationPath = $migrationDir . '/' . $migrationFilename;
        
        $saveFile = file_put_contents($migrationPath, $sqlQueries);
    
        if ($saveFile === false) {
            throw new \Exception("Failed to save SQL queries to $migrationPath");
        }
    
        $this->db->executeSQL($migrationPath);
    
        return "New migration created at $migrationPath";
    }

    private function generateSQL(string $entity): string
    {
        $classBody = "";
        $properties = $this->getEntityProperties($entity);
    
        if (!$properties) {
            throw new \Exception("No properties found for $entity");
        }
    
        $tableName = $this->getTableName($entity);
        $sql = "";
    
        if ($this->db->getTable($tableName)) {
            $existingColumns = $this->db->getColumns($tableName);
            $existingColumnsIndexed = array_flip($existingColumns);
    
            $sql = "ALTER TABLE `$tableName` ";
            $sqlChanges = [];
    
            foreach ($properties as $property) {
                $columnName = $property['name'];
    
                if (isset($existingColumnsIndexed[$columnName])) {
                    unset($existingColumnsIndexed[$columnName]);
    
                    $sqlChanges[] = "MODIFY COLUMN `$columnName` {$property['type']}" . 
                                    ($property['length'] && $property['type'] === 'VARCHAR' ? "({$property['length']})" : '') .
                                    (isset($property['autoincrement']) ? ' AUTO_INCREMENT' : '') .
                                    (isset($property['nullable']) && $property['nullable'] === 'NOT NULL' ? ' NOT NULL' : '') .
                                    (isset($property['default']) ? " DEFAULT {$property['default']}" : '');
                } else {
                    $sqlChanges[] = "ADD COLUMN `$columnName` {$property['type']}" . 
                                    ($property['length'] && $property['type'] === 'VARCHAR' ? "({$property['length']})" : '') .
                                    (isset($property['autoincrement']) ? ' AUTO_INCREMENT' : '') .
                                    (isset($property['nullable']) && $property['nullable'] === 'NOT NULL' ? ' NOT NULL' : '') .
                                    (isset($property['default']) ? " DEFAULT {$property['default']}" : '');
                }
            }

            foreach ($existingColumnsIndexed as $columnName => $position) {
                $sqlChanges[] = "DROP COLUMN `$columnName`";
            }
    
            $sql .= implode(", ", $sqlChanges) . ";";

            $primaryKey = array_filter($properties, function($property) {
                return isset($property['primary']);
            });
    
            if (!empty($primaryKey)) {
                $sql .= "PRIMARY KEY (`id`)";
            }
        } else {
            $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (";
    
            foreach ($properties as $property) {
                $columnName = $property['name'];
    
                $sql .= "`$columnName` {$property['type']}" . 
                        ($property['length'] && $property['type'] === 'VARCHAR' ? "({$property['length']})" : '') .
                        (isset($property['autoincrement']) ? ' AUTO_INCREMENT' : '') .
                        (isset($property['nullable']) && $property['nullable'] === 'NOT NULL' ? ' NOT NULL' : '') .
                        (isset($property['default']) ? " DEFAULT {$property['default']}" : '') .
                        ", ";
            }
    
            $primaryKey = array_filter($properties, function($property) {
                return isset($property['primary']);
            });
    
            if (!empty($primaryKey)) {
                $sql .= "PRIMARY KEY (`id`)";
            }
    
            $sql = rtrim($sql, ", ") . ");";
        }
    
        return $sql;
    }

    private function getEntityProperties(string $entity): array
    {
        $properties = [];
        $reflectionClass = new \ReflectionClass(Define::NAMESPACES['entities'] . $entity);
        $classAnnotations = $this->getClassAnnotations($reflectionClass);
    
        if (!isset($classAnnotations['Entity'])) {
            throw new \Exception("Missing @Entity annotation for $entity");
        } elseif (!isset($classAnnotations['Table'])) {
            throw new \Exception("Missing @Table annotation for $entity");
        }

        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE) as $property) {
            if ($property->getDeclaringClass()->getName() === $reflectionClass->getName()) {
                $propertyName = $property->getName();
                $propertyAnnotations = $this->getPropertyAnnotations($property);

                if (isset($propertyAnnotations['Column'])) {
                    $columnAnnotation = $propertyAnnotations['Column'];
                    $type = 'TEXT';
                    $length = null;

                    if ($columnAnnotation['type'] === 'string' && isset($columnAnnotation['length']) && $columnAnnotation['length'] <= 255) {
                        $type = 'VARCHAR';
                        $length = $columnAnnotation['length'];
                    } elseif ($columnAnnotation['type'] === 'datetime') {
                        $type = 'DATETIME';
                    } elseif ($columnAnnotation['type'] === 'date') {
                        $type = 'DATE';
                    } elseif ($columnAnnotation['type'] === 'integer') {
                        $type = 'INT';
                    } elseif ($columnAnnotation['type'] === 'bool') {
                        $type = 'TINYINT';
                    }

                    $properties[] = [
                        'name' => $propertyName,
                        'type' => $type,
                        'length' => $length,
                        'nullable' => $columnAnnotation['nullable'] === 'true' ? 'NULL' : 'NOT NULL',
                        'default' => $columnAnnotation['default'] ?? null,
                    ];
                } elseif (isset($propertyAnnotations['Id'])) {
                    $properties[] = [
                        'name' => $propertyName,
                        'type' => $propertyAnnotations['Id']['type'],
                        'length' => null,
                        'autoincrement' => $propertyAnnotations['Id']['autoincrement'] === 'true' ? 'AUTO_INCREMENT' : null,
                        'nullable' => $propertyAnnotations['Id']['nullable'] === 'true' ? 'NULL' : 'NOT NULL',
                        'primary' => 'PRIMARY KEY'
                    ];
                } elseif (isset($propertyAnnotations['OneToMany'])) {
                    $relationAnnotation = $propertyAnnotations['OneToMany'];
                    $properties[] = [
                        'name' => $relationAnnotation['mappedBy'],
                        'type' => 'INT',
                        'nullable' => 'NULL',
                        'foreign_key' => true,
                        'relation' => 'OneToMany',
                        'target_entity' => $relationAnnotation['targetEntity'],
                        'join_column' => $relationAnnotation['name'] ?? 'id',
                        'referenced_column' => $relationAnnotation['referencedColumnName'] ?? 'id',
                        'join_table' => $relationAnnotation['joinTable'] ?? null,
                        'order_by' => $relationAnnotation['OrderBy'] ?? null,
                    ];
                } elseif (isset($propertyAnnotations['ManyToMany'])) {
                    $relationAnnotation = $propertyAnnotations['ManyToMany'];
                    $joinColumns = [];
    
                    if (isset($relationAnnotation['JoinColumns'])) {
                        foreach ($relationAnnotation['JoinColumns'] as $joinColumn) {
                            $joinColumns[] = [
                                'name' => $joinColumn['name'],
                                'referenced_column' => $joinColumn['referencedColumnName']
                            ];
                        }
                    } else {
                        $joinColumns[] = [
                            'name' => $relationAnnotation['name'] ?? 'id',
                            'referenced_column' => $relationAnnotation['referencedColumnName'] ?? 'id'
                        ];
                    }
    
                    $properties[] = [
                        'name' => $propertyName,
                        'type' => 'INT',
                        'nullable' => 'NULL',
                        'foreign_key' => true,
                        'relation' => 'ManyToMany',
                        'target_entity' => $relationAnnotation['targetEntity'],
                        'join_column' => $relationAnnotation['name'] ?? 'id',
                        'referenced_column' => $relationAnnotation['referencedColumnName'] ?? 'id',
                        'join_table' => $relationAnnotation['joinTable'] ?? null,
                        'order_by' => $relationAnnotation['OrderBy'] ?? null,
                        'join_columns' => $joinColumns,
                    ];
                } else {
                    throw new \Exception("Missing @Column annotation for $entity::$propertyName");
                }
            }
        }

        return $properties;
    }    

    private function getClassAnnotations(\ReflectionClass $reflectionClass): array
    {
        if ($reflectionClass->getName() === 'Core\Entity') {
            return [];
        }

        $docComment = $reflectionClass->getDocComment();
        preg_match_all('/@(\w+)\s*(?:\(([^)]+)\)|(\S+))/m', $docComment, $matches);

        $annotations = [];
        foreach ($matches[1] as $index => $key) {
            $value = trim($matches[2][$index] ?? $matches[3][$index] ?? '');
            if ($key === 'Table') {
                if (!preg_match('/name\s*=\s*["\']([^"\']+)["\']/', $value, $tableName)) {
                    throw new \Exception("Invalid @Table annotation for {$reflectionClass->getName()}. You must specify the table name. (e.g. @Table(name=\"table_name\"))");
                }
                $value = $tableName[1];
            }
            $annotations[$key] = trim($value, '()\'"');
        }

        return $annotations;
    }

    private function getPropertyAnnotations(\ReflectionProperty $property): array
    {
        $docComment = $property->getDocComment();
        preg_match_all('/@(\w+)\s*(?:\(([^)]+)\))?/', $docComment, $matches);

        $annotations = [];

        $isIdFound = false;

        foreach ($matches[1] as $index => $key) {
            $value = trim($matches[2][$index] ?? '');

            if ($key === 'Id') {
                $isIdFound = true;
                continue;
            }

            if ($key === 'GeneratedValue') {
                $annotations['Id']['autoincrement'] = 'true';
                continue;
            }

            if ($key === 'Default') {
                $annotations['Column']['default'] = $this->parseAnnotationValue($value);
                continue;
            }

            if ($isIdFound && $key === 'Column') {
                $columnAnnotations = $this->parseAnnotationValue($value);

                if (!isset($columnAnnotations['name'])) {
                    $columnAnnotations['name'] = $property->getName();
                }

                $this->handleErrors($columnAnnotations, $property);

                $annotations['Id'] = $columnAnnotations;
            } elseif (!$isIdFound && $key === 'Column') {
                $columnAnnotations = $this->parseAnnotationValue($value);

                if (!isset($columnAnnotations['name'])) {
                    $columnAnnotations['name'] = $property->getName();
                }

                $this->handleErrors($columnAnnotations, $property);

                $annotations['Column'] = $columnAnnotations;
            } elseif ($key === 'OneToMany' || $key === 'ManyToMany') {
                $relationAnnotation = $this->parseAnnotationValue($value);
                $annotations[$key] = $relationAnnotation;
            }
        }

        return $annotations;
    }

    private function parseAnnotationValue(string $value)
    {
        if (strpos($value, '=') !== false) {
            $pairs = explode(',', $value);
            $result = [];
            foreach ($pairs as $pair) {
                list($key, $val) = explode('=', $pair, 2);
                $result[trim($key)] = trim($val, '()\'"');
            }
            return $result;
        } else {
            return trim($value, '()\'"');
        }
    }

    private function getTableName(string $entity): string
    {
        $reflectionClass = new \ReflectionClass("\\App\\Entity\\$entity");
        $classAnnotations = $this->getClassAnnotations($reflectionClass);

        if (isset($classAnnotations['Table'])) {
            return $classAnnotations['Table'];
        } else {
            throw new \Exception("Missing @Table annotation for $entity");
        }
    }

    private function handleErrors(array $annotations, \ReflectionProperty $property)
    {
        $typeValues = ['integer', 'string', 'bool', 'date', 'datetime'];
        $boolValues = ['true', 'false'];

        if (!isset($annotations['type']) || !in_array($annotations['type'], $typeValues)) {
            throw new \Exception("Invalid @Column type for {$property->class}::{$property->name}. You must specify the column type. (e.g. @Column(type=\"integer\"))");
        } elseif (!isset($annotations['nullable']) || !in_array($annotations['nullable'], $boolValues)) {
            throw new \Exception("Invalid @Column nullable for {$property->class}::{$property->name}. You must specify if the column is nullable. (e.g. @Column(nullable=\"true\"))");
        } elseif ($annotations['type'] === 'integer' && (!isset($annotations['autoincrement']) || !in_array($annotations['autoincrement'], $boolValues))) {
            throw new \Exception("Invalid @Column autoincrement for {$property->class}::{$property->name}. You must specify if the column is autoincrement. (e.g. @Column(autoincrement=\"true\"))");
        } elseif ($annotations['type'] === 'string' && ($annotations['length'] < 1)) {
            throw new \Exception("Invalid @Column length for {$property->class}::{$property->name}. You must specify the column length. (e.g. @Column(length=255))");
        } elseif (in_array($annotations['type'], ['integer', 'bool', 'date', 'datetime']) && isset($annotations['length'])) {
            throw new \Exception("Invalid @Column length for {$property->class}::{$property->name}. You must not specify the column length for this type. (e.g. @Column(type=\"integer\"))");
        } elseif (in_array($annotations['type'], ['string', 'bool', 'date', 'datetime']) && isset($annotations['autoincrement'])) {
            throw new \Exception("Invalid @Column autoincrement for {$property->class}::{$property->name}. You must not specify the column autoincrement for this type. (e.g. @Column(type=\"string\"))");
        }
    }

    private function handleGenerateErrors(): void
    {
        if (!file_exists(Define::DIRECTORIES['entities'])) {
            throw new \Exception("Entities directory not found");
        } elseif (!is_dir(Define::DIRECTORIES['entities'])) {
            throw new \Exception("Entities is not a directory");
        } elseif (!is_readable(Define::DIRECTORIES['entities'])) {
            throw new \Exception("Entities directory is not readable");
        } elseif (!file_exists(Define::DIRECTORIES['migrations'])) {
            throw new \Exception("Migrations directory not found");
        } elseif (!is_dir(Define::DIRECTORIES['migrations'])) {
            throw new \Exception("Migrations is not a directory");
        } elseif (!is_writable(Define::DIRECTORIES['migrations'])) {
            throw new \Exception("Migrations directory is not writable");
        }
    }
}