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
     * Test URL: http://localhost/verifyfa/index.php/EmailController/accountIdUnlocking
     */
    public function accountIdUnlocking() {
        // Static data for testing (as requested)
        $receiverName = "John Doe";
        $tempPassword = "TEMP_" . rand(1000, 9999);
        $transactionDate = date("d-M-Y H:i:s");
        $applicationName = "VerifyFA";
        $companyName = "VerifyFA Support Team";
        $webLink = base_url() . "index.php/login/VerifyForChangePassword";
        
        $to = 'tusharparmartlsu1507@gmail.com'; // Testing recipient
        $subject = "VerifyFA - Account ID Unlocking / Password Reset";

        // Building the email content with the requested format
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

        // Initialize email protocol from helper
        $CI = setEmailProtocol();
        $from_email = 'solutions@ethicalminds.in'; 
        
        $CI->email->set_newline("\r\n");
        $CI->email->set_mailtype("html");
        $CI->email->from($from_email, 'VerifyFA Notification');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($email_actual_content);

        // Send and display status
        if($CI->email->send()){
             echo "<h3 style='color: green;'>Email Sent Successfully!</h3>";
             echo "<p>Recipient: $to</p>";
             echo "<hr><h4>Live Preview of Content:</h4>" . $email_actual_content;
        } else {
             echo "<h3 style='color: red;'>Email Sending Failed.</h3>";
             echo $CI->email->print_debugger();
        }
    }








  /**
     * Case 1: At the time of Registration/ Activation
     * For Validation and Password generation
     */
    public function registrationActivation() {
        $receiverName = "John Doe";
        $activationLink = base_url() . "index.php/login/validate/XYZ123";
        $tempPassword = "AUTH_" . rand(1000, 9999);
        $to = 'tusharparmartlsu1507@gmail.com';
        
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
        $this->_sendEmailTest($to, "VerifyFA - Account Registration / Activation", $email_content);
    }
    /**
     * Case 2: At the time of Activation Link Expiration
     * Receive email to revalidate the Activation Link
     */
    public function activationLinkExpiration() {
        $receiverName = "John Doe";
        $reactivationLink = base_url() . "index.php/login/revalidate/XYZ123";
        $to = 'tusharparmartlsu1507@gmail.com';
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
        $this->_sendEmailTest($to, "VerifyFA - Activation Link Expired", $email_content);
    }
    /**
     * Case 3: Upon successful Registration/ Activation
     * Receive confirmation email with Account ID generated and link to login
     */
    public function successfulRegistration() {
        $receiverName = "John Doe";
        $accountId = "VFA-2026-0001";
        $loginLink = base_url() . "index.php/login";
        $to = 'tusharparmartlsu1507@gmail.com';
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
        $this->_sendEmailTest($to, "VerifyFA - Account Activated Successfully", $email_content);
    }
    /**
     * Case 6: Upon successful De-Registration/ Unsubscribe Account
     * Receive confirmation email with Account ID de-registered
     */
    public function successfulDeRegistration() {
        $receiverName = "John Doe";
        $accountId = "VFA-2026-0001";
        $to = 'tusharparmartlsu1507@gmail.com';
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
        $this->_sendEmailTest($to, "VerifyFA - Account De-registration Confirmation", $email_content);
    }
    /**
     * Case 11: Upon Account Suspension due to non-renewal
     * Receive final email with details of Account expiration/ suspension
     */
    public function accountSuspension() {
        $receiverName = "John Doe";
        $expiryDate = "20-Apr-2026";
        $to = 'tusharparmartlsu1507@gmail.com';
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
        $this->_sendEmailTest($to, "VerifyFA - Account Suspension Notice", $email_content);
    }
    /**
     * Case 12: Upon successful Account Renewal
     * Receive email for successful Account Renewal
     */
    public function accountRenewal() {
        $receiverName = "John Doe";
        $newExpiryDate = "20-Apr-2027";
        $to = 'tusharparmartlsu1507@gmail.com';
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
        $this->_sendEmailTest($to, "VerifyFA - Account Renewal Successful", $email_content);
    }
    /**
     * Case 13: Upon successful Account Upgradation
     * Receive email for successful Account Upgradation with updated details
     */
    public function accountUpgradation() {
        $receiverName = "John Doe";
        $newPlan = "Premium Enterprise Plan";
        $to = 'tusharparmartlsu1507@gmail.com';
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
        $this->_sendEmailTest($to, "VerifyFA - Account Upgradation Successful", $email_content);
    }
    /**
     * Case 14: At the time of adding the User
     * Receive Email with login details and link to download VerifyFA App
     */
    public function addingUserByAdmin() {
        $receiverName = "Jane Smith";
        $userId = "jane_smith_vfa";
        $password = "VerifyFA@2026";
        $appDownloadLink = "https://play.google.com/store/apps/details?id=com.verifyfa";
        $to = 'tusharparmartlsu1507@gmail.com';
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
                                    <p>User ID: <b>' . $userId . '</b><br>Password: <b>' . $password . '</b></p>
                                    <p>You can download the VerifyFA App from here:</p>
                                    <p><a href="' . $appDownloadLink . '" style="background-color: #000; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Download App</a></p>
                                    <p>Regards,<br><b>VerifyFA Team</b></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';
        $this->_sendEmailTest($to, "VerifyFA - Login Credentials & App Download Link", $email_content);
    }
    /**
     * Helper to send and display test email results
     */
    private function _sendEmailTest($to, $subject, $content) {
        $CI = setEmailProtocol();
        $CI->email->set_newline("\r\n");
        $CI->email->set_mailtype("html");
        $CI->email->from('solutions@ethicalminds.in', 'VerifyFA Notification');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($content);
        if($CI->email->send()){
             echo "<h3 style='color: green;'>Email Sent Successfully: $subject</h3>";
             echo "<hr><h4>Live Preview:</h4>" . $content;
        } else {
             echo "<h3 style='color: red;'>Email Sending Failed: $subject</h3>";
             echo $CI->email->print_debugger();
        }
    }








}