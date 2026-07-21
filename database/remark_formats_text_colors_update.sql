ALTER TABLE `remark_formats`
  ADD COLUMN `title_color` varchar(7) NOT NULL DEFAULT '#212529' AFTER `remark_content`,
  ADD COLUMN `subtitle_color` varchar(7) NOT NULL DEFAULT '#6c757d' AFTER `title_color`,
  ADD KEY `idx_remark_formats_title_color` (`title_color`),
  ADD KEY `idx_remark_formats_subtitle_color` (`subtitle_color`);
