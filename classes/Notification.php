<?php

class Notification
{
    public $id;
    public $receiverId;
    public $notificationMessage;
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
     * This Method creates a new Notification
     * @return bool
     */
    public function newNotification() {
        # Query Builder
        $query = "INSERT INTO `notification`(`receiver_id`, `notification_message`) 
                  VALUES 
                  (:receiver_id, :notification_message)"
        ;

        # Enable error Mode
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        # Prepare the statement
        $stmt = $this->_db->prepare($query);

        # Bind the values
        $stmt->bindParam(":receiver_id", $this->receiverId);
        $stmt->bindParam(":notification_message", $this->notificationMessage);

        # Execute the query and get a Feedback
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

    }

}