<?php
//echo "hello";

$plugins_url = plugins_url();
$base_url = get_option( 'siteurl' );
$plugins_dir = str_replace( $base_url, ABSPATH, $plugins_url );
//echo $plugins_dir;
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( is_plugin_active( 'mailpoet/mailpoet.php' ) ){
    require_once $plugins_dir . "/mailpoet/lib/API/MP/v1/API.php";
}
//require_once $plugins_dir . "/mailpoet/lib/API/MP/v1/API.php";

//Check if MailPoet API class exists
if (class_exists(\MailPoet\API\API::class)) {
    // Instantiate MailPoet API with version 'v1'
    $mailpoet_api = \MailPoet\API\API::MP('v1');
    
    // Retrieve available lists
    $lists = $mailpoet_api->getSubscribers( $filter = [], $limit = 50, $offset = 0);
    //echo "<pre>";
    //print_r($lists);
    

?>

<div class="export-mep">
<h1 class="all-subscriber-mep-list-main-heading">Subscribers List</h1>
<button class="export-btn-mep">Export CSV</button></div>
<div class="all-subscriber-mep-list">
<table id="all-subscriber-mep-table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Subscription</th>
                </tr>
            </thead>
          
     
            <tbody>
                <?php
            foreach($lists as $i){
                ?>
			<input type="hidden" class="selectedRowD" name="selectedRowD" value="">
                      <tr>
                        <td><?php echo $i['first_name'];  ?></td>
                        <td><?php echo $i['last_name'];  ?></td>
                        <td><?php echo $i['email'];  ?></td>
                        <td><?php echo $i['status'];  ?></td>   
                        <td><?php echo $i['created_at'];  ?></td>   
                        <td><?php echo $i['subscriptions'][0]['status'];  ?></td>   
                         
                    </tr>
                 <?php
            }
                 ?>
            </tbody>
        </table>
</div>
<!-- ajax call to send the data export the csv file -->
<script>
jQuery(document).ready(function() {
    jQuery('.export-btn-mep').on('click', function() {
        var data = [];
        
        var headers = [];
        jQuery('#all-subscriber-mep-table thead th').each(function() {
            headers.push(jQuery(this).text());
        });
        data.push(headers.join(','));

        jQuery('#all-subscriber-mep-table tbody tr').each(function() {
            var rowData = [];
            jQuery(this).find('td').each(function() {
                rowData.push(jQuery(this).text());
            });
            data.push(rowData.join(','));
        });
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo plugin_dir_url( __FILE__ ) ?>export-csv.php',
            data: { csvData: data.join('\n') },
            success: function(response) {
                // Handle success
                console.log('CSV file generated successfully');      
                var blob = new Blob([response], { type: 'text/csv' });

                // Create a link element
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);

                link.download = 'Subscriber-data.csv';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error('Error generating CSV file:', error);
            }
        });
    });
});
</script>

<?php
}