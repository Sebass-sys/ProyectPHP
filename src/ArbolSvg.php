<?php
declare(strict_types=1);


class ArbolSvg
{
    public function dibujar(ArbolBinario $arbol): string
    {
        $letrasMaximas = 1;
        foreach ($arbol->preorden() as $valor) {
            $letrasMaximas = max($letrasMaximas, mb_strlen($valor));
        }

        $radio = max(20, 7 * $letrasMaximas + 10);
        $espacioX = 2 * $radio + 20;
        $espacioY = 2 * $radio + 34;
        $margen = $radio + 14;

        $lineas = [];
        $nodos = [];
        $contador = 0;

        $recorrer = function (?NodoArbol $nodo, int $nivel) use (
            &$recorrer, &$contador, &$lineas, &$nodos, $radio, $espacioX, $espacioY, $margen
        ): ?array {
            if ($nodo === null) {
                return null;
            }

            $izquierdo = $recorrer($nodo->izquierdo, $nivel + 1);

            $x = $margen + $contador * $espacioX;
            $y = $margen + $nivel * $espacioY;
            $contador++;

            $derecho = $recorrer($nodo->derecho, $nivel + 1);

            foreach ([$izquierdo, $derecho] as $hijo) {
                if ($hijo !== null) {
                    $lineas[] = sprintf('<line x1="%d" y1="%d" x2="%d" y2="%d"/>', $x, $y, $hijo[0], $hijo[1]);
                }
            }

            $nodos[] = sprintf(
                '<g><circle cx="%d" cy="%d" r="%d"/><text x="%d" y="%d">%s</text></g>',
                $x,
                $y,
                $radio,
                $x,
                $y,
                htmlspecialchars($nodo->valor, ENT_QUOTES, 'UTF-8')
            );

            return [$x, $y];
        };

        $recorrer($arbol->raiz(), 0);

        $ancho = 2 * $margen + ($contador - 1) * $espacioX;
        $alto = 2 * $margen + ($arbol->altura() - 1) * $espacioY;

        return sprintf(
            '<svg class="arbol-svg" xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d" role="img" aria-label="Dibujo del árbol binario">%s%s</svg>',
            $ancho,
            $alto,
            $ancho,
            $alto,
            implode('', $lineas),
            implode('', $nodos)
        );
    }
}
