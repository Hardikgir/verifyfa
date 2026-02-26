SELECT * FROM `company_projects` WHERE `id` = '30';
SELECT * FROM `company_locations` WHERE `id` = '39';
SELECT count(*) as tag_verified FROM `test_prj_adm_1` WHERE `tag_status_y_n_na` = 'Y' AND `verification_status` = 'Verified';
SELECT count(*) as tag_not_verified FROM `test_prj_adm_1` WHERE `tag_status_y_n_na` = 'Y' AND `verification_status` != 'Verified';
SELECT count(*) as non_tag_verified FROM `test_prj_adm_1` WHERE `tag_status_y_n_na` = 'N' AND `verification_status` = 'Verified';
SELECT count(*) as non_tag_not_verified FROM `test_prj_adm_1` WHERE `tag_status_y_n_na` = 'N' AND `verification_status` != 'Verified';

SELECT count(*) as nverified FROM `test_prj_adm_1` WHERE `tag_status_y_n_na` = 'N' AND `verified_by` IS NOT NULL;
SELECT DISTINCT(item_category) FROM `test_prj_adm_1` WHERE `tag_status_y_n_na` = 'NA';
SELECT SUM(total_item_amount_capitalized) as amount, count(*) as catitems FROM `test_prj_adm_1` WHERE `item_category` = 'OE'