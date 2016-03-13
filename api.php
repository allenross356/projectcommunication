<?php

/*
Admin can assign a coder to an employer or unassign. Once assigned, the coder and employer can communicate with each other through chat as well as offline msgs. As well files can be shared with other. Coder and employer can always send msg to admin, and admin can send msgs to coders and employers.

The chat, messages and files will be scanned for any words such as gmail, usd, how much, numbers etc. If such words are present, it will be marked and admin will be notified of it.

The software will ask the user to log in. The software will know which user is coder and which is client and which is admin.

The coder will be able to leave a request for admin to collect payments from the client in order to secure funds for himself. When the request is submitted, the admin will be notified. Admin can mark the payment complete whenever he wants to, or mark it denied.

The employer can submit a request to find a new coder. 
*/


/*
TODO:
Implement url security, webrequest security, tokens for authentications.
Implement SSL.
Implement file access security so that the uploaded file links are only accessible by users who are logged in and who have permission to access the file.

Implement Database semaphores for atomic operations.

Encryption of passwords

Implement Mysql injection proof queries
*/


//*********Authentication START****************
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

*/


//*********Authentication END****************






//*********Messaging START****************
/*
sendMessage($toEmail,$msg)
Permission: Coder, Employer, Admin
Description: Connected Coder and employer can send msg to each other. Any coder and employer can send msgs to admin, and admin can send msgs to any coder or employer. Admin's email is always my personal email address. Function returns a msg ID on success, or false otherwise.

sendFile($toEmail)
Permission: Coder, Employer, Admin 
Description: Similar to sendMessage but with files. Function returns a file ID as well as a file link on success, or false otherwise.

forwardFile($toEmail,$fileId)
Permission: Coder, Employer, Admin
Description: Any file that a user receives from others, he can forward to someone else. Return true or false.

forwardMessage($toEmail,$msgId)
Permission: Coder, Employer, Admin
Description: Any msg that a user receives from others, he can forward to someone else. Return true or false.

retrieveMessages($fromEmail,$startIndex)
Permission: Coder, Employer, Admin
Description: Retrieves a list of 20 msgs received from a sender starting from $startIndex arranged in decreasing order with respect to date.

retrieveFiles($fromEmail,$startIndex)
Permission: Coder, Employer, Admin
Description: Retrieves a list of 20 files' links received from a sender starting from $startIndex arranged in decreasing order with respect to date.

retrieveConnections($startIndex)
Permission: Coder, Employer, Admin
Description: Retrieves 20 connections of the currently logged in user starting from $startIndex. 
*/
//*********Messaging END****************


//*********Scanning START****************
//*********Scanning END****************


//*********Coder START****************

//*********Coder END****************


//*********Employer START****************
/*
requestToFindNewCoder()
*/
//*********Employer END****************

//*********Admin START****************
/*
assign($coderEmail,$employerEmail)
unassign($coderEmail,$employerEmail)
*/
//*********Admin END****************

//*********GUI START****************
/*
getEmployerConnections($employerEmail)
getCoderConnections($coderEmail)
*/
//*********GUI START****************










?>