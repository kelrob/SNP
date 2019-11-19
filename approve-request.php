<?php

ob_start();
session_start();

# Important Files
require_once 'classes/Database.php';
require_once 'classes/Users.php';
require_once 'utils/Utils.php';
require_once 'classes/LeaveRequest.php';

# Database Object Declaration
$db = new Database();
$conn = $db->getConnection();

# Util Object Declaration
$util = new Utils($conn);

# User Object Declaration
$user = new Users($conn);

# Leave Request Object Declaration
$leaveRequest = new LeaveRequest($conn);

include_once  'includes/line-manager-authentication.php';

$id = $util->sanitize($_GET['id']);
$days = $util->sanitize($_GET['days']);
$userId = $util->sanitize($_GET['user_id']);
$user->leaveDays = $util->getTableFieldById('employees', 'leave_days', $userId);

if ($util->checkValueExist('leave_request', 'id', $id)) {

    # Make sure Employee has enough days left
    if ($user->leaveDays >= $days) {

        # Approve the Request
        $currentDate = date('Y-m-d');
        $expireDate = date('M_d', strtotime($currentDate. ' + '. $days .' days'));

        # Loop through the next days to check for public Holidays
        for ($i=1; $i <= $days; $i++) {

            # fetch all public Holidays
            $query = $util->allHolidays();
            while ($rows = $query->fetch(PDO::FETCH_ASSOC)) {
                $holidays = $rows['dates'];

                # Deduct occurence of holidays from leave
                if (
                    ($newDate = date('M_d', strtotime($currentDate. ' + '. $i .' days')) == $holidays)
                    ||
                    (date('D', strtotime($currentDate. ' + '. $i .' days')) == 'Sat')
                    ||
                    (date('D', strtotime($currentDate. ' + '. $i .' days')) == 'Sun')
                ) {
                    $days = $days - 1;
                }
            }

        }

        $user->leaveDays = $user->leaveDays - $days;
        $util->updateTableColumnById('employees', 'leave_days', $user->leaveDays, $userId);

        # Approve the Request
        if ($util->updateTableColumnById('leave_request', 'status', 1, $id)) {
            header('Location: line-manager');
            exit();
        }

    } else {
        die('Days left not up to no of days requested for leave <a href="line-manager">Go Back</a>');
    }


} else {
    die('error');
}