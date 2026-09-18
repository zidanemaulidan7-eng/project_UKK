<?php
session_start();
session_unset();
session_destroy();
header('Location: /project_UKK/MediSell/auth/login.php');
exit;
