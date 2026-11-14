<?php
declare(strict_types=1);

/**
 * Utilidades de presentación de números.
 */
class Formato
{
    /**
     * Imprime un número sin ceros sobrantes ni ruido de coma flotante
     * (0.1 + 0.2 se muestra como 0.3).
     */
    public static function numero(float $valor, int $decimales = 10): string
    {
        $magnitud = abs($valor);

        // Números enormes o diminutos: notación científica compacta.
        if ($magnitud >= 1e15 || ($magnitud > 0 && $magnitud < 1e-10)) {
            [$mantisa, $exponente] = explode('e', sprintf('%.9e', $valor));

            return rtrim(rtrim($mantisa, '0'), '.') . 'e' . $exponente;
        }

        $texto = number_format($valor, $decimales, '.', '');

        if (str_contains($texto, '.')) {
            $texto = rtrim(rtrim($texto, '0'), '.');
        }

        return ($texto === '' || $texto === '-0') ? '0' : $texto;
    }
}
