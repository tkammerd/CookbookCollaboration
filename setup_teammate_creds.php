<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Teammate Setup Form</TITLE>
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
    <?php
      require_once 'recipe_support.php';
      showDemoStep(__FILE__);
      $_SESSION['lastScript'] = basename(__FILE__);
    ?>
  </body>
</html>