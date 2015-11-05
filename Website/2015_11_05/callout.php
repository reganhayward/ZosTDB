<?php
	$strSQL_peptide = "SELECT functional_annotations.Unigene_Num, amino_acids.Identifier, amino_acids.Sequence 
  FROM functional_annotations
  INNER JOIN amino_acids ON functional_annotations.Unigene_Num=amino_acids.Identifier 
  WHERE amino_acids.Identifier = '$Name'";

  $strSQL_transcript = "SELECT functional_annotations.Unigene_Num, transcripts.Identifier, transcripts.Sequence 
  FROM functional_annotations
  INNER JOIN transcripts ON functional_annotations.Unigene_Num=transcripts.Identifier 
  WHERE transcripts.Identifier = '$Name'";

  //Perform the SQL query for the transcript
   $recordset_transcript = mysqli_query($connection, $strSQL_transcript);
  
  //loop through the records
  if($recordset_transcript){ //checking to see if the variable exists
    while($row2= mysqli_fetch_assoc($recordset_transcript)){ //will take each row and place it into an array  
      //Check to see if the name = the identifier  ?>
      <table class="callout_table">
        <tr>
          <td><strong id="strong_heading">Transcript Sequence:</strong></td>
        </tr>
        <tr>
          <td width="300"><?php echo wordwrap($row2['Sequence'],100,"<br>\n",TRUE); ?> </td>          
        </tr>
      </table><?php
               
    } 
  }else{  //this happens if the variable dosent exist
    //display error messages
    echo'<p>';
    echo mysqli_error($connection);
    echo '</p>';
  }

  //Perform the SQL query for the peptide
   $recordset_peptide = mysqli_query($connection, $strSQL_peptide);
  
  //loop through the records
  if($recordset_peptide){ //checking to see if the variable exists
    while($row3= mysqli_fetch_assoc($recordset_peptide)){ //will take each row and place it into an array  
      //Check to see if the name = the identifier  ?>
      <table class="callout_table">
        <tr>
          <td><strong id="strong_heading">Amino Acid Sequence:</strong></td>
        </tr>
        <tr>
          <td width="300"><?php echo wordwrap($row3['Sequence'],100,"<br>\n",TRUE); ?> </td>          
        </tr>
      </table><?php
               
    } 
  }else{  //this happens if the variable dosent exist
    //display error messages
    echo'<p>';
    echo mysqli_error($connection);
    echo '</p>';
  }
?>