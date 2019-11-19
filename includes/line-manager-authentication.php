<?php
# Important Authorization
if (isset($_SESSION['i_snap_user_id'])) {
    $user->id = $_SESSION['i_snap_user_id'];

    # Get the logged in user Role
    $user->role = $util->getTableFieldById('employees', 'role', $user->id);

    if ($user->role != 'line_manager') {
        header('Location: logout');
        exit();
    }

} else {
    header('Location: logout');
    exit();
}