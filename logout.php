<?php

session_start();
session_unset();
session_destroy();

header("Location: DOLPHIN_LOGIN.php");
exit;