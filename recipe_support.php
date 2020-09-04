<?php

function my_connect()
{
  require 'recipe_variables.php';
  $connect = @mysqli_connect($server, $user, $pass);
  if (!$connect)
  {
    print "<SCRIPT>alert('Cannot connect to $server using $user');</SCRIPT>\n";
  }
  return $connect;
}

function check_password($userid, $password)
{
  require 'recipe_variables.php';
  $encrypted_password = encrypt($password);
  $table_name = 'TEAM';
  $connect = my_connect();
  if ($connect)
  {
    $query = "SELECT EncryptedPassword FROM $table_name " .
      "WHERE TeammateName = '$userid'";
    // print '<SCRIPT>alert("DEBUG: The Query is ' . $query . '");</SCRIPT>' . "\n";
    mysqli_select_db($connect, $mydb);
    $results_id = @mysqli_query($connect, $query);
    if ($results_id)
    {
      $row = mysqli_fetch_assoc($results_id);
      mysqli_close($connect);
      return ($row['EncryptedPassword'] == $encrypted_password);
    }
    else
    {
      print '    <SCRIPT>alert("query=' . $query . ' Failed");</SCRIPT>' . "\n";
      mysqli_close($connect);
      return false;
    }
  }
}

function encrypt($plaintext)
{
  // The production version of this function will actually encrypt
  // the plaintext using a one-way hash
  return crypt($plaintext, "simple");
}

function create_recipe_text()
{
  $text = "@@@@@\n\n";
  $text .= $_POST['title'] . "\n\n";
  $text .= $_POST['category']. "\n\n";

  $ingred_set = 0;
  $ingred_num = 0;
  $ingred_subscripts = $ingred_set . "_" . $ingred_num;
  $cur_qty = 'qty_' . $ingred_subscripts;
  while ($_POST[$cur_qty] != null)
  {
    $cur_usehead = 'usehead_' . $ingred_set;
    $cur_heading = 'heading_' . $ingred_set;
    if ($_POST[$cur_usehead] == "yes")
      $text .= "      ----" . $_POST[$cur_heading] . "----\n";
    $cur_qty = 'qty_' . $ingred_subscripts;
    while ($_POST[$cur_qty] != null)
    {
      $cur_unit = 'unit_' . $ingred_subscripts;
      $cur_ingred_name = 'ingred_' . $ingred_subscripts;
      $cur_prep = 'prep_' . $ingred_subscripts;
		$unit_text = $_POST[$cur_unit];
		$unit_text = str_replace (' ', '~' , $unit_text);
		$qty_val = $_POST[$cur_qty];
		if (preg_match("/^\s*((\d+\s*\/\s*\d+)|1|scant)\s*$/i", $qty_val))
		{
		  switch ($unit_text)
		  {
		    case 'ga': $unit_text = "gallon"; break;
		    case 'qt': $unit_text = "quart"; break;
		    case 'pt': $unit_text = "pint"; break;
		    case 'c' : $unit_text = "cup"; break;
		    case 'fl': $unit_text = "fluid~ounce"; break;
		    case 'ds': $unit_text = "dash"; break;
		    case 'dr': $unit_text = "drop"; break;
		    case 'tb': $unit_text = "tablespoon"; break;
		    case 'ts': $unit_text = "teaspoon"; break;
		    case 'pn': $unit_text = "pinch"; break;
		    case 'lb': $unit_text = "pound"; break;
		    case 'oz': $unit_text = "ounce"; break;
		    case 'cn': $unit_text = "can"; break;
		    case 'pk': $unit_text = "package"; break;
		    case 'ct': $unit_text = "carton"; break;
		    case 'bn': $unit_text = "bunch"; break;
		    case 'sl': $unit_text = "slice"; break;
		    case 'ea': $unit_text = "each"; break;
		    case ' ' : $unit_text = "x"; break;
		    case 'lg': $unit_text = "large"; break;
		    case 'md': $unit_text = "medium"; break;
		    case 'sm': $unit_text = "small"; break;
		    case 'l' : $unit_text = "liter"; break;
		    case 'dl': $unit_text = "deciliter"; break;
		    case 'cl': $unit_text = "centiliter"; break;
		    case 'ml': $unit_text = "milliliter"; break;
		    case 'cc': $unit_text = "cubic~centimeter"; break;
		    case 'kg': $unit_text = "kilogram"; break;
		    case 'g' : $unit_text = "gram"; break;
		    case 'dg': $unit_text = "decigram"; break;
		    case 'cg': $unit_text = "centigram"; break;
		    case 'mg': $unit_text = "milligram"; break;
		    case 'x' : $unit_text = "x"; break;
			 default  : $unit_text = "<<" . $unit_text . ">>"; break;
		  }
		}
		else
		{
		  switch ($unit_text)
		  {
		    case 'ga': $unit_text = "gallons"; break;
		    case 'qt': $unit_text = "quarts"; break;
		    case 'pt': $unit_text = "pints"; break;
		    case 'c' : $unit_text = "cups"; break;
		    case 'fl': $unit_text = "fluid~ounces"; break;
		    case 'ds': $unit_text = "dashes"; break;
		    case 'dr': $unit_text = "drops"; break;
		    case 'tb': $unit_text = "tablespoons"; break;
		    case 'ts': $unit_text = "teaspoons"; break;
		    case 'pn': $unit_text = "pinches"; break;
		    case 'lb': $unit_text = "pounds"; break;
		    case 'oz': $unit_text = "ounces"; break;
		    case 'cn': $unit_text = "cans"; break;
		    case 'pk': $unit_text = "packages"; break;
		    case 'ct': $unit_text = "cartons"; break;
		    case 'bn': $unit_text = "bunches"; break;
		    case 'sl': $unit_text = "slices"; break;
		    case 'ea': $unit_text = "each"; break;
		    case ' ' : $unit_text = "x"; break;
		    case 'lg': $unit_text = "large"; break;
		    case 'md': $unit_text = "medium"; break;
		    case 'sm': $unit_text = "small"; break;
		    case 'l' : $unit_text = "liters"; break;
		    case 'dl': $unit_text = "deciliters"; break;
		    case 'cl': $unit_text = "centiliters"; break;
		    case 'ml': $unit_text = "milliliters"; break;
		    case 'cc': $unit_text = "cubic~centimeters"; break;
		    case 'kg': $unit_text = "kilograms"; break;
		    case 'g' : $unit_text = "grams"; break;
		    case 'dg': $unit_text = "decigrams"; break;
		    case 'cg': $unit_text = "centigrams"; break;
		    case 'mg': $unit_text = "milligrams"; break;
		    case 'x' : $unit_text = "x"; break;
			 default  : $unit_text = "<<" . $unit_text . ">>"; break;
		  }
		}
      $text .= $qty_val . " " . $unit_text . " " . $_POST[$cur_ingred_name];
      if ($_POST[$cur_prep] != "")
        $text .= "; " . $_POST[$cur_prep];
      $text .= "\n";
      $ingred_num++;
      $ingred_subscripts = $ingred_set . "_" . $ingred_num;
      $cur_qty = 'qty_' . $ingred_subscripts;
    }
    $ingred_num = 0;
    $ingred_set++;
    $ingred_subscripts = $ingred_set . "_" . $ingred_num;
    $cur_qty = 'qty_' . $ingred_subscripts;
  }
  $text .= "\n";
  $text .= $_POST['directions'] . "\n\n";
  $text .= "Contributor: " . $_POST['author_fname'] . " " . $_POST['author_lname'] . " (";
  if ($_POST['author_cname'] != "" && $_POST['author_cname'] != $_POST['author_lname'])
    $text .= "was " . $_POST['author_cname'] . " in ";
  $text .= $_POST['author_class'] . ")\n\n";
  $text .= "Yield: " . $_POST['servings'] . " serving";
  if ($_POST['servings'] > 1)
    $text .= "s\n\n";
  else
    $text .= "\n\n";  
  $text .= "Preparation Time: " . $_POST['prep_hours'] . ":" . $_POST['prep_mins'];
  return $text;
}

function delete_table($table_name)
{
  require 'recipe_variables.php';
  $connect = my_connect();
  if ($connect)
  {
    $SQLcmd = "DROP TABLE $table_name";
    mysqli_select_db($connect, $mydb);
    if (mysqli_query($connect, $SQLcmd))
    {
      print '<FONT SIZE="4" COLOR="blue">Deleted Table ';
      print "<I>$table_name</I> in database <I>$mydb</I><BR></FONT>";
      print "<BR>SQLcmd=$SQLcmd";
    }
    else
    {
      die ("Table Deletion Failed." /* . "DEBUG: SQLcmd=$SQLcmd" */ );
    }
    mysqli_close($connect);
  }
}

function showDemoStep($sourceFile)
{
  	require 'recipe_variables.php';
	  $table_name = 'COOKBOOK_DEMO';
	  $connect = my_connect();
	  if ($connect)
	  {
      $query = "SELECT * FROM $table_name WHERE DemoStep=" . $_SESSION['demoStep'];
      // print '<SCRIPT>alert("DEBUG: The Query is ' . $query . '");</SCRIPT>' . "\n";
      mysqli_select_db($connect, $mydb);
      if ($results_id = mysqli_query($connect, $query))
      {
        $row = mysqli_fetch_assoc($results_id);        
        /*
        print '<SCRIPT>alert("DEBUG: source file from database = \'' .
          basename($row['DemoModule']) . '\', current source file = \'' . 
          basename($sourceFile) . '\'");</SCRIPT>' . "\n";
        */
        if (basename($row['DemoModule']) == basename($sourceFile))
        {
          print "<script>document.body.style.marginRight='41ch';</script>";
          print '<br/><iframe srcdoc=\'<h3 style="text-align:center;">' . 
            'Demo Instructions</h3><p style="text-align:center;">Step ' . 
            $_SESSION['demoStep'] . '</p>' . 
            str_replace("'", "&apos;", $row['DemoInstructions']) . 
            '\' style=\'position:fixed;width:40ch;height:100%;top:0;right:0;\'></iframe>';
          $_SESSION['demoStep']++;
        }
      }
    }
    mysqli_close($connect);
}
?>
