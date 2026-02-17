<?php if (!empty($breadcrumbs)): ?>
<nav class="breadcrumbs" aria-label="Migas de pan">
  <ol>
    <?php foreach ($breadcrumbs as $index => $crumb): ?>
      <li>
        <?php if (isset($crumb['url']) && $index !== array_key_last($breadcrumbs)): ?>
          <a href="<?= $crumb['url'] ?>">
            <?= htmlspecialchars($crumb['label']) ?>
          </a>
        <?php else: ?>
          <span aria-current="page">
            <?= htmlspecialchars($crumb['label']) ?>
          </span>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>
<?php endif; ?>
