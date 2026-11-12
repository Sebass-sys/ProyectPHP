<?php
declare(strict_types=1);

/**
 * Historial de operaciones de la calculadora, guardado en la sesión del usuario.
 */
class HistorialCalculadora
{
    private const CLAVE = 'calculadora_historial';
    private const MAXIMO = 50;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION[self::CLAVE]) || !is_array($_SESSION[self::CLAVE])) {
            $_SESSION[self::CLAVE] = [];
        }
    }

    public function agregar(string $expresion, string $resultado): void
    {
        // La operación más reciente queda de primera.
        array_unshift($_SESSION[self::CLAVE], [
            'expresion' => $expresion,
            'resultado' => $resultado,
            'hora'      => date('H:i:s'),
        ]);

        $_SESSION[self::CLAVE] = array_slice($_SESSION[self::CLAVE], 0, self::MAXIMO);
    }

    /**
     * @return array<int, array{expresion: string, resultado: string, hora: string}>
     */
    public function todos(): array
    {
        return $_SESSION[self::CLAVE];
    }

    public function estaVacio(): bool
    {
        return count($_SESSION[self::CLAVE]) === 0;
    }

    public function limpiar(): void
    {
        $_SESSION[self::CLAVE] = [];
    }
}
