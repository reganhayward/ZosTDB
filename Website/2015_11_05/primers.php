<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <title>ZosTDB | Design Primers</title>
    <!-- style sheet link -->
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
    <?php 
      include 'ie_elements.php'; 
      include 'analyticstracking.php';
    ?>
  </head>
<body>
  <?php include 'header.php'; ?>
    <div id="navigation_menu_wrapper"> 
      <div id="navigation_menu">  
        <?php
          //Names the page which is used for highlighting which page is current in the top menu bar
          $This_Page = "ssr"; 
          include 'top_menu_bar.php';
          include 'analyticstracking.php'; 
        ?>
      </div> <!-- end of menu -->
    </div> <!-- end of menu wrapper -->
  <main>
    <div id="content_wrapper">
      <div id="content">  
        <?php
          //Store the passed unigene number
          $Unigene_No=$_GET["ID"];
          //Code making sure the Unigene_No name has been passed correctly. Basically a sefety check for interjection
          //Step 1:  Make sure the first two characters are SG
          //Step 2:  Make sure the length is 8

          //Step 1.
          //Testing for the first two characters
          $First_Two_Chars_Test_Unigene_No = mb_substr($Unigene_No, 0,2);
          if ($First_Two_Chars_Test_Unigene_No == "SG") {

            //Step 2.
            //Testing for the length
            $Length_Of_Unigene_No = strlen($Unigene_No);
            if ($Length_Of_Unigene_No == 10) {
              //Unigene No. has been passed correctly - run the SQL query for the unigene number
              $strSQL = "SELECT primers.*, simple_sequence_repeats.* FROM primers INNER JOIN simple_sequence_repeats ON primers.SSR_ID=simple_sequence_repeats.SSR_ID
                         WHERE primers.SSR_ID = '$Unigene_No'";
            }else{  
              //The length of Unigene No. is incorrect  ?>
              <script type="text/javascript">
                alert("The unigene ID has been passed incorrectly - The default SSR page will now be displayed");
                location.href="ssr.php"
              </script> <?php
            }
          }else{
            //The first two characters are not SG from unigene No.  ?>
            <script type="text/javascript">
              alert("The unigene ID has been passed incorrectly - The default SSR page will now be displayed");
              location.href="ssr.php"
            </script> <?php
          }
          //--------------------------------------
          //Populate the Primers table 
          //--------------------------------------
          //Step 1:  Connect to the database
          //Step 2:  Query the database for the unique unigene number by looping through the records
          //Step 3:  Display the unique primer information in 3 different tables

          //Step 1.
          include 'connect.php';

          //Step 2.
          //returns the result in the variable recordset
          $recordset = mysqli_query($connection, $strSQL);
          //loop through the records
          if($recordset){ //checking to see if the variable exists
            while($row= mysqli_fetch_assoc($recordset)){ //will take each row and place it into an array

              //Step 3.  ?>
              <h3>Design Primers</h3>
              <center>
                <!-- Table for Primer 1 -->
                <table class="Primers_Search_Table">
                  <tr>
                    <td colspan="14" class="Center"><b>Repeat: </b><?php echo $row['SSR']; ?></td>
                  </tr>
                  <tr>
                    <td colspan="14" class="Center"><b>Size: </b>
                      <?php echo $row['Size']; ?> bp &nbsp;<b> Start: </b>
                      <?php echo $row['Start']; ?> &nbsp; <b> End: </b>
                      <?php echo $row['End']; ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="14" class="Center"><b>Forward Primer 1</b></td>
                  </tr>
                  <tr>
                    <td colspan="14" class="Center"><?php echo $row['Forward_Primer_1']; ?></td>
                  </tr>
                  <tr>
                    <td colspan="14" class="Center"><b>Reverse Primer 1</b></td>
                  </tr>
                  <tr>
                    <td colspan="14" class="Center"><?php echo $row['Reverse_Primer_1']; ?></td>
                  </tr>
                  <tr>
                    <td class="Center"><b>Tm F1:</b></td>
                    <td class="Center"><?php echo $row['Melt_Temp_F_1']; ?></td>
                    <td class="Center"><b>Size F1:</b></td>
                    <td class="Center"><?php echo $row['Size_F_1']; ?></td>
                    <td class="Center"><b>Tm R1:</b></td>
                    <td class="Center"><?php echo $row['Melt_Temp_R_1']; ?></td>
                    <td class="Center"><b>Size R1:</b></td>
                    <td class="Center"><?php echo $row['Size_R_1']; ?></td>
                    <td class="Center"><b>Product Size:</b></td>
                    <td class="Center"><?php echo $row['Product_1_Size']; ?></td>
                    <td class="Center"><b>Primer Start:</b></td>
                    <td class="Center"><?php echo $row['Primer_1_Start']; ?></td>
                    <td class="Center"><b>Primer End:</b></td>
                    <td class="Center"><?php echo $row['Primer_1_End']; ?></td>                    
                  </tr>
                </table>
                <br><br>
                <!-- Table for Primer 2 -->
                <table class="Primers_Search_Table">          
                <tr>
                  <td colspan="14" class="Center"><b>Forward Primer 2:</b></td>
                </tr>
                <tr>
                  <td colspan="14" class="Center"><?php echo $row['Forward_Primer_2']; ?></td>
                </tr>
                <tr>
                  <td colspan="14" class="Center"><b>Reverse Primer 2:</b></td>
                </tr>
                <tr>
                  <td colspan="14" class="Center"><?php echo $row['Reverse_Primer_2']; ?></td>
                </tr>
                <tr>
                  <td class="Center"><b>Tm F2:</b></td>
                  <td class="Center"><?php echo $row['Melt_Temp_F_2']; ?></td>
                  <td class="Center"><b>Size F2:</b></td>
                  <td class="Center"><?php echo $row['Size_F_2']; ?></td>
                  <td class="Center"><b>Tm R2:</b></td>
                  <td class="Center"><?php echo $row['Melt_Temp_R_2']; ?></td>
                  <td class="Center"><b>Size R2:</b></td>
                  <td class="Center"><?php echo $row['Size_R_2']; ?></td>
                  <td class="Center"><b>Product Size:</b></td>
                  <td class="Center"><?php echo $row['Product_2_Size']; ?></td>
                  <td class="Center"><b>Primer Start:</b></td>
                  <td class="Center"><?php echo $row['Primer_2_Start']; ?></td>
                  <td class="Center"><b>Primer End:</b></td>
                  <td class="Center"><?php echo $row['Primer_2_End']; ?></td> 
                </tr>
              </table>
              <br><br>
              <!-- Table for Primer 3 -->
              <table class="Primers_Search_Table"> 
                <tr>
                  <td colspan="14" class="Center"><b>Forward Primer 3:</b></td>
                </tr>
                <tr>
                  <td colspan="14" class="Center"><?php echo $row['Forward_Primer_3']; ?></td>
                </tr>
                <tr>
                  <td colspan="14" class="Center"><b>Reverse Primer 3:</b></td>
                </tr>
                <tr>
                  <td colspan="14" class="Center"><?php echo $row['Reverse_Primer_3']; ?></td>
                </tr>
                <tr>
                  <td class="Center"><b>Tm F3:</b></td>
                  <td class="Center"><?php echo $row['Melt_Temp_F_3']; ?></td>
                  <td class="Center"><b>Size F3:</b></td>
                  <td class="Center"><?php echo $row['Size_F_3']; ?></td>
                  <td class="Center"><b>Tm R3:</b></td>
                  <td class="Center"><?php echo $row['Melt_Temp_R_3']; ?></td>
                  <td class="Center"><b>Size R3:</b></td>
                  <td class="Center"><?php echo $row['Size_R_3']; ?></td>
                  <td class="Center"><b>Product Size:</b></td>
                  <td class="Center"><?php echo $row['Product_3_Size']; ?></td>
                  <td class="Center"><b>Primer Start:</b></td>
                  <td class="Center"><?php echo $row['Primer_3_Start']; ?></td>
                  <td class="Center"><b>Primer End:</b></td>
                  <td class="Center"><?php echo $row['Primer_3_End']; ?></td>                    
                </tr>  <?php
                }
              }else{  //this happens if the variable doesn’t exist
                //display error messages
                echo'<p>';
                echo mysqli_error($connection);
                echo '</p>';
              }
            ?>
          </table><br><br><br><br>
        </center>
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
