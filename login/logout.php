<?php

require "koneksi.php";

// SESSION LOGOUT
$_SESSION = [];
session_unset();
session_destroy();
session_write_close();
session_regenerate_id(true);

header('location: login.php');
exit;