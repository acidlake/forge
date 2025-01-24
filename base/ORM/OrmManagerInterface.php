<?php

namespace Base\ORM;

/**
 * OrmManagerInterface
 *
 * Defines the contract for an ORM manager. It provides methods to interact with
 * a database, allowing CRUD operations (Create, Read, Update, Delete) and other
 * functionalities such as querying, pagination, and handling NoSQL or soft-deleted records.
 */
interface OrmManagerInterface
{
    /**
     * Set the table name for the ORM.
     *
     * This method sets the table that the ORM should interact with.
     *
     * @param string $table The name of the table to set.
     * @return self The instance of the ORM manager for method chaining.
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
    public function find(string|int $id): ?object;

    /**
     * Find a record by a specific field and value.
     *
     * This method retrieves a record that matches a given field and value.
     *
     * @param string $field The field name to search by.
     * @param mixed $value The value to match in the field.
     * @return object|null The record object if found, or null if not found.
     */
    public function findBy(string $field, mixed $value): ?object;

    /**
     * Apply conditions to a query.
     *
     * This method allows the user to filter results by specific conditions.
     *
     * @param array $conditions An associative array of conditions (e.g., ['field' => 'value']).
     * @return self The instance of the ORM manager for method chaining.
     */
    public function where(array $conditions): self;

    /**
     * Retrieve all records.
     *
     * This method retrieves all records from the currently set table, with any applied conditions.
     *
     * @return array An array of record objects.
     */
    public function all(): array;

    /**
     * Insert a new record.
     *
     * This method inserts a new record into the table with the provided data.
     *
     * @param array $data An associative array of data to insert into the table.
     * @return object The created record object.
     */
    public function insert(array $data): object;

    /**
     * Update an existing record.
     *
     * This method updates an existing record identified by its ID with the provided data.
     *
     * @param string|int $id The ID of the record to update.
     * @param array $data The data to update the record with.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update(string|int $id, array $data): bool;

    /**
     * Delete a record by its ID.
     *
     * This method deletes a record identified by its ID.
     *
     * @param string|int $id The ID of the record to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete(string|int $id): bool;

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
     * Enable or disable NoSQL mode.
     *
     * This method toggles between traditional SQL mode and NoSQL mode for handling data.
     *
     * @param bool $enable True to enable NoSQL mode, false to disable it.
     * @return self The instance of the ORM manager for method chaining.
     */
    public function enableNoSQLMode(bool $enable): self;

    /**
     * Enable the inclusion of soft-deleted records.
     *
     * This method enables the inclusion of soft-deleted records in the query results.
     *
     * @return void
     */
    public function enableIncludeTrashed(): void;

    /**
     * Enable the filtering of only soft-deleted records.
     *
     * This method filters the query to only return soft-deleted records.
     *
     * @return void
     */
    public function enableOnlyTrashed(): void;

    /**
     * Paginate the query results.
     *
     * This method splits the query results into pages, allowing for easier pagination in results.
     *
     * @param int $perPage The number of records per page.
     * @param int $currentPage The current page number.
     * @return array An array containing the paginated results (usually with keys 'data' and 'total').
     */
    public function paginate(int $perPage = 10, int $currentPage = 1): array;

    public function setModel(BaseModelInterface $model): self;
}
