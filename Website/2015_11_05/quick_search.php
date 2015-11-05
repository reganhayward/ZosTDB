<?php
  // Start and store session variables
  //Needs to be the first thing that happens - otherwise the Meta words creats an error by sending info to the session first
  session_start();
  $_SESSION["Gene_Ontology_Annotations_Dropdown_Value"] = ""; 
  $_SESSION["Interpro_Annotations_Dropdown_Value"] = "";
  $_SESSION["Transcription_Factor_Family_Dropdown_Value"] = "";
  $_SESSION["Query_Is"] = "";
  $_SESSION["SQL_All_Results"] = "";
  $_SESSION["Recordset"] = "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <meta name="description" content="Zostera muelleri - Quick Search Page" />
    <meta name="keywords" content="ZosTDB, Zostera muelleri Transcriptomics, Southern Hemisphere Seagrass, Zostera muelleri SSR, Conserved Orthologs, Zostera muelleri BLAST" />
    <title>ZosTDB | Quick Search</title>
    <!-- style sheet link -->
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
    <?php 
      include 'ie_elements.php';
      include 'analyticstracking.php'
    ?>
    <style type="text/css">     
      select { width:200px; }
    </style>
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
    //Step 2:  Load and store data for the dropdown boxes

    //Step 1.
    //connect to the database
    include 'connect.php';

    //Step 2.
    //Store the details for the GO annotations dropdown menu
    $strSQL_Gene_Ontology_Annotations = "SELECT * FROM gene_ontology_annotations_distinct ORDER BY Annotations ASC ";
    //Return the results from the database
    $recordset_Gene_Ontology_Annotations = mysqli_query($connection, $strSQL_Gene_Ontology_Annotations);
    //Store the details for the IP annotations dropdown menu
    $strSQL_Interpro_Annotations = "SELECT * FROM interpro_annotations_distinct ORDER BY Annotations ASC ";
    //Return the results from the database
    $recordset_Interpro_Annotations = mysqli_query($connection, $strSQL_Interpro_Annotations);
    //Store the details for the Family dropdown menu
    $strSQL_Transcription_Factor_Family = "SELECT Distinct Family FROM transcription_factors ORDER BY Family ASC ";
    //Return the results from the database
    $recordset_Transcription_Factor_Family = mysqli_query($connection, $strSQL_Transcription_Factor_Family);
  ?>
  <main>
    <div id="content_wrapper">
      <div id="content">  
        <!-- Table for the three dropdown menus and the web stats--> 
        <table>
          <tr>
            <td><h3>Quick Search</h3></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="4"><p>To perform a quick search, please select the value from the dropdown menu and click on the search button</p></td>
            <td rowspan="4">
              
            </td>
          </tr>

        
          
          
            <tr>
              <td width="200">&nbsp;&nbsp;Gene Ontology Annotation:</td>
              <td>  
                <form action="quick_search_search.php" method="post">
                  <select name="Gene_Ontology_Annotations">
                    <option value="">Please Select...</option>
                    <?php
                      //===================================================================//
                      // Loading the values into the Gene dropdown              //
                      //===================================================================//
                      //loop through the records
                      if($recordset_Gene_Ontology_Annotations){ //checking to see if the variable exists
                        //will take each row and place it into an array
                        while($row1= mysqli_fetch_assoc($recordset_Gene_Ontology_Annotations)){ 
                          //Load each value into the dropdown ?> 
                          <option value="<?php echo $row1['Annotations']; ?> "> <?php echo $row1['Annotations']; ?></option><?php
                        }
                      }else{  //this happens if the variable $recordset_Gene_Ontology_Annotations dosent exist
                        //display error messages
                        echo'<p>';
                        echo mysqli_error($connection);
                        echo '</p>';
                      }//end of initial if
                    ?>
                  </select>
                </td>
                <td>
                  &nbsp; &nbsp;
                  <input type="submit" value="Search" name="Submit_Gene_Ontology_Annotations">
                </td>
                <td width="300"></td>
              </tr>
              <tr>
                <td>&nbsp;&nbsp;InterPro Annotations:</td>
                <td>
                  <select name="Interpro_Annotations">
                    <option value="">Please Select...</option>
                    <?php
                      //===================================================================//
                      // Loading the values into the Gene dropdown              //
                      //===================================================================//
                      //loop through the records
                      if($recordset_Interpro_Annotations){ //checking to see if the variable exists
                        while($row2= mysqli_fetch_assoc($recordset_Interpro_Annotations)){ //will take each row and place it into an array  
                          //Load each value into the dropdown ?>
                          <option value="<?php echo $row2['Annotations']; ?> "> <?php echo $row2['Annotations']; ?></option><?php
                        }
                      }else{  //this happens if the variable $recordset_Interpro_Annotations dosent exist
                        //display error messages
                        echo'<p>';
                        echo mysqli_error($connection);
                        echo '</p>';
                      }//end of initial if
                    ?>
                  </select>
                </td>
                <td>
                  &nbsp; &nbsp;
                  <input type="submit" value="Search" name="Submit_Interpro_Annotations">
                </td>
                <td></td>
              </tr>
              <tr>
                <td>&nbsp;&nbsp;Transcription Factor Family:</td>
                <td>
                  <select name="Transcription_Factor_Family">
                    <option value="">Please Select...</option>
                    <?php
                      //=================================================================//
                      // Loading the values into the dropdown                                 //
                      //=================================================================//
                      //loop through the records
                      if($recordset_Transcription_Factor_Family){ //checking to see if the variable exists
                        //will take each row and place it into an array  
                        while($row3= mysqli_fetch_assoc($recordset_Transcription_Factor_Family)){ 
                          //Load each value into the dropdown ?>
                          <option value="<?php echo $row3['Family']; ?> "> <?php echo $row3['Family']; ?></option><?php
                        }
                      }else{  //this happens if the variable $recordset_Transcription_factor dosent exist
                        //display error messages
                        echo'<p>';
                        echo mysqli_error($connection);
                        echo '</p>';
                      }
                    ?>
                  </select>
                </td>
                <td>
                  &nbsp; &nbsp;
                  <input type="submit" value="Search" name="Submit_Transcription_Factor_Family">
                </td>
                <td>&nbsp;</td>
              </form>
            </tr>
          </table><!-- End of table for the drop downs -->
          <br><br>
          <h3>BLAST</h3>
          <p>
            To BLAST against assembled transcriptome sequences, please 
            <script language="JavaScript">
              document.write('<a href="' + window.location.protocol + '//' + window.location.hostname + ':8080'  + '" >click here</a> ' );
            </script>
            <br><br><br>
            <h3>Export</h3>
            <p>Type or paste in <i>Z. muelleri</i> unigenes separated by new lines, to extract the corresponding transcripts and peptides.<br>
            <form target="_blank" action="export.php" method="post">
              <textarea class="export_box" name="export_box" id="export_box" placeholder="For example: &#13;&#10;SG00001 &#13;&#10;SG00002"></textarea><br>
              <input type="submit" value="&nbsp; Export &nbsp;" onclick="return export_check();">
            </form>
            <script type="text/javascript">
              function export_check(){
                var export_box_content = document.getElementById("export_box").value;
                //check to see if there is a value
                if (export_box_content == ""){
                  alert("Please type in at least one unigene such as SG000001");
                  return false;
                }
                //check that the string is a minimum length of 7
                if (export_box_content.length < 8){
                  alert("Unigenes should be 7 characters long");
                  return false;
                }else{
                  //check to see if the first two characters are SG
                  export_box_content_uc = export_box_content.toUpperCase();
                  export_box_content_sg = export_box_content_uc.substring(0,2);
                  if (export_box_content_sg != "SG"){
                    alert("Unigenes should start with SG");
                    return false;
                  }
                }
              }
            </script>
            <br><br><br>
            </p>
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
