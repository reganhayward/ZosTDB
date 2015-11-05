<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
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
      //Step 1:  Start session and connect to the database
      //Step 2:  Check to see if a page number has been passed and validate thats its numeric
      //Step 3:  Create start variable and then store the SQL query

      //Step 1.
      include 'connect.php';
      session_start();

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
      //------------------------------------------------------------------------
      // checks to see if the page has been submitted from a submit button(POST)
      //------------------------------------------------------------------------
      if (!isset($_POST['submit'])) {
        //Form hasent been submitted
      }else{
        //The form has been submitted
        //Check to make sure an option has been selected, then store the sort order and sort by options into variables
        //Checking the sort dropdown box value
        if (isset($_POST["sort"])) {
          $sort = mysqli_real_escape_string($connection, $_POST['sort']);
          $_SESSION["Sort"] = $sort;
        }
        //Checking the sort by radio buttons
        if (isset($_POST["sort_order"])) {
          $sort_order = mysqli_real_escape_string($connection, $_POST['sort_order']);
          $_SESSION["Sort_Order"] = $sort_order;
        }else{
          //this option hasnt been selected - error ?>
          <script type="text/javascript">
            alert("Please select a sort order option to perform a search");
            location.href="orthologs.php"
          </script> <?php
        }
        if ($sort == ""){
          //this option hasnt been selected - error  ?>
          <script type="text/javascript">
            alert("Please select a value from the dropdown menu to perform a search");
            location.href="orthologs.php"
          </script> <?php
        }
      }//end of if form submitted

      $sortby = $_SESSION["Sort"];
      $sortby_order =  $_SESSION["Sort_Order"];
      //the select all statement with the limits of first record and how many records
      $strSQL = "SELECT * FROM orthologs_flowering_species ORDER BY $sortby $sortby_order LIMIT $start_from, $records_per_page";
      //returns the result in the variable recordset
      $recordset = mysqli_query($connection, $strSQL)
    ?>
  <main>
    <div id="content_wrapper">
      <div id="content">  
        <h3>Orthologs Across Flowering Species</h3>
        <p>The orthology page describe the detection of the co-orthologus genes across the clade of flowering plants and marine species 
          <i>Spirodela polyrihza</i>. Availability of these orthologs serve as a first step for delineating the phylogenomics of seagrasses across 
          the flowering plants and looking  into the evolutionary divergence.<br><br>
          To sort this table by a different heading, choose the option below and press search:
        </p>
        <!-- Table to display the dropdown and radio buttons -->   
        <table>
          <tr>
            <td>
              <form action="orthologs_search.php" method="post">
                <select name="sort">
                  <?php
                    //----------------------------------------------------------
                    //Check to see which option has been selected in the dropdown
                    //----------------------------------------------------------
                    if ($sortby == ""){
                      //nothing has been selected, so load all of the values into the dropdown  ?>
                      <option value="">Please Select...</option>
                      <option value="Alg_Conn">Alg.-Conn.</option>
                      <option value="Unigene_Num">Unigenes</option><?php
                    }else{ //A value has been selected
                      //check to see which value is selected
                      //Alg-Conn has been selected - so select it
                      if ($sortby == "Alg_Conn"){  ?>
                        <option value="">Please Select...</option>
                        <option value="Alg_Conn" selected>Alg.-Conn.</option>
                        <option value="Unigene_Num">Unigenes</option><?php
                      }
                      //Unigene Number has been selected - so select it
                      if ($sortby == "Unigene_Num"){  ?>
                        <option value="">Please Select...</option>
                        <option value="Alg_Conn">Alg.-Conn.</option>
                        <option value="Unigene_Num" selected>Unigenes</option><?php
                      }
                    }
                  ?>
                </select>&nbsp;
                <?php
                  //----------------------------------------------------------
                  //Check to see which sory by order has been selected 
                  //----------------------------------------------------------
                  if($sortby_order =="ASC"){
                    //The Order is ASC  ?>
                    Ascending<input type="radio" name="sort_order" value="ASC" checked="checked">
                    &nbsp;
                    Descending<input type="radio" name="sort_order" value="DESC">
                    &nbsp;<?php
                  }else{
                    //The Order is DESC  ?>
                    Ascending<input type="radio" name="sort_order" value="ASC">
                    &nbsp;
                    Descending<input type="radio" name="sort_order" value="DESC" checked="checked">
                    &nbsp;<?php
                  }
                ?>
              <input type="submit" value="Search" name="submit">
            </form>
          </td>
        </tr>
      </table> <!-- End of table to display the dropdown and radio buttons -->
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
              }else{  //this happens if the variable dosent exist
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
