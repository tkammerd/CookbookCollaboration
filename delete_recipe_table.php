<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Delete Recipe Table</TITLE>
  </HEAD>
  <BODY>
    <?php
      require_once 'recipe_support.php';
      delete_table('RECIPES');
      showDemoStep(__FILE__);
      $_SESSION['lastScript'] = basename(__FILE__);
    ?>
  </body>
</html>