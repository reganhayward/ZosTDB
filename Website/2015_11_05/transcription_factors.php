<?php
	//Start and store session variables
	//Needs to be the first thing that happens - otherwise the Meta words creats an error by sending info to the session first
	session_start();
	//Setting the session variables
	$_SESSION["Domains_Dropdown_Value"] = "Please Select...";
	$_SESSION["Family_Dropdown_Value"] = "Please Select...";
	$_SESSION["Family_Type_Dropdown_Value"] = "Please Select...";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
		<meta name="description" content="Zostera muelleri - Transcription Factors Page" />
		<meta name="keywords" content="ZosTDB, Zostera muelleri Transcriptomics, Southern Hemisphere Seagrass, Zostera muelleri SSR, Conserved Orthologs, Zostera muelleri BLAST" />
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
		<?php 
			include 'ie_elements.php';
			include 'analyticstracking.php';
			include 'functions.php';  
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
		//Connect to the database
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
					location.href="transcription_factors.php"
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
			//Page hasnt been submitted 
			//Storing the select all statement data, with the limits of first record and how many records to display
			$strSQL = "SELECT * FROM transcription_factors ORDER BY Unigene_Num ASC LIMIT $start_from, $records_per_page";
			//variable to capture the total records from the search sql						
      $strSQL_pages = "transcription_factors";
			//Save as session variable
			$_SESSION["default_sql_string"] = $strSQL;
			//returns the result in the variable $recordset
			$recordset = mysqli_query($connection, $strSQL);
		}
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
				<!-- Table for the two dropdown menus --> 
				<table>
					<tr>
						<td width="90">Domains:</td>
						<td>
							<form action="transcription_factors_search.php" method="post">
								<select name="Domains" onchange="setChkDomains(this.value);">
									<option value="">Please Select...</option>
									<?php
										//--------------------------------------
										//Populate the Domains dropdown 
										//--------------------------------------
										//loop through the records
										if($recordset_Domains){ //checking to see if the variable exists  
											while($row1= mysqli_fetch_assoc($recordset_Domains)){ //will take each row and place it into an array
											//Load each value into the dropdown ?>
											<option value="<?php echo $row1['Domains']; ?> "> <?php echo $row1['Domains']; ?></option><?php
											}
										}else{  //this happens if the variable $recordset_Domains dosent exist
											//display error messages
											echo'<p>';
											echo mysqli_error($connection);
											echo '</p>';
										}
									?>
								</select>&nbsp;
							</td>
							<td> 
								<input type="checkbox" name="Chk_Domains" value="Chk_Domains" id="Chk_Domains"> 
							</td>
							<td></td>
						</tr>
						<tr>
							<td>Family:</td>
							<td>
								<select name="Family" onchange="setChkFamily(this.value);">
									<option value="">Please Select...</option>
									<?php
										//--------------------------------------
										//Populate the Family dropdown 
										//--------------------------------------
										//loop through the records
										if($recordset_Family){ //checking to see if the variable exists
											while($row2= mysqli_fetch_assoc($recordset_Family)){ //will take each row and place it into an array
											//Load each value into the dropdown ?>  
											<option value="<?php echo $row2['Family']; ?> "> <?php echo $row2['Family']; ?></option><?php
										}
									}else{  //this happens if the variable dosent exist
										//display error messages
										echo'<p>';
										echo mysqli_error($connection);
										echo '</p>';
									}
								?>
							</select>&nbsp; 
						</td>
						<td>
							<input type="checkbox" name="Chk_Family" value="Chk_Family" id="Chk_Family"> 
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
								  //Populate the IP annotations dropdown 
								  //--------------------------------------
									//loop through the records
									if($recordset_Family_Type){ //checking to see if the variable exists
										while($row3= mysqli_fetch_assoc($recordset_Family_Type)){ //will take each row and place it into an array
										//Load each value into the dropdown ?>
										<option value="<?php echo $row3['Family_Type']; ?> "> <?php echo $row3['Family_Type']; ?></option><?php
									}
								}else{  //this happens if the variable dosent exist
									//display error messages
									echo'<p>';
									echo mysqli_error($connection);
									echo '</p>';
								}
							?>                 
						</select>&nbsp;
					</td>
					<td> 
						<input type="checkbox" name="Chk_Family_Type" value="Chk_Family_Type" id="Chk_Family_Type">
					</td>
					<td>
						<input type="submit" value="Search" name="submit">
					</td>
				</tr>
			</table>
		</form>
		<br>
		<center>
		<!-- Table to display the search results -->
		<table class="Generic_Search_Table Zebra">
			<tr>
				<th style="width:80px"><p class="Center">Zostera<br>Unigenes</p></th>
				<th><p class="Left"><br>Domains</p></th>
				<th style="width:120px"><p class="Center"><br>Family</p></th>
				<th style="width:270px"><p class="Left"><br>Family Type</p></th>
			</tr> 
			<?php
				//--------------------------------------
				//Populate the results table 
				//--------------------------------------
					//loop through the records
					if($recordset){ //checking to see if the variable exists
						while($row= mysqli_fetch_assoc($recordset)){ 
							//will take each row and place it into an array ?>
							<tr>
								<td class="Center_Mid"><?php echo $row['Unigene_Num']; ?></td>
								<td class="Left_Mid"><?php echo $row['Domains']; ?></td>
								<td class="Center_Mid"><?php echo $row['Family']; ?></td>
								<td class="Left_Mid"><?php echo $row['Family_Type']; ?></td>
							</tr> <?php
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
