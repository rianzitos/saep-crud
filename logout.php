<?php
// Encerra a sessão e volta para a tela de login.
session_start();
$_SESSION = [];
session_destroy();
header('Location: login.php');
exit;
