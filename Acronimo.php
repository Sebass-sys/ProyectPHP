<?php
declare(strict_types=1);

/**
 * Convierte una frase en su acrónimo.
 *
 * Reglas del enunciado:
 *  - los guiones separan palabras (igual que los espacios);
 *  - cualquier otro signo de puntuación se elimina.
 */
class Acronimo
{
    private string $frase;

    public function __construct(string $frase)
    {
        $this->frase = trim($frase);

        if ($this->frase === '') {
            throw new InvalidArgumentException('Escribe una frase para convertir.');
        }
    }

    /**
     * Palabras de la frase una vez aplicadas las reglas de limpieza.
     *
     * @return string[]
     */
    public function palabras(): array
    {
        // Los guiones (normal, medio y largo) se vuelven espacios.
        $texto = str_replace(['-', '–', '—'], ' ', $this->frase);

        // Se conservan solo letras (con sus tildes), números y espacios.
        $texto = preg_replace('/[^\p{L}\p{M}\p{N}\s]/u', '', $texto) ?? '';

        $palabras = preg_split('/\s+/u', trim($texto), -1, PREG_SPLIT_NO_EMPTY);

        return $palabras === false ? [] : $palabras;
    }

    public function generar(): string
    {
        $acronimo = '';

        foreach ($this->palabras() as $palabra) {
            $acronimo .= mb_strtoupper(mb_substr($palabra, 0, 1, 'UTF-8'), 'UTF-8');
        }

        if ($acronimo === '') {
            throw new InvalidArgumentException('La frase no contiene palabras válidas.');
        }

        return $acronimo;
    }
}
