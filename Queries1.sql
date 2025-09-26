SELECT * FROM v74_ci_verifyfa_live_server_db.users where entity_code = 'PROBUDS';
SELECT * FROM v74_ci_verifyfa_live_server_db.user_role where entity_code = 'PROBUDS';

SELECT *,c.company_name FROM v74_ci_verifyfa_live_server_db.company_projects cp
LEFT JOIN company c 
       ON cp.company_id = c.id
where cp.id in (10,11,12);

SELECT * FROM v74_ci_verifyfa_live_server_db.03sep_tg1;
SELECT * FROM v74_ci_verifyfa_live_server_db.03sep_nt;
SELECT * FROM v74_ci_verifyfa_live_server_db.03sep_tg2;

SELECT ur.*,c.company_name,cl.location_name,CONCAT(u.firstName, ' ', u.lastName) AS fullname,
       REPLACE(
         REPLACE(
           REPLACE(
             REPLACE(
               REPLACE(
                 REPLACE(ur.user_role,
                   '5', 'Group Admin'),
               '4', 'Sub Admin'),
             '0', 'Manager'),
           '2', 'Process Owner'),
         '3', 'Entity Owner'),
       '1', 'Verifier') AS roles
FROM v74_ci_verifyfa_live_server_db.user_role ur
LEFT JOIN company c 
       ON ur.company_id = c.id
LEFT JOIN company_locations cl 
       ON ur.location_id = cl.id
LEFT JOIN users u 
       ON ur.user_id = u.id       
where ur.entity_code = 'PROBUDS';