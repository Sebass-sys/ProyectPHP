<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$texto = '';
$conversor = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texto = trim((string) ($_POST['numero'] ?? ''));

    try {
        $conversor = ConversorBinario::desdeTexto($texto);
    } catch (InvalidArgumentException $excepcion) {
        $error = $excepcion->getMessage();
    }
}

$titulo = 'Entero a binario';
require __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <h1>Entero a binario</h1>
    <p class="ayuda">Ingresa un número entero y obtén su representación en binario.</p>

    <form method="post" class="formulario">
        <div class="campo">
            <label for="numero">Número entero</label>
            <input type="text" id="numero" name="numero" inputmode="numeric" value="<?= e($texto) ?>" placeholder="25"
                required autofocus>
        </div>
        <button type="submit" class="btn">Convertir</button>
    </form>

    <?php if ($error !== null): ?>
        <div class="alerta error" role="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($conversor !== null): ?>
        <div class="resultado">
            <span class="etiqueta">
                <?= e($conversor->numero()) ?> en binario
            </span>
            <strong class="grande mono bloque-bin"><?= e($conversor->convertir()) ?></strong>
        </div>

        <?php $pasos = $conversor->pasos(); ?>
        <?php if (count($pasos) > 0): ?>
            <h2 style="margin-top:1.25rem;">Divisiones sucesivas entre 2</h2>
            <p class="ayuda">Los residuos se leen de abajo hacia arriba.</p>
            <table class="tabla">
                <thead>
                    <tr>
                        <th>División</th>
                        <th>Cociente</th>
                        <th>Residuo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pasos as $paso): ?>
                        <tr>
                            <td class="num"><?= e($paso['dividendo']) ?> ÷ 2</td>
                            <td class="num"><?= e($paso['cociente']) ?></td>
                            <td class="num"><strong><?= e($paso['residuo']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>