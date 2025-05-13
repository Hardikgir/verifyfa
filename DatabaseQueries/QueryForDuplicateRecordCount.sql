SELECT * FROM u337050398_verifyfadatabs.project_1746816387;
SELECT COUNT(`item_unique_code`) AS adet, item_unique_code,item_sub_category FROM  `project_1746816387` GROUP BY `item_unique_code` ORDER BY adet DESC;
SELECT item_unique_code, COUNT(*) AS duplicate_count FROM project_1746816387 GROUP BY item_unique_code HAVING COUNT(*) > 1;