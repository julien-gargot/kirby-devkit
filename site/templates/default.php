<?php snippet('header') ?>

  <div class="container py-4 px-3 mx-auto">
    <h1><?php echo $page->title()->html() ?></h1>
    <?php echo $page->text()->kirbytext() ?>
    <button class="btn btn-primary">Primary button</button>
  </div>

<?php snippet('footer') ?>
