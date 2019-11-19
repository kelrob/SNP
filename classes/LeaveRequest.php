<?php

class LeaveRequest
{
    public $id;
    public $employeeId;
    public $status;
    public $days;
    public $timeCreated;
    public $timeUpdated;
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

    public function newRequest() {
        # Query Builder
        $query = "INSERT INTO `leave_request`
                  (`employee_id`, `status`,`days`) 
                  VALUES 
                  (:employee_id,:status,:days)";
        ;

        #Enable error Mode
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        # Prepare the statement
        $stmt = $this->_db->prepare($query);

        # Bind the values
        $stmt->bindParam(":employee_id", $this->employeeId);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":days", $this->days);

        # Execute the query and get a Feedback
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * This Method checks to see if a user has a pending Leave Request
     * @param $userId   - The User Id to check
     *
     * @return int      - Integer response
     */
    public function pendingLeaveRequest($userId) {
        # Query Builder
        $query = "SELECT COUNT(*) FROM leave_request WHERE employee_id = '$userId' AND status = '0'";

        # Perform a Direct Query
        $stmt = $this->_db->query($query);

        # Rows Returned
        $rows = (int)$stmt->fetchColumn();

        return $rows;
    }

    /**
     * This Method fetches all Leave Request For Admin
     *
     * @param $mgrId   - The Line Manager Id to check
     * @return mixed    - Returns an array of result
     */
    public function mgrPendingLeaveRequest($mgrId) {
        # Sql Query
        $query = "SELECT leave_request.id, leave_request.employee_id, leave_request.status, leave_request.days, leave_request.time_created, leave_request.time_updated FROM `leave_request` INNER JOIN line_manager ON leave_request.employee_id = line_manager.employee_id WHERE line_manager.l_manager_id = '$mgrId'";

        # Prepare Statement
        $stmt = $this->_db->prepare($query);

        $stmt->execute();

        return $stmt;
    }

}