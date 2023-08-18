<?php

namespace LeanPHP\Core\Interface;

/**
 * Interface IController
 * 
 * Represents the standard operations for controllers.
 * 
 * @package LeanPHP\Core\Contracts
 */
interface IController {

    /**
     * List all records.
     *
     * @return array - List of all records.
     */
    public function index();
    
    /**
     * Display a specific record by its unique identifier.
     *
     * @param int|string $id - Unique identifier for the record.
     * @return mixed - The displayed record.
     */
    public function show($id);

    /**
     * Store a new record using the provided data.
     *
     * @param array $data - Data for the new record.
     * @return mixed - The stored record.
     */
    public function store($data);

    /**
     * Update a record by its unique identifier using the provided data.
     *
     * @param int|string $id - Unique identifier for the record.
     * @param array $data - Data to update the record.
     * @return mixed - The updated record.
     */
    public function update($id, $data);

    /**
     * Remove a record by its unique identifier.
     *
     * @param int|string $id - Unique identifier for the record.
     * @return bool - Whether the record was removed.
     */
    public function destroy($id);
}
