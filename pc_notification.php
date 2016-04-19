<?php
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
?>
