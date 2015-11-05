<?php
  //Start and store session variables
  //Needs to be the first thing that happens - otherwise the Meta words creats an error by sending info to the session first
  session_start();
  $_SESSION["Sort_Order"] = "";
  $_SESSION["Sort"] = "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <meta name="description" content="Zostera muelleri - Orthologs Across Flowering Species Page" />
    <meta name="keywords" content="ZosTDB, Zostera muelleri Transcriptomics, Southern Hemisphere Seagrass, Zostera muelleri SSR, Conserved Orthologs, Zostera muelleri BLAST" />
    <title>ZosTDB | Orthologs Across Flowering Species</title>
    <!-- style sheet link -->
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
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
        $This_Page = "orthologs";
        include 'top_menu_bar.php'; 
      ?>
    </div> <!-- end of menu -->
  </div> <!-- end of menu wrapper -->
  <?php
    //Start of the PHP code:
    //Step 1:  Connect to the database
    //Step 2:  Check to see if a page number has been passed and validate thats its numeric
    //Step 3:  Create start variable and then store the SQL query

    //Step 1.
    //connect to the database
    include 'connect.php';

    //Step 2.
    //Checking to see if a page number has been passed
    if (isset($_GET["page"])) { 
      $page  = $_GET["page"]; 
      //If a page value has been passed, check to make sure its a number
      if (is_numeric($page)){
        //do nothing as its a valid number
      }else{
        //the page number isnt a number - possible interjection or error ?>
        <script type="text/javascript">
          alert("The page number isnt numeric - Please try searching again");
          location.href="orthologs.php"
        </script> <?php
      }
    }else{ 
      //the page has been loaded for the first time (No number has been passed), so display page 1
      $page=1; 
    }

    //Step 3.
    //Creating a start variable, which is used to control the amount of records displayed per page
    $records_per_page = 30;
    $start_from = ($page-1) * $records_per_page;
    //the select all statement with the limits of first record and how many records
    $strSQL = "SELECT * FROM orthologs_flowering_species ORDER BY Unigene_Num ASC LIMIT $start_from, $records_per_page";
    //returns the result in the variable recordset
    $recordset = mysqli_query($connection, $strSQL)
  ?>
  <main>
    <div id="content_wrapper">
      <div id="content">  
        <h3>Orthologs Across Flowering Species</h3>
        <p> The orthology page describe the detection of the co-orthologus genes across the clade of flowering plants and marine species 
          <i>Spirodela polyrihza</i>. Availability of these orthologs serve as a first step for delineating the phylogenomics of seagrasses across 
          the flowering plants and looking into the evolutionary divergence.<br><br>
          To sort this table by a different heading, choose the option below and press search:
        </p>
        <!-- Table for displaying the dropdown and radio buttons -->      
          <table>
            <tr>
              <td>
                <form action="orthologs_search.php" method="post">
                  <select name="sort">
                    <option value="">Please Select...</option>
                    <option value="Alg_Conn">Alg.-Conn.</option>
                    <option value="Unigene_Num">Unigenes</option>
                  </select>
                  &nbsp; Ascending
                  <input type="radio" name="sort_order" value="ASC">
                  &nbsp; Descending
                  <input type="radio" name="sort_order" value="DESC">
                  &nbsp;
                  <input type="submit" value="Search" name="submit">
                </form>
              </td>
            </tr>
          </table>
          <br>
          <center>
            <!-- Table to display the search results -->
            <table class="Generic_Search_Table Zebra">
              <tr>
                <th style="width:51px"><p class="Center">Alg.<br>-Conn.</p></th>
                <th><p class="Center">Zostera<br>Unigenes</p></th>
                <th style="width:105px"><p class="Center"><i>Spirodela<br>polyrhiza</i></p></th>
                <th style="width:125px"><p class="Center"><i>Vitis<br>vinifera</i></p></th>
                <th style="width:170px"><p class="Center"><i>Arabidopsis<br> lyrata</i></p></th>
                <th style="width:110px"><p class="Center"><i>Populus<br>trichocarpa</i></p></th>
                <th style="width:100px"><p class="Center"><i>Oryza<br>sativa</i></p></th>
                <th style="width:80px"><p class="Center"><i>Sorghum<br>bicolor</i></p></th>
                <th style="width:79px"><p class="Center"><i>Arabidopsis<br>thaliana</i></p></th>
                <th style="width:115px"><p class="Center"><i>Zea<br>mays</i></p></th>
              </tr>
              <?php
                //--------------------------------------
                //Populate the results table 
                //--------------------------------------
                //loop through the records
                if($recordset){ //checking to see if the variable exists
                  while($row= mysqli_fetch_assoc($recordset)){ //will take each row and place it into an array  ?>
                    <tr>
                      <td class="Center_Mid"><?php echo $row['Alg_Conn']; ?></td>
                      <td class="Center_Mid"><?php echo $row['Unigene_Num']; ?></td>
                      <td class="Center_Mid"><?php echo $row['Spirodela_Polyrhiza']; ?></td>
                      <td class="Center_Mid"><?php echo $row['Vitis_Vinifera']; ?></td>
                      <td class="Center_Mid"><?php echo $row['Arabidopsis_Lyrata']; ?></td>
                      <td class="Center_Mid"><?php echo $row['Populus_Trichocarpa']; ?></td>
                      <td class="Center_Mid"><?php echo $row['Oryza_Sativa']; ?></td>
                      <td class="Center_Mid"><?php echo $row['Sorghum_Bicolor']; ?></td>
                      <td class="Center_Mid"><?php echo $row['Arabidopsis_Thaliana']; ?></td>
                      <td class="Center_Mid"><?php echo $row['Zea_Mays']; ?></td>
                    </tr>  <?php
                  }
                }else{  //this happens if the variable dosent exist (an error in the search query)
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
              $strSQL_pages = "orthologs_flowering_species"; 
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
