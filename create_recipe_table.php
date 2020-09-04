<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Create Recipe Table</TITLE>
  </HEAD>
  <BODY>
    <?php
      require 'recipe_variables.php';
      require_once 'recipe_support.php';
      $table_name = 'RECIPES';
      $connect = my_connect();
      if ($connect)
      { 
        $SQLcmd = "CREATE TABLE $table_name
          (
            RecipeId INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            RecipeTitle VARCHAR(60), 
            RecipeAuthFName VARCHAR(40),
            RecipeAuthLName VARCHAR(40),
            RecipeAuthCName VARCHAR(40),
            RecipeAuthClass INT,
            RecipeInputBy VARCHAR(40),
            RecipeLastStartedBy VARCHAR(40),
            RecipeLastStartedAt TIMESTAMP,
            RecipeRawFilePath VARCHAR(60),
            RecipeText TEXT
          )";
        mysqli_select_db($connect, $mydb) /* ||
          print "<SCRIPT>alert('DEBUG: Failed to select database $mydb using " .
          mysqli_get_host_info($connect) .
          "Errors: " . print_r(mysqli_error_list($connect, true)) . "');</SCRIPT>\n" */ ;
        if (mysqli_query($connect, $SQLcmd))
        {
          print '<FONT SIZE="4" COLOR="blue">Table Created.';
          // print "<BR>(DEBUG: <I>$table_name</I> in database <I>$mydb</I>)";
          print "<BR></FONT>";
          // print "<BR>DEBUG: SQLcmd=$SQLcmd";
        }
        else
        {
          die ("Table Creation Failed." /* . "<BR>(DEBUG: SQLcmd=$SQLcmd)" */ );
        }
        mysqli_close($connect);
      }
    ?>
    <?php
      require_once 'recipe_support.php';
      showDemoStep(__FILE__);
      $_SESSION['lastScript'] = basename(__FILE__);
    ?>
  </body>
</html>