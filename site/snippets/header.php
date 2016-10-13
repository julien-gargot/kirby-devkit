<!DOCTYPE html>
<html lang="<?php echo $site->language() ?>">
<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="description" content="<?php echo $site->description()->html() ?>">
  <meta name="keywords" content="<?php echo $site->keywords()->html() ?>">
  <meta name="author" content="<?php echo $site->author()->html() ?>">

  <title><?php echo $site->title()->html() ?> | <?php echo $page->title()->html() ?></title>

  <?php
    if ( c::get('environment') == 'local' ) :
      foreach ( c::get('styles') as $style):
        echo css($style);
      endforeach;
    else:
      echo css('assets/production/all.min.css');
    endif
  ?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn’t work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->

  <meta property="og:site_name" content="<?php echo $site->title()->html() ?>">
  <meta property="og:title" content="<?php echo $page->title()->html() ?>">
  <meta property="og:description" content="<?php echo $site->description()->html() ?>">
  <meta property="og:url" content="<?php echo $site->url() ?>">
  <meta property="og:image" content="<?php echo $site->ogimage()->html() ?>">
  <meta property="og:type" content="website">

  <meta name="twitter:title" content="<?php echo $page->title()->html() ?>">
  <meta name="twitter:description" content="<?php echo $site->description()->html() ?>">
  <meta name="twitter:url" content="<?php echo $site->url() ?>">
  <meta name="twitter:image" content="<?php echo $site->ogimage()->html() ?>">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="<?php echo $site->title()->html() ?>">
  <meta name="twitter:creator" content="<?php echo $site->author()->html() ?>">

  <meta name="apple-mobile-web-app-capable" content="yes">

</head>
<body
   data-login="<?php e($site->user(),'true', 'false') ?>"
   data-template="<?php echo $page->template() ?>"
   data-intended-template="<?php echo $page->intendedTemplate() ?>">
