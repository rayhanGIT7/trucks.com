<?php

namespace App\Core;

/**
 * Base class for all models. One model = one database table.
 *
 * A child class sets $table and declares one public property per column.
 * Common queries (find, create, update) are written here once;
 * table-specific queries are written in the child class.
 *
 * Note: "static" here means "the child class that called the method",
 * so User::find(1) returns a User and Truck::find(1) returns a Truck.
 */
abstract class Model
{
    protected static string $table = '';

    public ?int $id = null;

    /** Find one row by id. Returns null if not found. */
    public static function find(int $id)
    {
        return static::queryOne('SELECT * FROM ' . static::$table . ' WHERE id = ?', [$id]);
    }

    /** Find one row by id. Shows the 404 page if not found. */
    public static function findOrFail(int $id)
    {
        $model = static::find($id);
        if ($model === null) {
            throw new HttpException(404);
        }
        return $model;
    }

    /**
     * Insert a new row and return it.
     * Example: User::create(['name' => 'Rahim', 'email' => 'r@x.com'])
     */
    public static function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = 'INSERT INTO ' . static::$table . " ($columns) VALUES ($placeholders)";

        $pdo = Database::connection();
        $pdo->prepare($sql)->execute(array_values($data));

        return static::find((int) $pdo->lastInsertId());
    }

    /**
     * Update this row.
     * Example: $user->update(['name' => 'New name'])
     */
    public function update(array $data): void
    {
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "$column = ?";
        }
        $sql = 'UPDATE ' . static::$table . ' SET ' . implode(', ', $setParts) . ' WHERE id = ?';

        $values = array_values($data);
        $values[] = $this->id;
        Database::connection()->prepare($sql)->execute($values);

        // Keep the object in sync with the database.
        foreach ($data as $column => $value) {
            $this->$column = $value;
        }
    }

    /** Run a SELECT and return a list of models. */
    protected static function query(string $sql, array $params = []): array
    {
        $statement = Database::connection()->prepare($sql);
        $statement->execute($params);

        $models = [];
        foreach ($statement->fetchAll() as $row) {
            $models[] = static::fromRow($row);
        }
        return $models;
    }

    /** Run a SELECT and return the first model, or null. */
    protected static function queryOne(string $sql, array $params = [])
    {
        $models = static::query($sql, $params);
        return $models[0] ?? null;
    }

    /** Run a query that returns one value, e.g. SELECT COUNT(*). */
    protected static function scalar(string $sql, array $params = []): mixed
    {
        $statement = Database::connection()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchColumn();
    }

    /** Turn one database row (array) into a model object. */
    protected static function fromRow(array $row)
    {
        $model = new static();
        foreach ($row as $column => $value) {
            if (property_exists($model, $column)) {
                $model->$column = $value;
            }
        }
        return $model;
    }
}
