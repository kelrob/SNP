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

# Important Variables
$user->username = $util->getTableFieldById('employees', 'username', $user->id);


?>
<!DOCTYPE html>
<html lang="en">
<head lang="en">
    <title>Snapnet</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!--Google Fonts-->
    <link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Fonts -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- App CSS -->
    <link rel="stylesheet" href="assets/css/main.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>

<!--=========== Main Container Begins ===========-->
<div class="container-fluid">

    <div class="row">
        <!-- Login Form -->
        <div class="col-md-offset-2 col-md-8" style="color: #fff;" id="login_form">
            <h4 class="text-center">Welcome, <?php echo $user->username; ?></h4>
            <p class="text-center">List of Pending Leave Request</p>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Employee Name</th>
                            <th>Days Requested</th>
                            <th>Time Requested</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $query = $leaveRequest->mgrPendingLeaveRequest($user->id);
                        $count = 0;
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            $count++;
                            $id = $row['id'];
                            $status = $row['status'];
                            $employeeId = $row['employee_id'];
                            $daysRequested = $row['days'];
                            $timeRequested = $row['time_created'];
                            $employeeName = $util->getTableFieldById('employees', 'username', $employeeId);
                            $daysLeft = $util->getTableFieldById('employees', 'leave_days', $employeeId);
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $employeeName; ?></td>
                                <td><?php echo $daysRequested; ?></td>
                                <td><?php echo $util->readableDate($timeRequested); ?></td>
                                <td>
                                    <?php
                                        if ($status == 0) {
                                        ?>
                                            <a href="approve-request?id=<?php echo $id; ?>&days=<?php echo $daysRequested; ?>&user_id=<?php echo $employeeId?>" class="btn btn-primary">APPROVE</a>
                                            <a href="dissaprove-request?id=<?php echo $id; ?>" class="btn btn-danger">DECLINE</a>
                                        <?php
                                        } else if ($status == 1) {
                                            echo '<p>Approved</p>';
                                        } else if ($status == 2) {
                                            echo '<p style="background-color: #EE2123; color: #fff; padding: 1%;">Declined</p>';
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>

            <p class="text-center"><a href="logout" class="btn btn-primary">Logout</a></p>
        </div>
        <!-- End Login Form -->
    </div>
</div>
<!--=========== Main Container Ends ===========-->
</body>
</html>