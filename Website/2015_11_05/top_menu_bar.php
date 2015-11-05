<header>
  <nav>
    <ul>
      <li>
        <center>
          <a href="index.php" 
            <?php
              //Checks to see if the current page is index.php 
              if($This_Page == "index"){
                echo "class='selected'";
              }
            ?> 
            >Welcome to<br>ZosTDB
          </a>
        </center>
      </li>
      <li class="borderleft"></li>
      <li class="borderright"></li>
      <li>
        <center>
          <a href="zm_transcriptome.php" 
            <?php 
              //Checks to see if the current page is zm_transcriptome.php
              if($This_Page == "zm_transcriptome"){
                echo "class='selected'";
              }
            ?> 
            >Zm<br>Transcriptome
          </a>
        </center>
      </li>
      <li class="borderleft"></li>
      <li class="borderright"></li>
      <li>
        <center>
          <a href="functional_annotation.php" 
            <?php
              //Checks to see if the current page is functional_annotation.php
              if($This_Page == "functional_annotation"){
                echo "class='selected'";
              }
            ?> 
            >Functional <br>Annotation
          </a>
        </center>
      </li>
      <li class="borderleft"></li>
      <li class="borderright"></li>
      <li>
        <center>
          <a href="ssr.php" 
            <?php 
              //Checks to see if the current page is ssr.php
              if($This_Page == "ssr"){
                echo "class='selected'";
              }
            ?> 
            >SSR<br>Markers
          </a>
        </center>
      </li>
      <li class="borderleft"></li>
      <li class="borderright"></li>
      <li>
        <center>
          <a href="transcription_factors.php" 
            <?php 
              //Checks to see if the current page is transcription_factors.php
              if($This_Page == "transcription_factors"){
                echo "class='selected'";
              }
            ?> 
            >Transcription<br>Factors
          </a>
        </center>
      </li>
      <li class="borderleft"></li>
      <li class="borderright"></li>
      <li>
        <center>
          <a href="orthologs.php" 
            <?php 
              //Checks to see if the current page is orthologs.php
              if($This_Page == "orthologs"){
                echo "class='selected'";
              }
            ?> 
            >Orthologs Across<br>Flowering Species
          </a>
        </center>
      </li>
      <li class="borderleft"></li>
      <li class="borderright"></li>
      <li>
        <center>
          <a href="quick_search.php" 
            <?php 
              //Checks to see if the current page is quick_search.php
              if($This_Page == "quick_search"){
                echo "class='selected'";
              }
            ?>
            >Quick Search <br> BLAST and Export
          </a>
        </center>
      </li>
    </ul>
  </nav>
</header>