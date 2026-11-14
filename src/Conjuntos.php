<?php
declare(strict_types=1);


class Conjuntos
{
    /** @var int[] */
    private array $a;

    /** @var int[] */
    private array $b;

    /**
     * @param int[] $a
     * @param int[] $b
     */
    public function __construct(array $a, array $b)
    {
        if (count($a) === 0 || count($b) === 0) {
            throw new InvalidArgumentException('Cada conjunto debe tener al menos un número.');
        }

        $this->a = self::normalizar($a);
        $this->b = self::normalizar($b);
    }

    /**
     * Crea los conjuntos a partir de texto: enteros separados por
     * espacios, comas o punto y coma.
     */
    public static function desdeTexto(string $textoA, string $textoB): self
    {
        return new self(
            self::parsear($textoA, 'A'),
            self::parsear($textoB, 'B')
        );
    }

    /** @return int[] */
    public function conjuntoA(): array
    {
        return $this->a;
    }

    /** @return int[] */
    public function conjuntoB(): array
    {
        return $this->b;
    }

    /** @return int[] */
    public function union(): array
    {
        return self::normalizar(array_merge($this->a, $this->b));
    }

    /** @return int[] */
    public function interseccion(): array
    {
        return self::normalizar(array_intersect($this->a, $this->b));
    }

    /** A − B: elementos de A que no están en B. @return int[] */
    public function diferenciaAB(): array
    {
        return self::normalizar(array_diff($this->a, $this->b));
    }

    /** B − A: elementos de B que no están en A. @return int[] */
    public function diferenciaBA(): array
    {
        return self::normalizar(array_diff($this->b, $this->a));
    }

    /**
     * Texto con notación de conjunto: {1, 2, 3}  (o ∅ si está vacío).
     *
     * @param int[] $conjunto
     */
    public static function formatear(array $conjunto): string
    {
        return count($conjunto) === 0 ? '∅' : '{' . implode(', ', $conjunto) . '}';
    }

    /**
     * @return int[]
     */
    private static function parsear(string $texto, string $nombre): array
    {
        $trozos = preg_split('/[\s,;]+/', trim($texto), -1, PREG_SPLIT_NO_EMPTY);
        $numeros = [];

        foreach ($trozos === false ? [] : $trozos as $trozo) {
            if (!preg_match('/^[+-]?\d{1,18}$/', $trozo)) {
                throw new InvalidArgumentException("En el conjunto {$nombre}, «{$trozo}» no es un número entero.");
            }

            $numeros[] = (int) $trozo;
        }

        return $numeros;
    }

    /**
     * Quita repetidos y ordena de menor a mayor.
     *
     * @param int[] $valores
     * @return int[]
     */
    private static function normalizar(array $valores): array
    {
        $unicos = array_values(array_unique($valores));
        sort($unicos);

        return $unicos;
    }
}
