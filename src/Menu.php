<?php
declare(strict_types=1);


class Menu
{
    /**
     * @return array<int, array{titulo: string, corto: string, archivo: string, descripcion: string}>
     */
    public static function opciones(): array
    {
        return [
            [
                'titulo'      => 'Frase a acrónimo',
                'corto'       => 'Acrónimo',
                'archivo'     => 'acronimo.php',
                'descripcion' => 'Convierte un nombre largo en su sigla: Portable Network Graphics → PNG.',
            ],
            [
                'titulo'      => 'Fibonacci y factorial',
                'corto'       => 'Fibonacci / Factorial',
                'archivo'     => 'fibonacci_factorial.php',
                'descripcion' => 'Genera la sucesión de Fibonacci o la serie de factoriales de un número.',
            ],
            [
                'titulo'      => 'Promedio, mediana y moda',
                'corto'       => 'Estadística',
                'archivo'     => 'estadistica.php',
                'descripcion' => 'Calcula estadísticas básicas de una serie de números reales.',
            ],
            [
                'titulo'      => 'Operaciones con conjuntos',
                'corto'       => 'Conjuntos',
                'archivo'     => 'conjuntos.php',
                'descripcion' => 'Unión, intersección y diferencias (A−B y B−A) de dos conjuntos de enteros.',
            ],
            [
                'titulo'      => 'Entero a binario',
                'corto'       => 'Binario',
                'archivo'     => 'binario.php',
                'descripcion' => 'Convierte un número entero a binario y muestra las divisiones sucesivas.',
            ],
            [
                'titulo'      => 'Árbol binario por recorridos',
                'corto'       => 'Árbol binario',
                'archivo'     => 'arbol_binario.php',
                'descripcion' => 'Reconstruye y dibuja un árbol a partir de dos recorridos (preorden, inorden, postorden).',
            ],
            [
                'titulo'      => 'Calculadora con historial',
                'corto'       => 'Calculadora',
                'archivo'     => 'calculadora.php',
                'descripcion' => 'Suma, resta, multiplicación, división y porcentaje, con historial que se puede borrar.',
            ],
        ];
    }
}
