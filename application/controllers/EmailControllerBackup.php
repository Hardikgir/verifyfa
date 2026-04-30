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

        $receiverName = $user->firstName . " " . $user->lastName;
        $tempPassword = "TEMP_" . rand(1000, 9999);
        $transactionDate = date("d-M-Y H:i:s");
        $applicationName = "VerifyFA";
        $companyName = "VerifyFA Support Team";
        $webLink = base_url() . "index.php/login/VerifyForChangePassword";
        
        $to = $user->emailId; 
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








    /**
     * Case 1: At the time of Registration/ Activation
     * Test URL: http://localhost/verifyfa/index.php/EmailController/registrationActivation/1
     */
    public function registrationActivation($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->firstName . " " . $user->lastName;
        $activationLink = base_url() . "index.php/login/validate/" . md5($user->emailId);
        $tempPassword = "AUTH_" . rand(1000, 9999);
        $to = $user->emailId;
        
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
    // public function activationLinkExpiration($userId = 1) {
    //     $user = $this->Registered_user_model->get_registerd_user($userId);
    //     if(!$user) { echo "User not found"; return; }

    //     $receiverName = $user->firstName . " " . $user->lastName;
    //     $reactivationLink = base_url() . "index.php/login/revalidate/" . md5($user->emailId);
    //     $to = $user->emailId;

    //     $email_content = '
    //         <table role="presentation" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    //             <tr>
    //                 <td align="center">
    //                     <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px; text-align: left;">
    //                         <tr>
    //                             <td style="padding: 20px;">
    //                                 <h2 style="color: #d9534f;">Activation Link Expired</h2>
    //                                 <p>Dear <b>' . $receiverName . '</b>,</p>
    //                                 <p>Your previous activation link has expired. To continue with your registration, please click the link below to get a new activation link:</p>
    //                                 <p><a href="' . $reactivationLink . '" style="color: #337ab7; font-weight: bold;">Revalidate Activation Link</a></p>
    //                                 <p>Regards,<br><b>VerifyFA Team</b></p>
    //                             </td>
    //                         </tr>
    //                     </table>
    //                 </td>
    //             </tr>
    //         </table>';

    //     $this->_sendEmailDynamic($to, "VerifyFA - Activation Link Expired", $email_content);
    // }

















    /**
     * Case 3: Upon successful Registration/ Activation
     * Test URL: http://localhost/verifyfa/index.php/EmailController/successfulRegistration/1
     */
    // public function successfulRegistration($userId = 1) {
    //     $user = $this->Registered_user_model->get_registerd_user($userId);
    //     if(!$user) { echo "User not found"; return; }

    //     $receiverName = $user->firstName . " " . $user->lastName;
    //     $accountId = $user->entity_code;
    //     $loginLink = base_url() . "index.php/login";
    //     $to = $user->emailId;

    //     $email_content = '
    //         <table role="presentation" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    //             <tr>
    //                 <td align="center">
    //                     <table role="presentation" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #dddddd; padding: 20px; text-align: left;">
    //                         <tr>
    //                             <td style="padding: 20px;">
    //                                 <h2 style="color: #28a745;">Account Activated!</h2>
    //                                 <p>Dear <b>' . $receiverName . '</b>,</p>
    //                                 <p>Congratulations! Your account has been successfully activated.</p>
    //                                 <p>Your Account ID is: <b>' . $accountId . '</b></p>
    //                                 <p>You can now login to your dashboard:</p>
    //                                 <p><a href="' . $loginLink . '" style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Login to VerifyFA</a></p>
    //                                 <p>Regards,<br><b>VerifyFA Team</b></p>
    //                             </td>
    //                         </tr>
    //                     </table>
    //                 </td>
    //             </tr>
    //         </table>';

    //     $this->_sendEmailDynamic($to, "VerifyFA - Account Activated Successfully", $email_content);
    // }


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
    --------------------------------------------------
    */
    $this->load->model('Super_admin_model');
    $plan_data_details = $this->Super_admin_model->get_registered_user_plan($userId);
    $plan_details = null;
    $companiesAllowed = "1";
    $locationsAllowed = "5";
    $usersAllowed     = "10";
    $rowsAllowed      = "1000";
    $expiryDate       = date('d-m-Y', strtotime('+1 year'));

    if ($plan_data_details) {
        $expiryDate = date('d-m-Y', strtotime($plan_data_details->plan_end_date));
        $plan_id = $plan_data_details->plan_id;
        $plan_details = get_plan_row($plan_id);
        if ($plan_details) {
            $companiesAllowed = $plan_details->allowed_entities_no;
            $locationsAllowed = $plan_details->location_each_entity;
            $usersAllowed     = $plan_details->user_number_register;
            $rowsAllowed      = $plan_details->line_item_avaliable;
        }
    }

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



//case 2:
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
    $tempPassword = $user->password_view;

    // Revalidation Link
    $reactivationLink = base_url('index.php/generate-active-register-user/' . $userId);

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
     * Case 6: Upon successful De-Registration/ Unsubscribe Account
     */
    public function successfulDeRegistration($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->firstName . " " . $user->lastName;
        $accountId = $user->entity_code;
        $to = $user->emailId;

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

    /**
     * Case 11: Upon Account Suspension due to non-renewal
     */
    public function accountSuspension($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->firstName . " " . $user->lastName;
        $expiryDate = $user->expiryDate ?? date("d-M-Y");
        $to = $user->emailId;

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

    /**
     * Case 12: Upon successful Account Renewal
     */
    public function accountRenewal($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->firstName . " " . $user->lastName;
        $newExpiryDate = date("d-M-Y", strtotime("+1 year"));
        $to = $user->emailId;

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

    /**
     * Case 13: Upon successful Account Upgradation
     */
    public function accountUpgradation($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->firstName . " " . $user->lastName;
        $newPlan = "Premium Enterprise Plan";
        $to = $user->emailId;

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

    /**
     * Case 14: At the time of adding the User
     */
    public function addingUserByAdmin($userId = 1) {
        $user = $this->Registered_user_model->get_registerd_user($userId);
        if(!$user) { echo "User not found"; return; }

        $receiverName = $user->firstName . " " . $user->lastName;
        $userLoginId = $user->emailId;
        $password = "VerifyFA@" . date("Y");
        $appDownloadLink = "https://play.google.com/store/apps/details?id=com.verifyfa";
        $to = $user->emailId;

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







}