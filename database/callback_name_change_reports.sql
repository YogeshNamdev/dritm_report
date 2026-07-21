CREATE TABLE IF NOT EXISTS `callback_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `assigned_agent_id` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `remark_type` varchar(50) NOT NULL,
  `remark_other` text DEFAULT NULL,
  `latest_remark` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_callback_status_assigned` (`status`, `assigned_agent_id`),
  KEY `idx_callback_created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `callback_report_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `callback_id` int(11) NOT NULL,
  `action_type` enum('create','update','assign') NOT NULL DEFAULT 'update',
  `previous_agent_id` int(11) DEFAULT NULL,
  `new_agent_id` int(11) DEFAULT NULL,
  `remark_type` varchar(50) DEFAULT NULL,
  `remark_other` text DEFAULT NULL,
  `remark_text` text DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_callback_history_callback` (`callback_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `name_change_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `complaint_number` varchar(50) NOT NULL,
  `old_name` varchar(150) NOT NULL,
  `new_name` varchar(150) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `remark` varchar(50) NOT NULL DEFAULT 'DONE',
  `status` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_name_change_status_agent` (`status`, `agent_id`),
  KEY `idx_name_change_created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
