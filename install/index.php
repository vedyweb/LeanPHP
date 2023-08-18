<?php

require '../src/Model/BaseModel.php';

class SQLImporter extends BaseModel {

    public function importSQL() {

        try {
            $conn = new PDO("mysql:host=localhost;dbname=LeanPHP", "root", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Read the SQL file
            $sqlFile = '../data/LeanPHP.sql';
            $sql = file_get_contents($sqlFile);

            // Execute the SQL queries
            $conn->exec($sql);

            echo "SQL file has been successfully imported.";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $conn = null;
    }
}

// Usage example
$importSqlFile = new SQLImporter();

// Run the SQL import process
$run = $importSqlFile->importSQL();
//print_r($run);