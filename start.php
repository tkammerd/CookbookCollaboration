<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>ETBU Recipe System</TITLE>
  </HEAD>
  <BODY>
    <H3>
      This is the recipe entry system for the Alumni Cookbook Team at the East Texas Baptist
      University Alumni Association.  You will need an account and password to use this
      system.  To our team members -- Welcome!
      <P>
      <A HREF="login.php">Log In</A> to enter a recipe
    <?php
      require_once 'recipe_support.php';
      showDemoStep(__FILE__);
      $_SESSION['lastScript'] = basename(__FILE__);
    ?>
  </body>
</html>