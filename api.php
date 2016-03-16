<?php

//***************************************************** DEFINITION *****************************************************

//*********Errors START****************
function error_multiple_records()
{
	return "error_multiple_records";
}

function error_no_records_found()
{
	return "error_no_records_found";
}

function error_database_connection()
{
	return "error_database_connection";
}

function error_no_user_logged_in()
{
	return "error_no_user_logged_in";
}

function error_unknown()
{
	return "error_unknown";
}

function error_unauthorized_action()
{
	return "error_unauthorized_action";
}

function isMySqlError($x)
{
	if($x=="error_multiple_records" || $x=="error_database_connection" || $x=="error_no_user_logged_in" || $x=="error_unknown" || $x=="error_unauthorized_action" || $x=="error_no_records_found") return true;
	return false;
}
//*********Errors END****************







//*********Request and Notification Types START****************
function request_coder_requests_milestone()
{
	return "request_coder_requests_milestone";
}

function request_coder_requests_increased_budget()
{
	return "request_coder_requests_increased_budget";
}

function request_employer_requests_decreased_budget()
{
	return "request_employer_requests_decreased_budget";
}

function notification_user_cancels_budget_change_request()
{
	return "notification_user_cancels_budget_change_request";
}

function request_user_requests_withdrawal()
{
	return "request_user_requests_withdrawal";
}
//*********Request and Notification Types END****************








//*********Authentication START****************

//Tells whether the logged in user is admin or not.
function isAdmin()
{
	//<TODO>
}

//Tells whether the logged in user is admin or not.
function isCoder()
{
	//<TODO>
}

//Tells whether the logged in user is admin or not.
function isEmployer()
{
	//<TODO>
}

//Returns the type of logged in user.
function typeOfUser()
{
	//<TODO>
}

//Returns the admin email.
function getAdmin()
{
	//<TODO>
}

//Gets current balance in the specified user's account.
function getBalance($email)
{
	gf=getDatabaseConnection();	if(isMySqlError($gf)) return $gf;
	$r=$gf->query("select u_balance from pc_users where u_email='$email'");
	if($r)
	{
		if($r->num_rows==0)
			return error_no_records_found();
		elseif($r->num_rows==1)
			return $r['u_balance'];
		else
			return error_multiple_records();
	}
	else
		return error_unknown();
}

//Gets current balance in the logged in user's account.
function getBalance()
{
	$x=getLoggedInUser();	if(isMySqlError($x)) return $x;
	$x=getBalance($x);	if(isMySqlError($x)) return $x;
	return $x;
}

/*
doesUserExist($email)
Permission: Coder, Employer, Admin
Description: Returns true if the user exists in the database. False otherwise.

authenticateUser($email,$pass)
Permission: Coder, Employer, Admin
Description: Returns true if the user's credentials match. False otherwise.

login($email,$pass)
Permission: Coder, Employer, Admin
Description: Logs the user in if the credentials match and return true. False otherwise.

logout()
Permission: Coder, Employer, Admin
Description: Logs the user out if any user is logged in. If no user is logged in then nothing happens. Returns true always.

getLoggedInUser()
Permission: Coder, Employer, Admin
Description: Returns the email address of the logged in user. Error if no user logged in.
*/ 
//*********Authentication END****************






//*********Messaging START****************
function canCommunicate($toEmail)
{
	$gf=getDatabaseConnection();	if(isMySqlError($gf)) return $gf;
	$userEmail=getLoggedInUser();	if(isMySqlError($userEmail)) return $userEmail;
	$userType=getUserType($email);	if(isMySqlError($userType)) return $userType;	 
	$toType=getUserType($toEmail);	if(isMySqlError($toType)) return $toType;
	if($toType=="admin" || $userType=="admin") return true;
	if($userType==$toType) return false;
	if($userType=="employer") swap($userEmail,$toEmail);
	$r=$gf->query("select * from pc_connections where c_coderemail='$userEmail' and c_employeremail='$toEmail'");
	if($r)
	{
		if($r->num_rows==1)
			return true;
		elseif($r->num_rows==0)
			return false;
		else
			return error_multiple_records();		
	}
	else
		return error_unknown();
}

function sendMessage($toEmail,$msg)
{
	$gf=getDatabaseConnection();	if(isMySqlError($gf)) return $gf;
	$x=canCommunicate($toEmail);	if(isMySqlError($x)) return $x;
	if($x===false) return false;
	$fromEmail=getLoggedInUser();	if(isMySqlError($fromEmail)) return $fromEmail;
	$r=$gf->query("insert into pc_messages(m_fromemail,m_toemail,m_msg,m_isforwarded,m_forwardid,m_isseen) values('$fromEmail','$toEmail','$msg',false,null,false)");	//<TODO> Check false and null.
	if($r)
		return $gf->insert_id;
	else
		return error_unknown();	
}

/*sendFile($toEmail)
Permission: Coder, Employer, Admin 
Description: Similar to sendMessage but with files. Function returns a file ID as well as a file link on success, or false otherwise.*/
function sendFile($toEmail)
{
	//<TODO>
}

function shareMessage($toEmail,$msgIds)	//$msgIds is the array of msgId.
{
	$gf=getDatabaseConnection();	if(isMySqlError($gf)) return $gf;
	$x=isAdmin();	if(isMySqlError($x)) return $x;	//<TODO> implement isAdmin() function
	if($x===false) return false;
	$fromEmail=getLoggedInUser();	if(isMySqlError($fromEmail)) return $fromEmail;
	
	$ret=Array();
	for($msgIds as $i)		//<TODO> check for loop syntax
	{
		$r=$gf->query("insert into pc_messages(m_fromemail,m_toemail,m_msg,m_isforwarded,m_forwardid,m_isseen) values('$fromEmail','$toEmail',null,true,$i,false)");	//<TODO> Check false and null.
		if($r)
			$ret[]=$gf->insert_id;		//<TODO> check syntax
		else
			$ret[]=error_unknown();				
	}
	return $ret;
}

/*forwardFile($toEmail,$fileId)
Permission: Coder, Employer, Admin
Description: Any file that a user receives from others, he can forward to someone else. Return true or false.*/
function forwardFile($toEmail,$fileId)
{
	//<TODO>
}

//Helper function for markMsgSeen, markMsgUnseen, markFileSeen, markFileUnseen, markNotificationSeen and markNotificationUnseen functions.
function helperMarkSeen($msgId,$isSeen,$tableName,$idCol,$isSeenCol,$emailCol)	//$isSeen: Boolean in String
{
	$gf=getDatabaseConnection();	if(isMySqlError($gf)) return $gf;
	$email=getLoggedInUser();	if(isMySqlError($email)) return $email;
	$r=$gf->query("select * from $tableName where $idCol=$msgId");
	if($r->num_rows==0)
		return error_no_records_found();
	elseif($r->num_rows==1)
	{
		if($row=$r->fetch_array())
		{
			if($row[$emailCol]!=$email) return error_unauthorized_action();
			$r=$gf->query("update $tableName set $isSeenCol=$isSeen where $idCol=$msgId");		//<TODO> Confirm syntax of update query.
			if($r)
				return true;
			else
				return error_unknown();
		}
		else
			return error_unknown();
	}
	else
		return error_multiple_records();
}

function markMsgSeen($msgId)
{
	helperMarkSeen($msgId,"true","pc_messages","m_id","m_isseen","m_toemail");
}

function markMsgUnseen($msgId)
{
	helperMarkSeen($msgId,"false","pc_messages","m_id","m_isseen","m_toemail");
}

function markFileSeen($fileId)
{
	helperMarkSeen($msgId,"true","pc_files","f_id","f_isseen","f_toemail");
}

function markFileUnseen($fileId)
{
	helperMarkSeen($msgId,"false","pc_files","f_id","f_isseen","f_toemail");
}

/*retrieveMessages($fromEmail,$startIndex)
Permission: Coder, Employer, Admin
Description: Retrieves a list of 20 msgs received from a sender starting from $startIndex arranged in decreasing order with respect to date.*/
function retrieveMessages($fromEmail,$startIndex)
{
	//<TODO>
}

/*retrieveFiles($fromEmail,$startIndex)
Permission: Coder, Employer, Admin
Description: Retrieves a list of 20 files' links received from a sender starting from $startIndex arranged in decreasing order with respect to date.*/
function retrieveFiles($fromEmail,$startIndex)
{
	//<TODO>
}

//*********Messaging END****************










//*********Notification START****************

//Creates notification
function createNotification($toEmail,$type,$msg)
{
	$gf=getDatabaseConnection();	if(isMySqlError($gf)) return $gf;
	$r=$gf->query("insert into pc_notifications(n_email,n_type,n_msg) values('$toEmail','$type','$msg')");
	if($r)
		return true;
	else
		return error_unknown();	
}

function markNotificationSeen($notificationId)
{
	helperMarkSeen($notificationId,"true","pc_notifications","n_id","n_isseen","n_email");
}

function markNotificationUnseen($notificationId)
{
	helperMarkSeen($notificationId,"false","pc_notifications","n_id","n_isseen","n_email");
}
//*********Notification END****************







//*********Payments START****************
function coderRequestsPaymentCollection($employerEmail,$amount,$explanation)
{
	$fromEmail=getLoggedInUser();	if(isMySqlError($fromEmail)) return $fromEmail;
	$x=isCoder();	if(isMySqlError($x)) return $x;
	if($x===false) return error_unauthorized_action();
	$toEmail=getAdmin();	if(isMySqlError($toEmail)) return $toEmail;
	$rId=createRequest($fromEmail,$toEmail,request_coder_requests_milestone(),$employerEmail,$percentage,$explanation);	if(isMySqlError($rId)) return $rId;
	$x=createNotification($toEmail,request_coder_requests_milestone(),"Coder $fromEmail would like to notify that its time for collecting some payments from employer $employerEmail.");	if(isMySqlError($x)) return $x;		//<TODO> if error, still return rId.
	return $rId;		
}

/*adminConfirmsPaymentCollection($coderEmail,$employerEmail,$percentage)
Permission: Admin 
Description: Admin confirms that a part of the payment is collected from the employer and so its secured.*/
function adminConfirmsPaymentCollection($coderEmail,$employerEmail,$percentage)
{
	//<TODO>
}

adminDeniesPaymentCollection($coderEmail,$employerEmail)
Permission: Admin
Description: Admin denies the coder the payment. The admin can reply the excuse for denying in the msgs.

function coderRequestsIncreaseInBudget($employerEmail,$byAmount,$explanation)
{
	$x=isCoder();	if(isMySqlError($x)) return $x;
	if($x===false) return error_unauthorized_action();
	$fromEmail=getLoggedInUser();	if(isMySqlError($fromEmail)) return $fromEmail;
	$toEmail=getAdmin();	if(isMySqlError($toEmail)) return $toEmail;
	$rId=createRequest($fromEmail,$toEmail,request_coder_requests_increased_budget(),$employerEmail,$byAmount,$explanation);	if(isMySqlError($rId)) return $rId;
	$x=createNotification($toEmail,request_coder_requests_increased_budget(),"Coder $fromEmail requests increase in budget from employer $employerEmail.");	if(isMySqlError($x)) return $x;			//<TODO> if error, still return rId.
	return $rId;
}

function employerRequestsDecreaseInBudget($coderEmail,$byAmount,$explanation)
{
	$x=isEmployer();	if(isMySqlError($x)) return $x;
	if($x===false) return error_unauthorized_action();
	$fromEmail=getLoggedInUser();	if(isMySqlError($fromEmail)) return $fromEmail;
	$toEmail=getAdmin();	if(isMySqlError($toEmail)) return $toEmail;
	$rId=createRequest($fromEmail,$toEmail,request_employer_requests_decreased_budget(),$coderEmail,$byAmount,$explanation);	if(isMySqlError($rId)) return $rId;
	$x=createNotification($toEmail,request_employer_requests_decreased_budget(),"Employer $fromEmail requests decrease in budget from coder $coderEmail.");	if(isMySqlError($x)) return $x;			//<TODO> if error, still return rId.
	return $rId;
}

function cancelBudgetChangeRequest($budgetChangeRequestId)
{
	$fromEmail=getLoggedInUser();	if(isMySqlError($fromEmail)) return $fromEmail;
	$toEmail=getAdmin();	if(isMySqlError($toEmail)) return $toEmail;
	$i=requestInfo($budgetChangeRequestId);	if(isMySqlError($i))return $i;
	if($i[0]!=$fromEmail) return error_unauthorized_action();
	$isEmployer=isEmployer();	if(isMySqlError($isEmployer)) return $isEmployer;
	$x=cancelRequest($budgetChangeRequestId);	if(isMySqlError($x)) return $x;
	if($isEmployer===true)
		$x=createNotification($toEmail,notification_user_cancels_budget_change_request(),"Employer $fromEmail cancels budget change request from coder {$i[2]}.");	if(isMySqlError($x)) return $x;			//<TODO> if error, still return rId.
	else
		$x=createNotification($toEmail,notification_user_cancels_budget_change_request(),"Coder $fromEmail cancels budget change request from employer {$i[2]}.");	if(isMySqlError($x)) return $x;			//<TODO> if error, still return rId.
	return true;
}

adminAcceptsChangeInBudget($budgetChangeRequestId)
Permission: Admin
Description: Admin can accept the budget change request by the coder or employer.

adminRejectsChangeInBudget($budgetChangeRequestId)
Permission: Admin
Description: Admin can reject the budget change request by the coder or employer.

function userRequestsWithdrawal($amount)
{
	$fromEmail=getLoggedInUser();	if(isMySqlError($fromEmail)) return $fromEmail;
	$toEmail=getAdmin();	if(isMySqlError($toEmail)) return $toEmail;
	$rId=createRequest($fromEmail,$toEmail,request_user_requests_withdrawal(),$amount);	if(isMySqlError($rId)) return $rId;
	$x=isCoder();	if(isMySqlError($x)) return $x;
	if($x===true)
		$x=createNotification($toEmail,request_user_requests_withdrawal(),"Coder $fromEmail requests withdrawal of amount $$amount.");	if(isMySqlError($x)) return $x;			//<TODO> if error, still return rId.
	else
		$x=createNotification($toEmail,request_user_requests_withdrawal(),"Employer $fromEmail requests withdrawal of amount $$amount.");	if(isMySqlError($x)) return $x;			//<TODO> if error, still return rId.		
	return $rId;
}

function userCancelsWithdrawalRequest($withdrawalRequestId)
{
	$fromEmail=getLoggedInUser();	if(isMySqlError($fromEmail)) return $fromEmail;
	$toEmail=getAdmin();	if(isMySqlError($toEmail)) return $toEmail;
	$i=requestInfo($budgetChangeRequestId);	if(isMySqlError($i))return $i;
	if($i[0]!=$fromEmail) return error_unauthorized_action();
	$isEmployer=isEmployer();	if(isMySqlError($isEmployer)) return $isEmployer;
	$x=cancelRequest($withdrawaleRequestId);	if(isMySqlError($x)) return $x;
	if($isEmployer===true)
		$x=createNotification($toEmail,notification_user_cancels_budget_change_request(),"Employer $fromEmail cancels withdrawal request.");	if(isMySqlError($x)) return $x;			//<TODO> if error, still return rId.
	else
		$x=createNotification($toEmail,notification_user_cancels_budget_change_request(),"Coder $fromEmail cancels withdrawal request.");	if(isMySqlError($x)) return $x;			//<TODO> if error, still return rId.
	return true;
}

adminConfirmsWithdrawal($withdrawalRequestId)
Permission: Admin
Description: Admin confirms the withdrawal after the money is sent to the coder.

payCoder($coderEmail,$amount)
Permission: Admin
Description: Admin puts the amount in the coder's account which the coder can request to withdraw anytime.

chargeCoder($coderEmail,$amount)
Permission: Admin
Description: Admin can charge the coder from his account reducing his account's balance. The balance can go in negative as well.

resetBudget($employerEmail,$coderEmail)
Permission: Admin
Description: The budget of employer and admin will be reset. Which means that the employer paid amount and the admin paid amount will be subtracted from the employer budget amount and the admin budget amount respectively to reduce the figures.
//*********Payments END****************





//*********Requests START****************
//Helper function to create a request.
function createRequest($fromEmail,$toEmail,$type,$param1,$param2="",$param3="")
{
	$gf=getDatabaseConnection();	if(isMySqlError($gf)) return $gf;
	$r=$gf->query("insert into pc_requests(r_fromemail,r_toemail,r_type,r_param1,r_param2,r_param3) values('$fromEmail','$toEmail','$type','$param1','$param2','$param3')");
	if($r)
		return true;
	else
		return error_unknown();
}

/*
Permission: Creator of request (Coder,Employer)
Description: Creator of the request can cancel the request.
*/
function cancelRequest($requestId)
{
	//<TODO>
	//call markRequestForAutoDeletion()
}

//Returns information about a request. 
function requestInfo($requestId)	//Returns Array(request_creator_email, request_type, email_of_user_request_is_referring_to, $byAmount, $explanation)
{
	//<TODO>
}

//Sets the expiry date of the request to 30 days after current system date.
function markRequestForAutoDeletion($requestId)
{
	//<TODO>
}

//*********Requests END****************








//*********Chron START****************

//*********Chron END****************


?>