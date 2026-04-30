<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmailController extends CI_Controller {

	public function __construct() {
		parent::__construct();
        $this->load->library('session');	
        $this->load->model('Admin_model');
        $this->load->model('Registered_user_model');        
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



















}