-- Scholarship and student discount module updates
-- Run this script on the existing database.

START TRANSACTION;

ALTER TABLE `scholarships`
  ADD COLUMN `code` varchar(50) DEFAULT NULL AFTER `name`,
  ADD COLUMN `description` varchar(255) DEFAULT NULL AFTER `discount_percent`,
  ADD COLUMN `status` int(11) NOT NULL DEFAULT 1 AFTER `description`,
  ADD COLUMN `created_at` timestamp NOT NULL DEFAULT current_timestamp() AFTER `status`;

ALTER TABLE `student_fee_ledger`
  ADD COLUMN `gross_fee` decimal(10,2) DEFAULT NULL AFTER `admission_id`,
  ADD COLUMN `total_discount` decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `gross_fee`,
  ADD COLUMN `net_fee` decimal(10,2) DEFAULT NULL AFTER `total_discount`;

UPDATE `student_fee_ledger`
SET
  `gross_fee` = COALESCE(`gross_fee`, `total_fee`),
  `total_discount` = COALESCE(`total_discount`, 0.00),
  `net_fee` = COALESCE(`net_fee`, `total_fee`),
  `balance` = COALESCE(`balance`, `total_fee` - COALESCE(`paid`, 0.00));

CREATE TABLE IF NOT EXISTS `student_fee_discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admission_id` int(11) NOT NULL,
  `ledger_id` int(11) NOT NULL,
  `discount_category` varchar(30) NOT NULL COMMENT 'SCHOLARSHIP,REFERENCE,SPECIAL,OTHER',
  `scholarship_id` int(11) DEFAULT NULL,
  `discount_label` varchar(100) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `discount_type` varchar(20) NOT NULL COMMENT 'PERCENT,AMOUNT',
  `discount_value` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `admission_id` (`admission_id`),
  KEY `ledger_id` (`ledger_id`),
  KEY `scholarship_id` (`scholarship_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `student_scholarship`
  ADD COLUMN `created_at` timestamp NOT NULL DEFAULT current_timestamp() AFTER `discount_amount`;

COMMIT;
