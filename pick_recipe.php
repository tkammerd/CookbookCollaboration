<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Pick Recipe</TITLE>
  </HEAD>
  <BODY>
    <?php
      require_once 'recipe_support.php';
      extract($_REQUEST, EXTR_PREFIX_ALL|EXTR_REFS, "formval");
      if (!check_password($formval_teammateName, $formval_password))
      {
        print "    Authentication failure.  Please <A HREF='login.php'>log in</A> again.\n";
      }
      else
      {
        require 'recipe_variables.php';
        $table_name = 'RECIPES';
        $connect = my_connect();
        if ($connect)
        {
          $query = "SELECT * FROM $table_name";
          // print '<SCRIPT>alert("DEBUG: The Query is ' . $query . '");</SCRIPT>' . "\n";
          mysqli_select_db($connect, $mydb);
          $results_id = mysqli_query($connect, $query);
          if ($results_id)
          {
            print 
              "    <BR>\n" . 
              "    <TABLE BORDER=1>\n" .
              "      <TR>\n" .
              "        <TH>Title</TH><TH>Author Name</TH><TH>Status</TH>\n" .
              "      </TR>\n";
            while ($row = mysqli_fetch_assoc($results_id))
            {
              print "      <TR>\n";
              if ($row["RecipeInputBy"] == "")
              {
                print '        <TD><A HREF="enter_recipe.php?' .
                  'recipeId='       . urlencode($row['RecipeId']) .
                  '&teammateName='  . urlencode($formval_teammateName) .
                  '&password='      . urlencode($formval_password) .
                  '&initTitle='     . urlencode($row['RecipeTitle']) .
                  '&initAuthFName=' . urlencode($row['RecipeAuthFName']) .
                  '&initAuthLName=' . urlencode($row['RecipeAuthLName']) .
                  '&rawRecipePath=' . urlencode($row['RecipeRawFilePath']) .
                  '">' . $row["RecipeTitle"] . "</A></TD>\n";
              }
              else
              {
                print '        <TD>' . $row["RecipeTitle"] . "</TD>\n";
              }
              print '        <TD>' . $row["RecipeAuthFName"] . ' ' . 
                $row["RecipeAuthLName"] . "</TD>\n";
              if ($row["RecipeInputBy"] != "")
                print '        <TD>Entered by ' . $row["RecipeInputBy"] . "</TD>\n";
              else if ($row["RecipeLastStartedBy"] != "")
              {
                $formatted_date_row = mysqli_fetch_row(mysqli_query(
                  $connect, "SELECT DATE_FORMAT('" . $row['RecipeLastStartedAt'] . "', '%c/%e/%y %r')"));
                print '        <TD>Started by ' . $row["RecipeLastStartedBy"] . ' on ' .
                  $formatted_date_row[0] . "</TD>\n";
              }
              else
                print "        <TD>Not entered</TD>\n";
              print "      </TR>\n";
            }
            print "    </TABLE>\n";    
          }
          else
          {
            // print "<SCRIPT>alert('DEBUG: query=$query Failed');</SCRIPT>";
          }
          mysqli_close($connect);
        }
      }
    ?>
    <?php
      require_once 'recipe_support.php';
      showDemoStep(__FILE__);
      $_SESSION['lastScript'] = basename(__FILE__);
    ?>
  </body>
</html>