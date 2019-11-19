<?php

# Once the Login Button has been clicked
if (isset($_POST['login'])) {

    # Entered Email and Password
    $enteredUsername = $util->sanitize($_POST['username']);
    $enteredPassword = $util->sanitize($_POST['password']);

    # Check if username or password is empty
    if (empty($enteredPassword) || empty($enteredUsername)) {
        $errorMessage = 'All Fields are Required';
    } else {

        # Check if username Exist
        if ($util->checkValueExist('employees', 'username', $enteredUsername)) {

            $user->password = $enteredPassword;
            $user->username = $enteredUsername;

            # Login the User
            if ($user->login()) {

                # Get Password from the database
                $dbPassword = $util->getTableFieldById('employees', 'password', $user->id);
                $user->password = $dbPassword;

                if (password_verify($enteredPassword, $user->password)) {
                    $_SESSION['i_snap_user_id'] = $user->id;

                    # Get the user role
                    $user->role = $util->getTableFieldById('employees', 'role', $user->id);

                    # Redirect to page base on user role
                    if ($user->role == 'user') {
                        header('Location: employee');
                        exit();
                    } else if ($user->role == 'line_manager') {
                        header('Location: line-manager');
                        exit();
                    }

                } else {
                    $errorMessage = 'Invalid Login Credentials';
                }
            }

        } else {
            $errorMessage = 'Invalid Username';
        }
    }

}