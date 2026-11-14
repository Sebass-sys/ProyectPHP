<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$preorden = '';
$inorden = '';
$postorden = '';
$arbol = null;
$dibujo = '';
$avisos = [];
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $preorden = (string) ($_POST['preorden'] ?? '');
    $inorden = (string) ($_POST['inorden'] ?? '');
    $postorden = (string) ($_POST['postorden'] ?? '');

    try {
        $pre = ArbolBinario::parsearRecorrido($preorden);
        $in = ArbolBinario::parsearRecorrido($inorden);
        $post = ArbolBinario::parsearRecorrido($postorden);

        $arbol = ArbolBinario::desdeRecorridos($pre, $in, $post);
        $dibujo = (new ArbolSvg())->dibujar($arbol);

        if (count($pre) > 0 && count($in) > 0 && count($post) > 0) {
            $avisos[] = 'Ingresaste los tres recorridos: por defecto el árbol se construyó con preorden e inorden.';
        }

        if ($arbol->esAmbiguo()) {
            $avisos[] = 'Con preorden y postorden hay más de un árbol posible cuando un nodo tiene un solo hijo; '
                . 'se dibujó ese hijo a la izquierda. Usa el inorden para obtener un árbol único.';
        }
    } catch (InvalidArgumentException $excepcion) {
        $error = $excepcion->getMessage();
    }
} elseif (isset($_GET['ejemplo'])) {
    // Carga el ejemplo del enunciado en el formulario (no calcula nada todavía).
    $preorden = 'A → B → D → E → C';
    $inorden = 'D → B → E → A → C';
    $postorden = 'D → E → B → C → A';
}

$titulo = 'Árbol binario por recorridos';
require __DIR__ . '/includes/header.php';
?>
<section class="panel">
    <h1>Árbol binario por recorridos</h1>
    <p class="ayuda">
        Ingresa mínimo dos recorridos: preorden + inorden, postorden + inorden o preorden + postorden.
        Separa los nodos con flechas (→), comas o espacios. Si ingresas los tres, se usa preorden + inorden.
        Los nodos no pueden repetirse.
        <a href="arbol_binario.php?ejemplo=1">Cargar ejemplo</a>
    </p>

    <form method="post" class="formulario">
        <div class="campo">
            <label for="preorden">Preorden</label>
            <input type="text" id="preorden" name="preorden" value="<?= e($preorden) ?>"
                placeholder="A → B → D → E → C">
        </div>
        <div class="campo">
            <label for="inorden">Inorden</label>
            <input type="text" id="inorden" name="inorden" value="<?= e($inorden) ?>" placeholder="D → B → E → A → C">
        </div>
        <div class="campo">
            <label for="postorden">Postorden</label>
            <input type="text" id="postorden" name="postorden" value="<?= e($postorden) ?>"
                placeholder="D → E → B → C → A">
        </div>
        <button type="submit" class="btn">Construir árbol</button>
    </form>

    <?php if ($error !== null): ?>
        <div class="alerta error" role="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <?php foreach ($avisos as $aviso): ?>
        <div class="alerta aviso"><?= e($aviso) ?></div>
    <?php endforeach; ?>

    <?php if ($arbol !== null): ?>
        <div class="alerta ok">
            Árbol construido con <?= e($arbol->metodo()) ?>:
            <?= e($arbol->cantidadNodos()) ?> nodos, altura <?= e($arbol->altura()) ?>.
        </div>

        <div class="arbol-contenedor">
            <?= $dibujo /* SVG generado por ArbolSvg, los valores ya van escapados */ ?>
        </div>

        <table class="tabla">
            <thead>
                <tr>
                    <th>Recorrido</th>
                    <th>Nodos</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Preorden</td>
                    <td class="num"><?= e(implode(' → ', $arbol->preorden())) ?></td>
                </tr>
                <tr>
                    <td>Inorden</td>
                    <td class="num"><?= e(implode(' → ', $arbol->inorden())) ?></td>
                </tr>
                <tr>
                    <td>Postorden</td>
                    <td class="num"><?= e(implode(' → ', $arbol->postorden())) ?></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>