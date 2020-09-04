<?php session_start(); ?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Add a recipe to the recipe table</title>
  </head>
  <body>
    <i>Turkey Scallops</i> added to the recipe database.
    <p>
      <a href="../pick_recipe.php?teammateName=guest&password=guest">
        Enter another recipe</a>
    </p>
    <a href="../logout.php">Quit</a>
    <?php
      require_once '../recipe_support.php';
      showDemoStep(__FILE__);
      $_SESSION['lastScript'] = basename(__FILE__);
    ?>
  </body>
</html>