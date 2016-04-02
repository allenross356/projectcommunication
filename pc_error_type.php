<?php
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

function error_withdrawal_exceeds_balance()
{
	return "error_withdrawal_exceeds_balance";
}

function isMySqlError($x)
{
	if($x=="error_multiple_records" || $x=="error_database_connection" || $x=="error_no_user_logged_in" || $x=="error_unknown" || $x=="error_unauthorized_action" || $x=="error_no_records_found" || $x=="error_withdrawal_exceeds_balance") return true;
	return false;
}
//*********Errors END****************
?>