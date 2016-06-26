<?php

/*

 * To change this template file, choose Tools | 
 * 
 */

/**
 * Description of Database
 * This is an abstract class for database related classes
 * @author Ali Mohtasham Gilani <ali.mohtasham.g@gmail.com>
 */

namespace app\classes;

abstract class Database {

    abstract protected function connect_db($servername, $dbname, $username, $password);

    abstract public function insert_db($table, $columns, $values);

    abstract public function select_db($table, $columns, $where_stmt);

    abstract public function delete_db($table, $where_stmt);

    abstract public function update_db($table, $columns, $values, $where_stmt);
}
