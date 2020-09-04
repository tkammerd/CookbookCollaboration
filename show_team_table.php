<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Display Team Table</TITLE>
  </HEAD>
  <BODY>
    <?php
      require 'recipe_variables.php';
      require_once 'recipe_support.php';
      $table_name = 'TEAM';
      $connect = my_connect();
      if ($connect)
      {
        $query = "SELECT * FROM $table_name";
        // print "DEBUG: The Query is <I>$query</I><BR>";
        mysqli_select_db($connect, $mydb);
        $results_id = mysqli_query($connect, $query);
        if ($results_id)
        {
          print '<BR><TABLE BORDER=1>';
          print '<TR><TH>Teammate Name</TH><TH>Encrypted Password</TH>' .
            '<TH>Recipes Started</TH><TH>Latest Recipe Start</TH>' . 
            '<TH>Recipes Completed</TH><TH>Latest Recipe Completion</TH></TR>';
          while ($row = mysqli_fetch_row($results_id))
          {
            print '<TR>';
            foreach ($row as $field)
            {
              print "<TD>$field</TD>";
            }
            print '</TR>';
          }
          print '</TABLE>';
        }
        else
        {
          die("Unable to access any teams." /* . " DEBUG: query=$query" */ );
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