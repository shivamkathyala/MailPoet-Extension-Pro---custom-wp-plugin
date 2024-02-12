<?php

$plugins_url = plugins_url();
$base_url = get_option( 'siteurl' );
$plugins_dir = str_replace( $base_url, ABSPATH, $plugins_url );
//echo $plugins_dir;
require_once $plugins_dir . "/mailpoet/lib/API/MP/v1/API.php";

$fnameErr = $emailErr = "";
//Check if MailPoet API class exists
if (class_exists(\MailPoet\API\API::class)) {
    // Instantiate MailPoet API with version 'v1'
    $mailpoet_api = \MailPoet\API\API::MP('v1');
    $subscriber = [];
    $get_subscriber = "";
    $subscriber_form_fields = $mailpoet_api->getSubscriberFields();
    //echo "<pre>";
    //print_r($subscriber_form_fields);

    
    if($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['send'])){
          
            // $fname = $_POST['fname'];
            // echo $fname;
            // $lname = $_POST['lname'];
            // echo $lname;
            // $email = $_POST['email'];
            // echo $email;
            // $address = $_POST['address'];
            // echo $address;
    
          if(!empty($fname = $_POST['fname'])){
              $fname = $_POST['fname'];
          }else{
              $fnameErr = "First Name is required";
          };
  
          $lname = $_POST['lname'];

          if(!empty($email = $_POST['email'])){
            $email = $_POST['email'];
          }else{
              $emailErr = "Email is required";
          };

          $address = $_POST['address'];
    if(!empty($fname) && !empty($email)){
      //access database
      global $wpdb;

      $tableName = $wpdb->prefix."all-subscriber";

      $sql = "INSERT INTO `$tableName` (FirstName, LastName, Email, SubAddress)
      VALUES ('$fname', '$lname', '$email', '$address');";
      require_once(ABSPATH.'/wp-admin/includes/upgrade.php');
      
      $result = dbDelta($sql);
      if ($result === false){
      // Handle the error
      error_log("Error inserting data: " . $wpdb->last_error);
          }else{
            echo "<script>alert('Subscribed successfully')</script>";
              ;
              try {
                foreach ($subscriber_form_fields as $field) {
                    $subscriber[$field['id']] = $email;
                }
            
                $list_ids = [3];
            
                // Add subscriber
                $mailpoet_api->addSubscriber($subscriber, $list_ids);
            } catch (MailPoet\API\MP\v1\APIException $e) {
                $error_message = $e->getMessage();
                //echo "Error sending confirmation email";  
            }
            
      }

    } 
      
    }

    }

  echo '<div class="main-div-box-mep">
    <form action="" class="subscribe-from-mep" method="post">
        <div class="fname-mep">
        <input type="text" name="fname" placeholder="First Name">
        <small class="error-mep">' . $fnameErr . '</small>
        </div>
        <div class="lname-mep">
        <input type="text" name="lname" placeholder="Last Name">
        </div>
        <div class="email-mep">
        <input type="email" name="email" placeholder="Enter your email address">
        <small class="error-mep">' . $emailErr . '</small>
        </div>
        <div class="address-mep">
        <textarea name="address" cols="30" rows="5" placeholder="Enter your address (optional)"></textarea>
        </div>
        <div class="button-mep">
        <input type="submit" name="send" value="subscribe">
        </div>
    </form>
    </div>';

}