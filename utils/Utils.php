<?php

class Utils {

    private $_db;

    /**
     * Utils constructor.
     *
     * @param $conn
     */
    public function __construct($conn)
    {
        $this->_db = $conn;
    }

    /**
     * Method to Sanitize a String
     *
     * @param $string - String to Sanitize
     *
     * @return string - Return the Sanitized String
     */
    public function sanitize($string)
    {
        return htmlspecialchars(strip_tags($string));
    }

    /**
     * This Method Checks if a value exist in a table
     *
     * @param $table - Table Name
     * @param $column - Table Column to be checked
     * @param $value - Value to be checked for in the table Column
     *
     * @return bool     - Boolean response to work with
     */
    public function checkValueExist($table, $column, $value)
    {
        # Query that Checks if Value exist in table on the database
        $query = "SELECT COUNT(*) FROM `$table` WHERE `$column` = '$value'";

        # Perform the Direct Query
        $stmt = $this->_db->query($query);


        # Rows Returned
        $rows = (int)$stmt->fetchColumn();

        if ($rows > 0) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * This method brings a table field by the Id
     *
     * @param $table - Table Name
     * @param $column - Field to get value from
     * @param $id - Id Condition
     *
     * @return mixed
     */
    public function getTableFieldById($table, $column, $id)
    {
        $query = "SELECT `$column`
                  FROM `$table`
                  WHERE `id` = '$id'
                  LIMIT 1
                  ";
        $stmt = $this->_db->prepare($query);
        $stmt->execute();

        $tableData = $stmt->fetch();
        return $tableData[$column];
    }

    /**
     * This method updates a column of a table
     *
     * @param $table - Table to update
     * @param $column - Column to update data
     * @param $value - Value to replace with the old
     * @param $id - Column Id
     *
     * @return bool
     */
    public function updateTableColumnById($table, $column, $value, $id)
    {
        $value = addslashes($value);
        $query = "UPDATE $table 
                SET `$column` = '$value'
                WHERE id = '$id'";

        $stmt = $this->_db->prepare($query);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $table    String tableName within the database
     * @param $field    String columnName within the database
     * @param $column    String columnName within the database
     * @param $value    String columnName within the database
     *
     * @return mixed    returns value in a column
     */
    public function getTableFieldViaColumn($table, $field, $column, $value)
    {
        $query = "SELECT $field
                  FROM 
                       $table
                  WHERE $column = '$value'
                  LIMIT 1
        ";


        $stmt = $this->_db->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $userData = $stmt->fetch();
        return $userData[$field];
    }

    public function readableDate($date) {
        return date('M j Y g:i A', strtotime($date));
    }


    public function allHolidays() {
        # Sql Query
        $query = "SELECT * from public_holidays";

        # Prepare Statement
        $stmt = $this->_db->prepare($query);

        $stmt->execute();

        return $stmt;
    }

}