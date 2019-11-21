<?php
require_once 'sql-connect.php';

session_destroy();

header('Location: /index.php');
