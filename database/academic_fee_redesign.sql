-- Academic fee redesign
-- Adds batch-based fee plans, semester-aware installments, and year-bound discounts.

START TRANSACTION;

ALTER TABLE `fee_types`
  ADD COLUMN `fee_category` varchar(30) NOT NULL DEFAULT 'MISC' AFTER `fee_name`,
  ADD COLUMN `charge_mode` varchar(20) NOT NULL DEFAULT 'ONE_TIME' AFTER `fee_category`,
  ADD COLUMN `discount_allowed` tinyint(1) NOT NULL DEFAULT 0 AFTER `charge_mode`;

UPDATE `fee_types`
SET
  `fee_category` = CASE
    WHEN LOWER(`fee_name`) LIKE '%admission%' THEN 'ADMISSION'
    WHEN LOWER(`fee_name`) LIKE '%tuition%' THEN 'TUITION'
    ELSE 'MISC'
  END,
  `charge_mode` = CASE
    WHEN LOWER(`fee_name`) LIKE '%admission%' THEN 'ONE_TIME'
    WHEN LOWER(`fee_name`) LIKE '%tuition%' THEN 'YEARLY'
    ELSE 'SEMESTER'
  END,
  `discount_allowed` = CASE
    WHEN LOWER(`fee_name`) LIKE '%tuition%' THEN 1
    ELSE 0
  END;

CREATE TABLE IF NOT EXISTS `batch_fee_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `tuition_fee_type_id` int(11) DEFAULT NULL,
  `admission_fee_type_id` int(11) DEFAULT NULL,
  `tuition_fee_yearly` decimal(10,2) NOT NULL DEFAULT 0.00,
  `admission_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `misc_charge_mode` varchar(20) NOT NULL DEFAULT 'YEARLY',
  `effective_from` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `batch_fee_plan_misc_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) NOT NULL,
  `academic_year_no` int(11) NOT NULL,
  `semester_no` int(11) DEFAULT NULL,
  `fee_type_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `charge_mode` varchar(20) NOT NULL DEFAULT 'YEARLY',
  `remarks` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `student_fee_plan_snapshot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admission_id` int(11) NOT NULL,
  `batch_fee_plan_id` int(11) NOT NULL,
  `course_duration_years` int(11) NOT NULL,
  `tuition_fee_yearly` decimal(10,2) NOT NULL DEFAULT 0.00,
  `admission_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `misc_charge_mode` varchar(20) NOT NULL DEFAULT 'YEARLY',
  `snapshot_json` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `fee_installments`
  ADD COLUMN `academic_year_no` int(11) DEFAULT NULL AFTER `ledger_id`,
  ADD COLUMN `semester_no` int(11) DEFAULT NULL AFTER `academic_year_no`,
  ADD COLUMN `fee_type_id` int(11) DEFAULT NULL AFTER `semester_no`,
  ADD COLUMN `component_group` varchar(30) DEFAULT NULL AFTER `fee_type_id`,
  ADD COLUMN `installment_label` varchar(100) DEFAULT NULL AFTER `component_group`,
  ADD COLUMN `gross_amount` decimal(10,2) DEFAULT NULL AFTER `installment_label`,
  ADD COLUMN `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `gross_amount`,
  ADD COLUMN `net_amount` decimal(10,2) DEFAULT NULL AFTER `discount_amount`;

UPDATE `fee_installments`
SET
  `gross_amount` = COALESCE(`gross_amount`, `amount`),
  `net_amount` = COALESCE(`net_amount`, `amount`),
  `component_group` = COALESCE(`component_group`, 'LEGACY'),
  `installment_label` = COALESCE(`installment_label`, 'Legacy Installment');

ALTER TABLE `student_fee_discounts`
  ADD COLUMN `academic_year_no` int(11) NOT NULL DEFAULT 1 AFTER `ledger_id`,
  ADD COLUMN `semester_no` int(11) DEFAULT NULL AFTER `academic_year_no`,
  ADD COLUMN `applicable_on` varchar(30) NOT NULL DEFAULT 'TUITION' AFTER `semester_no`,
  ADD COLUMN `valid_from` date DEFAULT NULL AFTER `applicable_on`,
  ADD COLUMN `valid_to` date DEFAULT NULL AFTER `valid_from`;

COMMIT;
