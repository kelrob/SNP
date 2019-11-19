<?php


class LineManager
{
    public $id;
    public $l_manager_id;
    public $employee_id;
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
}
