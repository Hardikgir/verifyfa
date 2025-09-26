<<<<<<< HEAD
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


//routes for super admin//
$route['super-admin-login'] = 'login/super_admin_login';
$route['super-admin-login-check'] = 'login/super_admin_login_check';
$route['super-admin-dashboard'] = 'superadmin_controller/super_admin_dashboard';
$route['super-admin-dashboard-second'] = 'superadmin_controller/super_admin_dashboard_second';
$route['super-admin-dashboard-second-2'] = 'superadmin_controller/super_admin_dashboard_second2';
$route['super-admin-dashboard-second-3'] = 'superadmin_controller/super_admin_dashboard_second3';
$route['manage-subscription'] = 'superadmin_controller/mange_subscription';
$route['create-subscription'] = 'superadmin_controller/create_subscription';
$route['save-subscription'] = 'superadmin_controller/save_subscription';
$route['edit-subscription/(:num)'] = 'superadmin_controller/edit_subscription/$1';
$route['edit-save-subscription'] = 'superadmin_controller/edit_save_subscription';
$route['inactive-plan/(:num)'] = 'superadmin_controller/inactive_plan/$1';
$route['active-plan/(:num)'] = 'superadmin_controller/active_plan/$1';
$route['manage-user'] = 'superadmin_controller/manage_user';
$route['select-plan'] = 'superadmin_controller/select_plan/';
$route['create-user/(:num)'] = 'superadmin_controller/create_user/$1';
$route['calculate-user-plan'] = 'superadmin_controller/calculate_user_plan';
$route['confirmation-user-detail/(:num)'] = 'superadmin_controller/confirmation_userdetail/$1';
$route['save-registred-user'] = 'superadmin_controller/save_registred_user';
$route['test'] = 'superadmin_controller/test';

$route['generate-activation-link/(:num)'] = 'superadmin_controller/generate_activation_link/$1';
$route['send-activation-link/(:num)'] = 'superadmin_controller/send_activation_link/$1';
$route['regenerate-activation-link/(:num)'] = 'superadmin_controller/regenerate_activation_link/$1';
$route['send-activation-link-regenreted/(:num)'] = 'superadmin_controller/regenerate_activation_send_link/$1';
$route['suspend-account/(:num)'] = 'superadmin_controller/suspend_account/$1';
$route['unsubscribe-account/(:num)'] = 'superadmin_controller/unsubscribe_account/$1';
$route['reactive-account/(:num)'] = 'superadmin_controller/reactive_account/$1';
$route['edit-user/(:num)'] = 'superadmin_controller/edit_user/$1';
$route['save-edit-registred-user'] = 'superadmin_controller/save_edit_registred_user/';
$route['view-subscription/(:num)'] = 'superadmin_controller/view_subscription/$1';
$route['view-user/(:num)'] = 'superadmin_controller/view_user/$1';
$route['logout-superadmin'] = 'login/logout_superadmin';
$route['check-subscrition'] = 'superadmin_controller/checksubscription';
$route['check-useremail'] = 'superadmin_controller/checkuseremail';
$route['check-entitycode'] = 'superadmin_controller/checkentitycode';
$route['upgrade-user-plan/(:num)'] = 'superadmin_controller/upgrade_plan/$1';
$route['upgrade-user-save'] = 'superadmin_controller/upgrade_plan_save';

//routes for super admin//


//routes for Registered User//
$route['registered-user-login'] = 'login/registered_user_login/';
$route['registered-user-login-check'] = 'login/registered_user_login_check/';
$route['registered-user-dashboard'] = 'registeredusercontroller/registered_user_dashboard/';
$route['registered-user-change-password'] = 'registeredusercontroller/change_password';
$route['registered-user-check-pass'] = 'registeredusercontroller/check_current_pass';
$route['registered-user-passwod-save'] = 'registeredusercontroller/registered_user_passwod_save';
$route['activation-registered-user/(:num)'] = 'login/activation_registered_user/$1';
$route['logout-registereduser'] = 'login/logout_registereduser';
$route['registered-user-subscription'] = 'registeredusercontroller/user_detail_plan';
$route['registered-user-profile'] = 'registeredusercontroller/registered_user_profile';
$route['update-profile-registred-user'] = 'registeredusercontroller/registered_user_update_profile';
$route['transfer-account'] = 'registeredusercontroller/transfer_account';
$route['transfer-account-save'] = 'registeredusercontroller/transfer_account_save';
$route['transfer-logout-confirmation'] = 'login/transfer_logout_confirmation';
$route['request-to-renew/(:num)'] = 'registeredusercontroller/renew_request/$1';
$route['unsubscribe-now/(:num)'] = 'registeredusercontroller/unsubscribe_account/$1';
$route['request-resubscribe/(:num)'] = 'registeredusercontroller/request_resubscribe/$1';
$route['registered-user-as-admin/(:num)'] = 'registeredusercontroller/as_admin_login/$1';
//routes for Registered User//


// Routes for admin and //
$route['admin-create-entity'] = 'Admin_controller/create_company';
$route['check-company-shortcode'] = 'Admin_controller/check_company_shortcode';
$route['save-company-data'] = 'Admin_controller/save_company_data';
$route['manage-entity'] = 'Admin_controller/manage_entity';
$route['edit-entity/(:num)'] = 'Admin_controller/edit_entity/$1';
$route['edit-save-entity'] = 'Admin_controller/edit_save_entity';
$route['manage-location'] = 'Admin_controller/manage_location';
$route['admin-create-location'] = 'Admin_controller/admin_create_location';
$route['check-location-shortcode'] = 'Admin_controller/check_location_shortcode';
$route['save-location-data'] = 'Admin_controller/save_location_data';
$route['edit-location/(:num)'] = 'Admin_controller/edit_location/$1';
$route['edit-save-location'] = 'Admin_controller/edit_save_location';
$route['check-location-company-cnt'] = 'Admin_controller/check_location_company_cnt';
$route['manage-department'] = 'Admin_controller/manage_department';
$route['admin-create-department'] = 'Admin_controller/create_department';
$route['save-department-data'] = 'Admin_controller/save_department';
$route['edit-department/(:num)'] = 'Admin_controller/edit_department/$1';
$route['edit-save-department'] = 'Admin_controller/edit_department_save';
$route['check-department-name'] = 'Admin_controller/check_department_name';
$route['manage-user-admin'] = 'Admin_controller/manage_user_admin';
$route['admin-create-user'] = 'Admin_controller/admin_create_user';
$route['save-admin-user'] = 'Admin_controller/save_admin_user';
$route['check-admin-userEmail'] = 'Admin_controller/check_admin_userEmail';
$route['edit-admin-user/(:num)'] = 'Admin_controller/edit_admin_user/$1';
$route['edit-admin-user-save'] = 'Admin_controller/edit_admin_user_save';
$route['manage-user-role'] = 'Admin_controller/manage_user_role';
$route['add-user-role'] = 'Admin_controller/add_user_role';
$route['assign-user-role-save'] = 'Admin_controller/assign_user_role_save';
$route['get-company-location'] = 'Admin_controller/get_company_location';
$route['get-company-location-sub-admin'] = 'Admin_controller/get_company_location_sub_admin';
$route['get-company-location-user'] = 'Admin_controller/get_company_location_user';
$route['get-user_detail'] = 'Admin_controller/get_user_row';
$route['edit-user-role/(:num)'] = 'Admin_controller/edit_user_role/$1';
$route['edit-user-role-save'] = 'Admin_controller/edit_user_role_save';
$route['my-profile'] = 'Admin_controller/my_profile';
$route['save-my-profile'] = 'Admin_controller/save_my_profile';
$route['change-my-password'] = 'Admin_controller/change_my_password';
$route['admin-user-check-pass'] = 'Admin_controller/admin_user_check_pass';
$route['admin-user-passwod-save'] = 'Admin_controller/admin_user_passwod_save';
$route['manage-notification'] = 'Admin_controller/manage_notification';
$route['brodcast-notification'] = 'Admin_controller/brodcast_notification';
$route['save-brodcast-message'] = 'Admin_controller/save_brodcast_notification';
$route['view-reply-notofication/(:num)'] = 'Admin_controller/view_notification/$1';
$route['save-notification-reply'] = 'Admin_controller/save_notification_reply';
$route['view-all-notification'] = 'Admin_controller/view_all_notification';
$route['delete-entity/(:num)'] = 'Admin_controller/delete_entity/$1';
$route['delete-location/(:num)'] = 'Admin_controller/delete_location_location/$1';
$route['check-department-shortcode'] = 'Admin_controller/check_department_shortcode';
$route['delete-department/(:num)'] = 'Admin_controller/delete_department/$1';
$route['get-role'] = 'Admin_controller/get_role';
$route['delete-user/(:num)'] = 'Admin_controller/delete_user/$1';
$route['reset-user-login/(:num)'] = 'Admin_controller/reset_user_login/$1';

//routes for super admin//
$route['generate-active-register-user/(:num)'] = 'login/generate_active_register_user/$1';
$route['request-for-delete'] = 'Admin_controller/request_for_delete';

$route['manage-issue-for-me'] = 'Admin_controller/manage_issue_for_me';
$route['manage-my-issue'] = 'Admin_controller/manage_my_issue';
$route['add-issue'] = 'Admin_controller/add_issue';
$route['view-issue/(:num)'] = 'Admin_controller/view_issue/$1';
$route['save-issue'] = 'Admin_controller/save_issue';
$route['update-issue'] = 'Admin_controller/update_issue';





// API ROUTES//

// $route['verify-dashboard-company-data'] = 'Admin_controller/save_notification_reply';
=======
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


//routes for super admin//
$route['super-admin-login'] = 'login/super_admin_login';
$route['super-admin-login-check'] = 'login/super_admin_login_check';
$route['super-admin-dashboard'] = 'superadmin_controller/super_admin_dashboard';
$route['super-admin-dashboard-second'] = 'superadmin_controller/super_admin_dashboard_second';
$route['super-admin-dashboard-second-2'] = 'superadmin_controller/super_admin_dashboard_second2';
$route['super-admin-dashboard-second-3'] = 'superadmin_controller/super_admin_dashboard_second3';
$route['manage-subscription'] = 'superadmin_controller/mange_subscription';
$route['create-subscription'] = 'superadmin_controller/create_subscription';
$route['save-subscription'] = 'superadmin_controller/save_subscription';
$route['edit-subscription/(:num)'] = 'superadmin_controller/edit_subscription/$1';
$route['edit-save-subscription'] = 'superadmin_controller/edit_save_subscription';
$route['inactive-plan/(:num)'] = 'superadmin_controller/inactive_plan/$1';
$route['active-plan/(:num)'] = 'superadmin_controller/active_plan/$1';
$route['manage-user'] = 'superadmin_controller/manage_user';
$route['select-plan'] = 'superadmin_controller/select_plan/';
$route['create-user/(:num)'] = 'superadmin_controller/create_user/$1';
$route['calculate-user-plan'] = 'superadmin_controller/calculate_user_plan';
$route['confirmation-user-detail/(:num)'] = 'superadmin_controller/confirmation_userdetail/$1';
$route['save-registred-user'] = 'superadmin_controller/save_registred_user';
$route['test'] = 'superadmin_controller/test';

$route['generate-activation-link/(:num)'] = 'superadmin_controller/generate_activation_link/$1';
$route['send-activation-link/(:num)'] = 'superadmin_controller/send_activation_link/$1';
$route['regenerate-activation-link/(:num)'] = 'superadmin_controller/regenerate_activation_link/$1';
$route['send-activation-link-regenreted/(:num)'] = 'superadmin_controller/regenerate_activation_send_link/$1';
$route['suspend-account/(:num)'] = 'superadmin_controller/suspend_account/$1';
$route['unsubscribe-account/(:num)'] = 'superadmin_controller/unsubscribe_account/$1';
$route['reactive-account/(:num)'] = 'superadmin_controller/reactive_account/$1';
$route['edit-user/(:num)'] = 'superadmin_controller/edit_user/$1';
$route['save-edit-registred-user'] = 'superadmin_controller/save_edit_registred_user/';
$route['view-subscription/(:num)'] = 'superadmin_controller/view_subscription/$1';
$route['view-user/(:num)'] = 'superadmin_controller/view_user/$1';
$route['logout-superadmin'] = 'login/logout_superadmin';
$route['check-subscrition'] = 'superadmin_controller/checksubscription';
$route['check-useremail'] = 'superadmin_controller/checkuseremail';
$route['check-entitycode'] = 'superadmin_controller/checkentitycode';
$route['upgrade-user-plan/(:num)'] = 'superadmin_controller/upgrade_plan/$1';
$route['upgrade-user-save'] = 'superadmin_controller/upgrade_plan_save';

//routes for super admin//


//routes for Registered User//
$route['registered-user-login'] = 'login/registered_user_login/';
$route['registered-user-login-check'] = 'login/registered_user_login_check/';
$route['registered-user-dashboard'] = 'registeredusercontroller/registered_user_dashboard/';
$route['registered-user-change-password'] = 'registeredusercontroller/change_password';
$route['registered-user-check-pass'] = 'registeredusercontroller/check_current_pass';
$route['registered-user-passwod-save'] = 'registeredusercontroller/registered_user_passwod_save';
$route['activation-registered-user/(:num)'] = 'login/activation_registered_user/$1';
$route['logout-registereduser'] = 'login/logout_registereduser';
$route['registered-user-subscription'] = 'registeredusercontroller/user_detail_plan';
$route['registered-user-profile'] = 'registeredusercontroller/registered_user_profile';
$route['update-profile-registred-user'] = 'registeredusercontroller/registered_user_update_profile';
$route['transfer-account'] = 'registeredusercontroller/transfer_account';
$route['transfer-account-save'] = 'registeredusercontroller/transfer_account_save';
$route['transfer-logout-confirmation'] = 'login/transfer_logout_confirmation';
$route['request-to-renew/(:num)'] = 'registeredusercontroller/renew_request/$1';
$route['unsubscribe-now/(:num)'] = 'registeredusercontroller/unsubscribe_account/$1';
$route['request-resubscribe/(:num)'] = 'registeredusercontroller/request_resubscribe/$1';
$route['registered-user-as-admin/(:num)'] = 'registeredusercontroller/as_admin_login/$1';
//routes for Registered User//


// Routes for admin and //
$route['admin-create-entity'] = 'Admin_controller/create_company';
$route['check-company-shortcode'] = 'Admin_controller/check_company_shortcode';
$route['save-company-data'] = 'Admin_controller/save_company_data';
$route['manage-entity'] = 'Admin_controller/manage_entity';
$route['edit-entity/(:num)'] = 'Admin_controller/edit_entity/$1';
$route['edit-save-entity'] = 'Admin_controller/edit_save_entity';
$route['manage-location'] = 'Admin_controller/manage_location';
$route['admin-create-location'] = 'Admin_controller/admin_create_location';
$route['check-location-shortcode'] = 'Admin_controller/check_location_shortcode';
$route['save-location-data'] = 'Admin_controller/save_location_data';
$route['edit-location/(:num)'] = 'Admin_controller/edit_location/$1';
$route['edit-save-location'] = 'Admin_controller/edit_save_location';
$route['check-location-company-cnt'] = 'Admin_controller/check_location_company_cnt';
$route['manage-department'] = 'Admin_controller/manage_department';
$route['admin-create-department'] = 'Admin_controller/create_department';
$route['save-department-data'] = 'Admin_controller/save_department';
$route['edit-department/(:num)'] = 'Admin_controller/edit_department/$1';
$route['edit-save-department'] = 'Admin_controller/edit_department_save';
$route['check-department-name'] = 'Admin_controller/check_department_name';
$route['manage-user-admin'] = 'Admin_controller/manage_user_admin';
$route['admin-create-user'] = 'Admin_controller/admin_create_user';
$route['save-admin-user'] = 'Admin_controller/save_admin_user';
$route['check-admin-userEmail'] = 'Admin_controller/check_admin_userEmail';
$route['edit-admin-user/(:num)'] = 'Admin_controller/edit_admin_user/$1';
$route['edit-admin-user-save'] = 'Admin_controller/edit_admin_user_save';
$route['manage-user-role'] = 'Admin_controller/manage_user_role';
$route['add-user-role'] = 'Admin_controller/add_user_role';
$route['assign-user-role-save'] = 'Admin_controller/assign_user_role_save';
$route['get-company-location'] = 'Admin_controller/get_company_location';
$route['get-company-location-sub-admin'] = 'Admin_controller/get_company_location_sub_admin';
$route['get-company-location-user'] = 'Admin_controller/get_company_location_user';
$route['get-user_detail'] = 'Admin_controller/get_user_row';
$route['edit-user-role/(:num)'] = 'Admin_controller/edit_user_role/$1';
$route['edit-user-role-save'] = 'Admin_controller/edit_user_role_save';
$route['my-profile'] = 'Admin_controller/my_profile';
$route['save-my-profile'] = 'Admin_controller/save_my_profile';
$route['change-my-password'] = 'Admin_controller/change_my_password';
$route['admin-user-check-pass'] = 'Admin_controller/admin_user_check_pass';
$route['admin-user-passwod-save'] = 'Admin_controller/admin_user_passwod_save';
$route['manage-notification'] = 'Admin_controller/manage_notification';
$route['manage-notification-receiver'] = 'Admin_controller/manage_notification2';
$route['brodcast-notification'] = 'Admin_controller/brodcast_notification';
$route['save-brodcast-message'] = 'Admin_controller/save_brodcast_notification';
$route['view-reply-notofication/(:num)'] = 'Admin_controller/view_notification/$1';
$route['save-notification-reply'] = 'Admin_controller/save_notification_reply';
$route['view-all-notification'] = 'Admin_controller/view_all_notification';
$route['delete-entity/(:num)'] = 'Admin_controller/delete_entity/$1';
$route['delete-location/(:num)'] = 'Admin_controller/delete_location_location/$1';
$route['check-department-shortcode'] = 'Admin_controller/check_department_shortcode';
$route['delete-department/(:num)'] = 'Admin_controller/delete_department/$1';
$route['get-role'] = 'Admin_controller/get_role';
$route['delete-user/(:num)'] = 'Admin_controller/delete_user/$1';
$route['reset-user-login/(:num)'] = 'Admin_controller/reset_user_login/$1';

//routes for super admin//
$route['generate-active-register-user/(:num)'] = 'login/generate_active_register_user/$1';
$route['request-for-delete'] = 'Admin_controller/request_for_delete';



$route['manage-my-issue'] = 'Admin_controller/manage_my_issue';
$route['add-issue'] = 'Admin_controller/add_issue';
$route['view-issue/(:num)'] = 'Admin_controller/view_issue/$1';
$route['save-issue'] = 'Admin_controller/save_issue';
$route['update-issue'] = 'Admin_controller/update_issue';

//tushar
// Route for manager issues
// $route['manage-issue-for-me'] = 'Admin_controller/manage_issue_for_me';

$route['issue-for-me/manager'] = 'Admin_controller/issue_for_me/manager';
$route['issue-for-me/groupadmin'] = 'Admin_controller/issue_for_me/groupadmin';





//routes for Registered User//
$route['forget-password-register-user'] = 'login/registered_user_forget_password/';
$route['forget-password-verifyfa-user'] = 'login/verifyfa_user_forget_password/';

// API ROUTES//

// $route['verify-dashboard-company-data'] = 'Admin_controller/save_notification_reply';
>>>>>>> 5a939923fd6302d3dffefbde4eacd316ccc9d0f5
