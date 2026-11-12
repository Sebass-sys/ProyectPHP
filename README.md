# Taller PHP · POO

Siete aplicaciones web hechas con **HTML, CSS, PHP y programación orientada a objetos**, enlazadas desde una página principal con menú.

| # | Aplicación | Archivo | Clase(s) |
|---|------------|---------|----------|
| 1 | Frase a acrónimo | `acronimo.php` | `Acronimo` |
| 2 | Fibonacci y factorial | `fibonacci_factorial.php` | `Secuencias` |
| 3 | Promedio, mediana y moda | `estadistica.php` | `Estadistica` |
| 4 | Operaciones con conjuntos | `conjuntos.php` | `Conjuntos` |
| 5 | Entero a binario | `binario.php` | `ConversorBinario` |
| 6 | Árbol binario por recorridos | `arbol_binario.php` | `ArbolBinario`, `NodoArbol`, `ArbolSvg` |
| 7 | Calculadora con historial | `calculadora.php` | `Calculadora`, `HistorialCalculadora` |

## Estructura

```
taller_php/
├── index.php                 # página principal con el menú
├── acronimo.php ... calculadora.php
├── assets/style.css          # estilos compartidos
├── includes/                 # bootstrap (autoload), header y footer comunes
└── src/                      # clases (una por archivo, cargadas con autoload)
```

## Cómo ejecutarlo

**Con XAMPP:** copia la carpeta `taller_php` dentro de `htdocs`, inicia Apache y abre
`http://localhost/taller_php/`.

**Con el servidor integrado de PHP (8.0 o superior):**

```bash
cd taller_php
php -S localhost:8000
```

y abre `http://localhost:8000/`.

## Notas de cada aplicación

- **Acrónimo:** los guiones separan palabras; el resto de signos de puntuación se elimina.
- **Fibonacci / factorial:** usa enteros de 64 bits, por eso Fibonacci llega hasta 90 términos y el factorial hasta 20!.
- **Estadística:** «promedio» y «media» son el mismo valor, así que se muestra el promedio y además la mediana. Si ningún valor se repite, la serie no tiene moda.
- **Conjuntos:** los elementos repetidos se ignoran y los resultados salen ordenados; `∅` es el conjunto vacío.
- **Binario:** muestra las divisiones sucesivas entre 2; admite negativos con signo menos.
- **Árbol binario:** acepta preorden + inorden, postorden + inorden o preorden + postorden. Con los tres recorridos usa preorden + inorden. Con preorden + postorden puede haber más de un árbol posible (nodos con un solo hijo); en ese caso se avisa y se dibuja el hijo a la izquierda.
- **Calculadora:** el porcentaje es «A % de B». El historial se guarda en la sesión (máximo 50 operaciones) y se puede borrar.

## Subir a GitHub

```bash
git init
git add .
git commit -m "Taller PHP con POO"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/NOMBRE_DEL_REPOSITORIO.git
git push -u origin main
```
