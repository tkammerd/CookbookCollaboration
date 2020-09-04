<?php session_start(); ?>
<html>
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
      $table_name = 'RECIPES';
      $connect = my_connect();
      if ($connect)
      {
        $query = "UPDATE RECIPES SET RecipeLastStartedBy='$formval_teammateName' " .
          "WHERE RecipeId = '$formval_recipeId'";
        /* Note: When this query is executed, RecipeLastStartedAt will be automatically
           updated, because it is a TIMESTAMP column. */
        // print '<SCRIPT>alert("DEBUG: The Query is ' . $query . '");</SCRIPT>' . "\n";
        mysqli_select_db($connect, $mydb);
        if (mysqli_query($connect, $query))
        {
          // print "<SCRIPT>alert('Update of RECIPES in database $mydb was successful!');</SCRIPT>\n";
        }
        else
        {
          die('<SCRIPT>alert("Update' .
            //' (DEBUG: of RECIPES in database ' . $mydb . ')' .
            ' failed! ");</SCRIPT>' . "\n");        }
        $query = "UPDATE TEAM SET RecipesStarted=RecipesStarted+1, " .
          "LatestRecipeStart=NOW() " .
          "WHERE TeammateName = '$formval_teammateName'";
        if (mysqli_query($connect, $query))
        {
          // print "<SCRIPT>alert(
          //   'Update of TEAM in database $mydb was successful!');</SCRIPT>\n";
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
  <HEAD>
    <TITLE>Recipe Entry Form</TITLE>
    <SCRIPT LANGUAGE="JavaScript">
      <!--
        var ERROR_COLOR = "pink";
        var NON_ERROR_COLOR = "white";
        var MAX_INGRED_SETS = 10;
        var STARTING_INGRED_SETS = 1;
        var numIngredSets = 0;
        var curIngredNum = new Array(MAX_INGRED_SETS);
        var submittedBySubmitButton = false;

        function init(rawRecipePath)
        {
          // Open separate window showing rawRecipePath
          alert('Opening raw_recipes/' + rawRecipePath + " in a separate window.\n" +
            "If you are using popup blocking software, you need to\n" +
            "either disable it, or just allow popups from " +
            location.hostname + ".  Then try entering this recipe again.");
          window.open('raw_recipes/' + rawRecipePath, "_blank");

          // Initialize ingredient sets
          var count;
          for (count = 0; count < curIngredNum.length; count++)
            curIngredNum[count] = -1;
          for (count = 0; count < STARTING_INGRED_SETS; count++)
            addCompleteIngredSet();
        }

        function addCompleteIngredSet()
        {
          var ingredSet = numIngredSets;
          if (numIngredSets < MAX_INGRED_SETS)
          {
            addIngredSet(ingredSet);
            addIngredRow("ingredSet" + ingredSet, ingredSet);
          }
        }

        function delCompleteIngredSet()
        {
          if (numIngredSets > 1)
          {
            numIngredSets--;
            var recipeTable = document.getElementById("recipeTable");
            var lastIngredSet = document.getElementById("ingredSet" + numIngredSets);
            var lastIngredSetButtons = lastIngredSet.nextSibling;
            recipeTable.removeChild(lastIngredSet);
            recipeTable.removeChild(lastIngredSetButtons);
          }
        }

        function classSelect(firstYear, lastYear)
        {
          document.writeln('            <SELECT NAME="author_class">');
          document.writeln('              <OPTION>');
          for (year = firstYear; year <= lastYear; year++)
            document.writeln('              <OPTION>' + year);
          document.writeln('              <OPTION VALUE="0">faculty');
          document.writeln('              <OPTION VALUE="1">staff');
          document.writeln('            </SELECT>');
        }

        function genPrepSelect(tdContainer, ingredSet, ingredNum)
        {
          var selectElem = createNamedElement('SELECT',
            'prep_' + ingredSet + '_' + ingredNum);
          selectElem.size = 1;
          selectElem.multiple = true;
          tdContainer.appendChild(selectElem);
          addOption(selectElem, '', '-select-', true);
          addOption(selectElem, '', 'chopped', false);
          addOption(selectElem, '', 'sliced', false);
          addOption(selectElem, '', 'diced', false);
          addOption(selectElem, '', 'minced', false);
          addOption(selectElem, '', 'puried', false);
          addOption(selectElem, '', 'ground', false);
          addOption(selectElem, '', 'peeled', false);
          addOption(selectElem, '', 'boiled', false);
          addOption(selectElem, '', 'blanched', false);
          addOption(selectElem, '', 'strained', false);
          addOption(selectElem, '', 'sifted', false);
        }

        function addOption(selectContainer, curValue, curLabel, isDisabled)
        {
          /* IE registers the disabled value from:

             optionElem.disabled = true;

             change, but does not respond to it, i.e. the option is still 
             selectable in the form, so the following multi-browser 
             implementation may be necessary.  It doesn't seem to work even
             in a simple non-dynamic form in at least one IE version [userAgent =
             "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; 
             .NET CLR 1.1.4322; COM+ 1.0.2204; .NET CLR 1.0.2914)"],
             so it may be a simple browser support issue.
          */
          var optionElem;
          if (window.navigator.appName == "Microsoft Internet Explorer")
          {
            var elemHTML = '<OPTION' + (isDisabled?' DISABLED':'') + '>';
            optionElem = document.createElement(elemHTML);
          }
          else
          {
            optionElem = document.createElement('OPTION');
            if (isDisabled)
              optionElem.disabled = true;
          }
          if (curValue != '')
            optionElem.value = curValue;
          if (curLabel != '')
            optionElem.appendChild(document.createTextNode(curLabel));
          selectContainer.appendChild(optionElem);
        }          

        function checkForOtherUnit(selectElem)
        {
          if (selectElem.options.item(selectElem.selectedIndex).text == 'other')
          {
            var newUnit;
            do
            {
              newUnit = prompt('Please enter name of other unit\n' +
                '(18 character maximum length):');
              if (newUnit == null || newUnit.length == 0)
                alert('You must enter a name for the unit.');
              else if (newUnit.length > 18)
                alert('Your unit name is longer than 18 characters.\n' +
                  'Please modify it to 18 characters or less long.');
            }
            while (newUnit == null || newUnit.length > 18 || newUnit.length == 0);
            selectElem.options.item(selectElem.selectedIndex).value = 
              selectElem.options.item(selectElem.selectedIndex).text = newUnit;
            addOption(selectElem, '*', 'other', false);
          }         
        }

        function genUnitSelect(tdContainer, ingredSet, ingredNum)
        {
          var selectElem = createNamedElementWithEvent('SELECT',
            'unit_' + ingredSet + '_' + ingredNum, 'ONCHANGE',
            'checkForOtherUnit(this)');
          tdContainer.appendChild(selectElem);
          addOption(selectElem, ' ',  ' ', false);
          addOption(selectElem, 'ga', 'gallon(s)', false);
          addOption(selectElem, 'qt', 'quart(s)', false);
          addOption(selectElem, 'pt', 'pint(s)', false);
          addOption(selectElem, 'c',  'cup(s)', false);
          addOption(selectElem, 'fl', 'fluid ounce(s)', false);
          addOption(selectElem, 'ds', 'dash(es)', false);
          addOption(selectElem, 'dr', 'drop(s)', false);
          addOption(selectElem, 'tb', 'tablespoon(s)', false);
          addOption(selectElem, 'ts', 'teaspoon(s)', false);
          addOption(selectElem, 'pn', 'pinch(es)', false);
          addOption(selectElem, 'lb', 'pound(s)', false);
          addOption(selectElem, 'oz', 'ounce(s)', false);
          addOption(selectElem, 'cn', 'can(s)', false);
          addOption(selectElem, 'pk', 'package(s)', false);
          addOption(selectElem, 'ct', 'carton(s)', false);
          addOption(selectElem, 'bn', 'bunch(es)', false);
          addOption(selectElem, 'sl', 'slice(s)', false);
          addOption(selectElem, 'ea', 'each', false);
          addOption(selectElem, ' ',  'single', false);
          addOption(selectElem, 'lg', 'large', false);
          addOption(selectElem, 'md', 'medium', false);
          addOption(selectElem, 'sm', 'small', false);
          addOption(selectElem, 'l',  'liter(s)', false);
          addOption(selectElem, 'dl', 'deciliter(s)', false);
          addOption(selectElem, 'cl', 'centiliter(s)', false);
          addOption(selectElem, 'ml', 'milliliter(s)', false);
          addOption(selectElem, 'cc', 'cubic centimeter(s)', false);
          addOption(selectElem, 'kg', 'kilogram(s)', false);
          addOption(selectElem, 'g',  'gram(s)', false);
          addOption(selectElem, 'dg', 'decigram(s)', false);
          addOption(selectElem, 'cg', 'centigram(s)', false);
          addOption(selectElem, 'mg', 'milligram(s)', false);
          addOption(selectElem, 'x',  '(leave blank)', false);
          addOption(selectElem, '*', 'other', false); // Allow user to enter 18 chars for unit
        }

        function genIngredRow(trContainer, ingredSet, ingredNum)
        {
          var currentCell, currentCellElem;
          currentCell = document.createElement('TD');
          trContainer.appendChild(currentCell);
          currentCellElem = genInputElement('text', 'qty_' + ingredSet + '_' + ingredNum,
            '');
          currentCellElem.size = 11;
          currentCell.appendChild(currentCellElem);
          currentCell = document.createElement('TD');
          trContainer.appendChild(currentCell);
          genUnitSelect(currentCell, ingredSet, ingredNum);
          currentCell = document.createElement('TD');
          currentCell.colSpan = 2;
          trContainer.appendChild(currentCell);
          currentCellElem = genInputElement('text', 'ingred_' + ingredSet + '_' + 
            ingredNum, '');
          currentCellElem.size = 27;
          currentCell.appendChild(currentCellElem);
          currentCell = document.createElement('TD');
          trContainer.appendChild(currentCell);
          // genPrepSelect(currentCell, ingredSet, ingredNum);
          // Next 3 lines replace genPrepSelect() above with a text field
          currentCellElem = genInputElement('text', 'prep_' + ingredSet + '_' +
            ingredNum, '');
          currentCellElem.size = 12;
          currentCell.appendChild(currentCellElem);
        }

        function addIngredRow(tbodyId, ingredSet)
        {
          var tbodyContainer = document.getElementById(tbodyId); 
          var ingredRow;
          curIngredNum[ingredSet]++;
          ingredRow = document.createElement('TR');
          tbodyContainer.appendChild(ingredRow);
          genIngredRow(ingredRow, ingredSet, curIngredNum[ingredSet]);
        }
        
        function delIngredRow(tbodyId, ingredSet)
        {
          if (curIngredNum[ingredSet] > 0)
          {
            var tbodyContainer = document.getElementById(tbodyId); 
            tbodyContainer.removeChild(tbodyContainer.lastChild);
            curIngredNum[ingredSet]--;
          }
        }

        function createNamedElementWithEvent(elemType, elemName, elemEvent, eventHandler)
        {
          var elemHTML, elemNode;
          if (window.navigator.appName == "Microsoft Internet Explorer")
          {
            elemHTML = '<' + elemType + ((elemName != '')?(' NAME="' + elemName + '"'):'') + 
              ((elemEvent != '')?(' ' + elemEvent.toUpperCase() + '="' + eventHandler + '"'):'') + 
              '>';
          }
          else
          {
            elemHTML = elemType;
          }
          var elemNode = document.createElement(elemHTML);
          if (elemName != '')
            elemNode.name = elemName;
          if (elemEvent!= '')
            elemNode.setAttribute(elemEvent.toLowerCase(), eventHandler);
          return elemNode;
        }

        function createNamedElement(elemType, elemName)
        {
          /* According to 
             msdn.microsoft.com/workshop/author/dhtml/reference/properties/name_2.asp,
             IE must use its full-html version of createElement for elements that need to
             set the NAME attribute at runtime, but Gecko does not recognize an element 
             presented this way, which is the reason for the complex createElement 
             invocation below. */
          var elemHTML, elemNode;
          if (window.navigator.appName == "Microsoft Internet Explorer")
          {
            elemHTML = '<' + elemType + ((elemName != '')?(' NAME=' + elemName):'') + '>';
          }
          else
          {
            elemHTML = elemType;
          }
          var elemNode = document.createElement(elemHTML);
          if (elemName != '')
            elemNode.name = elemName;
          return elemNode;
        }

        function genInputElement(curType, curName, curValue)
        {
          var inputNode = createNamedElement('INPUT', curName);
          inputNode.type = curType;
          if (curValue != '')
            inputNode.value = curValue;
          return inputNode;
        }

        // For Debugging
        function showAttribs(oElem)
        {
          var txtAttribs= '';

          // Retrieve the collection of attributes for the specified object.
          var oAttribs = oElem.attributes;

          // Iterate through the collection.
          for (var i = 0; i < oAttribs.length; i++)
          {
            var oAttrib = oAttribs[i];

            // Print the name and value of the attribute. 
            // Additionally print whether or not the attribute was specified
            // in HTML or script.
            txtAttribs += oAttrib.nodeName + '=' + 
              oAttrib.nodeValue + ' (' + oAttrib.specified + ');  '; 
          }
          return txtAttribs
        }

        function createButton(buttonText, clickHandler)
        {
          /* There is probably a better way to dynamically create
             event handlers, but I've not figured it out yet.  Also,
             apparently the onclick property is read-only, so the
             following doesn't work:

             buttonElem.onclick = clickHandler;

             In the meantime, I've got one method that works with IE
             and another that works with Gecko, so here they are.
          */
          var buttonElem;
          if (window.navigator.appName == "Microsoft Internet Explorer")
          {
            var elemHTML = "<INPUT TYPE='button' VALUE='"
              + buttonText + "' ONCLICK='" + clickHandler + "'>";
            buttonElem = document.createElement(elemHTML);
          }
          else
          {
            buttonElem = genInputElement("button", "", buttonText);
            buttonElem.setAttribute('onclick', clickHandler);
          }
          return buttonElem;
        }

        function addIngredSet(ingredSet)
        {
          var currentRow, currentCell, currentCellElem;

          // Insert TBODY for ingredient set at correct spot in recipe table
          var recipeTable = document.getElementById("recipeTable");
          var addIngredSetSection = document.getElementById("addIngredSetButton");
          var newIngredSet = document.createElement('TBODY');
          newIngredSet.id = "ingredSet" + ingredSet;
          recipeTable.insertBefore(newIngredSet, addIngredSetSection);

          currentRow = document.createElement('TR');
          newIngredSet.appendChild(currentRow);
          currentCell = document.createElement('TD');
          currentCell.colSpan = 5;
          currentRow.appendChild(currentCell);
          currentCell.appendChild(document.createElement('HR'));

          currentRow = document.createElement('TR');
          newIngredSet.appendChild(currentRow);
          currentCell = document.createElement('TD');
          currentCell.colSpan = 3;
          currentCell.align = 'center';
          currentRow.appendChild(currentCell);
          currentCell.appendChild(document.createTextNode(
            "Heading for Ingredient List #" + (ingredSet+1) + ":"));

          currentCell = document.createElement('TD');
          currentCell.rowSpan = 2;
          currentCell.align = 'right';
          currentRow.appendChild(currentCell);
          currentCell.appendChild(document.createTextNode("Use Ingred."));
          currentCell.appendChild(document.createElement('BR'));
          currentCell.appendChild(document.createTextNode("Heading?"));

          currentCell = document.createElement('TD');
          currentRow.appendChild(currentCell);
          currentCellElem = genInputElement("radio", "usehead_" + ingredSet, "yes");
          currentCell.appendChild(currentCellElem);
          currentCell.appendChild(document.createTextNode(' Yes'));
          
          currentRow = document.createElement('TR');
          newIngredSet.appendChild(currentRow);
          currentCell = document.createElement('TD');
          currentCell.colSpan = 3;
          currentRow.appendChild(currentCell);
          currentCellElem = genInputElement("text", "heading_" + ingredSet, "");
          currentCellElem.size = 42;
          currentCellElem.maxLength = 40;
          currentCell.appendChild(currentCellElem);
          currentCell = document.createElement('TD');
          currentRow.appendChild(currentCell);
          currentCellElem = genInputElement("radio", "usehead_" + ingredSet, "no");
          currentCellElem.defaultChecked = true;
          currentCell.appendChild(currentCellElem);
          currentCell.appendChild(document.createTextNode(' No'));
          
          currentRow = document.createElement('TR');
          newIngredSet.appendChild(currentRow);
          currentCell = document.createElement('TD');
          currentCell.colSpan = 5;
          currentRow.appendChild(currentCell);
          currentCell.appendChild(document.createTextNode('Ingredients:'));

          currentRow = document.createElement('TR');
          currentRow.vAlign = 'bottom';
          newIngredSet.appendChild(currentRow);
          currentCell = document.createElement('TD');
          currentCell.align = 'center';
          currentRow.appendChild(currentCell);
          currentCell.appendChild(document.createTextNode('Quantity'));
          currentCell = document.createElement('TD');
          currentCell.align = 'center';
          currentRow.appendChild(currentCell);
          currentCell.appendChild(document.createTextNode('Unit'));
          currentCell = document.createElement('TD');
          currentCell.colSpan = 2;
          currentCell.align = 'center';
          currentRow.appendChild(currentCell);
          currentCell.appendChild(document.createTextNode('Ingredient Name'));
          currentCell = document.createElement('TD');
          currentCell.align = 'center';
          currentRow.appendChild(currentCell);
          currentCell.appendChild(document.createTextNode('Preparation'));
          currentCell.appendChild(document.createElement('BR'));
          currentCell.appendChild(document.createTextNode('Method'));
          
          // Insert TBODY for "Add Ingredient" button for ingredient set
          // at correct spot in recipe table
          var addIngredButton = document.createElement('TBODY');
          recipeTable.insertBefore(addIngredButton, addIngredSetSection);

          currentRow = document.createElement('TR');
          addIngredButton.appendChild(currentRow);
          currentCell = document.createElement('TD');
          currentCell.colSpan = 3;
          currentRow.appendChild(currentCell);
          currentCellElem = createButton("Add Ingredient", 
            'addIngredRow("ingredSet' + ingredSet + '", ' + ingredSet + ')');
          currentCell.appendChild(currentCellElem);
          currentCell = document.createElement('TD');
          currentCell.colSpan = 2;
          currentCell.align = "right";
          currentRow.appendChild(currentCell);
          currentCellElem = createButton("Remove Last Ingredient", 
            'delIngredRow("ingredSet' + ingredSet + '", ' + ingredSet + ')');
          currentCell.appendChild(currentCellElem);

          numIngredSets++;
        }

        function setCollegeNameDefault(currentNameElem)
        {
          var collegeNameElem = findUniqueNamedElem("author_cname");
          collegeNameElem.value = currentNameElem.value;
        }

        function checkServings(currentServingsElem)
        {        
          if (parseInt(currentServingsElem.value) > 9)
          {
            var confirmServings = 
              prompt("You have entered a servings value greater than 9.\n" +
                "Please confirm by re-entering this value here.");
            if (parseInt(confirmServings) != parseInt(currentServingsElem.value))
            {
              alert("Please re-enter the value for servings");
              currentServingsElem.value = "";
            }
          }
        }

        function checkPrepTime()
        {
          var hrsElem = findUniqueNamedElem("prep_hours");
          var minsElem = findUniqueNamedElem("prep_mins");
          if ((minsElem.value.match(/^\s*\d*\s*$/) && (parseInt(minsElem.value, 10) >= 0))
            && (hrsElem.value.match(/^\s*\d*\s*$/) && (parseInt(hrsElem.value, 10) >= 0)))
          {
            var pMins = parseInt(minsElem.value, 10);
            var pHrs = parseInt(hrsElem.value, 10);
            pHrs += (pMins - (pMins % 60)) / 60; // Int div by 60
            pMins = pMins % 60;
            hrsElem.value = pHrs;
            minsElem.value = pMins;
            var totalPrep = pHrs * 60 + pMins;
            if (totalPrep > 180)
            {
              var confirmTotalPrep = 
                prompt("You have entered a total of " + totalPrep + 
                  " minutes of preparation time.\n" +
                  "Please confirm by re-entering this total here:");
              if (parseInt(confirmTotalPrep) != totalPrep)
              {
                alert("Please re-enter the preparation time");
                hrsElem.value = "";
                minsElem.value = "";
              }
            }
          }
        }

        function findFirstNamedElem(elemName)
        /*
          Returns the first element named elemName in the document. 
          Throws an exception if there are no elements with that name. */
        {
          var allMatches = document.getElementsByName(elemName);
          if (allMatches.length < 1)
            throw "FieldNameNotAvailableException";
          return allMatches.item(0);
        }

        function findUniqueNamedElem(elemName)
        /*
          Returns the unique element named elemName in the document.
          Throws an exception if there is more than one element with
          that name. */
        {
          var allMatches = document.getElementsByName(elemName);
          if (allMatches.length > 1)
            throw "FieldNameNotUniqueException";
          return allMatches.item(0);
        }

        function countChecks(checkboxName)
        /* Returns the number of checked checkboxes or radio buttons named checkboxName. */
        {
          var checkCount = 0;
          var allMatches = document.getElementsByName(checkboxName);
          for (var count = 0; count < allMatches.length; count++)
            if (allMatches.item(count).type.match( /checkbox|radio/ ) &&
              allMatches.item(count).checked)
              checkCount++;
          return checkCount;
        }

        function colorChecks(checkboxName, checkboxColor)
        /* Changes the background color of all checkboxes named checkboxName
           to checkboxColor. */
        {
          var allMatches = document.getElementsByName(checkboxName);
          for (var count = 0; count < allMatches.length; count++)
            if (allMatches.item(count).type.match( /checkbox|radio/ ))
              allMatches.item(count).parentNode.style.backgroundColor = checkboxColor;
        }

        function minIntCheck(fieldName, minIntVal, errText, errorList)
        /* Check if value of the fieldName element is an integer greater than or equal
           to minIntVal.  If not, add errText to errorList, change the background color of
           the element to the error color, and return false.  Otherwise, change the 
           background color of the element to the non-error color and return true. */
        {
          var checkElem = findUniqueNamedElem(fieldName);
          if (checkElem.value.match(/^\s*\d*\s*$/) 
            && (parseInt(checkElem.value, 10) >= minIntVal))
          {
            checkElem.style.backgroundColor = NON_ERROR_COLOR;
            return true;
          }
          else
          {
            checkElem.style.backgroundColor = ERROR_COLOR;
            errorList.appendChild(createListItem(errText));
            return false;
          }
        }

        function checkHeading(ingredSet, errorList)
        {
          var hasNoErrors = true;
          var useCurHeading = eval("document.recipeForm.usehead_" + ingredSet)[0].checked;
          var curHeadingIsBlank = isBlank("heading_" + ingredSet);
          var checkElem = eval("document.recipeForm.heading_" + ingredSet);
          if (useCurHeading)
          {
            if (curHeadingIsBlank)
            {
              checkElem.style.backgroundColor = ERROR_COLOR;
              errorList.appendChild(createListItem("Ingredient list #" + (ingredSet + 1) + 
                " is using a heading, but its heading is blank"));
              hasNoErrors = false;
            }
            else
            {
              checkElem.style.backgroundColor = NON_ERROR_COLOR;
            }                   
          }
          else
          {
            if (!curHeadingIsBlank)
            {
              checkElem.style.backgroundColor = ERROR_COLOR;
              errorList.appendChild(createListItem("Ingredient list #" + (ingredSet + 1) +
                " is not using a heading, but its heading has a value"));
              hasNoErrors = false;
            }
            else
            {
              checkElem.style.backgroundColor = NON_ERROR_COLOR;
            }                   
          }
          return hasNoErrors;
        }

        function checkIngredients(ingredSet, errorList)
        {
          var hasNoErrors = true;
          var qtyErrExists = false, unitErrExists = false, nameErrExists = false;
          for (var ingredNum = 0; ingredNum <= curIngredNum[ingredSet]; ingredNum++)
          {
            // Check for errors in quantity field
            var curIngredQty = eval("document.recipeForm.qty_" + ingredSet + "_" + ingredNum);
            if (curIngredQty.value.match(
              /^\s*((\d+(\s+\d+\s*\/\s*\d+)?)|(\d+\s*\/\s*\d+)|(\d*\.\d+)|several|few|many|scant|\s*)\s*$/i ))
            {
              curIngredQty.style.backgroundColor = NON_ERROR_COLOR;
              if (curIngredQty.value.match(/^\s*$/))
                curIngredQty.value = "0";
            }
            else
            {
              curIngredQty.style.backgroundColor = ERROR_COLOR;
              qtyErrExists = true;
              hasNoErrors = false;
            }

            // Check for errors in unit field
            var curIngredUnit = eval("document.recipeForm.unit_" + ingredSet + "_" + ingredNum);
            if (isBlank("unit_" + ingredSet + "_" + ingredNum))
            {
              curIngredUnit.style.backgroundColor = ERROR_COLOR;
              unitErrExists = true;
              hasNoErrors = false;
            }
            else
            {
              curIngredUnit.style.backgroundColor = NON_ERROR_COLOR;
            }

            // Check for errors in ingredient name field
            var curIngredName = eval("document.recipeForm.ingred_" + ingredSet + "_" + ingredNum);
            if (isBlank("ingred_" + ingredSet + "_" + ingredNum))
            {
              curIngredName.style.backgroundColor = ERROR_COLOR;
              nameErrExists = true;
              hasNoErrors = false;
            }
            else
            {
              curIngredName.style.backgroundColor = NON_ERROR_COLOR;
            }
          }        
          if (qtyErrExists)
            errorList.appendChild(createListItem("At least one quantity in ingredient list #" +
              (ingredSet + 1) + " is malformed."));
          if (unitErrExists)
            errorList.appendChild(createListItem("At least one unit in ingredient list #" +
              (ingredSet + 1) + " is blank."));
          if (nameErrExists)
            errorList.appendChild(createListItem("At least one name in ingredient list #" +
              (ingredSet + 1) + " is blank."));
          
          return hasNoErrors;
        }
        
        function isBlank(fieldName)
        /* Returns true if the form field named fieldName is blank, and
           returns false otherwise.  Throws an exception if there is more
           than one element with that name, or if that element is not an
           input field. */ 
        {
          var matchingFieldElem = findUniqueNamedElem(fieldName);
          if (matchingFieldElem.tagName.match( /INPUT|TEXTAREA/ ))
            return matchingFieldElem.value.match( /^\s*$/ );
          else if (matchingFieldElem.tagName == "SELECT")
            /* When the VALUE attribute is not used in an OPTION element, IE
               considers it to be blank until the submit actually occurs, so
               we must match on the text value of the selected OPTION instead. */
            return matchingFieldElem.options.item(matchingFieldElem.selectedIndex).text.match( /^\s*$/ );
          else
            throw "FieldNameNotForInputException";         
        }

        function createListItem(listElemText)
        {
          var listItem = document.createElement("LI");
          listItem.appendChild(document.createTextNode(listElemText));
          return listItem;
        }

        function checkForm()
        {
          var hasNoErrors = false;
          if (submittedBySubmitButton)
          {
            var checkElem;
            hasNoErrors = true;
            var errorMsg = document.createDocumentFragment();
            errorMsg.appendChild(document.createTextNode(
              "Please correct the fields in red"));
            var errorList = document.createElement("UL");
            errorList.style.fontSize = "smaller";
            errorMsg.appendChild(errorList);
            try
            {
              var nonBlankFields = ["title", "author_fname", "author_lname", "author_cname",
                "author_class"];
              var nonBlankMsgs = ["Title of recipe is blank", "Author's first name is blank",
                "Author's current last name is blank", "Author's last name in college is blank",
                "Author's class year is blank"];
              for (var subscript in nonBlankFields)
              {
                checkElem = findUniqueNamedElem(nonBlankFields[subscript]);
                if (isBlank(nonBlankFields[subscript]))
                {
                  checkElem.style.backgroundColor = ERROR_COLOR;
                  errorList.appendChild(createListItem(nonBlankMsgs[subscript]));
                  hasNoErrors = false;
                }
                else
                {
                  checkElem.style.backgroundColor = NON_ERROR_COLOR;
                }
              }
              checkElem = findFirstNamedElem("category");
              if (countChecks("category") < 1)
              {
                colorChecks("category", ERROR_COLOR);
                errorList.appendChild(createListItem("No categories have been chosen"));
                hasNoErrors = false;
              }
              else
              {
                colorChecks("category", NON_ERROR_COLOR);
              }
              if (!minIntCheck("servings", 1, "Servings is not a positive integer", errorList))
                hasNoErrors = false;
              if (!minIntCheck("prep_hours", 0, 
                "Preparation hours is not a non-negative integer", errorList))
                hasNoErrors = false;
              if (!minIntCheck("prep_mins", 0, 
                "Preparation minutes is not a non-negative integer", errorList))
                hasNoErrors = false;
              var totTime = parseInt(findUniqueNamedElem("prep_hours").value) +
                parseInt(findUniqueNamedElem("prep_mins").value);
              if (totTime <= 0)
              {
                checkElem = findUniqueNamedElem("prep_hours");
                checkElem.style.backgroundColor = ERROR_COLOR;
                checkElem = findUniqueNamedElem("prep_mins");
                checkElem.style.backgroundColor = ERROR_COLOR;
                errorList.appendChild(createListItem(
                  "Total prep time in minutes and seconds is not greater than zero"));
                hasNoErrors = false;
              }
              for (var ingredSet=0; ingredSet < numIngredSets; ingredSet++)
              {
                if (!checkHeading(ingredSet, errorList))
                  hasNoErrors = false;
                if (!checkIngredients(ingredSet, errorList))
                  hasNoErrors = false;
              }
              checkElem = findUniqueNamedElem("directions");
              if (checkElem.value.match( /Enter directions here/ ) || isBlank("directions"))
              {
                checkElem.style.backgroundColor = ERROR_COLOR;
                errorList.appendChild(createListItem(
                  'Directions is blank or still contains "Enter directions here"'));
                hasNoErrors = false;
              }
              else
              {
                checkElem.style.backgroundColor = NON_ERROR_COLOR;
              }
            }
            catch (exception)
            {
              switch (exception)
              {
                case "FieldNameNotUniqueException":
                case "FieldNameNotForTextInputException":
                  alert("Exception thrown: " + exception);
                  hasNoErrors = false;
                  break;
                default: // Allow other exceptions to bubble up
              }
            }
            if (!hasNoErrors)
            {
              var errMsgSections = ["topErrorMsg"];
              for (var subscript in errMsgSections)
              {
                var errMsgSection = document.getElementById(errMsgSections[subscript]);
                while (errMsgSection.firstChild != null)
                  errMsgSection.removeChild(errMsgSection.firstChild);
                errMsgSection.style.color = "red";
                errMsgSection.style.fontStyle = "italic";
                errMsgSection.style.fontSize = "medium";
                errMsgSection.style.fontWeight = "bold";
                errMsgSection.appendChild(errorMsg);
              }
              window.scrollTo(0,0);
            }
          }
          submittedBySubmitButton = false; // Reset submit button
          return hasNoErrors;
        }
        
        function reloadPage()
        {
          if (confirm("Are you sure you want to clear the form?"))
          {
            window.location.reload();
            return true;
          }
          else
            return false;
        }

      // -->
    </SCRIPT>
  </HEAD>
  <BODY ONLOAD='init("<?php echo $formval_rawRecipePath; ?>")'>
    <DIV ID="topErrorMsg"></DIV>
    <FORM ACTION="add_recipe.php" METHOD="post" NAME="recipeForm" ONSUBMIT="return checkForm()"
      ONRESET="return reloadPage()">
      <INPUT TYPE="hidden" NAME="recipeId" VALUE="<?php echo $formval_recipeId; ?>">
      <INPUT TYPE="hidden" NAME="teammateName" VALUE="<?php echo $formval_teammateName; ?>">
      <INPUT TYPE="hidden" NAME="password" VALUE="<?php echo $formval_password; ?>">
      <TABLE ID="recipeTable" BORDER=0 WIDTH=495>
        <TBODY>
          <TR>
            <TD>Recipe Title:</TD>
            <TD COLSPAN=4><INPUT TYPE="text" NAME="title" SIZE=62 MAXLENGTH=60
              VALUE="<?php echo $formval_initTitle; ?>"></TD>
          </TR>
          <TR><TD COLSPAN=5><HR></TD></TR>
          <TR>
            <TD ROWSPAN=4>Author Info:</TD>

            <TD COLSPAN=2>First Name:</TD>
            <TD COLSPAN=2><INPUT TYPE="text" NAME="author_fname" SIZE=27
              VALUE="<?php echo $formval_initAuthFName; ?>"></TD>
          </TR>
          <TR>
            <TD COLSPAN=2>Last Name Currently:</TD>
            <TD COLSPAN=2><INPUT TYPE="text" NAME="author_lname" SIZE=27
              ONCHANGE="setCollegeNameDefault(this); return true;"
              VALUE="<?php echo $formval_initAuthLName; ?>"></TD>
          </TR>
          <TR>
            <TD COLSPAN=2>Last Name in College:</TD>
            <TD COLSPAN=2><INPUT TYPE="text" NAME="author_cname" SIZE=27
              VALUE="<?php echo $formval_initAuthLName; ?>"></TD>
          </TR>
          <TR>
            <TD COLSPAN=2>Class Year:</TD>
            <TD>
              <SCRIPT LANGUAGE="JavaScript">
                <!--
                  classSelect(1925,2004);
                // -->
              </SCRIPT>

            </TD>
          </TR>
          <TR><TD COLSPAN=5><HR></TD></TR>
          <TR>
            <TD WIDTH="20%"><INPUT TYPE="radio" NAME="category" VALUE="appetizer">
              appetizer</TD>
            <TD WIDTH="20%"><INPUT TYPE="radio" NAME="category" VALUE="beverage">
              beverage</TD>

            <TD WIDTH="20%"v><INPUT TYPE="radio" NAME="category" VALUE="bread">
              bread</TD>
            <TD WIDTH="20%"><INPUT TYPE="radio" NAME="category" VALUE="condiment">
              condiment</TD>
            <TD WIDTH="20%"><INPUT TYPE="radio" NAME="category" VALUE="dessert">
              dessert</TD>
          </TR>

          <TR>
            <TD><INPUT TYPE="radio" NAME="category" VALUE="main dish"> main dish</TD>
            <TD><INPUT TYPE="radio" NAME="category" VALUE="salad"> salad</TD>
            <TD><INPUT TYPE="radio" NAME="category" VALUE="sauce"> sauce</TD>
            <TD><INPUT TYPE="radio" NAME="category" VALUE="soup"> soup</TD>

            <TD><INPUT TYPE="radio" NAME="category" VALUE="vegetable"> vegetable</TD>
          </TR>
          <TR><TD COLSPAN=5><HR></TD></TR>
          <TR>
            <TD>Servings:</TD>
            <TD><INPUT TYPE="text" NAME="servings" SIZE=10 MAXLENGTH=2
              ONCHANGE="return checkServings(this)"></TD>
            <TD COLSPAN=2 ALIGN="right">Preparation Time:</TD>

            <TD>
              <INPUT TYPE="text" NAME="prep_hours" VALUE="0"SIZE=2 MAXLENGTH=2
                ONCHANGE="checkPrepTime()">
              <INPUT TYPE="text" NAME="prep_mins" VALUE="00"SIZE=2 MAXLENGTH=2
                ONCHANGE="checkPrepTime()">
            </TD>
          </TR>
        </TBODY>
        <TBODY ID="addIngredSetButton">
          <TR>
            <TD COLSPAN=3>
              <INPUT TYPE="button" VALUE="Add another set of ingredients"
                ONCLICK="addCompleteIngredSet()">
            </TD>
            <TD COLSPAN=2 ALIGN="right">
              <INPUT TYPE="button" VALUE="Remove last set of ingredients"
                ONCLICK="delCompleteIngredSet()"
                STYLE="font-size: xx-small">
            </TD>
          </TR>
        </TBODY>
        <TBODY ID="directionsArea">
          <TR><TD COLSPAN=5>
            <HR>
            Directions:<BR>
            <TEXTAREA NAME="directions" ROWS=6 COLS=60>Enter directions here...</TEXTAREA>
          </TD></TR>

          <TR>
            <TD COLSPAN=4>
              <INPUT TYPE="submit" NAME="submit_click" VALUE="Submit this Recipe"  
                ONMOUSEOVER="submittedBySubmitButton = true"
                ONMOUSEOUT="submittedBySubmitButton = false">
            </TD>
            <TD ALIGN="right">
              <INPUT TYPE="reset" VALUE="Start Over">
            </TD>
          </TR>
        </TBODY>
      </TABLE>
    </FORM>
    <?php
      require_once 'recipe_support.php';
      showDemoStep(__FILE__);
      $_SESSION['lastScript'] = basename(__FILE__);
    ?>
  </body>
</html>