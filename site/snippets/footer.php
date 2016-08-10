
  <footer>

    <p style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0;">
      <a href="http://g-u-i.net/">Made by Julien Gargot from <em>g.u.i.</em></a> and <a href="http://www.louiseveillard.com">Louis Eveillard</a>.
    </p>

  </footer>

  <!-- scripts -->
  <?php
    if ( c::get('environment') == 'local' ) :
      foreach ( c::get('scripts') as $style):
        echo js($style);
      endforeach;
    else:
      echo js('assets/production/all.min.js');
    endif
  ?>

</body>
</html>
