<?php
	$apikey = '6ea81a0fdec6d515e39b419086b6fde1-us7';
	$email = $_POST['email'];
	$name = $_POST['name'];
	$server = 'us7.';
	$listid = 'ae618f87d8';
	$auth = base64_encode( 'user:'.$apikey );
	$split = preg_split('/\s+/',$_POST['name']);
	$fname = $_POST['first'];
	$lname = $_POST['last'];
	$data = array(
		'apikey'        => $apikey,
		'email_address' => $email,
		'status'        => 'subscribed',
		'merge_fields'  => array(
			'FNAME' => $fname,
			'LNAME' => $lname,
			'PHONE' => $_POST['phone']
			)
		);
	$json_data = json_encode($data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://'.$server.'api.mailchimp.com/3.0/lists/'.$listid.'/members/');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		'Authorization: Basic '.$auth));
	curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
	$result = curl_exec($ch);
	echo $result;
	file_put_contents('mail.txt',print_r($result,true));

	$message = "<p>Hi (Name),<br/>Thank you for getting in touch! A member of our team will get back to you shortly.</p><p>Sincerely,</p><img src='http://haititakesroot.org/dist/mailogo.png' width='50'/><p>Haiti Takes Root<br/>Coalition for a Greener Haiti<br/>coalition@haititakesroot.org | +509 2227-6676</p>";
	$mess = $_POST['message'];
	$sub = $_POST['subject'];
	$headers = "From: Haiti Takes Root<noreply@haititakesroot.org>\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";


	mail($email,"Thank you for contacting Haiti Takes Root!". json_decode("'\u{1F343}'"),$message,$headers);
	mail('tyler.starmen@gmail.com',$subject,$mess,$headers);
	//echo mail($email,'Thank you for contacting me!',$mess);
	
	//echo json_decode($result);



?>
