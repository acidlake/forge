<?php

namespace Base\ORM;

/**
 * BaseModelInterface
 *
 * Defines the contract for a base model class in the ORM system.
 * It provides essential methods for interacting with the database,
 * including basic CRUD operations (Create, Read, Update, Delete), raw queries,
 * and model pagination. Additionally, it offers support for UUID management.
 */
interface BaseModelInterface
{
    /**
     * Set the table name for the model.
     *
     * This method sets the table that the model should interact with.
     *
     * @param string $table The name of the table to set.
     * @return self The instance of the model for method chaining.
     */
    public function setTable(string $table): self;

    /**
     * Find a record by its ID.
     *
     * This method retrieves a record by its unique identifier.
     *
     * @param string|int $id The ID of the record to find.
     * @return object|null The record object if found, or null if not found.
     */
    public static function find(string|int $id): ?object;

    /**
     * Find a record by a specific field and value.
     *
     * This method retrieves a record that matches a given field and value.
     *
     * @param string $field The field name to search by.
     * @param mixed $value The value to match in the field.
     * @return object|null The record object if found, or null if not found.
     */
    public static function findBy(string $field, mixed $value): ?object;

    /**
     * Apply conditions to a query.
     *
     * This method allows the user to filter results by specific conditions.
     *
     * @param array $conditions An associative array of conditions (e.g., ['field' => 'value']).
     * @return self The instance of the model for method chaining.
     */
    public static function where(array $conditions): self;

    /**
     * Retrieve all records.
     *
     * This method retrieves all records from the currently set table, with any applied conditions.
     *
     * @return array An array of record objects.
     */
    public static function all(): array;

    /**
     * Save a new or existing record.
     *
     * This method saves the provided data to the database, either creating a new record
     * or updating an existing one.
     *
     * @param array $data An associative array of data to save.
     * @return object The saved or updated record object.
     */
    public function save(array $data): object;

    /**
     * Delete a record by its ID.
     *
     * This method deletes a record identified by its ID from the database.
     *
     * @param string|int $id The ID of the record to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete(string|int $id): bool;

    /**
     * Enable or disable UUID usage for the model.
     *
     * This method allows enabling or disabling the automatic handling of UUIDs
     * for the model, which can be useful for uniquely identifying records.
     *
     * @param bool $enable True to enable UUIDs, false to disable them.
     * @return self The instance of the model for method chaining.
     */
    public function enableUuid(bool $enable): self;

    /**
     * Execute a raw SQL query.
     *
     * This method allows you to execute a raw SQL query with optional bindings for parameters.
     *
     * @param string $query The raw SQL query to execute.
     * @param array $bindings Optional array of bindings to safely insert into the query.
     * @return mixed The result of the query (could be a boolean, object, or array, depending on the query).
     */
    public function rawQuery(string $query, array $bindings = []): mixed;

    /**
     * Paginate the query results.
     *
     * This method splits the query results into pages, allowing for easier pagination in results.
     *
     * @param int $perPage The number of records per page.
     * @param int $currentPage The current page number.
     * @return array An array containing the paginated results (usually with keys 'data' and 'total').
     */
    public static function paginate(
        int $perPage = 10,
        int $currentPage = 1
    ): array;

    /**
     * Determine if the model uses soft deletes.
     *
     * @return bool
     */
    public function usesSoftDeletes(): bool;
}
