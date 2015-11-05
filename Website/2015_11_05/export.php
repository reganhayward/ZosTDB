<?php
  // Start the session to use global variables
  session_start();
  include 'connect.php';

  //Make sure a value has been passed, else close the window
  if(!empty($_POST['export_box'])) {

    //Get the contents from the esport box
    if (isset($_POST["export_box"])) {
      $identifiers = mysqli_real_escape_string($connection, $_POST['export_box']);
    }

    //perform some basic error checking
    $array_id_search_sql = str_replace(array('.', ' ', ";", "*", "-", "`", "drop", "select", "table"), "", $identifiers);
    //replace tabs and carriage returns with ',' to join the ID's for the sql query
    $array_id_search_sql = str_replace(array("\\t", "\\r\\n"), "','", $array_id_search_sql);

    //prepare sql statement
    $export_sql = "SELECT transcripts.Identifier as `Unigene`, transcripts.Sequence as `Transcript`, amino_acids.Sequence as `Peptide`
                   FROM transcripts
                   INNER JOIN amino_acids ON transcripts.Identifier=amino_acids.Identifier
                   WHERE transcripts.Identifier IN ('$array_id_search_sql')";



    $results = mysqli_query($connection,$export_sql);

    //make sure $results exists and contains data
    if (!$results) die('Couldn\'t fetch records. Please contact the administrator.');

    //Returns the numbers of columns from a recordset
    $num_fields = mysqli_num_fields($results);

    //Find the headers and save each heading name to an array called $headers
    $headers = array();
    while ($fieldinfo = mysqli_fetch_field($results)) {
        $headers[] = $fieldinfo->name;
    }

    //the write buffer like echo, just write to file
    $fp = fopen('php://output', 'w');  

    //make sure the open file and $results exists - otherwise end
    if ($fp && $results) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="report.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        fputcsv($fp, $headers);
        while ($row = $results->fetch_array(MYSQLI_NUM)) {
            fputcsv($fp, array_values($row));
        }
        die;
        fclose($fp);
    }
        
    }//end of post
die;

?>