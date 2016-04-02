<?php
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

//Returns employer and admin budget in the form array(employer budget, admin budget)
function getBudget($employerEmail,$coderEmail)
{
	$gf=getDatabaseConnection();	if(isMySqlError($gf)) return $gf;
	$r=$gf->query("select c_employerbudget, c_adminbudget from pc_connections where c_employeremail='$employerEmail' and c_coderemail='$coderEmail'");
	if($r)
	{
		if($r->num_rows==1)
		{
			if($row=$r->fetch_array())
				return $row;
			else
				return error_unknown();
		}
		elseif($r->num_rows==0)
			return error_no_records_found();
		else
			return error_multiple_records(); 
	}
	else
		return error_unknown();
}

function adminConfirmsPaymentCollection($coderEmail,$employerEmail,$employerAmount,$coderAmount,$coderPay)
{
	$x=isAdmin();	if(isMySqlError($x)) return $x;
	if($x===false) return error_unauthorized_action();
	$x=chargeUser($employerEmail,$employerAmount);	if(isMySqlError($x)) return $x;	
	$x=payUser($coderEmail,$coderPay);	if(isMySqlError($x)) return $x;	 //<TODO> make this function atomic, and roll-backable if any step fails.
	//<TODO> implement accuracy of upto 10 digits after decimal.
	$x=createNotification($coderEmail,notification_milestone_confirmed(),"Payment is received from $employerEmail of $$coderAmount.");	if(isMySqlError($x)) return $x; //<TODO> return true even if notification fails.
	if($coderPay>0)	//<TODO> Check this equation later
	{
		$x=createNotification($coderEmail,notification_coder_payment(),"Payment made in your account of $$coderPay from $employerEmail.");	if(isMySqlError($x)) return $x; //<TODO> return true even if notification fails.
	}
	return true;
}

function adminConfirmsPaymentCollectionRequest($requestId,$employerAmount,$coderAmount,$coderPay,$explanation="")
{
	$x=isAdmin();	if(isMySqlError($x)) return $x;
	if($x===false) return error_unauthorized_action();
	$i=getRequestInfo($requestId);	if(isMySqlError($i)) return $i;
	$x=chargeUser($i[3],$employerAmount);	if(isMySqlError($x)) return $x;
	$x=payUser($i[0],$coderPay);	if(isMySqlError($x)) return $x;	//<TODO> make this function atomic, and roll-backable if any step fails.
	$nt="Payment is received from {$i[3]} of $$coderAmount.";
	if($explanation!=="" && $explanation!==null && $explanation!==false) $nt.=" Explanation from admin: $explanation";
	$x=createNotification($i[0],notification_milestone_confirmed(),$nt);	if(isMySqlError($x)) return $x;		//<TODO> return true even if notification fails.
	if($coderPay>0)	//<TODO> Check this equation later
	{
		$x=createNotification($i[0],notification_coder_payment(),"Payment made in your account of $$coderPay from {$i[2]}.");	if(isMySqlError($x)) return $x; //<TODO> return true even if notification fails.
	}
	$x=markRequestApproved($requestId);	if(isMySqlError($x)) return $x; //<TODO> return true even if notification fails.
	return true;
}

function adminDeniesPaymentCollection($requestId,$explanation="")
{
	$x=isAdmin();	if(isMySqlError($x)) return $x;
	if($x===false) return error_unauthorized_action();
	$i=getRequestInfo($requestId);
	$x=markRequestDenied($requestId);
	$nt="Your milestone request from {$i[3]} of amount ${$i[4]} is denied.";
	if($explanation!=="" && $explanation!==null && $explanation!==false) $nt.=" Explanation from admin: $explanation";
	$x=createNotification($coderEmail,notification_coder_milestone_request_denied(),$nt);	if(isMySqlError($x)) return $x; //<TODO> return true even if notification fails.
	return true;
}

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

coderDecreasesBudget($employerEmail,$byAmount)				
Permission: Coder
Description: Coder decreases the budget of the project. The admin is notified of the decision.

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

employerIncreasesBudget($coderEmail,$byAmount)				
Permission: Employer
Description: Employer increases the budget of the project. The admin is notified of the decision.

function cancelBudgetChangeRequest($budgetChangeRequestId)
{
	$fromEmail=getLoggedInUser();	if(isMySqlError($fromEmail)) return $fromEmail;
	$toEmail=getAdmin();	if(isMySqlError($toEmail)) return $toEmail;
	$i=getRequestInfo($budgetChangeRequestId);	if(isMySqlError($i))return $i;
	if($i[0]!=$fromEmail) return error_unauthorized_action();
	$isEmployer=isEmployer();	if(isMySqlError($isEmployer)) return $isEmployer;
	$x=cancelRequest($budgetChangeRequestId);	if(isMySqlError($x)) return $x;
	if($isEmployer===true)
		$x=createNotification($toEmail,notification_user_cancels_budget_change_request(),"Employer $fromEmail cancels budget change request from coder {$i[2]}.");	if(isMySqlError($x)) return $x;			//<TODO> if error, still return rId.
	else
		$x=createNotification($toEmail,notification_user_cancels_budget_change_request(),"Coder $fromEmail cancels budget change request from employer {$i[2]}.");	if(isMySqlError($x)) return $x;			//<TODO> if error, still return rId.
	return true;
}

function adminAcceptsChangeInBudget($budgetChangeRequestId)
{
	//<TODO> Error handling
	$gf=getDatabaseConnection();
	$x=isAdmin();  
	if($x===false) return error_unauthorized_action(); 
	$i=getRequestInfo($budgetChangeRequestId);
	$x=isCoder($i[0]);
	if($x===true)
	{
		$coderEmail=$i[0];
		$employerEmail=$i[3];
		$field="c_adminbudget";
		$sign="+";
	}
	else
	{
		$coderEmail=$i[3];
		$employerEmail=$i[0];
		$field="c_employerbudget";
		$sign="-";
	}
	$byAmt=$i[4];
	$r=$gf->query("update pc_connections set $field = $field $sign $byAmt where c_coderemail='$coderEmail' and c_employeremail='$employerEmail'");
	//<TODO> Error handling with $r
	
	markRequestApproved($budgetChangeRequestId);
	createNotification($i[0],notification_admin_accepts_change_in_budget(),"Your request of change of budget is accepted.");	//<TODO> Put more specific notification msg.
	return true;
}

function adminRejectsChangeInBudget($budgetChangeRequestId)
{	//<TODO> implement error handling
	$x=isAdmin();
	if($x===false) return error_unauthorized_action();
	$i=getRequestInfo($budgetChangeRequestId);
	markRequestDenied($budgetChangeRequestId);
	createNotification($i[0],notification_admin_rejects_change_in_budget(),"Your request of change of budget is rejected. Contact admin to know the reasons.");	//<TODO> Put more specific notification msg.
	return true;
}

//Returns the total of pending withdrawal request amounts of the logged in user.
function getTotalWithdrawals()
{
	//<TODO> Implement error checking.
	$email=getLoggedInUser();
	$type=request_user_requests_withdrawal();
	$gf=getDatabaseConnection();
	$r=$gf->query("select sum(r_param1) as totalamount from pc_requests where r_type='$type' and r_fromemail='$email' and r_status=''");	//<TODO> check 'as' clause.
	if($r)
	{
		if($r->num_rows==1)
		{
			if($row=$r->fetch_array())
				return $row['totalamount'];
			else
				return error_unknown();
		}
		elseif($r->num_rows==0)
			return error_unknown();
		else
			return error_multiple_records();
	}
	else
		return error_unknown();
}

//Returns the total balance of the user with specified email. Trusts the calling function that the calling function will always provide the email of the logged in user only.
//$gf is the database connection that the calling function will provide.
function getTotalBalance($gf,$email)
{
	$r=$gf->query("select u_balance from pc_users where u_email='$email'");
	if($r)
	{
		if($r->num_rows==1)
		{
			if($row=$r->fetch_array())
				return $row['u_balance'];
			else
				return error_unknown();
		}
		elseif($r->num_rows==0)
			return error_no_records_found();
		else 
			return error_multiple_records();
	}
	else
		return error_unknown();
}

function userRequestsWithdrawal($amount)
{
	//<TODO>  implement check: amount of pending withdrawals plus current request shudnt be greater than total balance.
	$fromEmail=getLoggedInUser();	if(isMySqlError($fromEmail)) return $fromEmail;
	$toEmail=getAdmin();	if(isMySqlError($toEmail)) return $toEmail;
	$tw=getTotalWithdrawals();
	$twu=$amount+$tw;
	$tb=getTotalBalance($gf,$fromEmail);
	if($twu>$tb)
		return error_withdrawal_exceeds_balance();
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
	$i=getRequestInfo($budgetChangeRequestId);	if(isMySqlError($i))return $i;
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
function adminConfirmsWithdrawal($withdrawalRequestId)
{
	$gf=getDatabaseConnection();
	$i=getRequestInfo($withdrawalRequestId);
	
	update pc_users set u_balance=u_balance-$amount
	
	//<TODO> START FROM HERE
}


payUser($email,$amount)
Permission: Admin
Description: Admin puts the amount in the coder's account which the coder can request to withdraw anytime.

chargeUser($email,$amount)
Permission: Admin
Description: Admin can charge the coder from his account reducing his account's balance. The balance can go in negative as well.

resetBudget($employerEmail,$coderEmail)
Permission: Admin
Description: The budget of employer and admin will be reset. Which means that the employer paid amount and the admin paid amount will be subtracted from the employer budget amount and the admin budget amount respectively to reduce the figures.
//*********Payments END****************
?>