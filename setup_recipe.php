<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Add starting data to recipe file</TITLE>
    <SCRIPT LANGUAGE="JavaScript">
      <!--
        function checkForm()
        {
          var formOK = true;
          if (document.setupForm.title.value.match(/^((\s*)|(.*)["'](.*))$/))
          {
            document.setupForm.title.style.backgroundColor = "pink";
            formOK = false;
          }
          else
            document.setupForm.title.style.backgroundColor = "white";            
          if (document.setupForm.firstname.value.match(/^\s*$/))
          {
            document.setupForm.firstname.style.backgroundColor = "pink";
            formOK = false;
          }
          else
            document.setupForm.firstname.style.backgroundColor = "white";            
          if (document.setupForm.lastname.value.match(/^\s*$/))
          {
            document.setupForm.lastname.style.backgroundColor = "pink";
            formOK = false;
          }
          else
            document.setupForm.lastname.style.backgroundColor = "white";            
          if (document.setupForm.rawfilepath.value.match(/^\s*$/))
          {
            document.setupForm.rawfilepath.style.backgroundColor = "pink";
            formOK = false;
          }
          else
            document.setupForm.rawfilepath.style.backgroundColor = "white";            
          return formOK;
        }
      // -->
    </SCRIPT>
  </HEAD>
  <BODY>
    <?php
      require_once 'recipe_support.php';
      require 'recipe_variables.php';
      $table_name = 'RECIPES';
      extract($_REQUEST, EXTR_PREFIX_ALL|EXTR_REFS, "formval");
      if (isset($formval_title))
      {
        $connect = my_connect();
        if ($connect)
        {
          $query = "INSERT INTO $table_name " .
            "(RecipeID, RecipeTitle, RecipeAuthFName, RecipeAuthLName, RecipeRawFilePath)" .
            " VALUES (0, '$formval_title', '$formval_firstname', '$formval_lastname', " . 
            "'$formval_rawfilepath')";
          // print '<SCRIPT>alert("DEBUG: The Query is ' . $query . '");</SCRIPT>' . "\n";
          mysqli_select_db($connect, $mydb);
          if (mysqli_query($connect, $query))
          {
            print '<FONT SIZE="4" COLOR="blue">Insert ' . 
              // "(DEBUG: into <I>$table_name</I> in database <I>$mydb</I>) " .
              "successful.<BR></FONT>";
          }
          else
          {
            print '<FONT SIZE="4" COLOR="blue">Insert ' . 
              // "(DEBUG: into <I>$table_name</I> in database <I>$mydb</I>) " .
              "failed.<BR></FONT>";
          }
          
          mysqli_close($connect);
        }
      }
      print 
<<<RECIPE_SETUP_FORM
    <FORM NAME="setupForm" ACTION="setup_recipe.php" METHOD="post" ONSUBMIT="return checkForm()">
      <TABLE>
        <TR>
          <TD>Recipe Title:</TD><TD><INPUT TYPE="text" NAME="title"></TD>
        </TR>
        <TR>
          <TD>Submitter's First Name:</TD><TD><INPUT TYPE="text" NAME="firstname"></TD>
        </TR>
        <TR>
          <TD>Submitter's Last Name:</TD><TD><INPUT TYPE="text" NAME="lastname"></TD>
        </TR>
        <TR>
          <TD>Filename of raw recipe file (PDF or text):</TD>
          <TD><INPUT TYPE="text" NAME="rawfilepath"></TD>
        </TR>
        <TR>
          <TD><INPUT TYPE="submit"></TD><TD><INPUT TYPE="reset"></TD>
        </TR>
      </TABLE>
    </FORM>
    <p><a href="admin.php">Return to admin menu</a></p>
RECIPE_SETUP_FORM;
    ?>
    <?php
      require_once 'recipe_support.php';
      showDemoStep(__FILE__);
      $_SESSION['lastScript'] = basename(__FILE__);
    ?>
  </body>
</html>
