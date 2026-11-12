<?php
declare(strict_types=1);

/**
 * Convierte un número entero a binario con el método de divisiones sucesivas entre 2.
 */
class ConversorBinario
{
    private int $numero;

    public function __construct(int $numero)
    {
        $this->numero = $numero;
    }

    public static function desdeTexto(string $texto): self
    {
        $texto = trim($texto);

        if (!preg_match('/^[+-]?\d{1,18}$/', $texto)) {
            throw new InvalidArgumentException('Ingresa un número entero válido (por ejemplo 25 o -7).');
        }

        return new self((int) $texto);
    }

    public function numero(): int
    {
        return $this->numero;
    }

    public function convertir(): string
    {
        if ($this->numero === 0) {
            return '0';
        }

        $binario = '';
        foreach ($this->pasos() as $paso) {
            // Los residuos se leen de abajo hacia arriba: se antepone cada uno.
            $binario = $paso['residuo'] . $binario;
        }

        return ($this->numero < 0 ? '-' : '') . $binario;
    }

    /**
     * Divisiones sucesivas entre 2 (sobre el valor absoluto del número).
     *
     * @return array<int, array{dividendo: int, cociente: int, residuo: int}>
     */
    public function pasos(): array
    {
        $pasos = [];
        $dividendo = abs($this->numero);

        while ($dividendo > 0) {
            $cociente = intdiv($dividendo, 2);
            $pasos[] = [
                'dividendo' => $dividendo,
                'cociente'  => $cociente,
                'residuo'   => $dividendo % 2,
            ];
            $dividendo = $cociente;
        }

        return $pasos;
    }
}
