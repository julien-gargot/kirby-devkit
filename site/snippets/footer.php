
  <footer>

    <p style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0;">
      <a href="http://g-u-i.net/">Made by Julien Gargot from <em>g.u.i.</em></a>.
    </p>

  </footer>

  <!-- scripts -->
  <?php
    if ( option('environment') == 'local' ) :
      foreach ( option('julien-gargot.assets.scripts', array()) as $style):
        echo js($style.'?version='.md5(uniqid(rand(), true)));
      endforeach;
    else:
      echo js('assets/production/all.min.js');
    endif
  ?>

</body>
</html>
