<?php

//***************************************************** STORY *****************************************************

/*
Admin can assign a coder to an employer or unassign. Once assigned, the coder and employer can communicate with each other through chat as well as offline msgs. As well files can be shared with other. Coder and employer can always send msg to admin, and admin can send msgs to coders and employers. (Covered,)

The chat, messages and files will be scanned for any words such as gmail, usd, how much, numbers etc. If such words are present, it will be marked and admin will be notified of it. (Covered,)

The software will ask the user to log in. The software will know which user is coder and which is client and which is admin. (Covered,)

The coder will be able to leave a request for admin to collect payments from the client in order to secure funds for himself. When the request is submitted, the admin will be notified. Admin can mark the payment complete whenever he wants to, or mark it denied. (Covered,)

Coder can request admin to increase the price of the project. Employer can request admin to decrease the price of the project. Admin can accept or reject the request. (Covered,)

Coder can request payment withdrawal. (Covered,)

The employer can submit a request to find a new coder. (Covered,)

Coder and employer can report each other, and they can call admin to interject for some problem. (Covered,)

Users can mark notifications, msgs and files as seen or unseen. (Covered,)

Email of the user is verified.

User can change the email and password. User can request password if he forgets. User can add multiple contact informations including emails.
*/


/*
TODO:
Implement url security, webrequest security, encypted & expirable tokens for authentications.
Implement SSL.
Implement file access security so that the uploaded file links are only accessible by users who are logged in and who have permission to access the file.

Implement Database semaphores for atomic operations.

Encryption of passwords

Implement Mysql injection proof queries

Implement Update functionality in GUI.
*/
















//***************************************************** INTERFACE *****************************************************

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

getLoggedInUser()
Permission: Coder, Employer, Admin
Description: Returns the email address of the logged in user. False if no user logged in.
*/


//*********Authentication END****************






//*********Messaging START****************
/*
canCommunicate($toEmail)
Permission: Coder, Employer, Admin (Admin don't really need to use this function)
Description: Will return true if the logged in user is allowed to communicate with user with $toEmail email. False otherwise.

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

markMsgSeen($msgId)
Permission: Receiver of msg (Admin, Coder, Employer)
Description: Receiver of the msg can manually mark the msg as seen. However, the msg will be marked as seen automatically when its displayed to the receiver.

markMsgUnseen($msgId)
Permission: Receiver of msg (Admin, Coder, Employer)
Description: Receiver of the msg can manually mark the msg as unseen.

markFileSeen($msgId)
Permission: Receiver of file (Admin, Coder, Employer)
Description: Receiver of the file can manually mark the file as seen. However, the file will be marked as seen automatically when its displayed to the receiver.

markFileUnseen($msgId)
Permission: Receiver of file (Admin, Coder, Employer)
Description: Receiver of the file can manually mark the file as unseen.

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








//*********Notification START****************
/*
markNotificationSeen($notificationId)
Permission: Receiver of the notification (Employer, Coder, Admin)
Description: User can manually mark a notification as seen. However, when a notification is displayed to the user, it will automaticatically be marked as seen.

markNotificationUnseen($notificationId)
Permission: Receiver of the notification (Employer, Coder, Admin)
Description: User can manually mark a notification as unseen.
*/
//*********Notification END****************







//*********Payments START****************
/*
coderNotifiesPaymentCollection($employerEmail,$percentage,$explanation)
Permission: Coder
Description: Coder requests admin to collect a portion of funds from client to secure them since a part of the project or entire project is completed. This amount will be displayed to the coder as secured amount along with the total secured amount.

adminConfirmsPaymentCollection($coderEmail,$employerEmail,$percentage)
Permission: Admin 
Description: Admin confirms that a part of the payment is collected from the employer and so its secured.

adminDeniesPaymentCollection($coderEmail,$employerEmail)
Permission: Admin
Description: Admin denies the coder the payment. The admin can reply the excuse for denying in the msgs.

coderRequestsIncreaseInBudget($employerEmail,$byAmount,$explanation)
Permission: Coder
Description: Coder requests admin to increase the budget of the project. The admin is notified of the request.

employerRequestsDecreaseInBudget($coderEmail,$byAmount,$explanation)
Permission: Employer
Description: Employer can request admin to decrease the budget with an explanation.

cancelBudgetChangeRequest($budgetChangeRequestId)
Permission: Creator of the budget change request (Employer or Coder).
Description: Whoever created the budget change request can cancel the request as well.

adminAcceptsChangeInBudget($budgetChangeRequestId)
Permission: Admin
Description: Admin can accept the budget change request by the coder or employer.

adminRejectsChangeInBudget($budgetChangeRequestId)
Permission: Admin
Description: Admin can reject the budget change request by the coder or employer.

coderRequestsWithdrawal($coderEmail,$amount)
Permission: Coder
Description: Coder requests the admin the withdrawal from his account. Returns true if successful, or False if the amount requested is greater than the balance in his account.

adminConfirmsWithdrawal($withdrawalRequestId)
Permission: Admin
Description: Admin confirms the withdrawal after the money is sent to the coder.

payCoder($coderEmail,$amount)
Permission: Admin
Description: Admin puts the amount in the coder's account which the coder can request to withdraw anytime.

chargeCoder($coderEmail,$amount)
Permission: Admin
Description: Admin can charge the coder from his account reducing his account's balance. The balance can go in negative as well.
*/
//*********Payments END****************






//*********Report START****************
/*
reportUser($email,$explanation)
Permission: Employer, Coder
Description: Employer or coder can report each other.

reportActionTaken($reportId,$explanation)
Permission: Admin
Description: Admin marks the report as taken-care-of, and leaves a remark for the reporter of what kind of action was taken.
*/
//*********Report END****************









//*********Employer START****************
/*
requestToFindNewCoder($coderEmail,$explanation)
Permission: Employer
Description: Employer can request admin to find another coder. The admin will be notified of this request.
*/
//*********Employer END****************













//*********Admin START****************
/*
assign($coderEmail,$employerEmail)
Permission: Admin only.
Description: Connects a coder and employer, so that they can communicate with each other.

unassign($coderEmail,$employerEmail)
Permission: Admin only.
Description: Unconnects a coder and employer, so that they won't be able to communicate with each other anymore.
*/
//*********Admin END****************












//*********GUI START****************
/*
getConnections($startIndex)
Permission:
Description: The client app can request the list of 20 connections of the logged in user starting from $startIndex.

getNotifications($startIndex)
Description: The client app can request the list of 20 notifications of the logged in user starting from $startIndex arranged in decreasing order by date.

*/
//*********GUI START****************






//***************************************************** DATABASE *****************************************************
//Database Name: projectcommunication
//*********Users START****************
/*
//Table Name:	pc_users
user id (u_id)
full name (u_name)
email (u_email)
password (u_pass)
date time (u_creation_dt): Date-time of the account creation.
is email verified (u_emailverified): Boolean. Denotes if the email is verified.
*/
//*********Users END****************




//*********Sessions START****************
/*
session id
email
token
creation date time
expire date time
*/
//*********Users END****************





//*********Connections START****************
/*
connection id
user 1 email
user 2 email
*/
//*********Connections END****************





//*********Messages START****************
/*
message id
from email
to email
message 				:	String
is forwarded			:	Boolean
forwarded message id	
date time
*/
//*********Messages END****************




//*********Files START****************
/*
file id
from email
to email
file link				:	String
is forwarded			:	Boolean
forwarded file id	
date time
*/
//*********Files END****************




//***************************************************** DEFINITION *****************************************************

//*********Messaging START****************
function 
//*********Messaging END****************




?>