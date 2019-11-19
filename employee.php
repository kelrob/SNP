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

include_once  'includes/authentication.php';

# Important Variables
$user->username = $util->getTableFieldById('employees', 'username', $user->id);
$user->leaveDays = $util->getTableFieldById('employees', 'leave_days', $user->id);


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

    <!-- Custom Javascript
   ================================================ -->
    <script>
        function openRequestModal() {
            document.getElementById('request-modal').style.display = 'block';
            document.getElementById('request-fade').style.display = 'block';
        }

        function closeRequestModal() {
            document.getElementById('request-modal').style.display = 'none';
            document.getElementById('request-fade').style.display = 'none';
        }

        function processRequest(employeeId) {
            document.getElementById('request-response').innerHTML = '';
            openRequestModal();

            var days = document.getElementById("requestLeaveForm").elements.namedItem("days").value;

            xmlhttp = false;
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    closeRequestModal();
                    document.getElementById("request-response").innerHTML=this.responseText;
                }
            }

            xmlhttp.open("POST","process_leave.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("user_id=" + employeeId + "&days=" + days);

        }
    </script>
</head>
<body>

<!--=========== Main Container Begins ===========-->
<div class="container-fluid">

    <div class="row">
        <!-- Login Form -->
        <div class="col-md-offset-4 col-md-4" style="color: #fff;" id="login_form">
            <h4 class="text-center">Welcome, <?php echo $user->username; ?></h4>
            <p class="text-center" id="down_line">Leave Days Left: <b><?php echo $user->leaveDays; ?></b></p>

            <h4 class="text-center">What will you like to do today?</h4>

            <!-- Action Buttons -->
            <div id="action_buttons" align="center">
                <form id="requestLeaveForm">
                    <div id="request-fade"></div>
                    <div id="request-modal">
                        <div class="alert alert-info">
                            <p>Please wait, Request in process <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i></p>
                        </div>
                    </div>
                    <div id="request-response"><!-- Results are displayed here --></div>

                    <div id="form">
                        <?php
                            # Check to see if there is a pending request
                            if ($leaveRequest->pendingLeaveRequest($user->id) == 0) {
                                ?>
                                    <div class="form-group">
                                        <label for="days">No of days</label>
                                        <input type="number" class="form-control" name="days" id="days">
                                    </div>

                                    <a onclick="processRequest(<?php echo $user->id; ?>)" class="btn btn-primary">Request Leave</a>
                                <?php
                            } else {
                                ?>
                                <div class="alert alert-info">You have a pending request being attended to</div>
                                <?php
                            }
                        ?>
                    </div>

                </form>
            </div>
            <p class="text-center"><a href="logout" class="btn btn-primary">Logout</a></p>
        </div>
        <!-- End Login Form -->
    </div>
</div>
<!--=========== Main Container Ends ===========-->
</body>
</html>