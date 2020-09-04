<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Delete Team Table</TITLE>
  </HEAD>
  <BODY>
    <?php
      require_once 'recipe_support.php';
      delete_table('TEAM');
      showDemoStep(__FILE__);
    ?>
  </body>
</html>