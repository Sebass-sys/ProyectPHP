<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$textoA = '';
$textoB = '';
$conjuntos = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $textoA = (string) ($_POST['conjunto_a'] ?? '');
    $textoB = (string) ($_POST['conjunto_b'] ?? '');

    try {
        $conjuntos = Conjuntos::desdeTexto($textoA, $textoB);
    } catch (InvalidArgumentException $excepcion) {
        $error = $excepcion->getMessage();
    }
}

$titulo = 'Operaciones con conjuntos';
require __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <h1>Operaciones con conjuntos</h1>
    <p class="ayuda">
        Ingresa los elementos de cada conjunto (números enteros) separados por espacios o comas.
        Los repetidos se ignoran, porque un conjunto no tiene elementos duplicados.
    </p>

    <form method="post" class="formulario">
        <div class="fila">
            <div class="campo">
                <label for="conjunto_a">Conjunto A</label>
                <input type="text" id="conjunto_a" name="conjunto_a" value="<?= e($textoA) ?>"
                    placeholder="1, 2, 3, 4, 5" required autofocus>
            </div>
            <div class="campo">
                <label for="conjunto_b">Conjunto B</label>
                <input type="text" id="conjunto_b" name="conjunto_b" value="<?= e($textoB) ?>" placeholder="4, 5, 6, 7"
                    required>
            </div>
        </div>
        <button type="submit" class="btn">Calcular</button>
    </form>

    <?php if ($error !== null): ?>
        <div class="alerta error" role="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($conjuntos !== null): ?>
        <table class="tabla">
            <thead>
                <tr>
                    <th>Operación</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>A</td>
                    <td class="num"><?= e(Conjuntos::formatear($conjuntos->conjuntoA())) ?></td>
                </tr>
                <tr>
                    <td>B</td>
                    <td class="num"><?= e(Conjuntos::formatear($conjuntos->conjuntoB())) ?></td>
                </tr>
                <tr>
                    <td><strong>Unión</strong> (A ∪ B)</td>
                    <td class="num"><?= e(Conjuntos::formatear($conjuntos->union())) ?></td>
                </tr>
                <tr>
                    <td><strong>Intersección</strong> (A ∩ B)</td>
                    <td class="num"><?= e(Conjuntos::formatear($conjuntos->interseccion())) ?></td>
                </tr>
                <tr>
                    <td><strong>Diferencia</strong> (A − B)</td>
                    <td class="num"><?= e(Conjuntos::formatear($conjuntos->diferenciaAB())) ?></td>
                </tr>
                <tr>
                    <td><strong>Diferencia</strong> (B − A)</td>
                    <td class="num"><?= e(Conjuntos::formatear($conjuntos->diferenciaBA())) ?></td>
                </tr>
            </tbody>
        </table>
        <p class="ayuda" style="margin-top:.75rem;">El símbolo ∅ indica el conjunto vacío.</p>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>