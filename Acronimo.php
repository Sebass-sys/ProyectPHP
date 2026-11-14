<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$frase = '';
$resultado = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $frase = (string) ($_POST['frase'] ?? '');

    try {
        $acronimo = new Acronimo($frase);
        $resultado = $acronimo->generar();
    } catch (InvalidArgumentException $excepcion) {
        $error = $excepcion->getMessage();
    }
}

$titulo = 'Frase a acrónimo';
require __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <h1>Frase a acrónimo</h1>
    <p class="ayuda">
        Escribe un nombre largo y obtén su sigla. Los guiones separan palabras igual que los espacios;
        los demás signos de puntuación se ignoran.
    </p>

    <form method="post" class="formulario">
        <div class="campo">
            <label for="frase">Frase</label>
            <input type="text" id="frase" name="frase" value="<?= e($frase) ?>" placeholder="Portable Network Graphics"
                required autofocus>
        </div>
        <button type="submit" class="btn">Convertir</button>
    </form>

    <?php if ($error !== null): ?>
        <div class="alerta error" role="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($resultado !== null): ?>
        <div class="resultado">
            <span class="etiqueta">Acrónimo</span>
            <strong class="grande"><?= e($resultado) ?></strong>
        </div>
    <?php endif; ?>
</section>

<section class="panel">
    <h2>Ejemplos</h2>
    <table class="tabla">
        <thead>
            <tr>
                <th>Frase</th>
                <th>Acrónimo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>As Soon As Possible</td>
                <td>ASAP</td>
            </tr>
            <tr>
                <td>Liquid-crystal display</td>
                <td>LCD</td>
            </tr>
            <tr>
                <td>Thank George It's Friday!</td>
                <td>TGIF</td>
            </tr>
        </tbody>
    </table>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>