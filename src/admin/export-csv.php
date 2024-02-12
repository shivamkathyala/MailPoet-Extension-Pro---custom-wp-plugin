<?php
if(isset($_POST['csvData'])) {
    // Get the CSV data from the POST request
    $csvData = $_POST['csvData'];
    
    if(!empty($csvData)){
        echo $csvData;
    }
}
?>
