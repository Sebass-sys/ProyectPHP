<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$texto = '';
$estadistica = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texto = (string) ($_POST['numeros'] ?? '');

    try {
        $estadistica = Estadistica::desdeTexto($texto);
    } catch (InvalidArgumentException $excepcion) {
        $error = $excepcion->getMessage();
    }
}

$titulo = 'Promedio, mediana y moda';
require __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <h1>Promedio, mediana y moda</h1>
    <p class="ayuda">
        Ingresa tantos números reales como quieras, separados por espacios, comas, punto y coma o saltos de línea.
        El separador decimal es el punto (por ejemplo 3.5).
    </p>

    <form method="post" class="formulario">
        <div class="campo">
            <label for="numeros">Números</label>
            <textarea id="numeros" name="numeros" placeholder="4, 8.5, 15, 8.5, 23, 42" required autofocus><?= e($texto) ?></textarea>
        </div>
        <button type="submit" class="btn">Calcular</button>
    </form>

    <?php if ($error !== null): ?>
        <div class="alerta error" role="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($estadistica !== null): ?>
        <?php $modas = $estadistica->moda(); ?>
        <div class="metricas">
            <div class="metrica">
                <span class="etiqueta">Cantidad</span>
                <strong><?= e($estadistica->cantidad()) ?></strong>
            </div>
            <div class="metrica">
                <span class="etiqueta">Promedio (media)</span>
                <strong><?= e(Formato::numero($estadistica->promedio())) ?></strong>
            </div>
            <div class="metrica">
                <span class="etiqueta">Mediana</span>
                <strong><?= e(Formato::numero($estadistica->mediana())) ?></strong>
            </div>
            <div class="metrica">
                <span class="etiqueta">Moda</span>
                <strong>
                    <?php if (count($modas) === 0): ?>
                        Sin moda
                    <?php else: ?>
                        <?= e(implode(', ', array_map(static fn (float $m): string => Formato::numero($m), $modas))) ?>
                    <?php endif; ?>
                </strong>
            </div>
        </div>

        <?php if (count($modas) === 0): ?>
            <p class="ayuda" style="margin-top:.75rem;">Ningún valor se repite, por eso la serie no tiene moda.</p>
        <?php elseif (count($modas) > 1): ?>
            <p class="ayuda" style="margin-top:.75rem;">Hay varios valores con la misma frecuencia máxima (serie multimodal).</p>
        <?php endif; ?>

        <div class="resultado">
            <span class="etiqueta">Datos ordenados</span>
            <div class="fichas">
                <?php foreach ($estadistica->ordenados() as $dato): ?>
                    <span class="ficha"><?= e(Formato::numero($dato)) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <p class="ayuda" style="margin-top:1.25rem;">
        Nota: «promedio» y «media» son el mismo valor (la media aritmética), por eso se muestra una sola vez
        y se añade la mediana, que es la medida que suele acompañarlas.
    </p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
