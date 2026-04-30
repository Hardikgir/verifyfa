<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmailController extends CI_Controller {

	public function __construct() {
		parent::__construct();
        $this->load->library('session');	
        $this->load->model('Admin_model');
        $this->load->model('Registered_user_model');        
    }

    public function emailattachment(){
        // URL :- http://localhost:8080/codeigniter/verifyfa/index.php/EmailController/emailattachment
        
        // $name = 'sample.pdf';
        // $filename = 'sample.pdf';
        $filename = 'test.xls';
        $name = 'test.xls';
        
        $file = FCPATH."attachment/".$name;
        
        $email_actual_content = '
            <table role="presentation"
                style="width: 100%;border-collapse: collapse;border: 0px;border-spacing: 0px;font-family: Arial, Helvetica, sans-serif;background-color: rgb(250, 250, 250);">
                <tbody>
                <tr>
                    <td align="center" style="padding: 1rem 2rem; vertical-align: top; width: 100%;">
                    <table role="presentation" style="max-width: 600px; border-collapse: collapse; border: 0px; border-spacing: 0px; text-align: left;">
                        <tbody>
                        <tr>
                            <td style="padding: 40px 0px 0px;">
                            <div style="text-align: left;">
                                <div style="padding-bottom: 20px;text-align: center;">
                                    <img src="https://verifyfa.developmentdemo.co.in/assets/img/logo.png" alt="APPLICATIONLOGOCompany" style="width: 56px;">
                                </div>
                            </div>
                            <div style="padding: 20px;background-color: rgb(255, 255, 255);border: 1px solid grey;">
                                <div style="color: rgb(0, 0, 0); text-align: left;">

                                    <p style="font-size: 14px;color: gray;text-align: center;">
                                    ***** This is an auto generated NO REPLY communication and replies to this email id are
                                    not attended to. (Business Hours from Mon To Sat : 10:00am to 6:00pm) *****
                                    </p>

                                    <p style="font-size: 18px;"> TRANSACTIONRECORDDATETIME </p>
                                    <p style="font-size: 18px;">Dear <b>RECEIVERNAME</b>,</p>

                                    <p style="font-size: 18px;line-height: 28px;">


                                    Thank you for reaching out to us at the <b>APPLICATIONNAME</b> Complaint Care Centre.
                                    <br>

                                    The Complaint Tracking # TRACKINGID # has been generated against your complaint registered with us.
                                    <br>
                                    You can track its status online by logging here <a href="https://abhiyoga.developmentdemo.co.in/SETPASSWORDWEBLINK" target="_blank">Click Here</a>.
                                    We are working on your request and we will get back to you within RESPONSETAT.
                                    We strive to resolve all complaints within due time allotted. 
                                    </p>

                                <p style="font-size: 18px;">Thanks for your support and understanding. <br>                                
                                Regards, <br>
                                <b>COMPANYNAME</b></p>
                                 <div style="text-align: left;">
                                     <div style="padding-bottom: 20px">
                                        <img src="https://verifyfa.developmentdemo.co.in/assets/img/logo.png" alt="Company" style="width: 56px;">
                                    </div>
                                </div>

                                <p style="font-size: 14px;color: gray;text-align: center;">*****This is a system generated communication and does not require signature. *****</p>

                                </div>
                            </div>
                            <div style="padding-top: 20px; color: rgb(153, 153, 153); text-align: justify;">
                                Copyright <b>COMPANYNAME</b>. All rights reserved. Terms & Conditions Please do not share your Login details, such as User ID / Password / OTP with anyone, either over phone or through email.
                                Do not click on link from unknown/ unsecured sources that seek your confidential information. 
                                This email is confidential. It may also be legally privileged. If you are not the addressee, you may not copy, forward, disclose or use any part of it. Internet communications cannot be guaranteed to be timely, secure, error or virus free. The sender does not accept liability for any errors or omissions. We maintain strict security standards and procedures to prevent unauthorised access to any personal information about you.
                                Kindly read through the Privacy Policy on our website for use of Personal Information.
                                </p>
                            

                            </div>
                            <div style="padding-top: 20px; color: rgb(153, 153, 153); text-align: center;">
                            <a href="https://abhiyoga.developmentdemo.co.in/FOOTERHOMEPAGELINK">Home</a> | <a href="https://abhiyoga.developmentdemo.co.in/FOOTERPRIVECYPOLICYPAGELINK">Privacy Policy</a> | <a href="https://abhiyoga.developmentdemo.co.in/FOOTERDISCLAIMERPAGELINK">Disclaimer</a> | <a href="https://abhiyoga.developmentdemo.co.in/FOOTERSIGNINPAGELINK">Sign in</a>
                            </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
                </tbody>
            </table>';

        
        
        
        // $to = 'hardik.meghnathi12@gmail.com';
        $to = 'tusharparmartlsu1507@gmail.com';
        $subject = " Email Attachment";
        
        $CI = setEmailProtocol();
        $from_email = 'solutions@ethicalminds.in';

        
        $CI->email->set_newline("\r\n");
        $CI->email->set_mailtype("html");
        $CI->email->from($from_email);
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($email_actual_content);
        $CI->email->attach($file);

        $mailsend = 0;
        if($CI->email->send()){
            $mailsend = 1;
        }else{
            show_error($this->email->print_debugger());
        }

        echo '<pre>mailsend : ';
        print_r($mailsend);
        echo '</pre>';
        exit();


    }



public function accountUnlockEmail()
{
    $email_actual_content = '
    <h2>VerifyFA</h2>

    <p>Dear Admin,</p>

    <p>You are requested to unlock Username: romi@gmail.com under Entity Code: ENT001</p>

    <p>Regards,<br>VerifyFA Team</p>
    ';

    $to = 'yourgmail@gmail.com';
    $subject = 'VerifyFA - Account Unlock Request';

    $CI = setEmailProtocol();

    $CI->email->set_newline("\r\n");
    $CI->email->set_mailtype("html");
    $CI->email->from('solutions@ethicalminds.in');
    $CI->email->to($to);
    $CI->email->subject($subject);
    $CI->email->message($email_actual_content);

    if($CI->email->send()){
        echo "Mail Sent";
    } else {
        echo $CI->email->print_debugger();
    }
}











    /**
     * Account ID Unlocking / Password Reset Email
     * Test URL: http://localhost/verifyfa/index.php/EmailController/accountIdUnlocking/1
     */
    public function accountIdUnlocking($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        
        if(!$user) {
            echo "Error: User with ID $userId not found.";
            return;
        }

        $receiverName = $user->first_name . " " . $user->last_name;
        $tempPassword = "TEMP_" . rand(1000, 9999);
        $transactionDate = date("d-M-Y H:i:s");
        $applicationName = "VerifyFA";
        $companyName = "VerifyFA Support Team";
        $webLink = base_url() . "index.php/login/VerifyForChangePassword";
        
        $to = $user->email_id; 
        $subject = "VerifyFA - Account ID Unlocking / Password Reset";

        $email_actual_content = '
            <table role="presentation" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <tr>
                    <td align="center">
                        <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px; text-align: left;">
                            <tr>
                                <td style="padding: 20px;">
                                    <p style="font-size: 12px; color: #777777; text-align: center;">
                                        ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                                    </p>
                                    
                                    <p style="font-weight: bold; color: #333333;">' . $transactionDate . '</p>
                                    
                                    <p>Dear <b>' . $receiverName . '</b>,</p>
                                    
                                    <p style="line-height: 1.6;">
                                        Upon your request to reset Password/ unlocking the User Account, a Temporary Password has been generated: 
                                        <b style="color: #d9534f; font-size: 18px;">' . $tempPassword . '</b>
                                    </p>
                                    
                                    <p>You requested to enter New Password on <b>' . $applicationName . '</b>.</p>
                                    
                                    <p>Please click on the link to setup your New Password: 
                                        <a href="' . $webLink . '" target="_blank" style="color: #337ab7; font-weight: bold;">Click Here to Setup Password</a>
                                    </p>
                                    
                                    <p style="margin-top: 30px;">
                                        Thanks for your support and understanding.<br>
                                        Regards,<br>
                                        <b>' . $companyName . '</b>
                                    </p>
                                    
                                    <p style="font-size: 12px; color: #777777; text-align: center; border-top: 1px solid #eeeeee; padding-top: 15px;">
                                        *****This is a system generated communication and does not require signature. *****
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';

        $this->_sendEmailDynamic($to, $subject, $email_actual_content);
    }



//according to formate 
/**
 * Registration / Activation Email
 * URL:
 * http://localhost/verifyfa/index.php/EmailController/registrationActivation/5
 */
public function registrationActivation1($userId = 1)
{
    // Get user details
    $user = $this->Registered_user_model->get_registerd_user($userId);

    if (!$user) {
        echo "User not found.";
        return;
    }

    /*
    --------------------------------------------------
    YOUR REAL DATABASE COLUMN NAMES
    --------------------------------------------------
    first_name
    last_name
    email_id
    activation_link
    --------------------------------------------------
    */

    $receiverName = trim($user->first_name . ' ' . $user->last_name);
    $to           = $user->email_id;

    // Safety check
    if (empty($to)) {
        echo "Email ID not found.";
        return;
    }

    // Temporary Password
    $tempPassword = "AUTH_" . rand(1000, 9999);

    // Use DB activation link if exists
    if (!empty($user->activation_link)) {
        $activationLink = $user->activation_link;
    } else {
        $activationLink = base_url('index.php/login/validate/' . md5($to));
    }

    // Subject line as required format
    $subject = "VerifyFA - Activate Your Account and Setup New Password";

    // Date Time
    $dateTime = date('d-m-Y h:i A');

    // Logo Path
    $logo = base_url('assets/img/logo.png');

    // Email HTML Content
    $email_content = '
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
        <tr>
            <td align="center">

                <table width="650" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <img src="' . $logo . '" height="70">
                        </td>
                    </tr>

                    <!-- Auto Generated Message -->
                    <tr>
                        <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                            ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                        </td>
                    </tr>

                    <!-- Date -->
                    <tr>
                        <td style="font-size:13px;color:#666;padding-bottom:15px;">
                            ' . $dateTime . '
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="font-size:15px;color:#333;padding-bottom:15px;">
                            Dear <b>' . $receiverName . '</b>,
                        </td>
                    </tr>

                    <!-- Message -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:24px;padding-bottom:15px;">
                            Thanks for registering on <b>VerifyFA</b>.<br>
                            It is important to activate your account in due time to continue further.
                        </td>
                    </tr>

                    <!-- Password -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:15px;">
                            Your Temporary Password for 1st time login is:
                            <b style="color:#d9534f;">' . $tempPassword . '</b>
                        </td>
                    </tr>

                    <!-- please click on the link to setup your new password -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:24px;padding-bottom:15px;">
                           please click on the link to setup your new password 
                        </td>
                    </tr>

                    <!-- Button -->
                    <tr>
                        <td align="center" style="padding:20px 0;">
                            <a href="' . $activationLink . '" 
                               style="background:#007bff;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:5px;font-size:14px;display:inline-block;">
                               Activate Account & Setup New Password
                            </a>
                        </td>
                    </tr>

                    <!-- Thanks -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:15px;">
                            Thanks for your support and understanding.
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:20px;">
                            Regards,<br>
                            <b>VerifyFA</b>
                        </td>
                    </tr>

                    <!-- System Generated -->
                    <tr>
                        <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                            ***** This is a system generated communication and does not require signature *****
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>';

    // Send Email
    $result = $this->_sendEmailDynamic($to, $subject, $email_content);

    if ($result) {
        echo "Email sent successfully to " . $to;
    } else {
        echo "Email sending failed.";
    }
}





    /**
     * Case 1: At the time of Registration/ Activation
     * Test URL: http://localhost/verifyfa/index.php/EmailController/registrationActivation/1
     */
    public function registrationActivation($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->first_name . " " . $user->last_name;
        $activationLink = base_url() . "index.php/login/validate/" . md5($user->email_id);
        $tempPassword = "AUTH_" . rand(1000, 9999);
        $to = $user->email_id;
        
        $email_content = '
            <table role="presentation" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <tr>
                    <td align="center">
                        <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px; text-align: left;">
                            <tr>
                                <td style="padding: 20px;">
                                    <h2 style="color: #333;">Welcome to VerifyFA</h2>
                                    <p>Dear <b>' . $receiverName . '</b>,</p>
                                    <p>Thank you for registering. To complete your activation, please use the following temporary password and click the validation link below:</p>
                                    <p>Temporary Password: <b style="color: #d9534f;">' . $tempPassword . '</b></p>
                                    <p><a href="' . $activationLink . '" style="background-color: #337ab7; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Validate & Activate Account</a></p>
                                    <p>Regards,<br><b>VerifyFA Team</b></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';

        $this->_sendEmailDynamic($to, "VerifyFA - Account Registration / Activation", $email_content);
    }

    /**
     * Case 2: At the time of Activation Link Expiration
     * Test URL: http://localhost/verifyfa/index.php/EmailController/activationLinkExpiration/1
     */
    public function activationLinkExpiration($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->first_name . " " . $user->last_name;
        $reactivationLink = base_url() . "index.php/login/revalidate/" . md5($user->email_id);
        $to = $user->email_id;

        $email_content = '
            <table role="presentation" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <tr>
                    <td align="center">
                        <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px; text-align: left;">
                            <tr>
                                <td style="padding: 20px;">
                                    <h2 style="color: #d9534f;">Activation Link Expired</h2>
                                    <p>Dear <b>' . $receiverName . '</b>,</p>
                                    <p>Your previous activation link has expired. To continue with your registration, please click the link below to get a new activation link:</p>
                                    <p><a href="' . $reactivationLink . '" style="color: #337ab7; font-weight: bold;">Revalidate Activation Link</a></p>
                                    <p>Regards,<br><b>VerifyFA Team</b></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';

        $this->_sendEmailDynamic($to, "VerifyFA - Activation Link Expired", $email_content);
    }




//case 2: according to formate
/**
 * Case 2 : Activation Link Expired / Revalidation Required
 * Test URL:
 * http://localhost/verifyfa/index.php/EmailController/activationLinkExpiration/5
 */
public function activationLinkExpiration2($userId = 1)
{
    // Get User Data
    $user = $this->Registered_user_model->get_registerd_user($userId);

    if (!$user) {
        echo "User not found.";
        return;
    }

    /*
    -----------------------------------------
    Actual DB Fields
    -----------------------------------------
    first_name
    last_name
    email_id
    activation_link
    -----------------------------------------
    */

    $receiverName = trim($user->first_name . ' ' . $user->last_name);
    $to           = $user->email_id;

    if (empty($to)) {
        echo "Email ID not found.";
        return;
    }

    // Temporary Password
    $tempPassword = "AUTH_" . rand(1000, 9999);

    // Revalidation Link
    $reactivationLink = base_url('index.php/login/revalidate/' . md5($to));

    // Subject
    $subject = "VerifyFA - Revalidation required to Activate Your Account and Setup New Password";

    // Date Time
    $dateTime = date('d-m-Y h:i A');

    // Logo Path
    $logo = base_url('assets/img/logo.png');

    // Email Template
    $email_content = '
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
        <tr>
            <td align="center">

                <table width="650" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <img src="' . $logo . '" height="70">
                        </td>
                    </tr>

                    <!-- Auto Generated Message -->
                    <tr>
                        <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                            ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                        </td>
                    </tr>

                    <!-- Date -->
                    <tr>
                        <td style="font-size:13px;color:#666;padding-bottom:15px;">
                            ' . $dateTime . '
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="font-size:15px;color:#333;padding-bottom:15px;">
                            Dear <b>' . $receiverName . '</b>,
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:24px;padding-bottom:15px;">
                            Thanks for registering on <b>VerifyFA</b>.<br>
                            It is important to activate your account in due time to continue further.
                        </td>
                    </tr>

                    <!-- please click on the link to setup your new password -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:24px;padding-bottom:15px;">
                           Please click on the link to Activate and setup your New Password:
                        </td>


                    <!-- Temp Password -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:15px;">
                            Your Temporary Password for 1st time login is:
                            <b style="color:#d9534f;">' . $tempPassword . '</b>
                        </td>
                    </tr>

                    <!-- Button -->
                    <tr>
                        <td align="center" style="padding:20px 0;">
                            <a href="' . $reactivationLink . '" 
                               style="background:#dc3545;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:5px;font-size:14px;display:inline-block;">
                               Revalidate & Activate Account
                            </a>
                        </td>
                    </tr>

                    <!-- Thanks -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:15px;">
                            Thanks for your support and understanding.
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:20px;">
                            Regards,<br>
                            <b>VerifyFA</b>
                        </td>
                    </tr>

                    <!-- Bottom Note -->
                    <tr>
                        <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                            ***** This is a system generated communication and does not require signature *****
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>';

    // Send Mail
    $result = $this->_sendEmailDynamic($to, $subject, $email_content);

    if ($result) {
        echo "Revalidation email sent successfully to " . $to;
    } else {
        echo "Email sending failed.";
    }
}














    /**
     * Case 3: Upon successful Registration/ Activation
     * Test URL: http://localhost/verifyfa/index.php/EmailController/successfulRegistration/1
     */
    public function successfulRegistration($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->first_name . " " . $user->last_name;
        $accountId = $user->entity_code;
        $loginLink = base_url() . "index.php/login";
        $to = $user->email_id;

        $email_content = '
            <table role="presentation" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <tr>
                    <td align="center">
                        <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px; text-align: left;">
                            <tr>
                                <td style="padding: 20px;">
                                    <h2 style="color: #28a745;">Account Activated!</h2>
                                    <p>Dear <b>' . $receiverName . '</b>,</p>
                                    <p>Congratulations! Your account has been successfully activated.</p>
                                    <p>Your Account ID is: <b>' . $accountId . '</b></p>
                                    <p>You can now login to your dashboard:</p>
                                    <p><a href="' . $loginLink . '" style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Login to VerifyFA</a></p>
                                    <p>Regards,<br><b>VerifyFA Team</b></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';

        $this->_sendEmailDynamic($to, "VerifyFA - Account Activated Successfully", $email_content);
    }




//according to format for case 3:
    /**
 * Case 3 : Upon Successful Registration / Activation
 * Test URL:
 * http://localhost/verifyfa/index.php/EmailController/successfulRegistration/5
 */
public function successfulRegistration3($userId = 1)
{
    // Get User Data
    $user = $this->Registered_user_model->get_registerd_user($userId);

    if (!$user) {
        echo "User not found.";
        return;
    }

    /*
    --------------------------------------------------
    Real DB Fields
    --------------------------------------------------
    first_name
    last_name
    email_id
    entity_code
    plan_id
    created_at
    --------------------------------------------------
    */

    $receiverName = trim($user->first_name . ' ' . $user->last_name);
    $to           = $user->email_id;
    $entityCode   = $user->entity_code;

    if (empty($to)) {
        echo "Email ID not found.";
        return;
    }

    // Login Link
    $loginLink = base_url('index.php/login');

    // Subject
    $subject = "VerifyFA Activation Successful";

    // Date Time
    $dateTime = date('d-m-Y h:i A');

    // Logo
    $logo = base_url('assets/img/logo.png');

    /*
    --------------------------------------------------
    Subscription Plan Values
    Change if plan table exists later
    --------------------------------------------------
    */

    $companiesAllowed = "1";
    $locationsAllowed = "5";
    $usersAllowed     = "10";
    $rowsAllowed      = "1000";

    // Plan expiry after 1 year (example)
    $expiryDate = date('d-m-Y', strtotime('+1 year'));

    // Email Template
    $email_content = '
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
        <tr>
            <td align="center">

                <table width="680" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <img src="' . $logo . '" height="70">
                        </td>
                    </tr>

                    <!-- Top Note -->
                    <tr>
                        <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                            ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                        </td>
                    </tr>

                    <!-- Date -->
                    <tr>
                        <td style="font-size:13px;color:#666;padding-bottom:15px;">
                            ' . $dateTime . '
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="font-size:15px;color:#333;padding-bottom:15px;">
                            Dear <b>' . $receiverName . '</b>,
                        </td>
                    </tr>

                    <!-- Activation Success -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:24px;padding-bottom:15px;">
                            Your Account Activation on <b>VerifyFA</b> is successful.
                        </td>
                    </tr>

                    <!-- Plan Details -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:10px;">
                            <b>Following is the Subscription Plan Breakup:</b>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:20px;">
                            - No. of Companies allowed to be added: <b>' . $companiesAllowed . '</b><br>
                            - No. of Locations under each Company allowed to be added: <b>' . $locationsAllowed . '</b><br>
                            - Total No. of Users allowed to be added: <b>' . $usersAllowed . '</b><br>
                            - No. of Rows allowed for upload under each Location: <b>' . $rowsAllowed . '</b><br>
                            - Subscription Plan Expires on: <b>' . $expiryDate . '</b>
                        </td>
                    </tr>

                    <!-- Login Button -->
                    <tr>
                        <td align="center" style="padding:15px 0;">
                            <a href="' . $loginLink . '" 
                               style="background:#28a745;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:5px;font-size:14px;display:inline-block;">
                               Login to VerifyFA
                            </a>
                        </td>
                    </tr>

                    <!-- Credentials -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-top:20px;padding-bottom:15px;">
                            Please use the following login credentials:<br>
                            <b>Entity Code:</b> ' . $entityCode . '<br>
                            <b>Username:</b> ' . $to . '<br>
                            <b>Password:</b> User Defined
                        </td>
                    </tr>

                    <!-- Thanks -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:15px;">
                            Thanks for your support and understanding.
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:20px;">
                            Regards,<br>
                            <b>VerifyFA</b>
                        </td>
                    </tr>

                    <!-- Bottom Note -->
                    <tr>
                        <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                            ***** This is a system generated communication and does not require signature. *****
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>';

    // Send Email
    $result = $this->_sendEmailDynamic($to, $subject, $email_content);

    if ($result) {
        echo "Activation success email sent successfully to " . $to;
    } else {
        echo "Email sending failed.";
    }
}












    /**
     * Case 6: Upon successful De-Registration/ Unsubscribe Account
     */
    public function successfulDeRegistration($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->first_name . " " . $user->last_name;
        $accountId = $user->entity_code;
        $to = $user->email_id;

        $email_content = '
            <table role="presentation" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <tr>
                    <td align="center">
                        <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px; text-align: left;">
                            <tr>
                                <td style="padding: 20px;">
                                    <h2 style="color: #777;">Account De-registered</h2>
                                    <p>Dear <b>' . $receiverName . '</b>,</p>
                                    <p>This is to confirm that your VerifyFA account (<b>' . $accountId . '</b>) has been successfully de-registered as per your request.</p>
                                    <p>We are sorry to see you go!</p>
                                    <p>Regards,<br><b>VerifyFA Team</b></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';

        $this->_sendEmailDynamic($to, "VerifyFA - Account De-registration Confirmation", $email_content);
    }
    


//according to format for case 6:
    /**
 * Case 6 : Upon Successful De-Registration / Unsubscribe Account
 * Test URL:
 * http://localhost/verifyfa/index.php/EmailController/successfulDeRegistration/5
 */
public function successfulDeRegistration6($userId = 1)
{
    // Get User Data
    $user = $this->Registered_user_model->get_registerd_user($userId);

    if (!$user) {
        echo "User not found.";
        return;
    }

    /*
    -----------------------------------------
    Actual DB Fields
    -----------------------------------------
    first_name
    last_name
    email_id
    entity_code
    -----------------------------------------
    */

    $receiverName = trim($user->first_name . ' ' . $user->last_name);
    $to           = $user->email_id;
    $entityCode   = $user->entity_code;

    if (empty($to)) {
        echo "Email ID not found.";
        return;
    }

    // Subject
    $subject = "VerifyFA Account Successfully De-activated";

    // Date Time
    $dateTime = date('d-m-Y h:i A');

    // Logo Path
    $logo = base_url('assets/img/logo.png');

    // Email Template
    $email_content = '
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
        <tr>
            <td align="center">

                <table width="700" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <img src="' . $logo . '" height="70">
                        </td>
                    </tr>

                    <!-- Top Auto Message -->
                    <tr>
                        <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                            ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                        </td>
                    </tr>

                    <!-- Date -->
                    <tr>
                        <td style="font-size:13px;color:#666;padding-bottom:15px;">
                            ' . $dateTime . '
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="font-size:15px;color:#333;padding-bottom:15px;">
                            Dear <b>' . $receiverName . '</b>,
                        </td>
                    </tr>

                    <!-- Main Body -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            Your User Registration on <b>VerifyFA</b> is successfully 
                            <b style="color:#dc3545;">De-activated</b>.
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            Following your recorded consent to deactivate the Username:
                            <b>' . $to . '</b>,
                            your Entity Code:
                            <b>' . $entityCode . '</b>
                            is now completely suspended.
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            No User will be allowed to login using the Entity Code:
                            <b>' . $entityCode . '</b>.
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            As per the requirement of our Privacy Policy and to maintain data integrity,
                            your Personal Data Information has been completely encrypted and hashed,
                            and the same will no longer be useable for any communication or activity
                            related to <b>VerifyFA</b>.
                        </td>
                    </tr>

                    <!-- Thanks -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:15px;">
                            Thanks for your support and understanding.
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:20px;">
                            Regards,<br>
                            <b>VerifyFA</b>
                        </td>
                    </tr>

                    <!-- Bottom Note -->
                    <tr>
                        <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                            ***** This is a system generated communication and does not require signature. *****
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>';

    // Send Email
    $result = $this->_sendEmailDynamic($to, $subject, $email_content);

    if ($result) {
        echo "De-registration email sent successfully to " . $to;
    } else {
        echo "Email sending failed.";
    }
}











    /**
     * Case 11: Upon Account Suspension due to non-renewal
     */
    public function accountSuspension($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->first_name . " " . $user->last_name;
        $expiryDate = $user->expiryDate ?? date("d-M-Y");
        $to = $user->email_id;

        $email_content = '
            <table role="presentation" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <tr>
                    <td align="center">
                        <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px; text-align: left;">
                            <tr>
                                <td style="padding: 20px;">
                                    <h2 style="color: #d9534f;">Notice: Account Suspended</h2>
                                    <p>Dear <b>' . $receiverName . '</b>,</p>
                                    <p>Please note that your account has been suspended due to non-renewal of the subscription which expired on <b>' . $expiryDate . '</b>.</p>
                                    <p>To restore access, please contact your Group Admin or renew your plan.</p>
                                    <p>Regards,<br><b>VerifyFA Support</b></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';

        $this->_sendEmailDynamic($to, "VerifyFA - Account Suspension Notice", $email_content);
    }


//according to format for case 11:
    /**
 * Case 11 : Upon Account Suspension due to Non-Renewal
 * Test URL:
 * http://localhost/verifyfa/index.php/EmailController/accountSuspension/5
 */
public function accountSuspension11($userId = 1)
{
    // Get User Data
    $user = $this->Registered_user_model->get_registerd_user($userId);

    if (!$user) {
        echo "User not found.";
        return;
    }

    /*
    -----------------------------------------
    Actual DB Fields
    -----------------------------------------
    first_name
    last_name
    email_id
    -----------------------------------------
    */

    $receiverName = trim($user->first_name . ' ' . $user->last_name);
    $to           = $user->email_id;

    if (empty($to)) {
        echo "Email ID not found.";
        return;
    }

    // Subscription End Date
    // If DB field exists then use it, otherwise sample date
    $subscriptionEndDate = !empty($user->expiryDate)
        ? date('d-m-Y', strtotime($user->expiryDate))
        : date('d-m-Y');

    // Subject
    $subject = "VerifyFA Account Suspended due to non-renewal of Your Subscription Plan";

    // Date Time
    $dateTime = date('d-m-Y h:i A');

    // Logo Path
    $logo = base_url('assets/img/logo.png');

    // Contact Email
    $supportEmail = "solutions@ethicalminds.in";

    // Email Template
    $email_content = '
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
        <tr>
            <td align="center">

                <table width="700" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <img src="' . $logo . '" height="70">
                        </td>
                    </tr>

                    <!-- Auto Generated -->
                    <tr>
                        <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                            ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                        </td>
                    </tr>

                    <!-- Date -->
                    <tr>
                        <td style="font-size:13px;color:#666;padding-bottom:15px;">
                            ' . $dateTime . '
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="font-size:15px;color:#333;padding-bottom:15px;">
                            Dear <b>' . $receiverName . '</b>,
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            Please note that your current <b>VerifyFA</b> Subscription Plan has expired on 
                            <b>' . $subscriptionEndDate . '</b>.
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            Your Account is under suspension for next <b>30 days</b>.
                            Kindly renew your subscription to avoid permanent deletion of your Account
                            and any active projects.
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            In case you require to make changes to your Subscription Plan,
                            please contact us for more details at
                            <b>' . $supportEmail . '</b>.
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:18px;">
                            Your earliest action will be much appreciated.
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:20px;">
                            Regards,<br>
                            <b>VerifyFA</b>
                        </td>
                    </tr>

                    <!-- Bottom Note -->
                    <tr>
                        <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                            ***** This is a system generated communication and does not require signature. *****
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>';

    // Send Email
    $result = $this->_sendEmailDynamic($to, $subject, $email_content);

    if ($result) {
        echo "Account suspension email sent successfully to " . $to;
    } else {
        echo "Email sending failed.";
    }
}










    /**
     * Case 12: Upon successful Account Renewal
     */
    public function accountRenewal($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->first_name . " " . $user->last_name;
        $newExpiryDate = date("d-M-Y", strtotime("+1 year"));
        $to = $user->email_id;

        $email_content = '
            <table role="presentation" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <tr>
                    <td align="center">
                        <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px; text-align: left;">
                            <tr>
                                <td style="padding: 20px;">
                                    <h2 style="color: #28a745;">Subscription Renewed</h2>
                                    <p>Dear <b>' . $receiverName . '</b>,</p>
                                    <p>Your subscription for VerifyFA has been successfully renewed.</p>
                                    <p>New Expiry Date: <b>' . $newExpiryDate . '</b></p>
                                    <p>Thank you for your continued support.</p>
                                    <p>Regards,<br><b>VerifyFA Team</b></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';

        $this->_sendEmailDynamic($to, "VerifyFA - Account Renewal Successful", $email_content);
    }





//according to format for case 12:
    /**
 * Case 12 : Upon Successful Account Renewal
 * Test URL:
 * http://localhost/verifyfa/index.php/EmailController/accountRenewal/5
 */
public function accountRenewal12($userId = 1)
{
    // Get User Data
    $user = $this->Registered_user_model->get_registerd_user($userId);

    if (!$user) {
        echo "User not found.";
        return;
    }

    /*
    -----------------------------------------
    Actual DB Fields
    -----------------------------------------
    first_name
    last_name
    email_id
    -----------------------------------------
    */

    $receiverName = trim($user->first_name . ' ' . $user->last_name);
    $to           = $user->email_id;

    if (empty($to)) {
        echo "Email ID not found.";
        return;
    }

    // New Expiry Date
    // If DB renewal expiry exists use it, otherwise +1 year
    $expiryDate = !empty($user->expiryDate)
        ? date('d-m-Y', strtotime($user->expiryDate))
        : date('d-m-Y', strtotime('+1 year'));

    // Login Link
    $loginLink = base_url('index.php/login');

    // Subject
    $subject = "VerifyFA Account Subscription Plan Successfully Renewed";

    // Date Time
    $dateTime = date('d-m-Y h:i A');

    // Logo Path
    $logo = base_url('assets/img/logo.png');

    // Email Template
    $email_content = '
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
        <tr>
            <td align="center">

                <table width="700" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <img src="' . $logo . '" height="70">
                        </td>
                    </tr>

                    <!-- Auto Generated -->
                    <tr>
                        <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                            ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                        </td>
                    </tr>

                    <!-- Date -->
                    <tr>
                        <td style="font-size:13px;color:#666;padding-bottom:15px;">
                            ' . $dateTime . '
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="font-size:15px;color:#333;padding-bottom:15px;">
                            Dear <b>' . $receiverName . '</b>,
                        </td>
                    </tr>

                    <!-- Main Body -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            Your <b>VerifyFA</b> Account Subscription Plan has successfully been renewed.
                        </td>
                    </tr>

                    <!-- Expiry -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            - Subscription Plan Expires on:
                            <b>' . $expiryDate . '</b>
                        </td>
                    </tr>

                    <!-- Login Button -->
                    <tr>
                        <td align="center" style="padding:15px 0;">
                            <a href="' . $loginLink . '" 
                               style="background:#28a745;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:5px;font-size:14px;display:inline-block;">
                               Login to VerifyFA
                            </a>
                        </td>
                    </tr>

                    <!-- Credentials -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-top:15px;padding-bottom:18px;">
                            Your login credentials remain unchanged.
                        </td>
                    </tr>

                    <!-- Thanks -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:18px;">
                            Thanks for your support and understanding.
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:20px;">
                            Regards,<br>
                            <b>VerifyFA</b>
                        </td>
                    </tr>

                    <!-- Bottom Note -->
                    <tr>
                        <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                            ***** This is a system generated communication and does not require signature. *****
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>';

    // Send Email
    $result = $this->_sendEmailDynamic($to, $subject, $email_content);

    if ($result) {
        echo "Account renewal email sent successfully to " . $to;
    } else {
        echo "Email sending failed.";
    }
}














    /**
     * Case 13: Upon successful Account Upgradation
     */
    public function accountUpgradation($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->first_name . " " . $user->last_name;
        $newPlan = "Premium Enterprise Plan";
        $to = $user->email_id;

        $email_content = '
            <table role="presentation" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <tr>
                    <td align="center">
                        <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px; text-align: left;">
                            <tr>
                                <td style="padding: 20px;">
                                    <h2 style="color: #17a2b8;">Account Upgraded</h2>
                                    <p>Dear <b>' . $receiverName . '</b>,</p>
                                    <p>Your VerifyFA account has been successfully upgraded to the <b>' . $newPlan . '</b>.</p>
                                    <p>You can now enjoy the additional features of your new plan.</p>
                                    <p>Regards,<br><b>VerifyFA Team</b></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';

        $this->_sendEmailDynamic($to, "VerifyFA - Account Upgradation Successful", $email_content);
    }



//according to format for case 13:
    /**
 * Case 13 : Upon Successful Account Upgradation
 * Test URL:
 * http://localhost/verifyfa/index.php/EmailController/accountUpgradation/5
 */
public function accountUpgradation13($userId = 1)
{
    // Get User Data
    $user = $this->Registered_user_model->get_registerd_user($userId);

    if (!$user) {
        echo "User not found.";
        return;
    }

    /*
    -----------------------------------------
    Actual DB Fields
    -----------------------------------------
    first_name
    last_name
    email_id
    -----------------------------------------
    */

    $receiverName = trim($user->first_name . ' ' . $user->last_name);
    $to           = $user->email_id;

    if (empty($to)) {
        echo "Email ID not found.";
        return;
    }

    // Updated Plan Details (Change dynamically later if plan table exists)
    $companiesAllowed = "5";
    $locationsAllowed = "10";
    $usersAllowed     = "50";
    $rowsAllowed      = "5000";
    $expiryDate       = date('d-m-Y', strtotime('+1 year'));

    // Login Link
    $loginLink = base_url('index.php/login');

    // Subject
    $subject = "VerifyFA Account Subscription Plan Successfully Changed";

    // Date Time
    $dateTime = date('d-m-Y h:i A');

    // Logo Path
    $logo = base_url('assets/img/logo.png');

    // Email Template
    $email_content = '
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
        <tr>
            <td align="center">

                <table width="700" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <img src="' . $logo . '" height="70">
                        </td>
                    </tr>

                    <!-- Auto Generated -->
                    <tr>
                        <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                            ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                        </td>
                    </tr>

                    <!-- Date -->
                    <tr>
                        <td style="font-size:13px;color:#666;padding-bottom:15px;">
                            ' . $dateTime . '
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="font-size:15px;color:#333;padding-bottom:15px;">
                            Dear <b>' . $receiverName . '</b>,
                        </td>
                    </tr>

                    <!-- Main Body -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            Your <b>VerifyFA</b> Account Subscription Plan has successfully been changed.
                        </td>
                    </tr>

                    <!-- Plan Details -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:10px;">
                            <b>Following is the updated Subscription Plan Breakup:</b>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:20px;">
                            - No. of Companies allowed to be added: <b>' . $companiesAllowed . '</b><br>
                            - No. of Locations under each Company allowed to be added: <b>' . $locationsAllowed . '</b><br>
                            - Total No. of Users allowed to be added: <b>' . $usersAllowed . '</b><br>
                            - No. of Rows allowed for upload under each Location: <b>' . $rowsAllowed . '</b><br>
                            - Subscription Plan Expires on: <b>' . $expiryDate . '</b>
                        </td>
                    </tr>

                    <!-- Login Button -->
                    <tr>
                        <td align="center" style="padding:15px 0;">
                            <a href="' . $loginLink . '" 
                               style="background:#17a2b8;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:5px;font-size:14px;display:inline-block;">
                               Login to VerifyFA
                            </a>
                        </td>
                    </tr>

                    <!-- Credentials -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-top:15px;padding-bottom:18px;">
                            Your login credentials remain unchanged.
                        </td>
                    </tr>

                    <!-- Thanks -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:18px;">
                            Thanks for your support and understanding.
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:20px;">
                            Regards,<br>
                            <b>VerifyFA</b>
                        </td>
                    </tr>

                    <!-- Bottom Note -->
                    <tr>
                        <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                            ***** This is a system generated communication and does not require signature. *****
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>';

    // Send Email
    $result = $this->_sendEmailDynamic($to, $subject, $email_content);

    if ($result) {
        echo "Account upgradation email sent successfully to " . $to;
    } else {
        echo "Email sending failed.";
    }
}


















    /**
     * Case 14: At the time of adding the User
     */
    public function addingUserByAdmin($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->first_name . " " . $user->last_name;
        $userLoginId = $user->email_id;
        $password = "VerifyFA@" . date("Y");
        $appDownloadLink = "https://play.google.com/store/apps/details?id=com.verifyfa";
        $to = $user->email_id;

        $email_content = '
            <table role="presentation" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                <tr>
                    <td align="center">
                        <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px; text-align: left;">
                            <tr>
                                <td style="padding: 20px;">
                                    <h2 style="color: #333;">Login Credentials</h2>
                                    <p>Dear <b>' . $receiverName . '</b>,</p>
                                    <p>An account has been created for you on VerifyFA. Below are your login credentials:</p>
                                    <p>User ID: <b>' . $userLoginId . '</b><br>Password: <b>' . $password . '</b></p>
                                    <p>You can download the VerifyFA App from here:</p>
                                    <p><a href="' . $appDownloadLink . '" style="background-color: #000; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Download App</a></p>
                                    <p>Regards,<br><b>VerifyFA Team</b></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';

        $this->_sendEmailDynamic($to, "VerifyFA - Login Credentials & App Download Link", $email_content);
    }



/**
 * Case 14 : At the Time of Adding the User
 * Test URL:
 * http://localhost/verifyfa/index.php/EmailController/addingUserByAdmin/5
 */
public function addingUserByAdmin14($userId = 1)
{
    // Get User Data
    $user = $this->Registered_user_model->get_registerd_user($userId);

    if (!$user) {
        echo "User not found.";
        return;
    }

    /*
    -----------------------------------------
    Actual DB Fields
    -----------------------------------------
    first_name
    last_name
    email_id
    entity_code
    -----------------------------------------
    */

    $receiverName = trim($user->first_name . ' ' . $user->last_name);
    $to           = $user->email_id;
    $entityCode   = $user->entity_code;

    if (empty($to)) {
        echo "Email ID not found.";
        return;
    }

    // Temporary Password
    $tempPassword = "AUTH_" . rand(1000, 9999);

    // Login Link
    $loginLink = base_url('index.php/login');

    // Mobile App Download Link
    $appDownloadLink = "https://play.google.com/store/apps/details?id=com.verifyfa";

    // Subject
    $subject = "You have been added as VerifyFA User";

    // Date Time
    $dateTime = date('d-m-Y h:i A');

    // Logo Path
    $logo = base_url('assets/img/logo.png');

    // Email Template
    $email_content = '
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
        <tr>
            <td align="center">

                <table width="700" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <img src="' . $logo . '" height="70">
                        </td>
                    </tr>

                    <!-- Auto Generated -->
                    <tr>
                        <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                            ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                        </td>
                    </tr>

                    <!-- Date -->
                    <tr>
                        <td style="font-size:13px;color:#666;padding-bottom:15px;">
                            ' . $dateTime . '
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="font-size:15px;color:#333;padding-bottom:15px;">
                            Dear <b>' . $receiverName . '</b>,
                        </td>
                    </tr>

                    <!-- Main Body -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            You have been added as <b>VerifyFA</b> User.
                        </td>
                    </tr>

                    <!-- Login Button -->
                    <tr>
                        <td align="center" style="padding:10px 0 20px 0;">
                            <a href="' . $loginLink . '" 
                               style="background:#007bff;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:5px;font-size:14px;display:inline-block;">
                               Login to VerifyFA
                            </a>
                        </td>
                    </tr>

                    <!-- Credentials -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            <b>Entity Code:</b> ' . $entityCode . '<br>
                            <b>Username:</b> ' . $to . '<br>
                            <b>Your Temporary Password for 1st time login is:</b> 
                            <span style="color:#d9534f;"><b>' . $tempPassword . '</b></span><br>
                            It is strongly suggested to setup your New Password upon your 1st login.
                        </td>
                    </tr>

                    <!-- App Download -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:12px;">
                            Kindly make sure that you download the Android based 
                            <b>VerifyFA</b> Mobile App from here:
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <a href="' . $appDownloadLink . '" 
                               style="background:#28a745;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:5px;font-size:14px;display:inline-block;">
                               Download VerifyFA App
                            </a>
                        </td>
                    </tr>

                    <!-- Thanks -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:18px;">
                            Thanks for your support and understanding.
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:20px;">
                            Regards,<br>
                            <b>VerifyFA</b>
                        </td>
                    </tr>

                    <!-- Bottom Note -->
                    <tr>
                        <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                            ***** This is a system generated communication and does not require signature. *****
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>';

    // Send Email
    $result = $this->_sendEmailDynamic($to, $subject, $email_content);

    if ($result) {
        echo "User creation email sent successfully to " . $to;
    } else {
        echo "Email sending failed.";
    }
}





/**
 * Case 5 : Password Reset / Account ID Unlocking
 * Proper Working Version (Using _sendEmailDynamic like Case 2)
 *
 * Test URL:
 * http://localhost/verifyfa/index.php/EmailController/passwordResetUnlock/5
 */
public function passwordResetUnlock($userId = 1)
{
    // Get User Data
    $user = $this->Registered_user_model->get_registerd_user($userId);

    if (!$user) {
        echo "User not found.";
        return;
    }

    /*
    -----------------------------------------
    Actual DB Fields
    -----------------------------------------
    first_name
    last_name
    email_id
    entity_code
    -----------------------------------------
    */

    $receiverName = trim($user->first_name . ' ' . $user->last_name);
    $to           = $user->email_id;

    if (empty($to)) {
        echo "Email ID not found.";
        return;
    }

    // Temporary Password
    $tempPassword = "AUTH_" . rand(1000, 9999);

    // Reset Password Link
    $resetLink = base_url('index.php/login/resetPassword/' . md5($to));

    // Subject
    $subject = "VerifyFA - Reset Password";

    // Date Time
    $dateTime = date('d-m-Y h:i A');

    // Logo Path
    $logo = base_url('assets/img/logo.png');

    // Email Template
    $email_content = '
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
        <tr>
            <td align="center">

                <table width="650" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <img src="' . $logo . '" height="70">
                        </td>
                    </tr>

                    <!-- Auto Generated Message -->
                    <tr>
                        <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                            ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                        </td>
                    </tr>

                    <!-- Date -->
                    <tr>
                        <td style="font-size:13px;color:#666;padding-bottom:15px;">
                            ' . $dateTime . '
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="font-size:15px;color:#333;padding-bottom:15px;">
                            Dear <b>' . $receiverName . '</b>,
                        </td>
                    </tr>

                    <!-- Main Body -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:24px;padding-bottom:15px;">
                            Upon your request to reset Password / unlocking the User Account,
                            a Temporary Password has been generated:
                            <b style="color:#d9534f;">' . $tempPassword . '</b>
                        </td>
                    </tr>

                    <!-- Sub Body -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:24px;padding-bottom:15px;">
                            You requested to enter New Password on <b>VerifyFA</b>.
                            Please click on the link below to setup your New Password:
                        </td>
                    </tr>

                    <!-- Button -->
                    <tr>
                        <td align="center" style="padding:20px 0;">
                            <a href="' . $resetLink . '" 
                               style="background:#007bff;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:5px;font-size:14px;display:inline-block;">
                               Setup New Password
                            </a>
                        </td>
                    </tr>

                    <!-- Thanks -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:15px;">
                            Thanks for your support and understanding.
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:20px;">
                            Regards,<br>
                            <b>VerifyFA</b>
                        </td>
                    </tr>

                    <!-- Bottom Note -->
                    <tr>
                        <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                            ***** This is a system generated communication and does not require signature. *****
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>';

    // Send Email using your existing working dynamic function
    $result = $this->_sendEmailDynamic($to, $subject, $email_content);

    if ($result) {
        echo "Password reset email sent successfully to " . $to;
    } else {
        echo "Email sending failed.";
    }
}



//case 24: 

/**
 * Case 24 : Report Available for Download
 * Proper Working Version (Using _sendEmailDynamic like Case 2)
 *
 * Test URL:
 * http://localhost/verifyfa/index.php/EmailController/reportAvailableForDownload/5
 */
public function reportAvailableForDownload($userId = 1)
{
    // Get User Data
    $user = $this->Registered_user_model->get_registerd_user($userId);

    if (!$user) {
        echo "User not found.";
        return;
    }

    /*
    -----------------------------------------
    Actual DB Fields
    -----------------------------------------
    first_name
    last_name
    email_id
    entity_code
    organisation_name
    -----------------------------------------
    */

    $receiverName = trim($user->first_name . ' ' . $user->last_name);
    $to           = $user->email_id;
    $entityCode   = $user->entity_code;
    $companyName  = $user->organisation_name;

    if (empty($to)) {
        echo "Email ID not found.";
        return;
    }

    /*
    -----------------------------------------
    Demo Dynamic Values
    Replace from DB later
    -----------------------------------------
    */

    $locationName = "Main Branch";
    $projectName  = "Inventory Audit";
    $projectId    = "PRJ1001";
    $reportType   = "Summary Report";

    // Subject
    $subject = "Report Available for Download (Request generated VerifyFA Mobile App)";

    // Date Time
    $dateTime = date('d-m-Y h:i A');

    // Logo Path
    $logo = base_url('assets/img/logo.png');

    // App Download Link
    $appDownloadLink = "https://play.google.com/store/apps/details?id=com.verifyfa";

    // Attachment File Path
    $attachmentPath = FCPATH . 'uploads/reports/sample-report.pdf';

    /*
    -----------------------------------------
    Email HTML Template
    -----------------------------------------
    */

    $email_content = '
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
        <tr>
            <td align="center">

                <table width="700" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <img src="' . $logo . '" height="70">
                        </td>
                    </tr>

                    <!-- Auto Generated -->
                    <tr>
                        <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                            ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                        </td>
                    </tr>

                    <!-- Date -->
                    <tr>
                        <td style="font-size:13px;color:#666;padding-bottom:15px;">
                            ' . $dateTime . '
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="font-size:15px;color:#333;padding-bottom:15px;">
                            Dear <b>' . $receiverName . '</b>,
                        </td>
                    </tr>

                    <!-- Main Message -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                            As requested, please find attached the selected Report using 
                            <b>VerifyFA</b> Mobile App.
                        </td>
                    </tr>

                    <!-- Details -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:28px;padding-bottom:20px;">
                            <b>Entity Code:</b> ' . $entityCode . '<br>
                            <b>Company Name:</b> ' . $companyName . '<br>
                            <b>Location Name:</b> ' . $locationName . '<br>
                            <b>Project Name (Project ID):</b> ' . $projectName . ' (' . $projectId . ')<br>
                            <b>Report Type:</b> ' . $reportType . '
                        </td>
                    </tr>

                    <!-- App Link -->
                    <tr>
                        <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:12px;">
                            Kindly make sure that you have downloaded the Android based VerifyFA Mobile App from here: to execute the Project Assigned.
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <a href="' . $appDownloadLink . '" 
                               style="background:#28a745;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:5px;display:inline-block;">
                               Download VerifyFA App
                            </a>
                        </td>
                    </tr>

                    <!-- Thanks -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:18px;">
                            Thanks for your support and understanding.
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="font-size:14px;color:#333;padding-bottom:20px;">
                            Regards,<br>
                            <b>VerifyFA</b>
                        </td>
                    </tr>

                    <!-- Bottom -->
                    <tr>
                        <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                            ***** This is a system generated communication and does not require signature. *****
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>';

    /*
    -----------------------------------------
    Send Using Working Dynamic Method
    -----------------------------------------
    */

    $result = $this->_sendEmailDynamic($to, $subject, $email_content, $attachmentPath);

    if ($result) {
        echo "Report email sent successfully to " . $to;
    } else {
        echo "Email sending failed.";
    }
}





    /**
     * Helper to send and display dynamic email results
     */
    private function _sendEmailDynamic($to, $subject, $content) {
        $CI = setEmailProtocol();
        $CI->email->set_newline("\r\n");
        $CI->email->set_mailtype("html");
        $CI->email->from('solutions@ethicalminds.in', 'VerifyFA Notification');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($content);

        if($CI->email->send()){
             echo "<h3 style='color: green;'>Email Sent Successfully to $to</h3>";
             echo "<b>Subject:</b> $subject";
             echo "<hr><h4>Live Preview:</h4>" . $content;
        } else {
             echo "<h3 style='color: red;'>Email Sending Failed to $to</h3>";
             echo $CI->email->print_debugger();
        }
    }
}







