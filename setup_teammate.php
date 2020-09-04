<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Add starting data to team file</TITLE>
    <SCRIPT LANGUAGE="JavaScript">
      <!--
        function checkForm()
        {
          var formOK = true;
          if (setupForm.plaintextPassword.value != setupForm.passwordConfirm.value)
          {
            document.setupForm.plaintextPassword.style.backgroundColor = "pink";            
            document.setupForm.passwordConfirm.style.backgroundColor = "pink";            
            alert("Passwords don't match.  Please try again");
            formOK = false;
          }
          else
          {
            document.setupForm.plaintextPassword.style.backgroundColor = "white";            
            document.setupForm.passwordConfirm.style.backgroundColor = "white";            
          }
          if (document.setupForm.teammateName.value.match(/^\s*$/))
          {
            document.setupForm.teammateName.style.backgroundColor = "pink";
            alert("Teammate name is blank.  Please try again");
            formOK = false;
          }
          else
            document.setupForm.teammateName.style.backgroundColor = "white";            
          return formOK;
        }
      // -->
    </SCRIPT>
  </HEAD>
  <BODY>
    <?php
      require_once 'recipe_support.php';
      require 'recipe_variables.php';
      $table_name = 'TEAM';
      extract($_REQUEST, EXTR_PREFIX_ALL|EXTR_REFS, "formval");
      $encryptedPassword = encrypt($formval_plaintextPassword);
      $connect = my_connect();
      if ($connect)
      {
        $query = "INSERT INTO $table_name " .
          "(TeammateName, EncryptedPassword)" .
          " VALUES ('$formval_teammateName', '$encryptedPassword')";
        // print '<SCRIPT>alert("DEBUG: The Query is ' . $query . '");</SCRIPT>' . "\n";
        mysqli_select_db($connect, $mydb);
        if (mysqli_query($connect, $query))
        {
          print '<FONT SIZE="4" COLOR="blue">Insert ' . 
            // "(DEBUG: into <I>$table_name</I> in database <I>$mydb</I>) " .
            "successful.<BR></FONT>";
          print 
<<<TEAM_SETUP_FORM
    <FORM NAME="setupForm" ACTION="setup_teammate.php" METHOD="post" ONSUBMIT="return checkForm()">
      <TABLE>
        <TR>
          <TD>Teammate Name:</TD><TD><INPUT TYPE="text" NAME="teammateName"></TD>
        </TR>
        <TR>
          <TD>Teammate Password:</TD><TD><INPUT TYPE="password" NAME="plaintextPassword"></TD>
        </TR>
        <TR>
          <TD>Re-enter Teammate Password:</TD><TD><INPUT TYPE="password" NAME="passwordConfirm"></TD>
        </TR>
        <TR>
          <TD><INPUT TYPE="submit"></TD><TD><INPUT TYPE="reset"></TD>
        </TR>
      </TABLE>
    </FORM>
TEAM_SETUP_FORM;
        }
        else
        {
          print '<FONT SIZE="4" COLOR="blue">Insert ' . 
            // "(DEBUG: into <I>$table_name</I> in database <I>$mydb</I>) " .
            "failed.<BR></FONT>";
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
