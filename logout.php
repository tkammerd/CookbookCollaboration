<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Log out of recipe entry system</TITLE>
  </HEAD>
  <BODY>
    <H1>Thanks for the help!  See you later.</H1>
    <?php
      require_once 'recipe_support.php';
      showDemoStep(__FILE__);
      $_SESSION['lastScript'] = basename(__FILE__);
    ?>
  </body>
</html>