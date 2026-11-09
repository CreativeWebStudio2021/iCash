<?php
declare(strict_types=1);

/** @param list<array{0:string,1:?string}> $crumbs */
function render_pagetitle(string $heading, string $lead, array $crumbs, string $tone): void
{
    ?>
    <section class="page-hero tone-<?= e($tone) ?><?= $tone === 'prodotti' ? ' page-hero-compact' : '' ?>">
      <div class="wrap">
        <nav class="crumbs" aria-label="Percorso di navigazione">
          <?php foreach ($crumbs as $i => $crumb): ?>
            <?php if ($i > 0): ?><span aria-hidden="true">/</span><?php endif; ?>
            <?php if ($crumb[1]): ?>
              <a href="<?= e($crumb[1]) ?>" title="<?= e($crumb[0]) ?>"><?= e($crumb[0]) ?></a>
            <?php else: ?>
              <span aria-current="page"><?= e($crumb[0]) ?></span>
            <?php endif; ?>
          <?php endforeach; ?>
        </nav>
        <h1><?= e($heading) ?></h1>
        <?php if ($lead !== ''): ?><p class="lead"><?= e($lead) ?></p><?php endif; ?>
      </div>
    </section>
    <?php
}
