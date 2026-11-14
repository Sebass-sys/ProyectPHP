<?php
declare(strict_types=1);


class Estadistica
{
    /** @var float[] */
    private array $datos;

    /**
     * @param float[] $datos
     */
    public function __construct(array $datos)
    {
        if (count($datos) === 0) {
            throw new InvalidArgumentException('Ingresa al menos un número.');
        }

        $this->datos = array_map('floatval', array_values($datos));
    }

    /**
     * Crea el objeto a partir de un texto con números separados por
     * espacios, saltos de línea, comas o punto y coma. El separador decimal es el punto.
     */
    public static function desdeTexto(string $texto): self
    {
        $trozos = preg_split('/[\s,;]+/', trim($texto), -1, PREG_SPLIT_NO_EMPTY);
        $numeros = [];

        foreach ($trozos === false ? [] : $trozos as $trozo) {
            if (!is_numeric($trozo) || !is_finite((float) $trozo)) {
                throw new InvalidArgumentException("«{$trozo}» no es un número válido.");
            }

            $numeros[] = (float) $trozo;
        }

        return new self($numeros);
    }

    public function cantidad(): int
    {
        return count($this->datos);
    }

    public function suma(): float
    {
        return array_sum($this->datos);
    }

    /** Promedio = media aritmética. */
    public function promedio(): float
    {
        return $this->suma() / $this->cantidad();
    }

    public function mediana(): float
    {
        $ordenados = $this->ordenados();
        $n = count($ordenados);
        $mitad = intdiv($n, 2);

        if ($n % 2 === 1) {
            return $ordenados[$mitad];
        }

        return ($ordenados[$mitad - 1] + $ordenados[$mitad]) / 2;
    }

    /**
     * Valores que más se repiten. Devuelve un arreglo vacío cuando
     * ningún valor se repite (la serie no tiene moda).
     *
     * @return float[]
     */
    public function moda(): array
    {
        $conteo = [];

        foreach ($this->datos as $dato) {
            $clave = (string) $dato;
            $conteo[$clave] = ($conteo[$clave] ?? 0) + 1;
        }

        $maximo = max($conteo);

        if ($maximo === 1) {
            return [];
        }

        $modas = [];
        foreach ($conteo as $clave => $veces) {
            if ($veces === $maximo) {
                $modas[] = (float) $clave;
            }
        }

        sort($modas);

        return $modas;
    }

    /**
     * @return float[]
     */
    public function ordenados(): array
    {
        $copia = $this->datos;
        sort($copia);

        return $copia;
    }
}
