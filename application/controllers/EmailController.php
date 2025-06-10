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
        
        $name = 'sample.pdf';
        $filename = 'sample.pdf';
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
        
        
        
        $to = 'hardik.meghnathi12@gmail.com';
        $subject = " Email Attachment";
        
        $CI = setEmailProtocol();
        $from_email = 'solutions@ethicalminds';
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

}