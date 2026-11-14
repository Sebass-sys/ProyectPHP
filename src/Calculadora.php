<?php
declare(strict_types=1);


class Calculadora
{
    public const OPERACIONES = [
        'suma'           => ['simbolo' => '+',    'nombre' => 'Suma'],
        'resta'          => ['simbolo' => '−',    'nombre' => 'Resta'],
        'multiplicacion' => ['simbolo' => '×',    'nombre' => 'Multiplicación'],
        'division'       => ['simbolo' => '÷',    'nombre' => 'División'],
        'porcentaje'     => ['simbolo' => '% de', 'nombre' => 'Porcentaje (A % de B)'],
    ];

    /**
     * Convierte texto a número. Acepta punto o coma como separador decimal.
     */
    public static function parsearNumero(string $texto): float
    {
        $limpio = str_replace(',', '.', trim($texto));

        if ($limpio === '' || !is_numeric($limpio) || !is_finite((float) $limpio)) {
            $mostrado = $texto === '' ? 'vacío' : "«{$texto}»";
            throw new InvalidArgumentException("El valor {$mostrado} no es un número válido.");
        }

        return (float) $limpio;
    }

    public function calcular(float $a, string $operacion, float $b): float
    {
        $resultado = match ($operacion) {
            'suma'           => $a + $b,
            'resta'          => $a - $b,
            'multiplicacion' => $a * $b,
            'division'       => $b === 0.0
                ? throw new InvalidArgumentException('No se puede dividir entre cero.')
                : $a / $b,
            'porcentaje'     => $a * $b / 100,
            default          => throw new InvalidArgumentException('Operación no válida.'),
        };

        if (!is_finite($resultado)) {
            throw new InvalidArgumentException('El resultado es demasiado grande.');
        }

        return $resultado;
    }

    /**
     * Texto de la operación, por ejemplo "12 + 3" o "15 % de 200".
     */
    public function expresion(float $a, string $operacion, float $b): string
    {
        $simbolo = self::OPERACIONES[$operacion]['simbolo'] ?? '?';

        return Formato::numero($a) . ' ' . $simbolo . ' ' . Formato::numero($b);
    }
}
