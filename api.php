<?php

/*
Admin can assign a coder to an employer or unassign. Once assigned, the coder and employer can communicate with each other through chat as well as offline msgs. As well files can be shared with other. Coder and employer can always send msg to admin, and admin can send msgs to coders and employers.

The chat, messages and files will be scanned for any words such as gmail, usd, how much, numbers etc. If such words are present, it will be marked and admin will be notified of it.

The software will ask the user to log in. The software will know which user is coder and which is client and which is admin.

The coder will be able to leave a request for admin to collect payments from the client in order to secure funds for himself. When the request is submitted, the admin will be notified. Admin can mark the payment complete whenever he wants to, or mark it denied.

The employer can submit a request to find a new coder. 
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

sendFile($toEmail)
sendMessageToAdmin($msg)
sendFileToAdmin()

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