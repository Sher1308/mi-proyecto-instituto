<?php
// auth.php

// Inicia la sesión y establece la zona horaria
session_start();
date_default_timezone_set('Europe/Madrid');

// Evita el cacheo de páginas sensibles
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Comprueba que el usuario esté autenticado
if (empty($_SESSION['username'])) {
    // Si no hay sesión, redirige al index público
    header("Location: /index.html");
    exit;
}
