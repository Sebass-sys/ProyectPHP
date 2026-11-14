<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$historial = new HistorialCalculadora(); // también inicia la sesión
$calculadora = new Calculadora();

// Patrón Post/Redirect/Get: al recargar la página no se repite la operación.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = (string) ($_POST['accion'] ?? 'calcular');
    $textoA = (string) ($_POST['a'] ?? '');
    $textoB = (string) ($_POST['b'] ?? '');
    $mensaje = null;

    if ($accion === 'borrar') {
        $historial->limpiar();
        $mensaje = ['tipo' => 'ok', 'texto' => 'Historial borrado.', 'a' => $textoA, 'b' => $textoB];
    } else {
        try {
            $a = Calculadora::parsearNumero($textoA);
            $b = Calculadora::parsearNumero($textoB);
            $operacion = (string) ($_POST['operacion'] ?? '');

            $resultado = Formato::numero($calculadora->calcular($a, $operacion, $b));
            $expresion = $calculadora->expresion($a, $operacion, $b);

            $historial->agregar($expresion, $resultado);
            $mensaje = ['tipo' => 'ok', 'texto' => "{$expresion} = {$resultado}", 'a' => $textoA, 'b' => $textoB];
        } catch (InvalidArgumentException $excepcion) {
            $mensaje = ['tipo' => 'error', 'texto' => $excepcion->getMessage(), 'a' => $textoA, 'b' => $textoB];
        }
    }

    $_SESSION['calculadora_mensaje'] = $mensaje;
    header('Location: calculadora.php');
    exit;
}

$mensaje = $_SESSION['calculadora_mensaje'] ?? null;
unset($_SESSION['calculadora_mensaje']);

$valorA = $mensaje['a'] ?? '';
$valorB = $mensaje['b'] ?? '';

$titulo = 'Calculadora';
require __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <h1>Calculadora</h1>
    <p class="ayuda">
        Escribe dos números (enteros o decimales) y pulsa la operación que quieras.
        El botón <strong>%</strong> calcula «A % de B»: 15 y 200 dan 30.
    </p>

    <form method="post" class="formulario">
        <div class="fila">
            <div class="campo">
                <label for="a">Número A</label>
                <input type="text" id="a" name="a" inputmode="decimal" value="<?= e($valorA) ?>" placeholder="12.5"
                    required autofocus>
            </div>
            <div class="campo">
                <label for="b">Número B</label>
                <input type="text" id="b" name="b" inputmode="decimal" value="<?= e($valorB) ?>" placeholder="4"
                    required>
            </div>
        </div>

        <div class="calc-botones">
            <?php foreach (Calculadora::OPERACIONES as $clave => $datos): ?>
                <button type="submit" class="calc-btn" name="operacion" value="<?= e($clave) ?>"
                    title="<?= e($datos['nombre']) ?>" aria-label="<?= e($datos['nombre']) ?>">
                    <?= e($clave === 'porcentaje' ? '%' : $datos['simbolo']) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </form>

    <?php if ($mensaje !== null): ?>
        <?php if ($mensaje['tipo'] === 'ok' && str_contains($mensaje['texto'], '=')): ?>
            <div class="resultado">
                <span class="etiqueta">Resultado</span>
                <strong class="grande"><?= e($mensaje['texto']) ?></strong>
            </div>
        <?php else: ?>
            <div class="alerta <?= e($mensaje['tipo']) ?>" role="alert"><?= e($mensaje['texto']) ?></div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<section class="panel">
    <div class="encabezado-historial">
        <h2>Historial</h2>
        <form method="post">
            <input type="hidden" name="accion" value="borrar">
            <button type="submit" class="btn btn-peligro" <?= $historial->estaVacio() ? ' disabled' : '' ?>>Borrar
                historial</button>
        </form>
    </div>

    <?php if ($historial->estaVacio()): ?>
        <p class="ayuda" style="margin-top:.75rem;">Todavía no hay operaciones.</p>
    <?php else: ?>
        <ul class="historial">
            <?php foreach ($historial->todos() as $registro): ?>
                <li>
                    <span><?= e($registro['expresion']) ?> = <strong><?= e($registro['resultado']) ?></strong></span>
                    <span class="hora"><?= e($registro['hora']) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>