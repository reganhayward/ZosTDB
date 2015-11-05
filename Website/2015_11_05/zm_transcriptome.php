<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
	<meta name="description" content="Zostera muelleri Transcriptome Page" />
	<meta name="keywords" content="ZosTDB, Zostera muelleri Transcriptomics, Southern Hemisphere Seagrass, Zostera muelleri SSR, Conserved Orthologs, Zostera muelleri BLAST" />
	<title>ZosTDB | Zostera muelleri Transcriptome</title>
	<!-- style sheet link -->
	<link rel="stylesheet" type="text/css" href="css/styles.css"/>
	<?php 
		include 'ie_elements.php';
		include 'analyticstracking.php'; 
	?>
	<!-- Add jQuery library for fancyBox -->
	<script type="text/javascript" src="lib/jquery-1.10.1.min.js"></script>
	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="lib/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="lib/jquery.fancybox.css?v=2.1.5" media="screen" />
	<script type="text/javascript">
		$(document).ready(function() {
			$("#single_1").fancybox({
				helpers: {
					title : {
						type : 'float'
					}
				}
			});
			$(".fancybox")
			.attr('rel', 'gallery')
			.fancybox({
				type: 'iframe',
				autoSize : false,
				beforeLoad : function() {
					this.width  = parseInt(this.element.data('fancybox-width'));  
					this.height = parseInt(this.element.data('fancybox-height'));
				}
			});
		});
	</script>
</head>
<body>
	<?php include 'header.php'; ?>
	<div id="navigation_menu_wrapper"> 
		<div id="navigation_menu">  
			<?php
				//Names the page which is used for highlighting which page is current in the top menu bar 
				$This_Page = "zm_transcriptome";
				include 'top_menu_bar.php'; ?>
			</div> <!-- end of menu -->
		</div> <!-- end of menu wrapper -->
	<main>
		<div id="content_wrapper">
			<div id="content"> 
				The page graphically lays out the transcriptome assembly procedure and the functional annotation of the <i>Zostera muelleri</i> 
				transcriptome in the form of pie charts, describing Gene Ontologies (GOs), annotations and classifications of transcriptome wide observed 
				transcription factors. Simply click on an image to zoom in, then you can visualise the distributed annotations across the <i>Zostera 
				muelleri</i> transcriptome. The data (NGS reads) are available by clicking <a href="http://www.ncbi.nlm.nih.gov/bioproject/284122" target="_blank"> here </a>.<br><br><br>
				<center>
					<table class="aligned_center">
						<tr>
							<td width="450"><h3><i>Zostera muelleri</i> Transcriptome Assembly</h3></td>
							<td width="450"><h3><i>Zostera muelleri</i> Gene Ontology</h3></td>
						</tr>
						<tr>
							<td>
								<a id="single_1" href="images/ZM_Transcriptome_Assembly.png"  title="Zostera muelleri Transcription Assembly">
									<img src="images/ZM_Transcriptome_Assembly_300.png" alt="" />
								</a>
							</td>
							<td>
								<a id="single_1" href="images/ZM_Gene_Ontology_Figure.png" title="Zostera muelleri Gene Ontology">
									<img src="images/ZM_Gene_Ontology_Figure_300.png" alt="" />
								</a>
							</td>
						</tr>
						<tr>
							<td colspan="2"><br><br><h3><i>Zostera muelleri</i> Transcription Regulators</h3></td>
						</tr>
						<tr>
							<td colspan="2">
								<a id="single_1" href="images/ZM_Transcription_Regulators_Figure.png" title="Zostera muelleri Transcription Regulators">
									<img src="images/ZM_Transcription_Regulators_Figure_300.png" alt="" />
								</a>
							</td>
						</tr>
					</table><br><br>
				</center>
				<div id="bottom_spacer_20"></div>
			</div> <!-- end of content -->
		</div> <!-- end of content wrapper -->
	</main>
	<div id="footer_wrapper"> 
		<?php include 'footer.php'; ?>
	</div> <!-- end of footer wrapper-->
</body>
</html>
