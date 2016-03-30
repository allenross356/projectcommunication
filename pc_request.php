<?php
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
function getRequestInfo($requestId)	//Returns Array(from_email, to_email, request_type, param1, param2, param3)
{
	//<TODO>
}

//Sets the expiry date of the request to 30 days after current system date.
function markRequestForAutoDeletion($requestId)
{
	//<TODO>
}

function markRequestApproved($requestId)
{
	$gf=getDatabaseConnection();	if(isMySqlError($gf)) return $gf;
	$r=gf->query('update pc_requests set r_status="approved" where r_id=$requestId');
	if($r)
	{
		if($gf->affected_rows==0)
			return error_no_records_found();
		elseif($gf->affected_rows==1)
			return true;
		else
			return error_multiple_records();
	}
	else
		return error_unknown();
}

function markRequestDenied($requestId)
{
	$gf=getDatabaseConnection();	if(isMySqlError($gf)) return $gf;
	$r=gf->query('update pc_requests set r_status="denied" where r_id=$requestId');
	if($r)
	{
		if($gf->affected_rows==0)
			return error_no_records_found();
		elseif($gf->affected_rows==1)
			return true;
		else
			return error_multiple_records();
	}
	else
		return error_unknown();
}
//*********Requests END****************
?>