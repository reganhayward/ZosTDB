<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
	<meta name="description" content="Zostera muelleri Transcriptomics Database" />
	<meta name="keywords" content="ZosTDB, Zostera muelleri Transcriptomics, Southern Hemisphere Seagrass, Zostera muelleri SSR, Conserved  Orthologs, Zostera muelleri BLAST" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<title>ZosTDB | Home</title>
	<!-- style sheet link -->
	<link rel="stylesheet" type="text/css" href="css/styles.css"/>
	<?php 
		include 'ie_elements.php';
		include_once("analyticstracking.php"); 
	?>
</head>
<body>
	<?php include 'header.php'; ?>
	<div id="navigation_menu_wrapper"> 
		<div id="navigation_menu">  
			<?php
				//Names the page which is used for highlighting which page is current in the top menu bar
				$This_Page = "index";  
				include 'top_menu_bar.php'; 
			?>
		</div> <!-- end of menu -->
	</div> <!-- end of menu wrapper -->
<main>
	<div id="content_wrapper">
		<div id="content">  
			<h3>Welcome to ZosTDB</h3>
			<p>Seagrasses form a paraphyletic group of marine hydrophilus angiosperms belonging to the order of Alismatales, which has evolved 
			three to four times from the land into the sea. Due to this unusual evolutionary history, they offer an exciting model system to examine
			comparative biology with terrestrial plants, as well as examine genetic patterns of adaptation amongst marine plants (Waycott 2006, Larkum
			2006). In moving into the sea, seagrasses have adapted to several key challenges, including light attenuation through the water column 
			(Dalla 1998), sea level changes over time, resisting the hydrodynamic forces of wave action and tidal currents (Waycott 2006), high
			concentrations of salt in the surrounding seawater, the need to perform underwater pollination, as well as growing in anoxic marine sediment
			rich in sulphide (Terrados 1999).<br>
			ZosTDB is a wide transcriptomics portal, to access the transcriptome assembly and associated annotations, functions, and simple sequence
			repeats (SSRs), across the <i>Zostera muelleri</i> transcriptome in light stress. The platform has been developed using MySQL and HTML5
			and provides users several modules to study in-depth annotations and SSRs of <i>Zostera muelleri</i>. The embedded orthology
			information can also be used for functional and evolutionary experimentations and to trace back the evolutionary trajectory and modelled 
			pathways as a result of selection pressure (Wissler et al. 2011). ZosTDB is equipped with an in-house BLAST server to look for the putative 
			homologs against the user sequence of interest. This capacity of ZosTDB will ease the mining of the putative homologs against the 
			user-specified sequence and will provide wide access to research communities involved in exploring the marine habitats the transcriptome of 
			<i>Zostera muelleri</i>.<br><br><br>
			</p>
			<h4>References:</h4>
			<ol>
				<li>Waycott M, Procaccini G, Les D, Reusch T: Seagrasses: Biology, Ecology and Conservation. Seagrass Evolution, Ecology and
				Conservation: A Genetic Perspective. Berlin/Heidelberg: Springer-Verlag; 2006.</li>
				<li>Larkum AWD, Duarte CA, Orth R: Seagrasses: Biology, Ecology and Conservation. Berlin: Springer Verlag; 2006.</li>
				<li>Dalla Via J, Sturmbauer C, Schönweger G, Sötz E, Mathekowitsch S, Stifter M, Rieger R: Light gradients and meadow structure in
				Posidonia oceanica: ecomorphological and functional correlates. Marine Ecology Progress Series 1998, 163:267-278.</li>
				<li>Terrados J, Duarte CM, Kamp-Nielsen L, Agawin NSR, Gacia E, Lacap D, Fortes MD, Borum J, Lubanski M, Greve T: Are seagrass
				growth and survival constrained by the reducing conditions of the sediment? Aquatic Botany 1999, 65:175-197.</li>
				<li>Wissler L, Codoñer FM, Gu J, Reusch TB, Olsen JL, Procaccini G, Bornberg-Bauer E (2011) Back to the sea twice: identifying 
				candidate plant genes for molecular evolution to marine life. BMC Evol Biol. 11:8.</li>
			</ol>
			<div id="bottom_spacer_20"></div>
		</div> <!-- end of content -->
	</div> <!-- end of content wrapper -->
</main>
<div id="footer_wrapper">
	<?php include 'footer.php'; ?>
</div> <!-- end of footer wrapper-->
</body>
</html>
