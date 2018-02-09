ALTER TABLE `wtv_city` ADD COLUMN `city_is_deleted` TINYINT DEFAULT 0 AFTER `city_type`;
ALTER TABLE `wtv_district` ADD COLUMN `district_is_deleted` TINYINT DEFAULT 0 AFTER `district_city_code`;
ALTER TABLE `wtv_street` ADD COLUMN `street_is_deleted` TINYINT DEFAULT 0 AFTER `street_district_code`;