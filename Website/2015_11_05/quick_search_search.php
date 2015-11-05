<?php
  //Needs to be the first thing that happens - otherwise the Meta words creats an error by sending info to the session first
  session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <title>ZosTDB | Quick Search</title>
    <!-- style sheet link -->
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
    <?php include 'ie_elements.php'; ?>
    <?php include_once("analyticstracking.php") ?>
    <style type="text/css">     
      select { width:200px; }
    </style>
    <?php 
      include 'ie_elements.php';
      include 'analyticstracking.php';
      include 'functions.php';  
    ?>
  </head>
<body>
  <?php include 'header.php'; ?>
    <div id="navigation_menu_wrapper"> 
      <div id="navigation_menu">  
        <?php
          //Names the page which is used for highlighting which page is current in the top menu bar
          $This_Page = "quick_search"; 
          include 'top_menu_bar.php'; 
        ?>
      </div> <!-- end of menu -->
    </div> <!-- end of menu wrapper -->
    <?php 
      //Start of the PHP code:
      //Step 1:  Connect to the database
      //Step 2:  Check to see if a page number has been passed and validate thats its numeric

      //Step 1.
      //connect to the database
      include 'connect.php';

      //Step 2.
      //Checking to see if a page number has been passed
      if (isset($_GET["page"])) { 
        $page = $_GET["page"];
        //If a page value has been passed, check to make sure its a number
        if (is_numeric($page)){
          //do nothing as its a valid number
        }else{
          //the page number isnt a number - possible interjection or error ?>
          <script type="text/javascript">
            alert("The page number used isnt numeric - You will now be directed to the default quick search page");
            location.href="quick_search.php"
          </script> <?php
        }
      }else{ 
        //the page has been loaded for the first time (No number has been passed), so display page 1
        $page=1; 
      }
        //Creating a start variable, which is used to control the amount of records displayed per page
        $records_per_page = 30;
        $start_from = ($page-1) * $records_per_page;

      //------------------------------------------------------------------------
      // Checks to see which submit button was pressed
      //------------------------------------------------------------------------
      //The Gene Ontologies Annotations button was pressed
      if (isset($_POST['Submit_Gene_Ontology_Annotations'])) {
        $_SESSION["Query_Is"] = "Gene_Ontology_Annotations";
        $Gene_Ontology_Annotations = mysqli_real_escape_string($connection, $_POST['Gene_Ontology_Annotations']);
        $_SESSION["Gene_Ontology_Annotations_Dropdown_Value"] = $Gene_Ontology_Annotations;
      }
      //The InterPro annotations button was pressed
      if (isset($_POST['Submit_Interpro_Annotations'])) {
        $_SESSION["Query_Is"] = "Interpro_Annotations";
        $Interpro_Annotations = mysqli_real_escape_string($connection, $_POST['Interpro_Annotations']);
        $_SESSION["Interpro_Annotations_Dropdown_Value"] = $Interpro_Annotations;
      }
      //The Transcription factors button was pressed
      if (isset($_POST['Submit_Transcription_Factor_Family'])) {
        $_SESSION["Query_Is"] = "Transcription_Factor_Family";
        $Transcription_Factor_Family = mysqli_real_escape_string($connection, $_POST['Transcription_Factor_Family']);
        $_SESSION["Transcription_Factor_Family_Dropdown_Value"] = $Transcription_Factor_Family;
      }
      //------------------------------------------------------------------------
      // Depending on which submit button was pressed - perform SQL query
      //------------------------------------------------------------------------
      //The Gene Ontologies Annotations button was pressed
      if ($_SESSION["Query_Is"] == "Gene_Ontology_Annotations"){
        $Gene_Ontology_Annotations_Search = substr($_SESSION["Gene_Ontology_Annotations_Dropdown_Value"],0,10); 
        $Gene_Ontology_Annotations = $_SESSION["Gene_Ontology_Annotations_Dropdown_Value"];
        //the select all statement with the limits of first record and how many records
        $strSQL = "SELECT * FROM functional_annotations 
                  WHERE gene_ontology_annotations LIKE '%$Gene_Ontology_Annotations_Search%' 
                  ORDER BY Unigene_Num ASC 
                  LIMIT $start_from, $records_per_page";
        //variable to capture the total records from the search sql           
        $strSQL_pages = "functional_annotations WHERE gene_ontology_annotations LIKE '%$Gene_Ontology_Annotations_Search%'";
      }
      //The IP Annotations button was pressed
      if ($_SESSION["Query_Is"] == "Interpro_Annotations"){
        $Limit = 30;
        $Interpro_Annotations = $_SESSION["Interpro_Annotations_Dropdown_Value"];
        $Interpro_Annotations_Search = substr($_SESSION["Interpro_Annotations_Dropdown_Value"],0,9);
        //the select all statement with the limits of first record and how many records
        $strSQL = "SELECT * FROM functional_annotations 
                  WHERE Interpro_Annotations LIKE '%$Interpro_Annotations_Search%' 
                  ORDER BY Unigene_Num ASC 
                  LIMIT $start_from, $records_per_page";
        //variable to capture the total records from the search sql           
        $strSQL_pages = "functional_annotations WHERE Interpro_Annotations LIKE '%$Interpro_Annotations_Search%'";
      }
      //The Transcription Factor Family button was pressed
      if ($_SESSION["Query_Is"] == "Transcription_Factor_Family"){
        //This removes a space at the end of the Family_Dropdown_Value so it can be matched with the array value
        $Transcription_Factor_Family_Length = strlen($_SESSION["Transcription_Factor_Family_Dropdown_Value"]); //gets the length
        $Transcription_Factor_Family_Length_Minus_1 = $Transcription_Factor_Family_Length -1; //removes the last space
        //new string without the space at the end
        $Transcription_Factor_Family_Search = 
          substr($_SESSION["Transcription_Factor_Family_Dropdown_Value"],0,$Transcription_Factor_Family_Length_Minus_1);   
        //the select all statement with the limits of first record and how many records
        $strSQL = "SELECT * FROM transcription_factors 
                  WHERE Family='$Transcription_Factor_Family_Search' 
                  ORDER BY Unigene_Num ASC 
                  LIMIT $start_from, $records_per_page";
        //variable to capture the total records from the search sql           
        $strSQL_pages = "transcription_factors WHERE Family='$Transcription_Factor_Family_Search'";
      }
      //returns the SQL select query from above in the variable $recordset
      $recordset = mysqli_query($connection, $strSQL);
      //-----Recordsets for the dropdown menus-----
      //Store the details for the GO dropdown menu
      $strSQL_Gene_Ontology_Annotations = "SELECT * FROM gene_ontology_annotations_distinct ORDER BY Annotations ASC ";
      //Return the results from the database
      $recordset_Gene_Ontology_Annotations = mysqli_query($connection, $strSQL_Gene_Ontology_Annotations);
      //Store the details for the GO dropdown menu
      $strSQL_Interpro_Annotations = "SELECT * FROM interpro_annotations_distinct ORDER BY Annotations ASC ";
      //Return the results from the database
      $recordset_Interpro_Annotations = mysqli_query($connection, $strSQL_Interpro_Annotations);
      //Store the details for the GO dropdown menu
      $strSQL_Transcription_Factor_Family = "SELECT Distinct Family FROM transcription_factors ORDER BY Family ASC ";
      //Return the results from the database
      $recordset_Transcription_Factor_Family = mysqli_query($connection, $strSQL_Transcription_Factor_Family);
    ?>
    <main>
      <div id="content_wrapper">
        <div id="content">  
          <h3>Quick Search</h3>
          <p>To perform a quick search, please select the value from the dropdown menu and click on the search button</p>
          <!-- Table for the three dropdown menus -->
          <table>
            <tr>
              <td width="200">Gene Ontology Annotation:</td>
              <td>  
                <form action="quick_search_search.php" method="post">
                  <select name="Gene_Ontology_Annotations" id="Gene_Ontology_Annotations">
                    <option value="">Please Select...</option>
                    <?php
                      //--------------------------------------
                      //Populate the GO annotations dropdown 
                      //--------------------------------------
                      //loop through the records
                      if($recordset_Gene_Ontology_Annotations){ //checking to see if the variable exists
                        //will take each row and place it into an array
                        while($row1= mysqli_fetch_assoc($recordset_Gene_Ontology_Annotations)){ 
                          //---------------Checking to see if a value has previously been selected---------------
                          if($Gene_Ontology_Annotations_Search == ""){
                            //no existing values have been selected so load all values into the dropdown box  ?> 
                            <option value="<?php echo $row1['Annotations']; ?> "> <?php echo $row1['Annotations']; ?></option><?php
                          }else{  //there is a value selected in the dropdown box
                            //As the dropdownbox is populated by the while loop above, check to see if the array value = the one selected
                            if($Gene_Ontology_Annotations_Search == $row1['Annotations']){
                              //value is the same so select it  ?>
                              <option value="<?php echo $Gene_Ontology_Annotations_Search;?>" selected > 
                                <?php echo $Gene_Ontology_Annotations_Search;?>
                              </option><?php
                            }else{
                              //value isnt the same - so continue to populate the dropdown  ?>
                              <option value="<?php echo $row1['Annotations']; ?> "> <?php echo $row1['Annotations']; ?></option><?php
                            }
                          }
                        }//end of while
                      }else{  //this happens if the variable $recordset_Gene_Ontology_Annotations dosent exist
                        //display error messages
                        echo'<p>';
                        echo mysqli_error($connection);
                        echo '</p>';
                      }//end of initial if
                    ?>
                  </select>&nbsp; &nbsp;
                </td>
                <td>
                  <input type="submit" value="Search" name="Submit_Gene_Ontology_Annotations">
                </td>
              </tr>
              <tr>
                <td>InterPro Annotations:</td>
                <td>
                  <select name="Interpro_Annotations">
                    <option value="">Please Select...</option>
                    <?php
                      //--------------------------------------
                      //Populate the IP annotations dropdown 
                      //--------------------------------------
                      //loop through the records
                      if($recordset_Interpro_Annotations){ //checking to see if the variable exists
                        while($row2= mysqli_fetch_assoc($recordset_Interpro_Annotations)){ //will take each row and place it into an array
                          //---------------Checking to see if a value has previously been selected---------------
                          if($_SESSION["Interpro_Annotations_Dropdown_Value"] == ""){
                            //no existing values have been selected so load all values into the dropdown box ?>
                            <option value="<?php echo $row2['Annotations']; ?> "> <?php echo $row2['Annotations']; ?></option><?php
                          }else{  //there is a value selected in the dropdown box
                            //As the dropdownbox is populated by the while loop above, check to see if the array value = the one selected
                            if($Interpro_Annotations_Search == $row2['Annotations']){
                              //value is the same so select it  ?>
                              <option value="<?php echo $Interpro_Annotations_Search;?>" selected> 
                                <?php echo $Interpro_Annotations_Search;?>
                              </option><?php
                            }else{
                              //value isnt the same - so continue to populate the dropdown  ?>
                              <option value="<?php echo $row2['Annotations']; ?> "> <?php echo $row2['Annotations']; ?></option><?php
                            }
                          } 
                        }//end of while
                      }else{  //this happens if the variable $recordset_Interpro_Annotations dosent exist
                        //display error messages
                        echo'<p>';
                        echo mysqli_error($connection);
                        echo '</p>';
                      }//end of initial if
                    ?>
                  </select>&nbsp; &nbsp;
                </td>
                <td>
                  <input type="submit" value="Search" name="Submit_Interpro_Annotations">
                </td>
              </tr>
              <tr>
                <td>Transcription Factor Family:</td>
                <td>
                  <select name="Transcription_Factor_Family">
                    <option value="">Please Select...</option>
                    <?php
                      //-------------------------------------------------
                      //Populate the Transcription Factor Family dropdown 
                      //-------------------------------------------------
                      //loop through the records
                      if($recordset_Transcription_Factor_Family){ //checking to see if the variable exists
                        //will take each row and place it into an array
                        while($row3= mysqli_fetch_assoc($recordset_Transcription_Factor_Family)){ 
                          //---------------Checking to see if a value has previously been selected---------------
                          if($_SESSION["Transcription_Factor_Family_Dropdown_Value"] == ""){
                            //no existing values have been selected so load all values into the dropdown box  ?>
                            <option value="<?php echo $row3['Family']; ?> "> <?php echo $row3['Family']; ?></option><?php
                          }else{  //there is a value selected in the dropdown box
                            //As the dropdownbox is populated by the while loop above, check to see if the array value = the one selected
                            if($Transcription_Factor_Family_Search == $row3['Family']){
                              //value is the same so select it  ?>
                              <option value="<?php echo $Transcription_Factor_Family_Search;?>" selected>
                               <?php echo $Transcription_Factor_Family_Search;?>
                              </option><?php
                            }else{
                              //the value dosent match whats been selected, so continue to load in value  ?>
                              <option value="<?php echo $row3['Family']; ?> "> <?php echo $row3['Family']; ?></option><?php
                            }
                          }
                        }//end of while
                      }else{  //this happens if the variable $recordset_Gene_Ontology_Annotations dosent exist
                        //display error messages
                        echo'<p>';
                        echo mysqli_error($connection);
                        echo '</p>';
                        }//end of initial if
                      ?>
                    </select>&nbsp; &nbsp;
                  </td>
                  <td>
                    <input type="submit" value="Search" name="Submit_Transcription_Factor_Family">
                  </td>
                </form>
              </tr>
            </table><!-- End of table for the dropdowns -->
            <br><br>
            <center>
            <!-- 
            ======================================================
            //                                                  //
            // Start of table for the search/query results      //
            //                                                  //
            ======================================================
            -->
            <!-- 
            =======================================================
            // Three if statements to determine which table to create and view the results //
            =======================================================
            -->
            <?php
            //-----------------------------------------------------------
            //Display the search results table 
            //-----------------------------------------------------------
            //Step 1:  Determine which submit button was pressed
            //Step 2:  Loop through the records and display the results

            //Step 1.
            //--------Gene ontoloy annotations--------
            if ($_SESSION["Query_Is"] == "Gene_Ontology_Annotations"){  ?>
              <table class="Generic_Search_Table Zebra">
                <tr>
                  <th style="width:80px"><p class="Center">Zostera<br>Unigenes</p></th>
                  <th style="width:220px"><p class="Center">Gene Ontology<br>Annotations</p></th>
                  <th><p class="Left">Gene<br>Ontology Description</p></th>
                  <th style="width:90px"><p class="Center">InterPro<br>Annotations</p></th>
                  <th style="width:200px"><p class="Center">InterPro<br>Description</p></th>
                </tr>  <?php

                //Step 2.
                //loop through the records
                if($recordset){ //checking to see if the variable exists
                  while($row= mysqli_fetch_assoc($recordset)){ //will take each row and place it into an array  ?>
                    <tr>
                      <td class="Center"><?php echo $row['Unigene_Num']; ?></td>
                      <td class="Center"><?php echo $row['Gene_Ontology_Annotations']; ?></td>
                      <td class="Justify"><?php echo $row['Gene_Ontology_Desc']; ?></td>
                      <td class="Center"><?php echo $row['Interpro_Annotations']; ?></td>
                      <td class="Left"><?php echo $row['Interpro_Desc']; ?></td>
                    </tr>  <?php
                  }
                }else{  //this happens if the variable dosent exist (an error in the search query)
                  //display error messages
                  echo'<p>';
                  echo mysqli_error($connection);
                  echo '</p>';
                } ?>
              </table>
              <br> <?php  
            }

            //Step 1.
            //--------Interpro annotations annotations--------
            if ($_SESSION["Query_Is"] == "Interpro_Annotations"){  ?>
              <table class="Generic_Search_Table Zebra">
                <tr>
                  <th style="width:80px"><p class="Center">Zostera<br>Unigenes</p></th>
                  <th style="width:220px"><p class="Center">Gene Ontology<br> Annotations</p></th>
                  <th><p class="Left">Gene<br>Ontology Description</p></th>
                  <th style="width:90px"><p class="Center">InterPro<br>Annotations</p></th>
                  <th style="width:200px"><p class="Center">InterPro<br>Description</p></th>
                </tr>  <?php
                //Step 2.
                //loop through the records
                if($recordset){ //checking to see if the variable exists
                  while($row= mysqli_fetch_assoc($recordset)){ //will take each row and place it into an array  ?>
                    <tr>
                      <td class="Center"><?php echo $row['Unigene_Num']; ?></td>
                      <td class="Center"><?php echo $row['Gene_Ontology_Annotations']; ?></td>
                      <td class="Justify"><?php echo $row['Gene_Ontology_Desc']; ?></td>
                      <td class="Center"><?php echo $row['Interpro_Annotations']; ?></td>
                      <td class="Left"><?php echo $row['Interpro_Desc']; ?></td>
                    </tr> <?php
                  }
                }else{  //this happens if the variable dosent exist (an error in the search query)
                  //display error messages
                  echo'<p>';
                  echo mysqli_error($connection);
                  echo '</p>';
                }  ?>
              </table>
              <br> <?php  
            }

            //Step 1.
            //--------Transcription factor family--------
            if ($_SESSION["Query_Is"] == "Transcription_Factor_Family"){  ?>
              <table class="Generic_Search_Table Zebra">
                <tr>
                  <th style="width:80px"><p class="Center">Zostera<br>Unigenes</p></th>
                  <th><p class="Left"><br>Domains</p></th>
                  <th style="width:120px"><p class="Center"><br>Family</p></th>
                  <th style="width:250px"><p class="Left"><br>Family Type</p></th>
                </tr>  <?php
                //Step 2.
                //loop through the records
                if($recordset){ //checking to see if the variable exists
                  while($row= mysqli_fetch_assoc($recordset)){ //will take each row and place it into an array  ?>
                    <tr>
                      <td class="Center_Mid"><?php echo $row['Unigene_Num']; ?></td>
                      <td class="Left_Mid"><?php echo $row['Domains']; ?></td>
                      <td class="Center_Mid"><?php echo $row['Family']; ?></td>
                      <td class="Left_Mid"><?php echo $row['Family_Type']; ?></td>
                    </tr>  <?php
                  }
                }else{  //this happens if the variable dosent exist (an error in the search query)
                  //display error messages
                  echo'<p>';
                  echo mysqli_error($connection);
                  echo '</p>';
                }  ?>
              </table>
              <br>
              <?php
            }
            //---------------------------------------------
            //Display the pagination results and goto page box and update button 
            //--------------------------------------------- 
            //pagination function
            echo pagination($strSQL_pages,$records_per_page,$page,$url='?'); ?>
          </center>
          <br>
          <div id="bottom_spacer_20"></div>
        </div> <!-- end of content -->
      </div> <!-- end of content wrapper -->
    </main>
  <div id="footer_wrapper"> 
    <?php include 'footer.php'; ?>
  </div> <!-- end of footer wrapper-->
</body>
<?php
  //close the connection
  mysqli_close($connection);
?>
</html>
