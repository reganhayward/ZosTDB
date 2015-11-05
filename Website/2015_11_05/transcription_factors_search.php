<?php
	//Needs to be the first thing that happens - otherwise the Meta words creats an error by sending info to the session first
	session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
		<title>ZosTDB | Transcription Factors</title>
		<!-- style sheet link -->
		<link rel="stylesheet" type="text/css" href="css/styles.css"/>
		<!-- select the tickbox when an option is chosen from the dropdown menu -->
		<script type="text/javascript">
			function setChkDomains(value){
		    	var chk = document.getElementById('Chk_Domains');
		    	chk.checked = (value != '');
			}
			function setChkFamily(value){
		    	var chk = document.getElementById('Chk_Family');
		    	chk.checked = (value != '');
			}
			function setChkFamilyType(value){
		    	var chk = document.getElementById('Chk_Family_Type');
		    	chk.checked = (value != '');
			}
		</script>
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
					$This_Page = "transcription_factors"; 
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
				//do nothing as its a valid number
			}else{
				//the page number isnt a number - possible interjection or error  ?>
				<script type="text/javascript">
					alert("The page number isnt numeric - Please try searching again");
					location.href="transcription_factors.php"
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
			//Step 1:  Checks to see if a dropdown value has been selected, if not, do nothing, if yes, load into variable
			//Step 2:  Checks to see if the tickbox has been selected and assign variables as 'ticked' or 'not ticked'

			//Step 1
			//Checking the dropdown boxes values
			//-------------------------------------------------
			// Checking the Domains dropdown box for any values
			//-------------------------------------------------
			if (isset($_POST["Domains"])) {
				$Domains = mysqli_real_escape_string($connection, $_POST['Domains']);
				$_SESSION["Domains_Dropdown"] = True;
				$_SESSION["Domains_Dropdown_Value"] = $Domains;
			}else{
				$Domains ="";
				$_SESSION["Domains_Dropdown"] = False;
			}
			//-------------------------------------------------
			// Checking the Family dropdown box for any values
			//-------------------------------------------------
			if (isset($_POST["Family"])) {
				$Family = mysqli_real_escape_string($connection, $_POST['Family']);
				$_SESSION["Family_Dropdown"] = True;
				$_SESSION["Family_Dropdown_Value"] = $Family; 
			} else {
				$Family = "";
				$_SESSION["Family_Dropdown"] = False;
			}
			//-------------------------------------------------
			// Checking the Family Type dropdown box for any values
			//-------------------------------------------------
			if (isset($_POST["Family_Type"])) {
				$Family_Type = mysqli_real_escape_string($connection, $_POST['Family_Type']);
				$_SESSION["Family_Type_Dropdown"] = True;
				$_SESSION["Family_Type_Dropdown_Value"] = $Family_Type;
			} else {
				$Family_Type = "";
				$_SESSION["Family_Type_Dropdown"] = False;
			}

			//Step 2.
			//Checking the tickboxs values
			//-------------------------------------------------------
			// Checking the Domains tickbox to see if its been ticked
			//-------------------------------------------------------
			//check if its been ticked
			if (isset($_POST["Chk_Domains"])) {
				$Chk_Domains = $_POST['Chk_Domains'];
				$_SESSION["Domains_Tickbox"] = "ticked";
			} else {
				$Chk_Domains = "";
				$_SESSION["Domains_Tickbox"] = "not ticked"; 
			}
			//-------------------------------------------------------
			// Checking the Family tickbox to see if its been ticked
			//-------------------------------------------------------
			//check if its been ticked
			if (isset($_POST["Chk_Family"])) {
				$Chk_Family = $_POST['Chk_Family'];
				$_SESSION["Family_Tickbox"] = "ticked";
			} else {
				$Chk_Family = "";
				$_SESSION["Family_Tickbox"] = "not ticked";
			}
			//-------------------------------------------------------
			// Checking the Family Type tickbox to see if its been ticked
			//-------------------------------------------------------
			//check if its been ticked
			if (isset($_POST["Chk_Family_Type"])) {
				$Chk_Family_Type = $_POST['Chk_Family_Type'];
				$_SESSION["Family_Type_Tickbox"] = "ticked";  
			} else {
				$Chk_Family_Type = "";
				$_SESSION["Family_Type_Tickbox"] = "not ticked";
			}
		}//End of if statement if the form has been submitted

		//-----------------------------------------------------------------------------------------
		// Checks to see which tick boxes have been selected and will run the appropriate sql query
		//-----------------------------------------------------------------------------------------
		// Step 1:  Removing the rogue space at the end of the selected dropdown value, then load session variables
		// Step 2:  Determine which tickbox has been selected, then run the appropriate SQL query
		//					$strSQL 				= The actual query
		//					$sql 						= Counts the number of records that will be displayed
		//					$sql_all_results	= Counts the total records returned

		//Step 1.
		//removing the space at the end from Domains
		$Domains_Search = substr($_SESSION["Domains_Dropdown_Value"],0,9); 
		//Load variables from session variables
		$Domains = $_SESSION["Domains_Dropdown_Value"]; 
		$Family = $_SESSION["Family_Dropdown_Value"];
		$Family_Type = $_SESSION["Family_Type_Dropdown_Value"];

		//Step 2.
		//Only tickbox 1
		if($_SESSION["Domains_Tickbox"] == "ticked" AND  $_SESSION["Family_Tickbox"] == "not ticked" AND  $_SESSION["Family_Type_Tickbox"] == "not ticked"){
			$strSQL =	"SELECT * FROM transcription_factors 
								WHERE Domains LIKE '%$Domains_Search%' 
								ORDER BY Unigene_Num ASC 
								LIMIT $start_from, $records_per_page";
			//variable to capture the total records from the search sql						
      $strSQL_pages = "transcription_factors WHERE Domains LIKE '%$Domains_Search%'";
		}

		//Only tickbox 2
		if($_SESSION["Domains_Tickbox"] == "not ticked" AND  $_SESSION["Family_Tickbox"] == "ticked" AND  $_SESSION["Family_Type_Tickbox"] == "not ticked"){
			$strSQL =	"SELECT * FROM transcription_factors 
								WHERE Family='$Family' 
								ORDER BY Unigene_Num ASC 
								LIMIT $start_from, $records_per_page";
			//variable to capture the total records from the search sql						
      $strSQL_pages = "transcription_factors WHERE Family='$Family'";
		}

		//Only tickbox 3
		if($_SESSION["Domains_Tickbox"] == "not ticked" AND $_SESSION["Family_Tickbox"] == "not ticked" AND $_SESSION["Family_Type_Tickbox"] == "ticked"){
			$strSQL =	"SELECT * FROM transcription_factors 
								WHERE Family_Type='$Family_Type' 
								ORDER BY Unigene_Num ASC 
								LIMIT $start_from, $records_per_page";
			//variable to capture the total records from the search sql						
      $strSQL_pages = "transcription_factors WHERE Family_Type='$Family_Type'";
		}

		//Tickbox 1 and 2
		if($_SESSION["Domains_Tickbox"] == "ticked"  AND $_SESSION["Family_Tickbox"] == "ticked" AND $_SESSION["Family_Type_Tickbox"] == "not ticked"){
			$strSQL =	"SELECT * FROM transcription_factors 
								WHERE Domains LIKE '%$Domains_Search%' AND Family='$Family' 
								ORDER BY Unigene_Num ASC 
								LIMIT $start_from, $records_per_page";
			//variable to capture the total records from the search sql						
      $strSQL_pages = "transcription_factors WHERE Domains LIKE '%$Domains_Search%' AND Family='$Family'";
		}	

		//Tickbox 1 and 3
		if($_SESSION["Domains_Tickbox"] == "ticked" AND $_SESSION["Family_Tickbox"] == "not ticked" AND $_SESSION["Family_Type_Tickbox"] == "ticked"){
			$strSQL =	"SELECT * FROM transcription_factors 
								WHERE Domains LIKE '%$Domains_Search%' AND Family_Type='$Family_Type' 
								ORDER BY Unigene_Num ASC 
								LIMIT $start_from, $records_per_page";
			//variable to capture the total records from the search sql						
      $strSQL_pages = "transcription_factors WHERE Domains LIKE '%$Domains_Search%' AND Family_Type='$Family_Type'";
		}

		//Tickbox 2 and 3
		if($_SESSION["Domains_Tickbox"] == "not ticked" AND $_SESSION["Family_Tickbox"] == "ticked" AND $_SESSION["Family_Type_Tickbox"] == "ticked"){
			$strSQL =	"SELECT * FROM transcription_factors 
								WHERE Family='$Family' AND Family_Type='$Family_Type' 
								ORDER BY Unigene_Num ASC 
								LIMIT $start_from, $records_per_page";
			//variable to capture the total records from the search sql						
      $strSQL_pages = "transcription_factors WHERE Family='$Family' AND Family_Type='$Family_Type'";
		}

		//Tickbox 1 and 2 and 3
		if($_SESSION["Domains_Tickbox"] == "ticked" AND $_SESSION["Family_Tickbox"] == "ticked" AND $_SESSION["Family_Type_Tickbox"] == "ticked"){
			$strSQL =	"SELECT * FROM transcription_factors 
								WHERE Domains LIKE '%$Domains_Search%' AND Family='$Family' AND Family_Type='$Family_Type' 
								ORDER BY Unigene_Num ASC 
								LIMIT $start_from, $records_per_page";
			//variable to capture the total records from the search sql						
      $strSQL_pages = "transcription_factors WHERE Domains LIKE '%$Domains_Search%' AND Family='$Family' AND Family_Type='$Family_Type'";
		}

		//No tickboxes have been ticked
		if($_SESSION["Domains_Tickbox"] == "not ticked" AND $_SESSION["Family_Tickbox"] == "not ticked" AND $_SESSION["Family_Type_Tickbox"] == "not ticked"){
			//Display error message and redirect the user to the main functional annotations page  ?>
			<script type="text/javascript">
				alert("Please tick at least one tickbox to perform a search");
				location.href="transcription_factors.php"
			</script> <?php
			//Exit code
			exit();
		}

		//returns the SQL select query from above in the variable $recordset
		$recordset = mysqli_query($connection, $strSQL);
		//-----Recordsets for the dropdown menus -----
		//Store the details for the Domains dropdown menu
		$strSQL_Domains = "SELECT * FROM transcription_factors_domains ORDER BY Domains ASC ";
		//Return the results from the database
		$recordset_Domains = mysqli_query($connection, $strSQL_Domains);
		//Store the details for the Family dropdown menu
		$strSQL_Family = "SELECT Distinct Family FROM transcription_factors ORDER BY Family ASC ";
		//Return the results from the database
		$recordset_Family = mysqli_query($connection, $strSQL_Family);
		//Store the details for the Family Type dropdown menu
		$strSQL_Family_Type = "SELECT Distinct Family_Type FROM transcription_factors ORDER BY Family_Type ASC ";
		//Return the results from the database
		$recordset_Family_Type = mysqli_query($connection, $strSQL_Family_Type);
	?>
	<main>
		<div id="content_wrapper">
			<div id="content">  
				<h3>Transcription Factors</h3>
				<p>Transcription factors play an important role in the physiological process by regulating the gene expression. They have been widely
					elucidated as controllers and regulators of gene expression in several physiological and biochemical responses, including the stress 
					adaptation to changing environmental conditions. This page displays a wide catalogue of the transcription factors observed in <i>Zostera 
					muelleri</i>. <br><br>
					To perform a custom search, please select the value from the dropdown menu and then tick the associated tickbox, then click on search.
				</p>
				<!-- Table for the three dropdown menus -->
				<table>
					<tr>
						<td width="90">Domains:</td>
						<td>
							<form action="transcription_factors_search.php" method="post">
								<select name="Domains" onchange="setChkDomains(this.value);">
									<option value="">Please Select...</option>
										<?php
											//-------------------------------------------
											//Populate the Domains dropdown 
											//-------------------------------------------
											//loop through the records
											if($recordset_Domains){ //checking to see if the variable exists
												while($row1= mysqli_fetch_assoc($recordset_Domains)){ //will take each row and place it into an array
													//---------------Checking to see if a value has previously been selected---------------
													if($Domains_Search == ""){
														//no existing values have been selected so load all values into the dropdown box  ?>
														<option value="<?php echo $row1['Domains']; ?> "> <?php echo $row1['Domains']; ?></option><?php
													}else{  //there is a value selected in the dropdown box
														//As the dropdownbox is populated by the while loop above, check to see if the array value = the one selected
														if($Domains_Search == $row1['Domains']){
															//value is the same so select it  ?>
															<option value="<?php echo $Domains_Search;?>" selected > <?php echo $Domains_Search;?></option><?php
														}else{
															//value isnt the same - so continue to populate the dropdown  ?>
															<option value="<?php echo $row1['Domains']; ?> "> <?php echo $row1['Domains']; ?></option><?php
														}
													}
												}//end of while
											}else{  //this happens if the variable $recordset_Domains dosent exist
												//display error messages
												echo'<p>';
												echo mysqli_error($connection);
												echo '</p>';
											}//end of initial if
										?>
									</select>&nbsp;
								</td>
								<td>
									<?php 
										//----------------------------------------------------------------
										//Check to see if the Domains tickbox has been ticked 
										//----------------------------------------------------------------
										if($_SESSION["Domains_Tickbox"] == "ticked"){
											//Tickbox has been ticked, so keep ticked  ?>  
											<input type="checkbox" name="Chk_Domains" value="Chk_Domains" id="Chk_Domains" checked> <?php 
										}else{ 
											//The tickbox was not selected, so keep unticked  ?>
											<input type="checkbox" name="Chk_Domains" value="Chk_Domains" id="Chk_Domains"> <?php 
										} 
									?>
								</td>
								<td></td>
							</tr>
							<tr>
								<td>Family:</td>
								<td>
									<select name="Family" onchange="setChkFamily(this.value);">
										<option value="">Please Select...</option>
										<?php
											//-------------------------------------------
											//Populate the Domains dropdown 
											//-------------------------------------------
											//loop through the records
											if($recordset_Family){ //checking to see if the variable exists
												while($row2= mysqli_fetch_assoc($recordset_Family)){ //will take each row and place it into an array
													//---------------Checking to see if a value has previously been selected---------------
													if($_SESSION["Family_Dropdown_Value"] == ""){
														//no existing values have been selected so load all values into the dropdown box  ?>
														<option value="<?php echo $row2['Family']; ?> "> <?php echo $row2['Family']; ?></option><?php
													}else{  //there is a value selected in the dropdown box
														//As the length of the Family dropdown varies, we need to remove the rogue space at the end
														$Family_Length = strlen($_SESSION["Family_Dropdown_Value"]); //gets the length
														$Family_Length_Minus_1 = $Family_Length -1;  //removes the last space
														//new string without the space at the end
														$Family_Search = substr($_SESSION["Family_Dropdown_Value"],0,$Family_Length_Minus_1);
														//As the dropdownbox is populated by the while loop above, check to see if the array value = the one selected
														if($Family_Search == $row2['Family']){
															//value is the same so select it  ?>
															<option value="<?php echo $_SESSION["Family_Dropdown_Value"];?>" selected> 
																<?php echo $_SESSION["Family_Dropdown_Value"];?>
															</option><?php
														}else{
															//value isnt the same - so continue to populate the dropdown  ?>
															<option value="<?php echo $row2['Family']; ?> "> <?php echo $row2['Family']; ?></option><?php
														}
													}
												}//end of while
											}else{  //this happens if the variable $recordset_Domains dosent exist
												//display error messages
												echo'<p>';
												echo mysqli_error($connection);
												echo '</p>';
											}//end of initial if
										?>
									</select>&nbsp; 
								</td>
								<td>
									<?php 
										//-----------------------------------------------------------
										//Check to see if the Family tickbox has been ticked 
										//-----------------------------------------------------------
										if($_SESSION["Family_Tickbox"] == "ticked"){
											//Tickbox has been ticked, so keep ticked  ?>   
											<input type="checkbox" name="Chk_Family" value="Chk_Family" id="Chk_Family" checked> <?php 
										}else{
											//The tickbox was not selected, so keep unticked  ?> 
											<input type="checkbox" name="Chk_Family" value="Chk_Family" id="Chk_Family"> <?php 
										}
									?>
								</td>
								<td></td>
							</tr>
							<tr>
								<td>Family Type:</td>
								<td>
									<select name="Family_Type" onchange="setChkFamilyType(this.value);">
										<option value="">Please Select...</option>
										<?php
											//--------------------------------------
											//Populate the Family Type dropdown 
											//--------------------------------------
											//loop through the records
											if($recordset_Family_Type){ //checking to see if the variable exists
												while($row3= mysqli_fetch_assoc($recordset_Family_Type)){ //will take each row and place it into an array
													//---------------Checking to see if a value has previously been selected---------------
													if($_SESSION["Family_Type_Dropdown_Value"] == ""){
														//no existing values have been selected so load all values into the dropdown box  ?>
														<option value="<?php echo $row3['Family_Type']; ?> "> <?php echo $row3['Family_Type']; ?></option><?php
													}else{  //there is a value selected in the dropdown box
														//As the length of the Family Type dropdown varies, we need to remove the rogue space at the end
														$Family_Type_Length = strlen($_SESSION["Family_Type_Dropdown_Value"]); //gets the length
														$Family_Type_Length_Minus_1 = $Family_Type_Length -1;  //removes the last space
														//new string without the space at the end
														$Family_Type_Search =
															substr($_SESSION["Family_Type_Dropdown_Value"],0,$Family_Type_Length_Minus_1);
														//As the dropdownbox is populated by the while loop above, check to see if the array value = the one selected
														if($Family_Type_Search == $row3['Family_Type']){
															//value is the same so select it  ?>
															<option value="<?php echo $_SESSION["Family_Type_Dropdown_Value"];?>" selected> 
																<?php echo $_SESSION["Family_Type_Dropdown_Value"];?>
															</option><?php
														}else{
															//value isnt the same - so continue to populate the dropdown  ?>
															<option value="<?php echo $row3['Family_Type']; ?> ">
																<?php echo $row3['Family_Type']; ?>
															</option><?php
														}
													}
												}//end of while
											}else{  //this happens if the variable $recordset_Domains dosent exist
												//display error messages
												echo'<p>';
												echo mysqli_error($connection);
												echo '</p>';
											}//end of initial if
										?>
									</select>&nbsp;
								</td>
								<td>
									<?php 
										//-----------------------------------------------------------
										//Check to see if the Family Type tickbox has been ticked 
										//-----------------------------------------------------------
										if($_SESSION["Family_Type_Tickbox"] == "ticked"){ 
											//Tickbox has been ticked, so keep ticked  ?> 
											<input type="checkbox" name="Chk_Family_Type" value="Chk_Family_Type" id="Chk_Family_Type" checked> <?php 
										}else{
											//The tickbox was not selected, so keep unticked  ?> 
											<input type="checkbox" name="Chk_Family_Type" value="Chk_Family_Type" id="Chk_Family_Type"> <?php 
										} 
									?>
								</td>
								<td>
									<input type="submit" value="Search" name="submit">
								</td>
							</form>
						</tr>
					</table> <!-- End of table for the search bar -->
					<br>
					<center>
						<!-- Table to display the search results -->
						<table class="Generic_Search_Table Zebra">
							<tr>
								<th style="width:80px"><p class="Center">Zostera<br>Unigenes</p></th>
								<th><p class="Left"><br>Domains</p></th>
								<th style="width:120px"><p class="Center"><br>Family</p></th>
								<th style="width:250px"><p class="Left"><br>Family Type</p></th>
							</tr>
							<?php
								//--------------------------------------
								//Populate the results table 
								//--------------------------------------
								//loop through the records
								if($recordset){ //checking to see if the variable exists
									while($row= mysqli_fetch_assoc($recordset)){ //will take each row and place it into an array ?>
										<tr>
											<td class="Center_Mid"><?php echo $row['Unigene_Num']; ?></td>
											<td class="Left_Mid"><?php echo $row['Domains']; ?></td>
											<td class="Center_Mid"><?php echo $row['Family']; ?></td>
											<td class="Left_Mid"><?php echo $row['Family_Type']; ?></td>
										</tr> <?php
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
