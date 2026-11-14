<?php
declare(strict_types=1);


class NodoArbol
{
    public ?NodoArbol $izquierdo = null;
    public ?NodoArbol $derecho = null;

    public function __construct(public string $valor)
    {
    }
}
