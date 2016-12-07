<?php
/**
 * E-mail verification api
 *
 * @author ren <ren_ice@live.com>
 * @link https://github.com/guangrei
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 
//scoring function
$score=0;
//functional programing paradigm
function runner($fun,$args) {
	foreach($fun as $function):$function($args);
	endforeach;
}
//function check smtp
function check_smtp($email) {
	include('smtp-validate-email.php');
	$from='rend@tuta.io';
	$validator=new SMTP_Validate_Email($email,$from);
	$smtp_results=$validator->validate();
	if($smtp_results[$email]):$GLOBALS['score'] +=5;
	$GLOBALS['smtp']="passed";
	else
		:$GLOBALS['smtp']="failed";
	endif;
}
//function check mx
/*function check_mx($email) {
	list($username, $domain)=explode("@", $email);
	$GLOBALS["emails"]["user"]=$username;
	$GLOBALS["emails"]["domain"]=$domain;
	if(checkdnsrr($domain,"MX")):$GLOBALS['score'] +=2.5;
	$GLOBALS['mx']="passed";
	else
		:$GLOBALS['mx']="failed";
	endif;
}
*/
//function check disposable
function check_disposable($email) {
	list($username, $domain)=explode("@", $email);
	$GLOBALS["emails"]["user"]=$username;
	$GLOBALS["emails"]["domain"]=$domain;
	$data=file('disposable.txt',FILE_IGNORE_NEW_LINES);
	if(!in_array($domain,$data)):$GLOBALS['score'] +=2.5;
	$GLOBALS['disposable']="no";
	else
		:$GLOBALS['disposable']="yes";
	endif;
}
//function check trusted_domain
function trusted_domain($email) {
	$domain=explode("@",$email);
	$data=file('trusted_domain.txt',FILE_IGNORE_NEW_LINES);
	if(in_array($domain[1],$data)):$GLOBALS['score'] +=2.5;
	$GLOBALS['trusted_domain']="yes";

	else
		:$GLOBALS['trusted_domain']="no";
	endif;
}
//end function
//if get email
if(isset($_GET['email'])):$email=$_GET['email'];
//if email valid
if(filter_var($email,FILTER_VALIDATE_EMAIL)):runner([/*"check_mx",*/"check_disposable","trusted_domain","check_smtp"],$email);
$response=["status"=>"success","score"=>(int)$score*10,"result"=>["trusted_domain"=>$trusted_domain,"disposable"=>$disposable,/*"mx_record"=>$mx,*/"smtp_check"=>$smtp],"data"=>$emails];
//print response
header('Content-Type:application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
exit;
else
	://invalid email
header('Content-Type:application/json');
echo json_encode(["status"=>"error","err_msg"=>"invalid email"], JSON_PRETTY_PRINT);
exit;
endif;
//no input
else
	:header('Content-Type:application/json');
echo json_encode(["status"=>"error","err_msg"=>"no input"], JSON_PRETTY_PRINT);
exit;
endif;
?>