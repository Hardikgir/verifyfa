# Project Email Notification Analysis

This document provides a detailed analysis of the email notification system in the **VerifyFA** project. It outlines the current configuration, identified issues, and the status of each notification event requested by the client.

## 1. Current Configuration

The project uses a helper function to initialize the email protocol:

- **Location**: [function_helper.php](file:///c:/xampp/htdocs/verifyfa/application/helpers/function_helper.php#L6-L38)
- **Method**: `setEmailProtocol()`
- **Server Details**: Currently configured to use Gmail SMTP (`smtp.gmail.com`) with the following account:
  - **Host**: `smtp.gmail.com`
  - **Port**: `587`
  - **User**: `solutions@ethicalminds.in`
  - **Encryption**: `tls`

## 2. Identified Issues

While analyzing the project, several critical issues were found in the email implementation:

### A. Hardcoded Recipient Emails
In [Superadmin_controller.php](file:///c:/xampp/htdocs/verifyfa/application/controllers/Superadmin_controller.php#L1449), the `sent_email` function has a hardcoded recipient email address:
```php
$to = "hardik.meghnathi12@gmail.com"; 
```
This means that regardless of the intended recipient, all system emails sent through this function are going to a developer's personal email instead of the user.

### B. Legacy `mail()` Usage
The `sent_email` function uses the standard PHP `mail()` function instead of the initialized CodeIgniter Email library. This ignores the SMTP configuration defined in `setEmailProtocol`.

### C. Missing Logic in Password Resets
In [Login.php](file:///c:/xampp/htdocs/verifyfa/application/controllers/Login.php#L327) and [Login.php:L388](file:///c:/xampp/htdocs/verifyfa/application/controllers/Login.php#L388), the functions to update passwords after a "forget password" request do not actually send an email confirmation or a temporary password to the user.

## 3. Implementation Status of Notification Events

The following table summarizes the status of the notification events requested by the client based on the current code analysis:

| # | Notification Event | Code Location | Status | Notes |
|---|---|---|---|---|
| 1 | Registration / Activation | `Superadmin_controller::send_activation_link` | **Partial** | Needs fixing for hardcoded email and SMTP. |
| 2 | Activation Link Expiration | - | **Missing** | No automated background task found to check expiration. |
| 3 | Successful Registration | - | **Missing** | Logic to send confirmation after activation is absent. |
| 4 | Account ID Unlocking | - | **Missing** | No specific unlocking logic found. |
| 5 | Password Reset | `Login::updatePasswordFromForget` | **Incomplete** | Database updates work, but no email is sent. |
| 6 | De-Registration | `Superadmin_controller::unsubscribe_account` | **Incomplete** | Status changes to 6, but no email is sent. |
| 11 | Account Suspension | `Superadmin_controller::suspend_account` | **Incomplete** | Status changes to 5, but no email is sent. |
| 12 | Account Renewal | - | **Missing** | No renewal email logic found. |
| 13 | Account Upgradation | `Superadmin_controller::upgrade_plan_save` | **Missing** | No email trigger found in the upgrade logic. |
| 14 | Adding a User | `Dashboard::addUser` | **Missing** | Inserts to DB but does not send notification. |
| 24 | Report for Download | `Dashboard::generateExceptionReport` | **Missing** | No email trigger found when reports are generated. |

## 4. Recommendations for Enabling Notifications

To fully enable the requested notifications, the following steps are required:

1.  **Centralize Email Logic**: Refactor all controllers to use a common `Email_model` or updated helper that correctly calls `setEmailProtocol()`.
2.  **Fix Recipient Logic**: Replace hardcoded email addresses with the actual user email from the database or session.
3.  **Implement missing events**: Add email trigger calls in the corresponding controller methods (e.g., in `upgrade_plan_save`, `addUser`, etc.).
4.  **Use Templates**: Move hardcoded HTML email bodies into separate view files for better maintainability and design consistency.
5.  **Verification**: Test with a real SMTP account to ensure that outgoing emails are not flagged as spam and reach the destination.

---
> [!IMPORTANT]
> A fix has been applied to the "Blue URL" issue in the Consolidated Reports as requested. The project names are now clickable and will download the respective reports.
