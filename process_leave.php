<?php

session_start();

# Important Files
require_once 'classes/Database.php';
require_once 'classes/Users.php';
require_once 'utils/Utils.php';
require_once 'classes/Notification.php';
require_once 'classes/LeaveRequest.php';

# Database Object Declaration
$db = new Database();
$conn = $db->getConnection();

# Util Object Declaration
$util = new Utils($conn);

# User Object Declaration
$user = new Users($conn);

# Notification Object Declaration
$notification = new Notification($conn);

# Leave Request Object Declaration
$leaveRequest = new LeaveRequest($conn);

if (isset($_SESSION['i_snap_user_id'])) {
    $user->id = $_SESSION['i_snap_user_id'];

    # Get the logged in user Role and Line Manager
    $user->role = $util->getTableFieldById('employees', 'role', $user->id);
    $user->username = $util->getTableFieldById('employees', 'username', $user->id);
    $user->leaveDays = $util->getTableFieldById('employees', 'leave_days', $user->id);
    $userLineMgr = $util->getTableFieldViaColumn('line_manager', 'l_manager_id', 'employee_id', $user->id);

    if ($user->role != 'user') {
        echo '
            <div class="alert alert-danger">Unauthorized Access</div>
        ';
    }

    # Validation of request
    $days = $util->sanitize($_POST['days']);
    $employeeId = $util->sanitize($_POST['user_id']);
    if ($employeeId == $user->id) {

        # Make sure leave request is not more than 20 days
        if (empty($days)) {
            echo  '
                <div class="alert alert-danger">Field can not be empty</div>
            ';
        } else if ($days > 20) {
            echo  '
                <div class="alert alert-danger">Leave request can not be more than 20 days</div>
            ';
        } else if ($days <= 20 && $days <= $user->leaveDays) {

            # Submit Request
            $leaveRequest->employeeId = $user->id;
            $leaveRequest->status = 0;
            $leaveRequest->days = $days;

            # Notify Leave Manager
            $notification->receiverId = $userLineMgr;
            $notification->notificationMessage = $user->username . ' just requested for a leave of ' . $days . ' days';

            if ($notification->newNotification() == true && $leaveRequest->newRequest() == true) {
                echo '
                    <div class="alert alert-success">Request Submitted successfully <a href="">Go Back</a></a></div>
                    
                    <style>
                        #form {
                            display: none;
                        }
                    </style
                    
                ';
            }


            # Send Email
            # Come back here

        } else {
            echo '<div class="alert alert-danger">You do not have up to the amount of days left <a href="">Go Back</a></a></div>';
        }

    }


} else {
    echo '
        <div class="alert alert-danger">Unauthorized Access</div>
    ';
}