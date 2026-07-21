ALTER TABLE `no_same_resolution_report`
  ADD COLUMN `complaint_status` varchar(10) NOT NULL DEFAULT 'Open' AFTER `issue`,
  ADD KEY `idx_no_same_complaint_status` (`complaint_status`);

ALTER TABLE `copy_paste_wrong_resolution_report`
  ADD COLUMN `complaint_status` varchar(10) NOT NULL DEFAULT 'Open' AFTER `issue`,
  ADD KEY `idx_copy_paste_complaint_status` (`complaint_status`);

ALTER TABLE `direction_report`
  ADD COLUMN `complaint_status` varchar(10) NOT NULL DEFAULT 'Open' AFTER `issue`,
  ADD KEY `idx_direction_complaint_status` (`complaint_status`);

CREATE TABLE IF NOT EXISTS `report_complaint_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_key` varchar(50) NOT NULL,
  `report_name` varchar(100) NOT NULL,
  `record_id` int(11) NOT NULL,
  `complaint_no` bigint(20) NOT NULL,
  `previous_complaint_status` varchar(10) DEFAULT NULL,
  `new_complaint_status` varchar(10) DEFAULT NULL,
  `action_type` varchar(30) NOT NULL DEFAULT 'update',
  `officer_worked` tinyint(1) NOT NULL DEFAULT 0,
  `helpdesk_remark` text DEFAULT NULL,
  `other_remark` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_report_record` (`report_key`, `record_id`),
  KEY `idx_complaint_no` (`complaint_no`),
  KEY `idx_status_transition` (`previous_complaint_status`, `new_complaint_status`),
  KEY `idx_updated_by` (`updated_by`),
  KEY `idx_updated_at` (`updated_at`),
  KEY `idx_officer_worked` (`officer_worked`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
