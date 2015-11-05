<?php
	//Start and store session variables
	//Needs to be the first thing that happens - otherwise the Meta words creats an error by sending info to the session first
	session_start();
	$_SESSION["Gene_Ontology_Annotations_Dropdown_Value"] = "Please Select...";
	$_SESSION["Interpro_Annotations_Dropdown_Value"] = "Please Select...";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
		<meta name="description" content="Zostera muelleri - Functional Annotations Page" />
		<meta name="keywords" content="ZosTDB, Zostera muelleri Transcriptomics, Southern Hemisphere Seagrass, Zostera muelleri SSR, Conserved Orthologs, Zostera muelleri BLAST" />
		<title>ZosTDB | Functional Annotations</title>
		<!-- style sheet link -->
		<link rel="stylesheet" type="text/css" href="css/styles.css"/>
		<link rel="stylesheet" type="text/css" href="css/callout_styles.css"/>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
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
	<style>
		a.morelink{color:#0254eb}a.morelink:visited{color:#0254eb}
		a.morelink{text-decoration:none;outline:none}
		.morecontent span{display:none}
	</style>
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
					location.href="functional_annotation.php"
				</script> <?php
			}
		}else{ 
			//the page has been loaded for the first time (No number has been passed), so display page 1
			$page=1; 
		}

		//Step 3.
		//Creating a start variable, which is used to control the amount of records displayed per page
		$records_per_page = 20;
		$start_from = ($page-1) * $records_per_page;

		//------------------------------------------------------------------------
		// checks to see if the page has been submitted from a submit button(POST)
		//------------------------------------------------------------------------
		if (!isset($_POST['submit'])) {
			//Page hasnt been submitted  

			//Storing the select all statement data, with the limits of first record and how many records to display
			$strSQL = "SELECT * FROM functional_annotations ORDER BY Unigene_Num LIMIT $start_from, $records_per_page";
			//variable to capture the total records from the search sql						
      $strSQL_pages = "functional_annotations";
			//Save as session variable
			$_SESSION["default_sql_string"] = $strSQL;
			//returns the result in the variable $recordset
			$recordset = mysqli_query($connection, $strSQL);
		}

		//Store the details for the GO annotations dropdown menu
		$strSQL_Gene_Ontology_Annotations = "SELECT * FROM gene_ontology_annotations_distinct ORDER BY Annotations ASC ";
		//Return the results from the database
		$recordset_Gene_Ontology_Annotations = mysqli_query($connection, $strSQL_Gene_Ontology_Annotations);
		//Store the details for the IP annotations dropdown menu
		$strSQL_Interpro_Annotations = "SELECT * FROM interpro_annotations_distinct ORDER BY Annotations ASC ";
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
					<td width="155">
						<form action="functional_annotation_search.php" method="post"> 
							<select name="Gene_Ontology_Annotations" onchange="setChkGO(this.value);">
								<option value="">Please Select...</option>
								<?php
									//--------------------------------------
									//Populate the GO annotations dropdown 
									//--------------------------------------

									//loop through the records
									if($recordset_Gene_Ontology_Annotations){ //checking to see if the variable exists
										while($row1= mysqli_fetch_assoc($recordset_Gene_Ontology_Annotations)){ //take each row and place it into an array 
											//Load each value into the dropdown ?>
											<option value="<?php echo $row1['Annotations']; ?> "> <?php echo $row1['Annotations']; ?></option><?php
										}
									}else{  //this happens if the variable $recordset_Gene_Ontology_Annotations dosent exist
										//display error messages
										echo'<p>';
										echo mysqli_error($connection);
										echo '</p>';
									} 
								?>
							</select>&nbsp; 
						<input type="checkbox" name="Chk_Gene_Ontology_Annotations" value="Chk_Gene_Ontology_Annotations" id="Chk_Gene_Ontology_Annotations"> 
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
										//Load each value into the dropdown ?>  
										<option value="<?php echo $row2['Annotations']; ?> "> <?php echo $row2['Annotations']; ?></option><?php
									}
								}else{  //this happens if the variable dosent exist
									//display error messages
									echo'<p>';
									echo mysqli_error($connection);
									echo '</p>';
								}
							?>
						</select>&nbsp; 
						<input type="checkbox" name="Chk_Interpro_Annotations" value="Chk_Interpro_Annotations" id="Chk_Interpro_Annotations"> 
					</td>
					<td>
						<input type="submit" value="Search" name="submit">
					</td> 
				</form>
			</tr>
		</table><br>
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
					//will take each row and place it into an array
					while($row= mysqli_fetch_assoc($recordset)){ ?>
						<tr>
							<td class="Center">
              	<a class="balloon"><?php echo $row['Unigene_Num']; $Name = $row['Unigene_Num'];?>
                	<div>
                  	<img class="arrow" src="images/arrow.gif" />
                  	<?php include 'callout.php'; ?><br>
                	</div>
              	</a>
            	</td>
							<td class="Center"><div class="more"><?php echo $row['Gene_Ontology_Annotations']; ?></div></td>
							<td class="Justify"><div class="more2"><?php echo $row['Gene_Ontology_Desc']; ?></div></td>
							<td class="Center"><div class="more3"><?php echo $row['Interpro_Annotations']; ?></div></td>
							<td class="Left"><div class="more4"><?php echo $row['Interpro_Desc']; ?></div></td>
						</tr> <?php
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
<!-- Taken from here: http://viralpatel.net/blogs/dynamically-shortened-text-show-more-link-jquery/ -->
<SCRIPT>
    var yesrow = [];
    var norow = [];
    var j = 1;
    var k = 1;
    var l = 1;
$(document).ready(function() {
    var showChar=100;
    var ellipsestext="...";
    var moretext="more";
    var lesstext="less";
    var i = 1;
    $('.more').each(function() {
        var content=$(this).html();

        if(content.length>showChar){
            yesrow.push(i);
            var c=content.substr(0,showChar);
            var h=content.substr(showChar,content.length-showChar);
            var html=c+'<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>'+h+'</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';
            $(this).html(html);
            //alter the GO description length to be the shortened length
        }else{
        		norow.push(i);
        		//alter the GO description length to be the full length
        };//end of if length > 100
        i = i + 1;
    });//end of for each .more

    //This function reduces the text for the other columns if the GO column is > 100 chars
		$('.more2').each(function() {
    	
    	if (yesrow.indexOf(j) > -1){
    		var content2=$(this).html();
        //html2=yesrow.toString();
        var begin = content2.substr(0,250);
        var rest = content2.substr(begin,content2.length-250); 
        //var zz = content2.substr(0,250)+"...";
        var zz=begin+'<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>'+rest+'</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';
        $(this).html(zz);
    	}else{
    		var content3=$(this).html();
        //html3=norow.toString();
        $(this).html(content3);
    	};
    	j = j + 1;
    });	

    //This function reduces the text for the other columns if the GO column is > 100 chars
		$('.more3').each(function() {
    	
    	if (yesrow.indexOf(k) > -1){
    		var content4=$(this).html();
        //html2=yesrow.toString();
        var begin = content4.substr(0,22);
        var rest = content4.substr(begin,content4.length-22); 
        //var zz = content2.substr(0,250)+"...";
        var zz2=begin+'<span class="moreelipses"><br>'+ellipsestext+'</span><span class="morecontent"><span>'+rest+'</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';
        $(this).html(zz2);
    	}else{
    		var content5=$(this).html();
        //html3=norow.toString();
        $(this).html(content5);
    	};
    	k = k + 1;
    });

    //This function reduces the text for the other columns if the GO column is > 100 chars
		$('.more4').each(function() {
    	
    	if (yesrow.indexOf(l) > -1){
    		var content6=$(this).html();
        //html2=yesrow.toString();
        var begin = content6.substr(0,100);
        var rest = content6.substr(begin,content6.length-100); 
        //var zz = content2.substr(0,250)+"...";
        var zz3=begin+'<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>'+rest+'</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';
        $(this).html(zz3);
    	}else{
    		var content7=$(this).html();
        //html3=norow.toString();
        $(this).html(content7);
    	};
    	l = l + 1;
    });

		//.......
    $(".morelink").click(function(){
        if($(this).hasClass("less")){
            $(this).removeClass("less");
            $(this).html(moretext);
        }else{
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});
</SCRIPT>
<?php
	//close the connection
	mysqli_close($connection);
?>
</html>
