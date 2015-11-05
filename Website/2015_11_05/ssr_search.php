<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
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
		//Step 1:  Start session to store session variables and connect to the database
		//Step 2:  Check to see if a page number has been passed and validate thats its numeric
		//Step 3:  Perform the relevant SQL queries and load the data into variables 	

		//Step 1.
		// Start the session to use global variables
		session_start();
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
			//Form hasent been submitted
		}else{ //the form has been submitted
		//Step 1:  Checks to see if a dropdown value has been selected, if not, do nothing, if yes, load into variable
		//Step 2:  Checks to see if the tickbox has been selected and assign variables as 'ticked' or 'not ticked'

		//Step 1
		//Checking the dropdown boxes values
		//-------------------------------------------------
			// Checking the SSR Type dropdown box for any values
			//-------------------------------------------------
			if (isset($_POST["SSR_Type"])) {
				$SSR_Type = mysqli_real_escape_string($connection, $_POST['SSR_Type']);
				$_SESSION["SSR_Type_Dropdown"] = True;
				$_SESSION["SSR_Type_Dropdown_Value"] = $SSR_Type;
			}else{
				$SSR_Type = "";
				$_SESSION["SSR_Type_Dropdown"] = False;
			}

			//Step 2.
			//Checking the tickboxs values
			//-------------------------------------------------------
			// Checking the Domains tickbox to see if its been ticked
			//-------------------------------------------------------
			//check if its been ticked
			if (isset($_POST["Chk_SSR_Type"])) {
				$Chk_SSR_Type = $_POST['Chk_SSR_Type'];
				$_SESSION["SSR_Type_Tickbox"] = "ticked";
			} else {
				$Chk_SSR_Type = "";
				$_SESSION["SSR_Type_Tickbox"] = "not ticked";
			}
		}//End of if statement if the form has been submitted

		//-----------------------------------------------------------------------------------------
		// Checks to see which tick boxes have been selected and will run the appropriate sql query
		//-----------------------------------------------------------------------------------------
		// Step 1:	Load session variable
		// Step 2:	Determine if the tickbox has been selected, then run the appropriate SQL query
		//					$strSQL 				= The actual query
		//					$sql 						= Counts the number of records that will be displayed
		//					$sql_all_results	= Counts the total records returned

		//Step 1.
		//Load session variable
		$SSR_Type_Search = $_SESSION["SSR_Type_Dropdown_Value"];

		//Step 2.
		//SSR Type Tickbox
		if ($_SESSION["SSR_Type_Tickbox"] =="ticked"){
			$strSQL = "SELECT simple_sequence_repeats.*, functional_annotations.Interpro_Annotations as `InterPro`
                FROM simple_sequence_repeats 
                INNER JOIN functional_annotations ON simple_sequence_repeats.Unigene_Num=functional_annotations.Unigene_Num
                WHERE simple_sequence_repeats.SSR_Type='$SSR_Type_Search'
                ORDER BY simple_sequence_repeats.Unigene_Num ASC 
                LIMIT $start_from, $records_per_page";
		}

		//No tickboxes have been ticked
		if ($_SESSION["SSR_Type_Tickbox"] =="not ticked"){
			$strSQL = $_SESSION["default_sql_string"];
			$sql = $_SESSION["default_sql_count"];
			//Display error message and redirect the user to the main functional annotations page  ?>
			<script type="text/javascript">
				alert("Please tick at least one tickbox to perform a search");
				location.href="ssr.php"
			</script> <?php
		}
		//returns the SQL select query from above in the variable $recordset
		$recordset = mysqli_query($connection, $strSQL);

		//------Recordsets for the dropdown menus------
		//Store the details for the SSR Type dropdown menu
		$strSQL_SSR_Type = "SELECT Distinct SSR_Type FROM simple_sequence_repeats ORDER BY SSR_Type ASC ";
		////Return the results from the database
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
				<!-- Table for the dropdown menu -->
				<table>
					<tr>
						<td width="90">SSR Type:</td>
						<td><form action="ssr_search.php" method="post">
							<select name="SSR_Type" onchange="setChkSSR(this.value);">
								<option value="">Please Select...</option>
								<?php
									//-------------------------------------------
									//Populate the SSR Type dropdown 
									//-------------------------------------------
									//loop through the records
									if($recordset_SSR_Type){ //checking to see if the variable exists
										while($row2= mysqli_fetch_assoc($recordset_SSR_Type)){ //will take each row and place it into an array
											//---------------Checking to see if a value has previously been selected---------------
											if($_SESSION["SSR_Type_Dropdown_Value"] == ""){
												//no existing values have been selected so load all values into the dropdown box  ?>
												<option value="<?php echo $row2['SSR_Type']; ?> "> <?php echo $row2['SSR_Type']; ?></option><?php
											}else{  //there is a value selected in the dropdown
												//This removes the rogue space at the end of the SSR Type dropdown so it can be matched with the array value
												$SSR_Type_Length = strlen($_SESSION["SSR_Type_Dropdown_Value"]);  //gets the length
												$SSR_Type_Length_Minus_1 = $SSR_Type_Length -1;  //removes the last space
												//new string without the space at the end
												$SSR_Type_Search = substr($_SESSION["SSR_Type_Dropdown_Value"],0,$SSR_Type_Length_Minus_1); 
												//As the dropdownbox is populated by the while loop above, check to see if the array value = the one selected
												if($SSR_Type_Search == $row2['SSR_Type']){
													//value is the same so select it  ?>
													<option value="<?php echo $_SESSION["SSR_Type_Dropdown_Value"];?>" selected > 
														<?php echo $_SESSION["SSR_Type_Dropdown_Value"];?>
													</option><?php
												}else{
													//value isnt the same - so continue to populate the dropdown  ?>
													<option value="<?php echo $row2['SSR_Type']; ?> ">
														<?php echo $row2['SSR_Type']; ?>
													</option><?php
												}
											}
										}//end of while
									}else{  //this happens if the variable doesn’t exist
										//display error messages
										echo'<p>';
										echo mysqli_error($connection);
										echo '</p>';
									}
								?>
							</select>&nbsp; 
						</td>
						<td>
							<?php 
								//----------------------------------------------------------------
								//Check to see if the Domains tickbox has been ticked 
								//----------------------------------------------------------------
								if($_SESSION["SSR_Type_Tickbox"] == "ticked"){ 
									//Tickbox has been ticked, so keep ticked  ?>   
									<input type="checkbox" name="Chk_SSR_Type" value="Chk_SSR_Type" id="Chk_SSR_Type" checked> <?php 
								}else{ 
									//The tickbox was not selected, so keep unticked  ?>
									<input type="checkbox" name="Chk_SSR_Type" value="Chk_SSR_Type" id="Chk_SSR_Type"> <?php 
								}
							?>
						</td>
						<td>
							<input type="submit" value="Search" name="submit">
						</td>
					</form>
				</tr>
			</table><!-- End of table for the search bar -->
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
							while($row= mysqli_fetch_assoc($recordset)){ //will take each row and place it into an array  ?>
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
								</tr>  <?php
							}
						}else{  //this happens if the variable doesn’t exist (an error in the search query)
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
          $strSQL_pages = "simple_sequence_repeats WHERE SSR_Type='$SSR_Type_Search'";
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
