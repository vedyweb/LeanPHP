<?php

namespace LeanPress\Core\Interface;

/**
 * Interface IModel
 * 
 * Represents the standard operations for data models.
 * 
 * @package LeanPress\Core\Contracts
 */
interface IModel {

    /**
     * Create a new record using the provided data.
     *
     * @param array $data - Data for the new record.
     * @return mixed - The created record.
     */
    public function create($data);
    
    /**
     * Retrieve a record by its unique identifier.
     *
     * @param int|string $id - Unique identifier for the record.
     * @return mixed - The retrieved record.
     */
    public function read($id);
    /**
     * Edit a record by its unique identifier using the provided data.
     *
     * @param int|string $id - Unique identifier for the record.
     * @param array $data - Data to update the record.
     * @return mixed - The edited record.
     */
    public function edit($id);
    /**
     * Update a record by its unique identifier using the provided data.
     *
     * @param int|string $id - Unique identifier for the record.
     * @param array $data - Data to update the record.
     * @return mixed - The updated record.
     */
    public function update($id, $data);

    /**
     * Delete a record by its unique identifier.
     *
     * @param int|string $id - Unique identifier for the record.
     * @return bool - Whether the record was deleted.
     */
    public function delete($id);

    /**
     * Search records using a query.
     *
     * @param string $query - The search query.
     * @return array - List of matching records.
     */
    public function search($query);
}
