
  <footer>

    <p style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0;">
      <a href="http://g-u-i.net/">Made by Julien Gargot from <em>g.u.i.</em></a>.
    </p>

  </footer>

  <!-- scripts -->
  <?php
    if (option('environment') == 'local') :
      foreach (option('kirby-devkit.assets.scripts', array()) as $script) :
        echo js($script . '?version=' . md5(uniqid(rand(), true)));
      endforeach;
      snippet('dev');
    else :
      echo js('assets/dist/js/app.min.js?v=' . option('kirby-devkit.assets.version'));
    endif
    ?>
    <?= js('@auto') ?>

</body>
</html>
