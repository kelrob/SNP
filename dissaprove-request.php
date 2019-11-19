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

if ($util->checkValueExist('leave_request', 'id', $id)) {

    # Decline the Request
    if ($util->updateTableColumnById('leave_request', 'status', 2, $id)) {
        header('Location: line-manager');
        exit();
    }
    

} else {
    die('error');
}