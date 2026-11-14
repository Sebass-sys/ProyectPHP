<?php
/**
 * Encabezado común de todas las páginas.
 * Variable esperada: $titulo (string) definida antes de incluir este archivo.
 */
$titulo = $titulo ?? 'Taller PHP';
$paginaActual = basename($_SERVER['SCRIPT_NAME'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?> | Taller PHP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <a class="marca" href="index.php">Taller PHP</a>
    <nav class="nav-links" aria-label="Aplicaciones">
        <?php foreach (Menu::opciones() as $opcion): ?>
            <a href="<?= e($opcion['archivo']) ?>"<?= $paginaActual === $opcion['archivo'] ? ' class="activo" aria-current="page"' : '' ?>><?= e($opcion['corto']) ?></a>
        <?php endforeach; ?>
    </nav>
</header>
<main class="contenedor">
