<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>ETBU Recipe System</TITLE>
  </HEAD>
  <BODY>
    <H3>
      <A HREF="login.php">Log In</A> to enter a recipe<BR>
      <A HREF="create_recipe_table.php" 
        ONCLICK="return confirm('Are you sure?');">Create a new, empty, recipe table
      </A><BR>
      <A HREF="delete_recipe_table.php" 
        ONCLICK="return confirm('Are you sure?');">Delete the existing recipe table
      </A><BR>
      <A HREF="setup_recipe.php">Add starting data for a recipe<A><BR>
      <A HREF="show_recipe_table.php">Display the contents of the recipe table</A><BR>
      <A HREF="create_team_table.php" ONCLICK="return confirm('Are you sure?');">
        Create a new, empty, team table</A><BR>
      <A HREF="delete_team_table.php" ONCLICK="return confirm('Are you sure?');">
        Delete the existing team table</A><BR>
      <A HREF="setup_teammate_creds.php">Add a team member<A><BR>
      <A HREF="show_team_table.php">Display the contents of the team table</A><BR>
      <A HREF="cookbook_init.php">Reset Demo</A><BR>
      <A HREF="start.php">Return to home non-admin menu</A>
    </H3>
	  <?php
      require_once 'recipe_support.php';
      if (!isset($_SESSION['demoStep']))
        $_SESSION['demoStep'] = 1;
      showDemoStep(__FILE__);
    ?>
  </body>
</html>