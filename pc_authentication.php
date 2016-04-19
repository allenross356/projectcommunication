<?php
//*********Authentication START****************

session_start();

//Tells whether the logged in user is admin or not.
function isAdmin()
{
	//<TODO> Implement error handlers.
	$x=getLoggedInUserType();
	if($x=="admin") return true;
	return false;
}

//Tells whether the logged in user is coder or not.
function isCoder()
{
	//<TODO> Implement error handlers.
	$x=getLoggedInUserType();
	if($x=="coder") return true;
	return false;
}

//Tells whether the specified user is coder or not.
function isCoder($email)
{
	//<TODO> Implement error handlers.
	$x=getUserType($email);
	if($x=="coder") return true;
	return false;
}

//Tells whether the logged in user is employer or not.
function isEmployer()
{
	//<TODO> Implement error handlers.
	$x=getLoggedInUserType();
	if($x=="employer") return true;
	return false;
}

//Tells whether the specified user is employer or not.
function isEmployer($email)
{
	//<TODO> Implement error handlers.
	$x=getUserType($email);
	if($x=="employer") return true;
	return false;
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
		{
			if($row=$r->fetch_array())
				return $row['u_balance'];
			else
				return error_unknown();
		}
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

function doesUserExist($email)
{
	//<TODO> Implement the error handlers
	$g=getDatabaseConnection();
	$q="select u_name from pm_users where u_email='$email'";	//<TODO Make query injection proof>
	$r=$g->query($q);
	if($r)
	{
		if($r->num_rows==0)
			return false;	
		elseif($r->num_rows==1)
			return true;
		else
			return error_multiple_records();	 
	}
	else
		return error_unknown();	//<TODO> confirm if it won't return error string when num_rows=0
}

function authenticateUser($email,$pass)
{
	//<TODO> Implement secure encrypted password.
	//<TODO> Implement error handling.
	$gf=getDatabaseConnection();
	$r=$gf->query("select * from pc_users where u_email='$email' and u_pass='$pass'");
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

function login($email,$pass)
{
	//<TODO> Implement secure token session instead of storing email.
	//<TODO> Implement SSL security. 
	$x=authenticateUser($email,$pass);
	if($x===true)
	{
		$_SESSION['email']=$email;
		return true;
	}
	else
		return false;
}

function logout()
{
	unset($_SESSION['email']);
	return true;
}

function getLoggedInUser()
{
	//<TODO> Implement error handling.
	return $_SESSION['email'];
}

//Returns the type of the logged in user.
function getLoggedInUserType()
{
	//<TODO> Implement error handlers
	$email=getLoggedInUser();
	return getUserType($email);
}

//Returns type of the specified user.
function getUserType($email)
{
	//<TODO> Implement error handlers 
	$gf=getDatabaseConnection();
	$r=$gf->query("select u_type from pc_users where u_email='$email'");
	if($r)
	{
		if($r->num_rows==1)
		{
			if($row=$r->fetch_array())
				return $row['u_type'];
			else
				return error_unknown();
		}
		elseif($r->num_rows==0)
		{
			return error_no_records_found();
		}
		else
			return error_multiple_records();
	}
	else
		return error_unknown();	
}
//*********Authentication END****************
?>
