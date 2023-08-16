<?php
/**
 * BaseModel Class
 *
 * This class handles database connections using PHP Data Objects (PDO).
 * It provides a consistent way to connect to the database and perform operations.
 *
 * @package     LEANPRESS
 * @version     1.1.0
 * @link        http://www.leanpress.com
 * @license     GPL/GNU 3.0 
 * @author      Vedat Yildirim <info@vedatyildirim.com>
 */

namespace LeanPress\Model;

use PDO;
use PDOException;
use LeanPress\Utils\Logger;

class BaseModel
{

    protected $db;

    private $logger;

    /**
     * Constructor
     *
     * Establishes a database connection using the provided configuration constants.
     * Sets PDO options for error handling and fetch mode.
     */
    public function __construct()
    {
        $this->logger = new Logger();
        global $config;
        try {
            $this->db = new PDO(
                "{$config['database']['driver']}:host={$config['database']['host']};dbname={$config['database']['dbname']};charset={$config['database']['charset']}",
                $config['database']['username'],
                $config['database']['password']
            );

            // Set the PDO error mode to exception
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }


    /**
     * Handle PDO exceptions. 
     * In a real-world application, consider logging the exception instead of just displaying.
     *
     * @param PDOException $e
     * @return void
     */
    protected function handleException(PDOException $e): void
    { {

            // In a production environment, you would likely log this error instead of printing it out.
            echo "Database Error: " . $e->getMessage();

            // In production, you might want to log the error instead
            // error_log($e->getMessage());

            // Write the Error to Log fle
            $this->logger->write($e->getMessage());

            // And maybe render a user-friendly error message
            // echo "An error occurred. Please try again later.";
            exit(); // or you can redirect to an error page
        }

    }
}