<?php
//*********Authentication START****************

//Tells whether the logged in user is admin or not.
function isAdmin()
{
	//<TODO>
}

//Tells whether the logged in user is coder or not.
function isCoder()
{
	//<TODO>
}

//Tells whether the specified user is coder or not.
function isCoder($email)
{
	//<TODO>
}

//Tells whether the logged in user is employer or not.
function isEmployer()
{
	//<TODO>
}

//Tells whether the specified user is employer or not.
function isEmployer($email)
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
?>