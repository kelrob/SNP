<?php

ob_start();
session_start();

# Important Files
require_once 'classes/Database.php';
require_once 'classes/Users.php';
require_once 'utils/Utils.php';

# Database Object Declaration
$db = new Database();
$conn = $db->getConnection();

# Util Object Declaration
$util = new Utils($conn);

# User Object Declaration
$user = new Users($conn);

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


    <!-- App CSS -->
    <link rel="stylesheet" href="assets/css/main.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?php include_once 'includes/login_verification.php'; ?>


</head>
<body>

    <!--=========== Main Container Begins ===========-->
    <div class="container-fluid">

        <div class="row">
            <!-- Login Form -->
            <div class="col-md-offset-4 col-md-4" style="color: #fff;" id="login_form">
                <h4 class="text-center">Login</h4>
                <form method="post" action="" style="border-top: 1px solid #fff;" autocomplete="off">

                    <!-- Error Message on Login -->
                    <?php
                        if (isset($errorMessage)) {
                            ?>
                            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                            <?php
                        }
                    ?>


                    <div class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="LOGIN" name="login">
                    </div>
                </form>
            </div>
            <!-- End Login Form -->
        </div>
    </div>
    <!--=========== Main Container Ends ===========-->
</body>
</html>