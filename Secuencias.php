<?php
declare(strict_types=1);

/**
 * Sucesión de Fibonacci y factorial.
 *
 * Se trabaja con enteros nativos de PHP (64 bits), por eso cada operación
 * tiene un tope: pasado ese valor el resultado ya no cabe en un entero.
 */
class Secuencias
{
    public const MAX_FIBONACCI = 90; // F(90) cabe en 64 bits
    public const MAX_FACTORIAL = 20; // 20! cabe en 64 bits; 21! ya no

    /**
     * Primeros $n términos de Fibonacci: 0, 1, 1, 2, 3, 5...
     *
     * @return int[]
     */
    public function fibonacci(int $n): array
    {
        if ($n < 1 || $n > self::MAX_FIBONACCI) {
            throw new InvalidArgumentException(
                'Para Fibonacci ingresa un entero entre 1 y ' . self::MAX_FIBONACCI . '.'
            );
        }

        $serie = [];
        $actual = 0;
        $siguiente = 1;

        for ($i = 0; $i < $n; $i++) {
            $serie[] = $actual;
            [$actual, $siguiente] = [$siguiente, $actual + $siguiente];
        }

        return $serie;
    }

    public function factorial(int $n): int
    {
        $this->validarFactorial($n);

        $resultado = 1;
        for ($i = 2; $i <= $n; $i++) {
            $resultado *= $i;
        }

        return $resultado;
    }

    /**
     * Serie de factoriales desde 0! hasta n!  (clave = k, valor = k!).
     *
     * @return array<int, int>
     */
    public function serieFactorial(int $n): array
    {
        $this->validarFactorial($n);

        $serie = [0 => 1];
        $acumulado = 1;

        for ($k = 1; $k <= $n; $k++) {
            $acumulado *= $k;
            $serie[$k] = $acumulado;
        }

        return $serie;
    }

    /**
     * Desarrollo escrito, por ejemplo: 5! = 5 × 4 × 3 × 2 × 1 = 120
     */
    public function desarrolloFactorial(int $n): string
    {
        $valor = $this->factorial($n);

        if ($n <= 1) {
            return "{$n}! = 1";
        }

        $factores = implode(' × ', range($n, 1));

        return "{$n}! = {$factores} = {$valor}";
    }

    private function validarFactorial(int $n): void
    {
        if ($n < 0 || $n > self::MAX_FACTORIAL) {
            throw new InvalidArgumentException(
                'Para el factorial ingresa un entero entre 0 y ' . self::MAX_FACTORIAL . '.'
            );
        }
    }
}
