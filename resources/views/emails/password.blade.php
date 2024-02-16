<!DOCTYPE html>
<html>
<head>
    <title>Mathotsanayan Password Reset</title>
</head>
<body>
    <p>Your password has been reset.</p>
    <p>Your username is: {{ $email }}</p>
    <p>Your temporary password is: {{ $password }}</p>
    @php
        switch ($type) {
            case 1:
                echo '<p>Please log in at <a href="http://admins.mathotsanayan.com">admins.mathotsanayan.com</a> and change your password.</p>';
                break;
            case 2:
                echo '<p>Please log in at <a href="http://teachers.mathotsanayan.com">teachers.mathotsanayan.com</a> and change your password.</p>';
                break;  
            case 3:
                echo '<p>Please log in at <a href="http://mathotsanayan.com">mathotsanayan.com</a> change your password.</p>';
                break;  
        }
    @endphp
</body>
</html>
