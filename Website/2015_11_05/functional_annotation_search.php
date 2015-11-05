<?php
	//Needs to be the first thing that happens - otherwise the Meta words creats an error by sending info to the session first
	session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
		<title>ZosTDB | Functional Annotations</title>
		<!-- style sheet link -->
		<link rel="stylesheet" type="text/css" href="css/styles.css"/>
		<link rel="stylesheet" type="text/css" href="css/callout_styles.css"/>
		<!-- select the tickbox when an option is chosen from the dropdown menu -->
		<script type="text/javascript">
			function setChkGO(value){
		    	var chk = document.getElementById('Chk_Gene_Ontology_Annotations');
		    	chk.checked = (value != '');
			}
			function setChkIP(value){
		    	var chk = document.getElementById('Chk_Interpro_Annotations');
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
				$This_Page = "functional_annotation"; 
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
					alert("The page number isnt numeric - Please try searching again");
					location.href="functional_annotation.php"
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
			// Checking the GO dropdown box for any values
			//-------------------------------------------------
			if (isset($_POST["Gene_Ontology_Annotations"])) {
				$Gene_Ontology_Annotations = mysqli_real_escape_string($connection, $_POST['Gene_Ontology_Annotations']);
				$_SESSION["Gene_Ontology_Annotations_Dropdown"] = True;
				$_SESSION["Gene_Ontology_Annotations_Dropdown_Value"] = $Gene_Ontology_Annotations;
			}else{
				$Gene_Ontology_Annotations ="";
				$_SESSION["Gene_Ontology_Annotations_Dropdown"] = False;
			}
			//-------------------------------------------------
			// Checking the IP dropdown box for any values
			//-------------------------------------------------
			if (isset($_POST["Interpro_Annotations"])) {
				$Interpro_Annotations = mysqli_real_escape_string($connection, $_POST['Interpro_Annotations']);
				$_SESSION["Interpro_Annotations_Dropdown"] = True;
				$_SESSION["Interpro_Annotations_Dropdown_Value"] = $Interpro_Annotations; 
			}else{
				$Interpro_Annotations = "";
				$_SESSION["Interpro_Annotations_Dropdown"] = False;
			}

			//Step 2
			//Checking the tickboxs values

			//-------------------------------------------------------
			// Checking the GO tickbox to see if its been ticked
			//-------------------------------------------------------
			//check if its been ticked
			if (isset($_POST["Chk_Gene_Ontology_Annotations"])) {
				$Chk_Gene_Ontology_Annotations = $_POST['Chk_Gene_Ontology_Annotations'];
				$_SESSION["Gene_Ontology_Annotations_Tickbox"] = "ticked";
			}else{
				$Chk_Gene_Ontology_Annotations = "";
				$_SESSION["Gene_Ontology_Annotations_Tickbox"] = "not ticked"; 
			}

			//-------------------------------------------------------
			// Checking the IP tickbox to see if its been ticked
			//-------------------------------------------------------
			//check if its been ticked
			if (isset($_POST["Chk_Interpro_Annotations"])) {
				$Chk_Interpro_Annotations = $_POST['Chk_Interpro_Annotations'];
				$_SESSION["Interpro_Annotations_Tickbox"] = "ticked";
			}else{
				$Chk_Interpro_Annotations = "";
				$_SESSION["Interpro_Annotations_Tickbox"] = "not ticked";
			}
		}//End of if statement if the form has been submitted

			//-----------------------------------------------------------------------------------------
			// Checks to see which tick boxes have been selected and will run the appropriate sql query
			//-----------------------------------------------------------------------------------------
			// Step 1:	Removing the rogue space at the end of the selected dropdown values
			// Step 2:	Determine which tickbox has been selected, then run the appropriate SQL query
			//					$strSQL 				= The actual query
			//					$sql 						= Counts the number of records that will be displayed
			//					$sql_all_results 	= Counts the total records returned

			//Step 1.
			//Removing the space at the end of the GO annotation dropdown value to use within the SQL query
			$Gene_Ontology_Annotations_Search = substr($_SESSION["Gene_Ontology_Annotations_Dropdown_Value"],0,10); 
			$Gene_Ontology_Annotations = $_SESSION["Gene_Ontology_Annotations_Dropdown_Value"];
			//Removing the space at the end of the IP annotations dropdown value to use within the SQL query
			$Interpro_Annotations = $_SESSION["Interpro_Annotations_Dropdown_Value"];
			$Interpro_Annotations_Search = substr($_SESSION["Interpro_Annotations_Dropdown_Value"],0,9);

			//Step 2.
			//Only tickbox 1
			if ($_SESSION["Gene_Ontology_Annotations_Tickbox"] == "ticked" AND $_SESSION["Interpro_Annotations_Tickbox"] == "not ticked"){
				$strSQL =	"SELECT * FROM functional_annotations 
									WHERE Gene_Ontology_Annotations 
									LIKE '%$Gene_Ontology_Annotations_Search%' 
									ORDER BY Unigene_Num ASC 
									LIMIT $start_from, $records_per_page";
				//variable to capture the total records from the search sql						
			  $strSQL_pages = "functional_annotations WHERE Gene_Ontology_Annotations	LIKE '%$Gene_Ontology_Annotations_Search%'";
			}

			//Only tickbox 2
			if ($_SESSION["Gene_Ontology_Annotations_Tickbox"] == "not ticked" AND $_SESSION["Interpro_Annotations_Tickbox"] == "ticked"){
				$strSQL =	"SELECT * FROM functional_annotations 
									WHERE Interpro_Annotations 
									LIKE '%$Interpro_Annotations_Search%' 
									ORDER BY Unigene_Num ASC 
									LIMIT $start_from, $records_per_page";
				//variable to capture the total records from the search sql						
			  $strSQL_pages = "functional_annotations WHERE Interpro_Annotations LIKE '%$Interpro_Annotations_Search%'";
			}

			//Tickbox 1 and 2
			if ($_SESSION["Gene_Ontology_Annotations_Tickbox"] == "ticked" AND $_SESSION["Interpro_Annotations_Tickbox"] == "ticked"){
				$strSQL =	"SELECT * FROM functional_annotations 
									WHERE Gene_Ontology_Annotations 
									LIKE '%$Gene_Ontology_Annotations_Search%' 
									AND Interpro_Annotations LIKE '%$Interpro_Annotations_Search%' 
									ORDER BY Unigene_Num ASC 
									LIMIT $start_from, $records_per_page";
				//variable to capture the total records from the search sql						
			  $strSQL_pages = "functional_annotations WHERE Gene_Ontology_Annotations LIKE '%$Gene_Ontology_Annotations_Search%' AND Interpro_Annotations LIKE '%$Interpro_Annotations_Search%'";
			}	

			//No tickboxes have been ticked
			if ($_SESSION["Gene_Ontology_Annotations_Tickbox"] == "not ticked" AND $_SESSION["Interpro_Annotations_Tickbox"] == "not ticked"){
				//Display error message and redirect the user to the main functional annotations page  ?>
				<script type="text/javascript">
					alert("Please tick at least one tickbox to perform a search");
					location.href="functional_annotation.php"
				</script> <?php
				//Exit code
				exit(); //or die();
			}

			//returns the SQL select query from above in the variable $recordset
			$recordset = mysqli_query($connection, $strSQL);

			//------Recordsets for the dropdown menus------
			//Store the details for the GO dropdown menu
			$strSQL_Gene_Ontology_Annotations =	"SELECT * FROM gene_ontology_annotations_distinct 
																						ORDER BY Annotations ASC ";
			//Return the results from the database
			$recordset_Gene_Ontology_Annotations = mysqli_query($connection, $strSQL_Gene_Ontology_Annotations);

			//Store the details for the IP dropdown menu
			$strSQL_Interpro_Annotations =	"SELECT * FROM interpro_annotations_distinct 
																			ORDER BY Annotations ASC ";
			//Return the results from the database
			$recordset_Interpro_Annotations = mysqli_query($connection, $strSQL_Interpro_Annotations);
		?>
		<main>
			<div id="content_wrapper">
				<div id="content">  
				<h3>Functional Annotations</h3>
				<p>This page gives information on the functional annotation of the assembled trancriptome of <i>Zostera muelleri</i>. The tables below 
				lists the functional information in terms of the Gene Ontologies (GOs) and InterPro domains. To look for the specific GO and InterPro 
				annotations, drop-down search menus are provided. Additionally, by hovering over each unigene, the associated transcripts and peptides can be viewed from the callout box that appears.<br><br>
				To perform a custom search, please select the value from the dropdown menu and then tick the associated tickbox, then click on search.
				</p>
				<!-- Table for the two dropdown menus --> 
				<table>
					<tr>
						<td width="200">Gene Ontology Annotations:</td>
						<td width="130">	
							<form action="functional_annotation_search.php" method="post">
								<select name="Gene_Ontology_Annotations" onchange="setChkGO(this.value);">
									<option value="">Please Select...</option>
									<?php
										//--------------------------------------
										//Populate the GO annotations dropdown 
										//--------------------------------------
										//loop through the records
										if($recordset_Gene_Ontology_Annotations){ //checking to see if the variable exists
											while($row1= mysqli_fetch_assoc($recordset_Gene_Ontology_Annotations)){ //will take each row and place it into an array
												//---------------Checking to see if a value has previously been selected---------------
												if($Gene_Ontology_Annotations_Search == ""){
													//no existing values have been selected so load all values into the dropdown box ?>
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
								</select>&nbsp; 
							</td>
							<td>
							<?php
								//-----------------------------------------------------------
								//Check to see if the GO annotations tickbox has been ticked 
								//-----------------------------------------------------------
								if($_SESSION["Gene_Ontology_Annotations_Tickbox"] == "ticked"){
									//Tickbox has been ticked, so keep ticked  ?>
									<input type="checkbox" name="Chk_Gene_Ontology_Annotations" value="Chk_Gene_Ontology_Annotations" id="Chk_Gene_Ontology_Annotations" checked> <?php 
								}else{ 
									//The tickbox was not selected, so keep unticked  ?>
									<input type="checkbox" name="Chk_Gene_Ontology_Annotations" value="Chk_Gene_Ontology_Annotations" id="Chk_Gene_Ontology_Annotations"> <?php 
								}
							?>
							</td>
							<td></td>
						</tr>
						<tr>
							<td>InterPro Annotations:</td>
							<td>
								<select name="Interpro_Annotations" onchange="setChkIP(this.value);">
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
													//no existing values have been selected so load all values into the dropdown box  ?>
													<option value="<?php echo $row2['Annotations']; ?> "> <?php echo $row2['Annotations']; ?></option><?php
												}else{  //there is a value selected in the dropdown box
													//As the dropdownbox is populated by the while loop above, check to see if the array value = the one selected
													if($Interpro_Annotations_Search == $row2['Annotations']){
														//value is the same so select it  ?>
														<option value="<?php echo $_SESSION["Interpro_Annotations_Dropdown_Value"];?>" selected> 
															<?php echo $_SESSION["Interpro_Annotations_Dropdown_Value"];?>
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
								</select>&nbsp;
							</td>
							<td>
								<?php 
									//-----------------------------------------------------------
									//Check to see if the IP annotations tickbox has been ticked 
									//-----------------------------------------------------------
									if($_SESSION["Interpro_Annotations_Tickbox"] == "ticked"){
										//Tickbox has been ticked, so keep ticked  ?>
										<input type="checkbox" name="Chk_Interpro_Annotations" value="Chk_Interpro_Annotations" id="Chk_Interpro_Annotations" checked> <?php 
									}else{
										//The tickbox was not selected, so keep unticked  ?>
										<input type="checkbox" name="Chk_Interpro_Annotations" value="Chk_Interpro_Annotations" id="Chk_Interpro_Annotations"> <?php 
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
							<th style="width:80px"><p class="Center">Zostera<br>Unigenes</p></th>
							<th style="width:220px"><p class="Center">Gene Ontology<br> Annotations</p></th>
							<th><p class="Left">Gene<br>Ontology Description</p></th>
							<th style="width:90px"><p class="Center">InterPro<br>Annotations</p></th>
							<th style="width:200px"><p class="Center">InterPro<br>Description</p></th>
						</tr>
						<?php
							//--------------------------------------
							//Populate the results table 
							//--------------------------------------
							//loop through the records
							if($recordset){ //checking to see if the variable exists
								while($row= mysqli_fetch_assoc($recordset)){ //will take each row and place it into an array ?>
									<tr>
										<td class="Center">
              				<a class="balloon"><?php echo $row['Unigene_Num']; $Name = $row['Unigene_Num'];?>
                				<div>
                  				<img class="arrow" src="images/arrow.gif" />
                  				<?php include 'callout.php'; ?><br>
                				</div>
              				</a>
            				</td>
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
