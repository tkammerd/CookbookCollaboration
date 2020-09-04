<?php session_start(); ?>
<html>
  <head>
    <title>Initialize cookbook database with sample data</title>
  </head>
  <body>
    <?php
      require 'recipe_variables.php';
      extract($_REQUEST, EXTR_PREFIX_ALL|EXTR_REFS, "formval");
      $result = shell_exec(
        "mysql --user=$user --password=$pass	--database=$mydb < cookbook_init.sql");
      print "<h3>Cookbook re-initialized with sample data</h3>";
    ?>
    <a href="admin.php">Return to admin menu</a>
    <?php
      require_once 'recipe_support.php';
      showDemoStep(__FILE__);
      if (!isset($_SESSION['lastScript']))
        $_SESSION['lastScript'] = '';
      /*
      print "DEBUG: demoStep = " . $_SESSION['demoStep'] . ", " .
        "lastScript = " . $_SESSION['lastScript'] . ", currentScript = " . 
        basename(__FILE__) . "<br/>\n";
      print "DEBUG: Session variables before possible session_unset:<BR>\n";
      print_r($_SESSION);
      print "<br />\n";
      */
      if ($_SESSION['lastScript'] == basename(__FILE__))
        session_unset();
      else
        $_SESSION['lastScript'] = basename(__FILE__);
      /*
      print "DEBUG: Session variables after lastScript update:<BR>\n";
      print_r($_SESSION);
      print "<br />\n";
      */
    ?>
  </body>
</html>
