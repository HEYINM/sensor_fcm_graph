<?php
    //Now, we have to send the temperature alert to registered users.
	//generic php function to send GCM push notification
	
   function sendPushNotificationToGCM($registration_ids, $message) {
		//Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
        );
		// Google Cloud Messaging GCM API Key
		define("API_KEY", 'AAAAIKkmOYU:APA91bH5BnJXPbyFDVpZn2DpWFORZAOzbFdErEkOa5gCxmFlPCQJjL6HW5vSObiyBksGjc1FdjC2QIh0nd-PTbapybJCiTMPoGJgO7_T72D6YKmkNXSpsLr4HPLH5YMtWHRy9HXiFKm5'); 	// edit to add your API Key	
        $headers = array(
            'Authorization: key=' . API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);	
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);				
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
?>

<?php
    
	//this block is to receive the GCM regId from users and save them in the same MySQL database in the table 'regids'
	if(!empty($_GET["shareRegId"])) {
		$gcmRegID  = $_POST["regId"]; //App sends the GCM Reg ID through post, make sure you are on the same network for this.
		$server = 'localhost';
		$username = 'phpmyadmin';
		$password = 'root';
		$database = 'fcm';

		$conn = mysql_connect($server, $username, $password);
		$db = mysql_select_db($database);

		$command = "SELECT * FROM `regids` WHERE (`RegID` = '$gcmRegID');";
		$a = mysql_query($command);
		$r = mysql_num_rows($a);
		$s = "DELETE FROM `regids` WHERE (`RegID` = '');";
		if ($r) {
			echo "gcm-regID available in database"; // to prevent multiple registrations
		}
		else {
			$query = "INSERT INTO `regids` (`RegID`) VALUES ('$gcmRegID');";
		    mysql_query($query);
		    echo "done!";
		}
		mysql_query($s);
		mysql_close();
		exit;
	}
	
    //Code to get GCM RegIDs from the database and sending the alert
	$pushStatus = "";	
	if(!empty($_GET["push"])) {	
		$server = 'localhost';
		$username = 'root';
		$password = 'raspberry';
		$database = 'templog';
        //Connect to the database
		$conn = mysql_connect($server, $username, $password);
		$db = mysql_select_db($database);
		
		$query = "SELECT * FROM `regids`;";
		$result = mysql_query($query);
		$n = mysql_numrows($result);
		mysql_close();
		$RegistrationIDs = array();
		$i = 0;
		while ($i < $n) {
			$RegistrationIDs[$i] = mysql_result($result,$i,"RegID");
			$i++;
		}
		$pushMessage = $_GET["message"];	
		$message = array("m" => $pushMessage);	
		$pushStatus = sendPushNotificationToGCM($RegistrationIDs, $message);
		
		#This is just to help when you test your code to see if it is running properly
		echo $pushMessage;
		echo $pushStatus;
		echo json_encode($RegistrationIDs);	
	}		
	
	
    		
?>
