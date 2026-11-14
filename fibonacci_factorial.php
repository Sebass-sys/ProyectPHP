<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$numero = '';
$operacion = 'fibonacci';
$resultado = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = trim((string) ($_POST['numero'] ?? ''));
    $operacion = (string) ($_POST['operacion'] ?? '');

    try {
        $n = filter_var($numero, FILTER_VALIDATE_INT);

        if ($n === false) {
            throw new InvalidArgumentException('Ingresa un número entero válido.');
        }

        $secuencias = new Secuencias();

        if ($operacion === 'fibonacci') {
            $resultado = [
                'tipo' => 'fibonacci',
                'serie' => $secuencias->fibonacci($n),
            ];
        } elseif ($operacion === 'factorial') {
            $resultado = [
                'tipo' => 'factorial',
                'serie' => $secuencias->serieFactorial($n),
                'desarrollo' => $secuencias->desarrolloFactorial($n),
            ];
        } else {
            throw new InvalidArgumentException('Selecciona una operación válida.');
        }
    } catch (InvalidArgumentException $excepcion) {
        $error = $excepcion->getMessage();
    }
}

$titulo = 'Fibonacci y factorial';
require __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <h1>Fibonacci y factorial</h1>
    <p class="ayuda">
        <strong>Fibonacci</strong> genera los primeros <em>n</em> términos de la sucesión
        (máximo <?= Secuencias::MAX_FIBONACCI ?>).
        <strong>Factorial</strong> muestra el desarrollo de <em>n</em>! y la serie de 0! hasta <em>n</em>!
        (máximo <?= Secuencias::MAX_FACTORIAL ?>).
    </p>

    <form method="post" class="formulario">
        <div class="fila">
            <div class="campo">
                <label for="numero">Número (n)</label>
                <input type="text" id="numero" name="numero" inputmode="numeric" value="<?= e($numero) ?>"
                    placeholder="10" required autofocus>
            </div>
            <div class="campo">
                <label for="operacion">Operación</label>
                <select id="operacion" name="operacion">
                    <option value="fibonacci" <?= $operacion === 'fibonacci' ? ' selected' : '' ?>>Sucesión de Fibonacci
                    </option>
                    <option value="factorial" <?= $operacion === 'factorial' ? ' selected' : '' ?>>Factorial</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn">Calcular</button>
    </form>

    <?php if ($error !== null): ?>
        <div class="alerta error" role="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($resultado !== null && $resultado['tipo'] === 'fibonacci'): ?>
        <div class="resultado">
            <span class="etiqueta">Sucesión de Fibonacci · <?= count($resultado['serie']) ?> términos</span>
            <div class="fichas">
                <?php foreach ($resultado['serie'] as $valor): ?>
                    <span class="ficha"><?= e($valor) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($resultado !== null && $resultado['tipo'] === 'factorial'): ?>
        <div class="resultado">
            <span class="etiqueta">Factorial</span>
            <strong class="mono"
                style="font-size:1.25rem; word-break:break-word;"><?= e($resultado['desarrollo']) ?></strong>
            <span class="etiqueta" style="margin-top:1rem;">Serie de factoriales</span>
            <div class="fichas">
                <?php foreach ($resultado['serie'] as $k => $valor): ?>
                    <span class="ficha"><?= e($k) ?>! = <?= e($valor) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>