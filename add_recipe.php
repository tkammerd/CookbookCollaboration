<?php session_start(); ?>
<html>
  <HEAD>
    <TITLE>Add a recipe to the recipe table</TITLE>
  </HEAD>
  <BODY>
    <?php
      require_once 'recipe_support.php';
      extract($_REQUEST, EXTR_PREFIX_ALL|EXTR_REFS, "formval");
      if (!check_password($formval_teammateName, $formval_password))
      {
        die ("Authentication failure.  Please <A HREF='login.php'>log in</A> again.");
      }
      else
      {
        require 'recipe_variables.php';
        $connect = my_connect();
        if ($connect)
        {
          $recipe_text = create_recipe_text();
          $query = "UPDATE RECIPES SET RecipeTitle='$formval_title', " .
            "RecipeAuthFName='$formval_author_fname', RecipeAuthLName='$formval_author_lname', " .
            "RecipeAuthCName='$formval_author_cname', RecipeAuthClass=$formval_author_class, " .
            "RecipeInputBy='$formval_teammateName', RecipeText='$recipe_text' " .
            "WHERE RecipeId = '$formval_recipeId'";
          // print "DEBUG: The Query is <I>$query</I><BR>";
          mysqli_select_db($connect, $mydb);
          if (mysqli_query($connect, $query))
          {
            // print "<SCRIPT>alert('DEBUG: Update of RECIPES in database $mydb was successful!');</SCRIPT>\n";
            print "<I>$formval_title</I> added to the recipe database.<P>\n";
            print '<A HREF="pick_recipe.php?' .
              'teammateName='  . urlencode($formval_teammateName) .
              '&password='      . urlencode($formval_password) .
              '">Enter another recipe' . "</A><P>\n";
            print '<A HREF="logout.php">Quit</A>' . "\n";
          }
          else
          {
            $text_location = strpos($query, "RecipeText");
            $where_location = strpos($query, "WHERE RecipeId");
            die('<SCRIPT>alert("Update' .
              //' (DEBUG: of RECIPES in database ' . $mydb . ')' .
              ' failed! ' . 
              //' (DEBUG: query=' . substr($query,0,$text_location) . 
              //substr($query,$where_location) . ')' .
              '");</SCRIPT>' . "\n");
          }
          $query = "UPDATE TEAM SET RecipesCompleted=RecipesCompleted+1, LatestRecipeCompletion=NOW() " .
            "WHERE TeammateName = '$formval_teammateName'";
          if (mysqli_query($connect, $query))
          {
            /*
            print "<SCRIPT>alert('Update " .
              "(DEBUG: of TEAM in database $mydb) " .
              "was successful!');</SCRIPT>\n";
            */
          }
          else
          {
            print "<SCRIPT>alert('Update " .
              // "(DEBUG: of TEAM in database $mydb) " .
              "failed!');</SCRIPT>\n";
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