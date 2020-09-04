<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Create Team Table</TITLE>
  </HEAD>
  <BODY>
    <?php
      require 'recipe_variables.php';
      require_once 'recipe_support.php';
      $table_name = 'TEAM';
      $connect = my_connect();
      if ($connect)
      {
        $SQLcmd = "CREATE TABLE $table_name
          (
            TeammateName VARCHAR(40) NOT NULL PRIMARY KEY,
            EncryptedPassword VARCHAR(40),
            RecipesStarted INT DEFAULT 0,
            LatestRecipeStart DATETIME,
            RecipesCompleted INT DEFAULT 0,
            LatestRecipeCompletion DATETIME
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