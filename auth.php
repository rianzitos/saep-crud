<?php
// =====================================================================
// PROTEÇÃO DE LOGIN — todas as páginas internas fazem  require 'auth.php';
// Se a pessoa NÃO estiver logada, é mandada para o login.php.
// (Deve ser a PRIMEIRA linha de PHP da página, antes de qualquer HTML.)
// =====================================================================
session_start();

if (empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
