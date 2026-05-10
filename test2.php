<?php
session_start();
$_SESSION['role'] = 'customer';
$_SESSION['full_name'] = 'Test User';
$_SESSION['email'] = 'test@test.com';
?>
<!DOCTYPE html>
<html>
<body>
Test
</body>
</html>
