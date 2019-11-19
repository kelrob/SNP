<?php

class Users
{
    # Users Properties
    public $id;
    public $username;
    public $password;
    public $role;
    public $leaveDays;
    public $timeCreated;
    public $timeUpdated;
    private $_db;

    /**
     * Users constructor.
     *
     * @param $conn
     */
    public function __construct($conn)
    {
        $this->_db = $conn;
    }

    /**
     * This Logs in the user and return the user id to the $user->id property
     * @return bool
     */
    public function login()
    {
        $query = "SELECT id
                  FROM employees
                  WHERE 
                  username = :username";

        // Prepare the Sql Query
        $stmt = $this->_db->prepare($query);

        // Bind Statement
        $stmt->bindParam(":username", $this->username);

        // Execute Query
        $stmt->execute();

        // Rows Returned
        $rows = $stmt->rowCount();

        if ($rows > 0) {
            $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $userInfo['id'];

            return true;
        } else {
            return false;
        }
    }

}