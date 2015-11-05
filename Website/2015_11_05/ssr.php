<?php
  //Start and store session variables
  //Needs to be the first thing that happens - otherwise the Meta words creats an error by sending info to the session first
  session_start();
  //Setting the session variables
  $_SESSION["SSR_Type_Dropdown_Value"] = "Please Select...";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <meta name="description" content="Zostera muelleri - SSR Markers Page" />
    <meta name="keywords" content="ZosTDB, Zostera muelleri Transcriptomics, Southern Hemisphere Seagrass, Zostera muelleri SSR, Conserved Orthologs, Zostera muelleri BLAST" />
    <title>ZosTDB | SSR Markers</title>
    <!-- style sheet link -->
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
    <!-- select the tickbox when an option is chosen from the dropdown menu -->
    <script type="text/javascript">
      function setChkSSR(value){
          var chk = document.getElementById('Chk_SSR_Type');
          chk.checked = (value != '');
      }
    </script>
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
        $This_Page = "ssr";
        include 'top_menu_bar.php';
      ?>
    </div> <!-- end of menu -->
  </div> <!-- end of menu wrapper -->
  <?php
    //Start of the PHP code:
    //Step 1:  Connect to the database
    //Step 2:  Check to see if a page number has been passed and validate thats its numeric
    //Step 3:  Perform the relevant SQL queries and load the data into variables

    //Step 1.
    //connect to the database
    include 'connect.php';

    //Step 2.
    //Checking to see if a page number has been passed
    if (isset($_GET["page"])) { 
      $page  = $_GET["page"];
      //If a page value has been passed, check to make sure its a number
      if (is_numeric($page)){
      //do nothing
      }else{
        //the page number isnt a number - possible interjection or error ?>
        <script type="text/javascript">
          alert("The page number isnt numeric - Please try searching again");
          location.href="ssr.php"
        </script> <?php
      }
    }else{ 
      //the page has been loaded for the first time, so display page 1
      $page=1; 
    }

    //Step 3.
    //Creating a start variable, which is used to control the amount of records displayed per page
    $records_per_page = 30;
    $start_from = ($page-1) * $records_per_page;

    //------------------------------------------------------------------------
    // checks to see if the page has been submitted from a submit button(POST)
    //------------------------------------------------------------------------ 
    if (!isset($_POST['submit'])) {
      //Page hasn’t been submitted
      //Storing the select all statement data, with the limits of first record and how many records to display
      $strSQL = "SELECT simple_sequence_repeats.*, functional_annotations.Interpro_Annotations as `InterPro`
                FROM simple_sequence_repeats 
                INNER JOIN functional_annotations ON simple_sequence_repeats.Unigene_Num=functional_annotations.Unigene_Num
                ORDER BY simple_sequence_repeats.Unigene_Num ASC 
                LIMIT $start_from, $records_per_page";
      //Save as session variable
      $_SESSION["default_sql_string"] = $strSQL;
      //returns the result in the variable $recordset
      $recordset = mysqli_query($connection, $strSQL);
    }
    //Store the details for the SSR dropdown menu
    $strSQL_SSR_Type = "SELECT Distinct SSR_Type FROM simple_sequence_repeats ORDER BY SSR_Type ASC ";
    //Return the results from the database
    $recordset_SSR_Type = mysqli_query($connection, $strSQL_SSR_Type);
  ?>
  <main>
    <div id="content_wrapper">
      <div id="content">  
        <h3>SSR Markers</h3>
        <p>Genic microsatellites or Simple Sequence Repeats (SSRs), originate via replication slippage mechanisms, that play an important role
            as markers in elucidating the genetic diversity, species discrimination, and for estimating the gene flow. Cross-transferability of the 
            SSR markers among the related species is an important characteristic feature to develop efficient markers of choice in case of species 
            where no genome or transcriptome information is available. The present page displays the information of the mined SSRs across the 
            <i>Zostera muelleri</i> transcriptome along with the primer pair information, which can be used to validate “ready-to-go” markers 
            for species discrimination. <br><br> 
            To perform a custom search, please select the value from the dropdown menu and then tick the associated tickbox, then click on 
            search.
          </p>
          <!-- Table for the two dropdown menus -->         
          <table>
            <tr>
              <td width="90">SSR Type:</td>
              <td><form action="ssr_search.php" method="post">
                <select name="SSR_Type" onchange="setChkSSR(this.value);">
                  <option value="">Please Select...</option>
                  <?php
                    //--------------------------------------
                    //Populate the Domains dropdown 
                    //--------------------------------------
                    //loop through the records
                    if($recordset_SSR_Type){ //checking to see if the variable exists
                      while($row2= mysqli_fetch_assoc($recordset_SSR_Type)){ //will take each row and place it into an array
                        //Load each value into the dropdown ?>
                        <option value="<?php echo $row2['SSR_Type']; ?> "> <?php echo $row2['SSR_Type']; ?></option><?php
                      }
                    }else{  //this happens if the variable dosent exist
                      //display error messages
                      echo'<p>';
                      echo mysqli_error($connection);
                      echo '</p>';
                    }
                  ?>
                </select>&nbsp;
                <input type="checkbox" name="Chk_SSR_Type" value="Chk_SSR_Type" id="Chk_SSR_Type"> 
              </td>
              <td>
                <input type="submit" value="Search" name="submit">
              </td>
            </form>
          </tr>
        </table>
        <br>
        <center>
        <!-- Table to display the search results -->
        <table class="Generic_Search_Table Zebra">
          <tr>
            <th style="width:50px"><p class="Center">SSR<br>NO.</p></th>
            <th style="width:80px"><p class="Center">Zostera<br>Unigenes</p></th>
            <th><p class="Center"><br>SSR</p></th>
            <th><p class="Center">InterPro<br>Domains</p></th>
            <th style="width:50px"><p class="Center">SSR<br>Type</p></th>
            <th style="width:50px"><p class="Center"><br>Start</p></th>
            <th style="width:50px"><p class="Center"><br>End</p></th>
            <th style="width:50px"><p class="Center">Tract<br>Length</p></th>
            <th style="width:100px"><p class="Center">Primer<br>Design</p></th>
          </tr>
          <?php
            //--------------------------------------
            //Populate the results table 
            //--------------------------------------
            //loop through the records
            if($recordset){ //checking to see if the variable exists
              while($row= mysqli_fetch_assoc($recordset)){ //will take each row and place it into an array
                //will take each row and place it into an array ?>
                <tr>
                  <td class="Center_Mid"><?php echo $row['SSR_No']; ?></td>
                  <td class="Center_Mid"><?php echo $row['Unigene_Num']; ?></td>
                  <td class="Center_Mid"><?php echo $row['SSR']; ?></td>
                  <td class="Center_Mid"><?php echo $row['InterPro']; ?></td>
                  <td class="Center_Mid"><?php echo $row['SSR_Type']; ?></td>
                  <td class="Center_Mid"><?php echo $row['Start']; ?></td>
                  <td class="Center_Mid"><?php echo $row['End']; ?></td>
                  <td class="Center_Mid"><?php echo $row['Size']; ?></td>
                  <td class="Center_Mid"><?php echo "<a href='primers.php?ID=".$row['SSR_ID']."'> Design Primer</a>"; ?></td>
                </tr> <?php
              }
            }else{  //this happens if the variable doesn’t exist
              //display error messages
              echo'<p>';
              echo mysqli_error($connection);
              echo '</p>';
            }
          ?>
        </table>
        <br>
        <?php
          //---------------------------------------------
          //Display the pagination results and goto page box and update button 
          //--------------------------------------------- 
          //variable to capture the total records from the search sql
          $strSQL_pages = "simple_sequence_repeats"; 
          //pagination function
          echo pagination($strSQL_pages,$records_per_page,$page,$url='?'); 
        ?>
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
