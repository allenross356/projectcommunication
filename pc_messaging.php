<?php
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
?>