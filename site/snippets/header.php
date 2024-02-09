<!DOCTYPE html>
<html lang="<?= $kirby->language() ?? 'en' ?>">
<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="description" content="<?= $site->description()->html() ?>">
  <meta name="keywords" content="<?= $site->keywords()->html() ?>">
  <meta name="author" content="<?= $site->author()->html() ?>">

  <title><?= $site->title()->html() ?> | <?= $page->ctitle() ?></title>

  <?php
    if ( option('environment') == 'local' ) :
      foreach ( option('kirby-devkit.assets.styles', array()) as $style):
        echo css($style.'?version='.md5(uniqid(rand(), true)));
      endforeach;
      echo css('assets/src/css/dev.css');
    else:
      echo css('assets/dist/css/styles.min.css?v='.option('kirby-devkit.assets.version'));
    endif
  ?>

  <meta property="og:site_name"    content="<?= $site->title()->html() ?>">
  <meta property="og:url"          content="<?= $page->url() ?>">
  <meta property="og:title"        content="<?= $page->ctitle() ?>">
  <meta property="og:description"  content="<?php e( $page->text()->isNotEmpty(), Str::excerpt($page->text()->toBlocks(), 600), $site->description()->excerpt(600)) ?>">
  <meta property="og:type"         content="website">
  <?php if ( $page->hasImages() && $image = $page->images()->sortBy('sort', 'asc')->first() ): ?>
  <meta property="og:image"        content="<?= $image->resize(800, 800)->url() ?>">
  <?php endif; ?>

  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:site"        content="<?= $page->url() ?>">
  <meta name="twitter:creator"     content="<?= $site->author()->html() ?>">
  <meta name="twitter:title"       content="<?= $page->ctitle() ?>">
  <meta name="twitter:description" content="<?php e( $page->text()->isNotEmpty(), Str::excerpt($page->text()->toBlocks(), 600), $site->description()->excerpt(600)) ?>">
  <?php if ( $page->hasImages() && $image = $page->images()->sortBy('sort', 'asc')->first() ): ?>
  <meta name="twitter:image"       content="<?= $image->resize(800, 800)->url() ?>">
  <?php endif; ?>

</head>
<body
   data-login="<?php e($kirby->user(),'true', 'false') ?>"
   data-template="<?php echo $page->template() ?>"
   data-intended-template="<?php echo $page->intendedTemplate() ?>">
