<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Display Recipe Table</TITLE>
  </HEAD>
  <BODY>
    <?php
      require 'recipe_variables.php';
      require_once 'recipe_support.php';
      $table_name = 'RECIPES';
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
          print '<TR><TH>ID</TH><TH>Title</TH><TH>AuthFName</TH><TH>AuthLName</TH>' .
            '<TH>AuthCName</TH><TH>AuthClass</TH><TH>InputBy</TH><TH>LastStartedBy</TH>' .
            '<TH>LastStartedAt</TH><TH>RawFile</TH></TR>';
          while ($row = mysqli_fetch_row($results_id))
          {
            print '<TR>';
            $fieldCount = 0;
            foreach ($row as $field)
            {
              if ($fieldCount == 0)
              {
                print "<TD ROWSPAN=2>$field</TD>";
              }
              else if ($fieldCount == 8)
              {
                if ($row[7] == "")
                {
                  print "<TD></TD>";
                }
                else
                {
                  $formatted_date_row = mysqli_fetch_row(mysqli_query(
                    $connect, "SELECT DATE_FORMAT('$field', '%c/%e/%y %r')"));
                  print "<TD>" . $formatted_date_row[0] . "</TD>";
                }
              }
              else if ($fieldCount < 10)
              {
                print "<TD>$field</TD>";
              }
              else
              {
                print "</TR><TR><TD COLSPAN=9>" . 
                  nl2br(htmlspecialchars($field,
                  ENT_COMPAT | ENT_XHTML, 'ISO-8859-1')) . "</TD>";
              }
              $fieldCount++;
            }
            print '</TR>';
          }
          print '</TABLE>';
        }
        else
        {
          die("query=$query Failed");
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