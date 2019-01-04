<?php
	$apikey = 'd26d777a8aca7dd43336183dbe4e73c4-us7';
	$email = $_POST['email'];
	$name = $_POST['name'];
	$server = 'us7.';
	$listid = 'e9e546ac47';
	$auth = base64_encode( 'user:'.$apikey );
	$split = preg_split('/\s+/',$_POST['name']);
	$fname = $split[0];
	$lname = array_pop($split);
	$message = $_POST['message'];
	if(empty($message))
		$message = 'Newsletter Signup';
	$data = array(
		'apikey'        => $apikey,
		'email_address' => $email,
		'status'        => 'subscribed',
		'merge_fields'  => array(
			'FNAME' => $fname,
			'LNAME' => $lname,
			'MESSAGE'=> $message
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

	$mess = "<img src='https://gallery.mailchimp.com/1cbeb928ffd4e482fd1638121/images/3e57f4b0-162f-4f0a-a1c1-ac94ed523214.png' width='150' style='max-width: 100%;margin-bottom:0;display:block;margin:0 auto;'/><p>Greetings!</p><p>Thanks for writing in.</p><p>I apologize in advance for this auto-reply. I hope you can forgive me. Each day, I receive hundreds of emails and requests from listeners and podcasters and others seeking advice. I'm honored to be asked and delighted to help! But I'm not able to reply personally because of well...because of life getting in the way.</p><p>So in the meantime, I've put together some tips and answers to the most FREQUENTLY ASKED QUESTIONS. Hope they're useful!</p><p>Cheers</p><p>Guy</p><img src='https://gallery.mailchimp.com/1cbeb928ffd4e482fd1638121/images/d49cd80e-7f0b-46ce-b723-7ecf35f48d92.png' width='300' style='max-width: 100%;margin-bottom:0;display:block;margin:0 auto;'/>";
		$mess .= "<hr><ul><li><p>Q: HOW CAN I INQUIRE ABOUT HAVING GUY SPEAK OR APPEAR AT MY EVENT, CONFERENCE OR INSTITUTION OR HOST A LIVE EVENT?<p>A: please email United Talent Agency at <a href='mailto:guyrazteam@unitedtalent.com'>guyrazteam@unitedtalent.com</a></p></li><li>Q: HOW DO I PITCH A BUSINESS/FOUNDER FOR HOW I BUILT THIS?<p>A: please email hibt@npr.org</p></li><li>Q: HOW DO I PITCH MY BUSINESS FOR THE <em>HOW YOU BUILT THIS</em> SEGMENT AT THE END OF THE SHOW?<p>A: fill out the form at build.npr.org</p></li><li>Q: HOW CAN I PITCH MY TED TALK OR ANOTHER TED TALK OR MY EXPERT FOR TED RADIO HOUR?<p>A: please email tedradiohour@npr.org</p></li><li>Q: I WORK IN TV, FILM OR OTHER MEDIA AND WOULD LIKE TO INQUIRE ABOUT WORKING WITH GUY ON A PROJECT. WHO DO I CONTACT?<p>A: please email United Talent Agency at guyrazteam@unitedtalent.com</p></li><li>Q: THIS IS A MEDIA REQUEST TO INTERVIEW GUY. WHO DO I CONTACT?<p>A: please email mediarelations@npr.org</p></li><li>Q: THIS IS A QUESTION ABOUT THE REWIND WITH GUY RAZ ON SPOTIFY?<p>A: please email therewind@spotify.com </p></li><li>Q: HOW DO I CONTACT WOW IN THE WORLD OR TINKERCAST?<p>A: please email hello@wowintheworld.com</p></li><li>Q: I HATED SOMETHING YOU SAID ON TED RADIO HOUR! AND I WANT YOU TO KNOW WHAT A DUMMY YOU ARE! WHERE DO I WRITE?<p>A: please email tedradiohour@npr.org</p></li><li>Q: I HATED SOMETHING YOU SAID ON HOW I BUILT THIS! AND I WANT YOU TO KNOW WHAT A DUMMY YOU ARE! WHERE DO I WRITE?<p>A: please email hibt@npr.org</p></li><li>Q: I WOULD LIKE TO INQUIRE ABOUT SPONSORSHIP/ADVERTISING ON YOUR PODCASTS. WHO SHOULD I CONTACT?<p>A: please visit http://nationalpublicmedia.com/npr/platforms/npr-podcasts/ or http://nationalpublicmedia.com/get-started/</p></li><li>Q: HOW DO I GET PERMISSION/RIGHTS TO LICENSE OR USE MATERIAL FROM TED RADIO HOUR OR HOW I BUILT THIS?<p>A: please visit http://www.npr.org/about-npr/179881519/rights-and-permissions-information</p></li><li>Q: HOW CAN I WORK AT NPR OR BE AN INTERN ON YOUR SHOWS?<p>A: Please check out this link which will tell you all about NPR's internships and career opportunities https://www.npr.org/series/750004/careers</p></li><li>Q: HOW CAN I VOLUNTEER (AND WORK FOR FREE!) ON ONE OF YOUR SHOWS?<p>A: We'd love to accept the offer but unfortunately we cannot accept unpaid volunteers on our programs. But please see link above to inquire about internships.</p></li><li>Q: WHERE CAN I SEND MAIL?<p>A: 1111 North Capitol Street NE, Washington DC 20002</p></li><li>Q: CAN YOU PUT ME IN TOUCH WITH ONE OF THE GUESTS I HEARD ON ONE OF YOUR SHOWS?<p>A: Unfortunately, we cannot due to privacy reasons.</p></li><li>Q: HEY! HOW CAN I FIND OUT ABOUT YOUR NEXT LIVE EVENT?<p>A: The best (and only) way to find out about our next live shows (location, dates) is to listen to How I Built This or follow our twitter handles <a href='https://twitter.com/howibuiltthis?lang=en' target=_blank>@HowIBuiltThis</a> or <a href='https://twitter.com/guyraz' target=_blank>@guyraz</a> MAKE LINKS TO TWITTER FEEDS</p></li><li>Q: I WANT TO SEE YOUR NEXT LIVE SHOW BUT THE TICKETS ARE SOLD OUT! CAN YOU GET ME IN?<p>A: We'd love to but unfortunately, we don't control the box office. But, you may want to show up the night of the show. Occasionally, there are no-shows and tickets are made available at the last minute.</p></li><li>Q: CAN YOU PLEASE INTERVIEW BILL GATES, ELON MUSK, JEFF BEZOS, OPRAH, JAY-Z ETC ETC?<p>A: We promise we have asked and will continue to push them to be on the show.</p></li></ul><hr><p>HOW TO'S AND GUIDES:</p><p><p>transom.org</p><p>This has everything you need to learn about audio storytelling</p><p><p>https://www.thisamericanlife.org/about/make-radio/</p><p>This is also an amazing resource</p><p><p>https://zencastr.com/</p><p>I can't vouch for the company but I think what they offer seems interesting</p><p><p>https://www.buzzsprout.com/how-to-make-a-podcast</p><p>I don't know much about this company but their guide is very good</p><p><p>WHAT TO LISTEN TO?</p><p><p>START-UP: SEASON 1 (Start with this episode)</p><p>https://gimletmedia.com/show/startup/all/page/6/</p><p><p>PLANET MONEY</p><p>http://www.npr.org/sections/money/</p>";

	$headers = "From: Built It Productions <noreply@builtitprod.wpengine.com>\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	if($message!='Newsletter Signup')
		echo mail($email,'Thank you for contacting me!',$mess,$headers);
	//echo mail($email,'Thank you for contacting me!',$mess);
	
	//echo json_decode($result);



?>
