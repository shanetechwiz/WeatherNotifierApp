<?php

// checks to ensure that the needed data is being sent in a post request,
if(isset($_POST['type']) && isset($_POST['location'])) {

	$locations = array('3489854' => 'Kingston', '3489460' => 'Mobay');

	// trims the posted data and assigns them to a variable
	$type = trim($_POST['type']);
	$location = trim($_POST['location']);

	// gets the data from the json file that is used as the data store and stores them in a variable
	$employees = json_decode(file_get_contents('data/employees.json'), true);

	$itemails = '';
	$nonitemails = '';


	// loops through the data from the data store and groups the emails as either IT or Non-IT
	foreach ($employees as $key => $value) {
		foreach($value as $emp) {
			if($emp['city'] == $locations[$location]) {
				if($emp['role'] == "IT") { 
					if($itemails == '') { $itemails = $emp['email']; }
					else { $itemails .= ',' . $emp['email'];  }
				}
				else {
					if($nonitemails == '') { $nonitemails = $emp['email']; }
					else { $nonitemails .= ',' . $emp['email'];  }
				}
			}
		}		
	}


	// The email message and their recipients is determined by the 'type' as derived from the Javascript code
	if($type == 'no_rain') { SendEmail(($itemails . ',' . $nonitemails), MessageForAllEmployees_NoRain()); }
	else if($type == 'rain_tomorrow') {
		SendEmail(($itemails . ',' . $nonitemails), MessageForAllEmployees_Rain());
		SendEmail($itemails, MessageForITPersonnel_Rain());
	}
	else if($type == 'rain_over_the_next_5_days') {
		SendEmail($itemails, MessageForITPersonnel_FutureRain());
	}

}

// returns the necessary message(s) based on the criteria.
function MessageForITPersonnel_Rain() {
	$message = "<p>Greetings, <br /><br />";
	$message .= "Based on the weather forecast for tomorrow, there will be rain. Due this weather condition, we advise you as I.T. staff members to not go on the streets tomorrow. <br /><br />";
	$message .= "Please stay safe. <br /><br /> Regards, <br />Employee Updates, <br />Krace Gennedy</p>";

	return $message;
}

function MessageForITPersonnel_FutureRain() {
	$message = "<p>Greetings, <br /><br />";
	$message .= "Based on the weather forecast for the next 4-5 days, there will be rain. Due this weather condition, we advise you as I.T. staff members to be cautious when going on the streets. Seeing that the weather can change at any time, we ask that you check your emails on a regular basis so that you can be kept up to date as the days go by.<br /><br />";
	$message .= "Please stay safe. <br /><br /> Regards, <br />Employee Updates, <br />Krace Gennedy</p>";

	return $message;
}

function MessageForAllEmployees_NoRain() {
	$message = "<p>Greetings, <br /><br />";
	$message .= "Based on the weather forecast for tomorrow, there will be no rain. Therefore, you are expected to stick to your regular work schedule of 8 hours. <br /><br />";
	$message .= "We look forward to seeing you at work tomorrow. <br /><br /> Regards, <br />Employee Updates, <br />Krace Gennedy</p>";

	return $message;
}

function MessageForAllEmployees_Rain() {
	$message = "<p>Greetings, <br /><br />";
	$message .= "Based on the weather forecast for tomorrow, there will be rain. Due this weather condition, we advise you all that your work schedule for tomorrow should be restricted to 4 hours as opposed to the usual 8 hours. <br /><br />";
	$message .= "Please stay safe. <br /><br /> Regards, <br />Employee Updates, <br />Krace Gennedy</p>";

	return $message;
}

function SendEmail($to, $message) {
	$subject = 'Krace Gennedy - Weather Forecast and Work Update';

	// sets the necessary headers needed for an html email and adds a reply-to email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: employeeupdates@kracegennedy.com' . "\r\n" .
    'Reply-To: employeeupdates@kracegennedy.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    // php mail function used to send email.
    mail($to, $subject, $message, $headers);
}

?>