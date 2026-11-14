<?php
declare(strict_types=1);


class ArbolBinario
{
    public const MAX_NODOS = 60;

    private const MSG_INCONSISTENTE = 'Los recorridos ingresados no corresponden a un mismo árbol binario.';

    private NodoArbol $raiz;
    private string $metodo;
    private bool $ambiguo;

    private function __construct(NodoArbol $raiz, string $metodo, bool $ambiguo = false)
    {
        $this->raiz = $raiz;
        $this->metodo = $metodo;
        $this->ambiguo = $ambiguo;
    }

    /**
     * Convierte un texto como "A → B → D" o "A, B, D" o "A B D" en una lista de nodos.
     *
     * @return string[]
     */
    public static function parsearRecorrido(string $texto): array
    {
        $texto = trim($texto);

        if ($texto === '') {
            return [];
        }

        $nodos = preg_split('/\s*(?:→|->|,|;)\s*|\s+/u', $texto, -1, PREG_SPLIT_NO_EMPTY);

        return $nodos === false ? [] : array_values($nodos);
    }

    /**
     * Punto de entrada: elige la combinación según los recorridos recibidos.
     * Un recorrido vacío significa "no ingresado".
     *
     * @param string[] $preorden
     * @param string[] $inorden
     * @param string[] $postorden
     */
    public static function desdeRecorridos(array $preorden, array $inorden, array $postorden): self
    {
        $tienePre = count($preorden) > 0;
        $tieneIn = count($inorden) > 0;
        $tienePost = count($postorden) > 0;

        if (count(array_filter([$tienePre, $tieneIn, $tienePost])) < 2) {
            throw new InvalidArgumentException('Ingresa al menos dos recorridos.');
        }

        if ($tienePre && $tieneIn) {
            return self::desdePreInorden($preorden, $inorden);
        }

        if ($tieneIn && $tienePost) {
            return self::desdePostInorden($postorden, $inorden);
        }

        return self::desdePrePost($preorden, $postorden);
    }

    // ------------------------------------------------------------------
    // Construcción
    // ------------------------------------------------------------------

    /**
     * @param string[] $pre
     * @param string[] $in
     */
    private static function desdePreInorden(array $pre, array $in): self
    {
        self::validarPar($pre, $in, 'preorden', 'inorden');

        $posicion = array_flip($in);
        $indice = 0;

        $construir = function (int $ini, int $fin) use (&$construir, &$indice, $pre, $posicion): ?NodoArbol {
            if ($ini > $fin) {
                return null;
            }

            $valor = $pre[$indice++] ?? throw new InvalidArgumentException(self::MSG_INCONSISTENTE);
            $medio = $posicion[$valor];

            if ($medio < $ini || $medio > $fin) {
                throw new InvalidArgumentException(self::MSG_INCONSISTENTE);
            }

            $nodo = new NodoArbol($valor);
            $nodo->izquierdo = $construir($ini, $medio - 1);
            $nodo->derecho = $construir($medio + 1, $fin);

            return $nodo;
        };

        $raiz = $construir(0, count($in) - 1) ?? throw new InvalidArgumentException(self::MSG_INCONSISTENTE);
        $arbol = new self($raiz, 'preorden e inorden');
        $arbol->comprobar($pre, $in, null);

        return $arbol;
    }

    /**
     * @param string[] $post
     * @param string[] $in
     */
    private static function desdePostInorden(array $post, array $in): self
    {
        self::validarPar($post, $in, 'postorden', 'inorden');

        $posicion = array_flip($in);
        $indice = count($post) - 1;

        $construir = function (int $ini, int $fin) use (&$construir, &$indice, $post, $posicion): ?NodoArbol {
            if ($ini > $fin) {
                return null;
            }

            $valor = $post[$indice--] ?? throw new InvalidArgumentException(self::MSG_INCONSISTENTE);
            $medio = $posicion[$valor];

            if ($medio < $ini || $medio > $fin) {
                throw new InvalidArgumentException(self::MSG_INCONSISTENTE);
            }

            $nodo = new NodoArbol($valor);
            // En postorden la raíz está al final: primero se arma el lado derecho.
            $nodo->derecho = $construir($medio + 1, $fin);
            $nodo->izquierdo = $construir($ini, $medio - 1);

            return $nodo;
        };

        $raiz = $construir(0, count($in) - 1) ?? throw new InvalidArgumentException(self::MSG_INCONSISTENTE);
        $arbol = new self($raiz, 'postorden e inorden');
        $arbol->comprobar(null, $in, $post);

        return $arbol;
    }

    /**
     * @param string[] $pre
     * @param string[] $post
     */
    private static function desdePrePost(array $pre, array $post): self
    {
        self::validarPar($pre, $post, 'preorden', 'postorden');

        $posicionPost = array_flip($post);
        $ambiguo = false;

        // $n = cantidad de nodos del subárbol que empieza en $preIni / $postIni.
        $construir = function (int $preIni, int $postIni, int $n) use (&$construir, &$ambiguo, $pre, $posicionPost): ?NodoArbol {
            if ($n === 0) {
                return null;
            }

            $nodo = new NodoArbol($pre[$preIni]);

            if ($n === 1) {
                return $nodo;
            }

            // El siguiente nodo en preorden es la raíz del subárbol izquierdo.
            $raizIzquierda = $pre[$preIni + 1];
            $tamIzquierdo = $posicionPost[$raizIzquierda] - $postIni + 1;

            if ($tamIzquierdo < 1 || $tamIzquierdo > $n - 1) {
                throw new InvalidArgumentException(self::MSG_INCONSISTENTE);
            }

            if ($tamIzquierdo === $n - 1) {
                $ambiguo = true; // un solo hijo: podría ser izquierdo o derecho
            }

            $nodo->izquierdo = $construir($preIni + 1, $postIni, $tamIzquierdo);
            $nodo->derecho = $construir($preIni + 1 + $tamIzquierdo, $postIni + $tamIzquierdo, $n - 1 - $tamIzquierdo);

            return $nodo;
        };

        $raiz = $construir(0, 0, count($pre)) ?? throw new InvalidArgumentException(self::MSG_INCONSISTENTE);

        if ($raiz->valor !== $post[count($post) - 1]) {
            throw new InvalidArgumentException(self::MSG_INCONSISTENTE);
        }

        $arbol = new self($raiz, 'preorden y postorden', $ambiguo);
        $arbol->comprobar($pre, null, $post);

        return $arbol;
    }

    /**
     * Verifica que el árbol construido produzca exactamente los recorridos dados.
     *
     * @param string[]|null $pre
     * @param string[]|null $in
     * @param string[]|null $post
     */
    private function comprobar(?array $pre, ?array $in, ?array $post): void
    {
        if (
            ($pre !== null && $this->preorden() !== $pre)
            || ($in !== null && $this->inorden() !== $in)
            || ($post !== null && $this->postorden() !== $post)
        ) {
            throw new InvalidArgumentException(self::MSG_INCONSISTENTE);
        }
    }

    /**
     * @param string[] $a
     * @param string[] $b
     */
    private static function validarPar(array $a, array $b, string $nombreA, string $nombreB): void
    {
        if (count($a) !== count($b)) {
            throw new InvalidArgumentException("El {$nombreA} y el {$nombreB} deben tener la misma cantidad de nodos.");
        }

        if (count($a) > self::MAX_NODOS) {
            throw new InvalidArgumentException('El árbol puede tener máximo ' . self::MAX_NODOS . ' nodos.');
        }

        if (count(array_unique($a)) !== count($a) || count(array_unique($b)) !== count($b)) {
            throw new InvalidArgumentException('Los nodos no se pueden repetir dentro de un recorrido.');
        }

        if (count(array_diff($a, $b)) > 0 || count(array_diff($b, $a)) > 0) {
            throw new InvalidArgumentException("El {$nombreA} y el {$nombreB} deben tener los mismos nodos.");
        }
    }

    // ------------------------------------------------------------------
    // Consulta
    // ------------------------------------------------------------------

    public function raiz(): NodoArbol
    {
        return $this->raiz;
    }

    /** Recorridos usados para reconstruir el árbol, en texto. */
    public function metodo(): string
    {
        return $this->metodo;
    }

    /** True si con preorden + postorden había más de un árbol posible. */
    public function esAmbiguo(): bool
    {
        return $this->ambiguo;
    }

    /** @return string[] */
    public function preorden(): array
    {
        $salida = [];
        self::recorrerPre($this->raiz, $salida);

        return $salida;
    }

    /** @return string[] */
    public function inorden(): array
    {
        $salida = [];
        self::recorrerIn($this->raiz, $salida);

        return $salida;
    }

    /** @return string[] */
    public function postorden(): array
    {
        $salida = [];
        self::recorrerPost($this->raiz, $salida);

        return $salida;
    }

    public function altura(): int
    {
        return self::alturaDe($this->raiz);
    }

    public function cantidadNodos(): int
    {
        return count($this->preorden());
    }

    /** @param string[] $salida */
    private static function recorrerPre(?NodoArbol $nodo, array &$salida): void
    {
        if ($nodo === null) {
            return;
        }

        $salida[] = $nodo->valor;
        self::recorrerPre($nodo->izquierdo, $salida);
        self::recorrerPre($nodo->derecho, $salida);
    }

    /** @param string[] $salida */
    private static function recorrerIn(?NodoArbol $nodo, array &$salida): void
    {
        if ($nodo === null) {
            return;
        }

        self::recorrerIn($nodo->izquierdo, $salida);
        $salida[] = $nodo->valor;
        self::recorrerIn($nodo->derecho, $salida);
    }

    /** @param string[] $salida */
    private static function recorrerPost(?NodoArbol $nodo, array &$salida): void
    {
        if ($nodo === null) {
            return;
        }

        self::recorrerPost($nodo->izquierdo, $salida);
        self::recorrerPost($nodo->derecho, $salida);
        $salida[] = $nodo->valor;
    }

    private static function alturaDe(?NodoArbol $nodo): int
    {
        if ($nodo === null) {
            return 0;
        }

        return 1 + max(self::alturaDe($nodo->izquierdo), self::alturaDe($nodo->derecho));
    }
}
