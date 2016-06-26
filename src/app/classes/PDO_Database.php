<?php

/*

 * 
 * 
 */

/**
 * Description of PDO_Database
 * This class is using PDO to access MySQL database
 * 
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes;

use app\classes\Database;
use app\classes\JSON;
use PDO;

class PDO_Database extends Database {

    /**
     *
     * @var PDO|null 
     */
    private $conn;

    /**
     * Loads database specification form "config/config.json"
     */
    public function __construct() {

        $json = new JSON();
        $init_db = $json->readFile(__SITE_PATH . "\config\config.json");
        $this->connect_db("localhost", $init_db["database_name"], $init_db["database_username"], $init_db["database_password"]);
    }

    public function __destruct() {

        $this->conn = null;
    }

    /**
     * Conects to database
     * @param string $servername
     * @param string $dbname
     * @param string $username
     * @param string $password
     */
    protected function connect_db($servername, $dbname, $username, $password) {
        try {
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    /**
     * Insert data to database
     * @param string $table
     * @param string[] $columns
     * @param string[] $values
     * @return boolean
     */
    public function insert_db($table, $columns, $values) {

        $sql = "INSERT INTO " . $table . " (" . implode(",", $columns) . ") VALUES (:" . implode(", :", $columns) . ")";
        try {
            $stmt = $this->conn->prepare($sql);
            foreach ($columns as $key => $column) {
                $stmt->bindParam(':' . $column, $values[$key]);
            }

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Selects data from database
     * @param string $table
     * @param string[] $columns
     * @param string $where_stmt
     * @param boolean $have_condition can be false to avoid condition statement
     * @return boolean
     */
    public function select_db($table, $columns, $where_stmt, $have_condition = true) {
        if ($have_condition) {
            $where_stmt = " WHERE " . $where_stmt;
        }
        if ($columns != '*') {
            $columns = implode(",", $columns);
        }
        $sql = "SELECT " . $columns . " FROM " . $table . " " . $where_stmt;

        try {

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($result)) {
                return false;
            } else {
                return $result;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Deletes data from database
     * @param string $table
     * @param string $where_stmt
     * @return boolean
     */
    public function delete_db($table, $where_stmt) {
        try {
            $sql = "DELETE FROM " . $table . " WHERE " . $where_stmt;
            $this->conn->exec($sql);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Updates database
     * @param string $table
     * @param string[] $columns
     * @param string[] $values
     * @param string $where_stmt
     * @return boolean
     */
    public function update_db($table, $columns, $values, $where_stmt) {

        $sql = "UPDATE " . $table . " SET ";
        foreach ($columns as $key => $column) {
            if ($key == 0) {
                $sql.=$column . " = :" . $column;
            } else {
                $sql.="," . $column . " = :" . $column;
            }
        }
        $sql.=" WHERE " . $where_stmt;
        try {
            $stmt = $this->conn->prepare($sql);
            foreach ($columns as $key => $column) {
                $stmt->bindParam(':' . $column, $values[$key]);
            }
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * Counts $table row numbers
     * @param string $table
     * @return boolean
     */
    public function table_row_count($table) {

        $sql = "SELECT count(*) FROM " . $table;

        try {

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($result)) {
                return false;
            } else {
                return $result[0]["count(*)"];
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}
