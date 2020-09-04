<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Login to Recipe Entry System</TITLE>
  </HEAD>
  <BODY>
    <FORM ACTION="pick_recipe.php" METHOD="post">
      <?php
        require 'recipe_variables.php';
        require_once 'recipe_support.php';
        $table_name = 'TEAM';
        $connect = my_connect();
        if ($connect)
        {
          $query = "SELECT TeammateName FROM $table_name";
          // print '<SCRIPT>alert("DEBUG: The Query is ' . $query . '");</SCRIPT>' . "\n";
          mysqli_select_db($connect, $mydb);
          $results_id = @mysqli_query($connect, $query);
          if ($results_id)
          {
            print "      Your Name:\n";
            print "      <SELECT NAME='teammateName'>\n";
            while ($row = mysqli_fetch_row($results_id))
              print "        <OPTION>$row[0]\n";
            print '      </SELECT>';
          }
          else
          {
            die("<SCRIPT>alert('Query Failed.'" /* . "\nDEBUG: query=$query" */ .
              ");</SCRIPT>");
          }
          mysqli_close($connect);
        }        
      ?><BR>
      Password: <INPUT TYPE="password" NAME="password"><BR>
      <INPUT TYPE="submit" VALUE="Log In">
    </FORM>
    <?php
      require_once 'recipe_support.php';
      showDemoStep(__FILE__);
      $_SESSION['lastScript'] = basename(__FILE__);
    ?>
  </body>
</html>
