<?php
declare(strict_types=1);

/**
 * Archivo de arranque: se incluye al inicio de cada página.
 * - Fija la zona horaria (se usa en el historial de la calculadora).
 * - Registra el autocargador de clases (carpeta src/).
 * - Define el helper e() para escapar texto antes de imprimirlo en HTML.
 */

date_default_timezone_set('America/Bogota');

spl_autoload_register(function (string $clase): void {
    $archivo = __DIR__ . '/../src/' . $clase . '.php';

    if (is_file($archivo)) {
        require_once $archivo;
    }
});

/**
 * Escapa un valor para imprimirlo de forma segura en HTML (evita XSS).
 */
function e(string|int|float|null $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
