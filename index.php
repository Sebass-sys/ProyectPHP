<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$titulo = 'Inicio';
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <h1>Taller de aplicaciones en PHP</h1>
    <p>
        Siete aplicaciones construidas con HTML, CSS, PHP y programación orientada a objetos.
        Elige una del menú para empezar.
    </p>
</section>

<section class="rejilla">
    <?php foreach (Menu::opciones() as $indice => $opcion): ?>
        <a class="tarjeta" href="<?= e($opcion['archivo']) ?>">
            <span class="num"><?= $indice + 1 ?></span>
            <h2><?= e($opcion['titulo']) ?></h2>
            <p><?= e($opcion['descripcion']) ?></p>
            <span class="ir">Abrir →</span>
        </a>
    <?php endforeach; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
