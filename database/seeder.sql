
SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `is_active`, `avatar`) VALUES
(10, 'Budi Santoso', 'budi@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 1, 1, 'avatar1.jpg'),
(11, 'Siti Rahayu', 'siti@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 1, 1, 'avatar2.jpg'),

(20, 'Andi Wijaya', 'andi@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 2, 1, 'avatar3.jpg'),
(21, 'Dewi Lestari', 'dewi@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 2, 1, 'avatar4.jpg'),
(22, 'Eko Prasetyo', 'eko@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 2, 1, 'avatar5.jpg'),
(23, 'Fani Putri', 'fani@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 2, 1, 'avatar6.jpg'),
(24, 'Gunawan', 'gunawan@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 2, 1, 'avatar7.jpg'),

(30, 'Hartono', 'hartono@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 3, 1, 'avatar8.jpg'),
(31, 'Indah Sari', 'indah@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 3, 1, 'avatar9.jpg'),
(32, 'Joko Anwar', 'joko@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 3, 1, 'avatar10.jpg'),
(33, 'Kartika', 'kartika@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 3, 1, 'avatar11.jpg'),
(34, 'Lina Marlina', 'lina@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 3, 1, 'avatar12.jpg'),

(40, 'Maya Sari', 'maya@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 4, 1, 'avatar13.jpg'),
(41, 'Nurul Huda', 'nurul@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 4, 1, 'avatar14.jpg'),
(42, 'Oscar', 'oscar@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 4, 1, 'avatar15.jpg'),
(43, 'Putri Ayu', 'putri@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 4, 1, 'avatar16.jpg'),
(44, 'Rizky', 'rizky@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 4, 1, 'avatar17.jpg'),
(45, 'Siska', 'siska@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 4, 1, 'avatar18.jpg'),
(46, 'Tono', 'tono@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 4, 1, 'avatar19.jpg'),
(47, 'Wulan', 'wulan@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 4, 1, 'avatar20.jpg');

INSERT INTO `projects` (`id`, `title`, `description`, `type`, `periode_start`, `periode_end`, `deadline`, `status`, `created_by`) VALUES
(100, 'Weekly Content - July Week 1', 'Social media content for first week of July 2026', 'weekly', '2026-07-06', '2026-07-12', '2026-07-12', 'active', 10),
(101, 'Weekly Content - July Week 2', 'Social media content for second week of July 2026', 'weekly', '2026-07-13', '2026-07-19', '2026-07-19', 'active', 10),
(102, 'Weekly Content - July Week 3', 'Social media content for third week of July 2026', 'weekly', '2026-07-20', '2026-07-26', '2026-07-26', 'active', 10),
(103, 'Weekly Content - July Week 4', 'Social media content for fourth week of July 2026', 'weekly', '2026-07-27', '2026-08-02', '2026-08-02', 'active', 10),
(104, 'Monthly Campaign - July 2026', 'Complete monthly campaign for July with theme "Summer Vibes"', 'monthly', '2026-07-01', '2026-07-31', '2026-07-31', 'active', 10),
(105, 'Product Launch - Q3 2026', 'New product launch campaign for Q3', 'monthly', '2026-07-01', '2026-09-30', '2026-09-30', 'active', 10),
(106, 'Brand Awareness Campaign', 'Increase brand visibility across all platforms', 'monthly', '2026-06-01', '2026-08-31', '2026-08-31', 'active', 10),
(107, 'Weekly Content - June Week 4', 'Social media content for last week of June 2026', 'weekly', '2026-06-22', '2026-06-28', '2026-06-28', 'completed', 10),
(108, 'Weekly Content - June Week 3', 'Social media content for third week of June 2026', 'weekly', '2026-06-15', '2026-06-21', '2026-06-21', 'completed', 10),
(109, 'Monthly Campaign - June 2026', 'Complete monthly campaign for June with theme "Mid-Year Sale"', 'monthly', '2026-06-01', '2026-06-30', '2026-06-30', 'completed', 10),
(110, 'Weekly Content - June Week 2', 'Social media content for second week of June 2026', 'weekly', '2026-06-08', '2026-06-14', '2026-06-14', 'completed', 10),
(111, 'Weekly Content - June Week 1', 'Social media content for first week of June 2026', 'weekly', '2026-06-01', '2026-06-07', '2026-06-07', 'completed', 10),
(112, 'Monthly Campaign - May 2026', 'Complete monthly campaign for May with theme "Ramadan"', 'monthly', '2026-05-01', '2026-05-31', '2026-05-31', 'completed', 10),
(113, 'Weekly Content - May Week 4', 'Social media content for last week of May 2026', 'weekly', '2026-05-25', '2026-05-31', '2026-05-31', 'completed', 10),
(114, 'Weekly Content - May Week 3', 'Social media content for third week of May 2026', 'weekly', '2026-05-18', '2026-05-24', '2026-05-24', 'completed', 10);

INSERT INTO `tasks` (`id`, `project_id`, `title`, `description`, `assigned_to`, `deadline`, `status`, `priority`, `created_by`, `completed_at`, `quality_score`, `revision_count`) VALUES
(1000, 100, 'Edit Instagram Reel - Summer Sale', 'Create 15-second reel for summer sale promotion', 20, '2026-07-08', 'done', 'high', 10, '2026-07-07', 5, 0),
(1001, 100, 'Design Instagram Story Template', 'Create branded story template for daily use', 30, '2026-07-07', 'done', 'medium', 10, '2026-07-06', 4, 1),
(1002, 100, 'Schedule Facebook Posts Week 1', 'Schedule 7 posts for the first week', 40, '2026-07-06', 'done', 'medium', 10, '2026-07-05', 5, 0),
(1003, 100, 'Create TikTok Content - Product Demo', '30-second product demonstration video', 21, '2026-07-09', 'on_progress', 'high', 10, NULL, NULL, 0),
(1004, 100, 'Design LinkedIn Carousel', 'Professional carousel for B2B audience', 31, '2026-07-08', 'on_progress', 'medium', 10, NULL, NULL, 0),
(1005, 100, 'YouTube Shorts Editing', 'Edit 5 shorts for YouTube channel', 22, '2026-07-10', 'pending', 'high', 10, NULL, NULL, 0),
(1006, 100, 'Twitter Thread Design', 'Design visuals for 10-tweet thread', 32, '2026-07-07', 'done', 'low', 10, '2026-07-06', 4, 0),
(1007, 100, 'Instagram Reel - Behind the Scenes', 'Show team behind-the-scenes content', 23, '2026-07-09', 'on_progress', 'medium', 10, NULL, NULL, 0),
(1008, 100, 'Pinterest Pin Design', 'Create 20 pins for Pinterest marketing', 33, '2026-07-11', 'pending', 'low', 10, NULL, NULL, 0),
(1009, 100, 'Schedule LinkedIn Articles', 'Schedule 3 articles for the week', 41, '2026-07-06', 'done', 'medium', 10, '2026-07-05', 5, 0),
(1010, 100, 'Edit Video Testimonials', 'Compile customer testimonials into video', 24, '2026-07-10', 'pending', 'high', 10, NULL, NULL, 0),
(1011, 100, 'Design Email Newsletter Graphics', 'Create graphics for weekly newsletter', 34, '2026-07-08', 'on_progress', 'medium', 10, NULL, NULL, 0),
(1012, 100, 'Twitter Engagement Task', 'Engage with 50 relevant accounts', 42, '2026-07-07', 'done', 'low', 10, '2026-07-06', 3, 0),
(1013, 100, 'Video Ad - 15 Second Cut', 'Create 15-second video ad for Instagram', 20, '2026-07-09', 'on_progress', 'high', 10, NULL, NULL, 0),
(1014, 100, 'Design Blog Featured Images', 'Create 10 featured images for blog posts', 30, '2026-07-11', 'pending', 'medium', 10, NULL, NULL, 0),

(1015, 101, 'Edit Instagram Reel - New Arrival', 'Showcase new products in reel format', 21, '2026-07-14', 'on_progress', 'high', 10, NULL, NULL, 0),
(1016, 101, 'Design Facebook Ad Set', 'Create 5 ad variations for A/B testing', 31, '2026-07-15', 'pending', 'high', 10, NULL, NULL, 0),
(1017, 101, 'Schedule Instagram Stories Week 2', 'Daily story scheduling for week 2', 40, '2026-07-13', 'done', 'medium', 10, '2026-07-12', 5, 0),
(1018, 101, 'YouTube Video Editing', 'Edit 5-minute YouTube video', 22, '2026-07-16', 'pending', 'high', 10, NULL, NULL, 0),
(1019, 101, 'Design Packaging Mockups', 'Create product packaging mockups', 32, '2026-07-15', 'pending', 'medium', 10, NULL, NULL, 0),
(1020, 101, 'TikTok Trend Participation', 'Create content following current trend', 23, '2026-07-14', 'on_progress', 'medium', 10, NULL, NULL, 0),
(1021, 101, 'LinkedIn Company Page Updates', 'Update company page banner and info', 41, '2026-07-13', 'done', 'low', 10, '2026-07-12', 4, 0),
(1022, 101, 'Edit Tutorial Video Series', 'Edit 3-part tutorial series', 24, '2026-07-17', 'pending', 'high', 10, NULL, NULL, 0),
(1023, 101, 'Design Infographic - July Stats', 'Create monthly stats infographic', 33, '2026-07-15', 'on_progress', 'medium', 10, NULL, NULL, 0),
(1024, 101, 'Community Management Week 2', 'Respond to all comments and DMs', 42, '2026-07-13', 'done', 'low', 10, '2026-07-12', 5, 0),
(1025, 101, 'Video Ad Production', 'Produce 30-second video ad', 20, '2026-07-16', 'pending', 'high', 10, NULL, NULL, 0),
(1026, 101, 'Design Social Media Kit', 'Create cohesive social media kit', 34, '2026-07-15', 'pending', 'medium', 10, NULL, NULL, 0),
(1027, 101, 'Twitter Space Promotion', 'Promote upcoming Twitter Space', 43, '2026-07-14', 'on_progress', 'low', 10, NULL, NULL, 0),
(1028, 101, 'Reels Editing Batch', 'Edit 10 reels in bulk', 21, '2026-07-17', 'pending', 'high', 10, NULL, NULL, 0),
(1029, 101, 'Brand Guidelines Update', 'Update brand guidelines document', 30, '2026-07-15', 'pending', 'low', 10, NULL, NULL, 0),

(1030, 104, 'Edit July Campaign Hero Video', 'Main campaign video - 60 seconds', 20, '2026-07-15', 'on_progress', 'high', 10, NULL, NULL, 0),
(1031, 104, 'Design Campaign Visuals Pack', 'Complete visual package for campaign', 30, '2026-07-14', 'done', 'high', 10, '2026-07-13', 5, 0),
(1032, 104, 'Multi-platform Content Schedule', 'Schedule content across all platforms', 40, '2026-07-12', 'done', 'high', 10, '2026-07-11', 5, 0),
(1033, 104, 'Campaign Launch Video', 'Launch announcement video', 21, '2026-07-16', 'pending', 'high', 10, NULL, NULL, 0),
(1034, 104, 'Design Landing Page Graphics', 'Graphics for campaign landing page', 31, '2026-07-15', 'done', 'medium', 10, '2026-07-14', 4, 1),
(1035, 104, 'Influencer Content Coordination', 'Coordinate with 5 influencers', 41, '2026-07-20', 'on_progress', 'medium', 10, NULL, NULL, 0),
(1036, 104, 'Email Campaign Video Headers', 'Animated headers for emails', 22, '2026-07-17', 'pending', 'medium', 10, NULL, NULL, 0),
(1037, 104, 'Design Press Kit', 'Complete press kit design', 32, '2026-07-18', 'pending', 'high', 10, NULL, NULL, 0),
(1038, 104, 'Social Media Contest Setup', 'Setup and manage contest mechanics', 42, '2026-07-15', 'done', 'high', 10, '2026-07-14', 5, 0),
(1039, 104, 'Video Testimonials Compilation', 'Compile 10 customer testimonials', 23, '2026-07-19', 'pending', 'high', 10, NULL, NULL, 0),
(1040, 104, 'Design Ad Creatives Bundle', '20 ad variations for testing', 33, '2026-07-16', 'done', 'high', 10, '2026-07-15', 4, 0),
(1041, 104, 'Campaign Analytics Tracking', 'Setup analytics and tracking', 43, '2026-07-12', 'done', 'medium', 10, '2026-07-11', 5, 0),
(1042, 104, 'Product Demo Video Series', '5-part product demo series', 24, '2026-07-20', 'pending', 'high', 10, NULL, NULL, 0),
(1043, 104, 'Design Story Highlights', 'Create Instagram story highlights', 34, '2026-07-17', 'on_progress', 'low', 10, NULL, NULL, 0),
(1044, 104, 'UGC Content Curation', 'Curate and repost UGC content', 44, '2026-07-15', 'done', 'low', 10, '2026-07-14', 4, 0),
(1045, 104, 'Campaign Recap Video', 'End-of-campaign recap video', 20, '2026-07-30', 'pending', 'medium', 10, NULL, NULL, 0),
(1046, 104, 'Design Thank You Graphics', 'Thank you graphics for supporters', 30, '2026-07-28', 'pending', 'low', 10, NULL, NULL, 0),
(1047, 104, 'Live Stream Coverage', 'Cover campaign launch live', 41, '2026-07-13', 'done', 'high', 10, '2026-07-12', 5, 0),
(1048, 104, 'Video Ad Variations', 'Create 10 ad video variations', 21, '2026-07-22', 'pending', 'high', 10, NULL, NULL, 0),
(1049, 104, 'Design Campaign Report', 'Visual campaign performance report', 31, '2026-07-31', 'pending', 'medium', 10, NULL, NULL, 0),

(1050, 107, 'Edit Reel - June Promo', 'June promotion reel content', 22, '2026-06-26', 'done', 'high', 10, '2026-06-25', 5, 0),
(1051, 107, 'Design Father\'s Day Posts', 'Father\'s Day special content', 32, '2026-06-25', 'done', 'high', 10, '2026-06-24', 5, 0),
(1052, 107, 'Schedule June End Content', 'End of month content scheduling', 42, '2026-06-24', 'done', 'medium', 10, '2026-06-23', 4, 0),
(1053, 107, 'Video - Mid Year Review', 'Mid-year review video', 23, '2026-06-27', 'done', 'high', 10, '2026-06-26', 5, 1),
(1054, 107, 'Design July Teaser', 'Teaser content for July', 33, '2026-06-26', 'done', 'medium', 10, '2026-06-25', 4, 0),
(1055, 107, 'Community June Wrap-up', 'June community management wrap', 43, '2026-06-24', 'done', 'low', 10, '2026-06-23', 5, 0),
(1056, 107, 'Edit Batch Content', 'Batch edit 10 videos', 24, '2026-06-27', 'done', 'high', 10, '2026-06-26', 4, 1),
(1057, 107, 'Design Sale Banner', 'End of season sale banner', 34, '2026-06-25', 'done', 'medium', 10, '2026-06-24', 5, 0),
(1058, 107, 'Instagram Takeover Setup', 'Influencer takeover coordination', 44, '2026-06-26', 'done', 'medium', 10, '2026-06-25', 5, 0),
(1059, 107, 'Video - Customer Stories', 'Customer story compilation', 20, '2026-06-27', 'done', 'high', 10, '2026-06-26', 5, 0),
(1060, 107, 'Design Event Graphics', 'Graphics for virtual event', 30, '2026-06-25', 'done', 'medium', 10, '2026-06-24', 4, 0),
(1061, 107, 'Twitter Chat Host', 'Host Twitter chat session', 41, '2026-06-24', 'done', 'low', 10, '2026-06-23', 5, 0),
(1062, 107, 'Tutorial Video Edit', 'Edit tutorial video', 21, '2026-06-26', 'done', 'medium', 10, '2026-06-25', 4, 0),
(1063, 107, 'Design Newsletter Header', 'Monthly newsletter header', 31, '2026-06-25', 'done', 'low', 10, '2026-06-24', 5, 0),
(1064, 107, 'Analytics Report Video', 'Video analytics summary', 22, '2026-06-27', 'done', 'medium', 10, '2026-06-26', 4, 0),

(1065, 112, 'Eid Mubarak Campaign Video', 'Special Eid campaign video', 23, '2026-05-25', 'done', 'high', 10, '2026-05-26', 3, 2),
(1066, 112, 'Design Ramadan End Graphics', 'Ramadan ending graphics', 33, '2026-05-24', 'done', 'high', 10, '2026-05-24', 5, 0),
(1067, 112, 'Eid Content Scheduling', 'Schedule Eid content across platforms', 42, '2026-05-23', 'done', 'high', 10, '2026-05-23', 5, 0),
(1068, 112, 'Video - Family Moments', 'Family-themed video content', 24, '2026-05-26', 'done', 'medium', 10, '2026-05-27', 3, 1),
(1069, 112, 'Design Greeting Cards', 'Digital greeting card designs', 34, '2026-05-25', 'done', 'low', 10, '2026-05-25', 5, 0),
(1070, 112, 'Community Management May', 'May community management wrap', 43, '2026-05-23', 'done', 'medium', 10, '2026-05-23', 4, 0),
(1071, 112, 'Special Offer Video Edit', 'Promotional video editing', 20, '2026-05-27', 'done', 'high', 10, '2026-05-28', 3, 2),
(1072, 112, 'Design Celebration Assets', 'Celebration graphic assets', 30, '2026-05-25', 'done', 'medium', 10, '2026-05-25', 5, 0),
(1073, 112, 'Social Media Contest', 'Eid social media contest', 44, '2026-05-24', 'done', 'high', 10, '2026-05-24', 4, 1),
(1074, 112, 'Thank You Video', 'Thank you video for supporters', 21, '2026-05-28', 'done', 'medium', 10, '2026-05-28', 5, 0),

(1075, 102, 'July Week 3 Planning', 'Plan content for third week', 40, '2026-07-20', 'pending', 'medium', 10, NULL, NULL, 0),
(1076, 102, 'Design Week 3 Calendar', 'Content calendar design', 31, '2026-07-19', 'pending', 'low', 10, NULL, NULL, 0),
(1077, 102, 'Video Production Week 3', 'Video tasks for week 3', 22, '2026-07-22', 'pending', 'high', 10, NULL, NULL, 0),
(1078, 103, 'July Week 4 Planning', 'Plan content for fourth week', 41, '2026-07-27', 'pending', 'medium', 10, NULL, NULL, 0),
(1079, 103, 'Design Week 4 Calendar', 'Content calendar design', 32, '2026-07-26', 'pending', 'low', 10, NULL, NULL, 0),
(1080, 103, 'Video Production Week 4', 'Video tasks for week 4', 23, '2026-07-29', 'pending', 'high', 10, NULL, NULL, 0),
(1081, 105, 'Q3 Launch Video', 'Q3 campaign launch video', 24, '2026-08-05', 'pending', 'high', 10, NULL, NULL, 0),
(1082, 105, 'Design Q3 Assets', 'Q3 campaign assets', 33, '2026-08-04', 'pending', 'high', 10, NULL, NULL, 0),
(1083, 106, 'Brand Awareness Content', 'Brand awareness video content', 20, '2026-08-10', 'pending', 'medium', 10, NULL, NULL, 0),
(1084, 106, 'Design Brand Kit', 'Brand awareness design kit', 34, '2026-08-09', 'pending', 'medium', 10, NULL, NULL, 0);

INSERT INTO `task_tags` (`task_id`, `tag_id`) VALUES
(1000, 1), (1005, 1), (1010, 1), (1015, 1), (1018, 1), (1022, 1), (1025, 1), (1028, 1), (1030, 1), (1033, 1),
(1001, 2), (1016, 2), (1031, 2), (1032, 2), (1038, 2), (1040, 2), (1042, 2), (1048, 2), (1051, 2), (1053, 2),
(1002, 3), (1004, 3), (1011, 3), (1014, 3), (1019, 3), (1026, 3), (1034, 3), (1036, 3), (1043, 3), (1049, 3),
(1006, 4), (1012, 4), (1021, 4), (1027, 4), (1044, 4), (1046, 4), (1055, 4), (1061, 4), (1063, 4), (1069, 4),
(1000, 5), (1003, 5), (1005, 5), (1007, 5), (1010, 5), (1013, 5), (1015, 5), (1018, 5), (1020, 5), (1022, 5),
(1001, 6), (1004, 6), (1011, 6), (1019, 6), (1023, 6), (1026, 6), (1034, 6), (1037, 6), (1040, 6), (1049, 6);

INSERT INTO `task_comments` (`task_id`, `user_id`, `comment`, `created_at`) VALUES
(1000, 10, 'Great work on the reel! Colors look perfect.', '2026-07-07 10:30:00'),
(1000, 20, 'Thanks! Added the transition effects as requested.', '2026-07-07 11:00:00'),
(1001, 10, 'Please adjust the font size on the third slide.', '2026-07-06 09:15:00'),
(1001, 30, 'Done! Font size increased by 20%.', '2026-07-06 10:00:00'),
(1001, 10, 'Perfect, approved!', '2026-07-06 10:30:00'),
(1003, 40, 'Product demo is trending, prioritize this!', '2026-07-07 14:00:00'),
(1005, 10, 'YouTube shorts need to be vertical format.', '2026-07-06 16:00:00'),
(1015, 10, 'New arrival content needs to highlight discount.', '2026-07-13 09:00:00'),
(1030, 10, 'Hero video needs to be emotional and impactful.', '2026-07-10 10:00:00'),
(1031, 30, 'Visual pack ready for review.', '2026-07-13 15:00:00'),
(1032, 40, 'All platforms scheduled successfully.', '2026-07-11 17:00:00'),
(1038, 42, 'Contest is getting great engagement!', '2026-07-14 12:00:00'),
(1050, 10, 'June promo performed well, good job!', '2026-06-25 14:00:00'),
(1053, 10, 'Mid-year review needs minor edits.', '2026-06-26 10:00:00'),
(1053, 23, 'Revisions completed and resubmitted.', '2026-06-26 14:00:00'),
(1065, 10, 'Eid campaign was late but quality is good.', '2026-05-26 11:00:00'),
(1068, 10, 'Please submit on time next time.', '2026-05-27 09:00:00');

INSERT INTO `task_files` (`task_id`, `file_path`, `file_name`, `file_type`, `file_size`, `uploaded_by`, `uploaded_at`) VALUES
(1000, '/uploads/reels/', 'summer_sale_reel_v1.mp4', 'video/mp4', 15728640, 20, '2026-07-07 10:00:00'),
(1000, '/uploads/thumbnails/', 'summer_sale_thumb.jpg', 'image/jpeg', 524288, 20, '2026-07-07 10:01:00'),
(1001, '/uploads/templates/', 'story_template_final.psd', 'application/x-photoshop', 20971520, 30, '2026-07-06 09:00:00'),
(1001, '/uploads/templates/', 'story_template_v2.fig', 'application/x-figma', 15728640, 30, '2026-07-06 10:00:00'),
(1003, '/uploads/videos/', 'product_demo_raw.mp4', 'video/mp4', 52428800, 21, '2026-07-07 11:00:00'),
(1015, '/uploads/reels/', 'new_arrival_reel.mp4', 'video/mp4', 12582912, 21, '2026-07-13 10:00:00'),
(1030, '/uploads/videos/', 'hero_campaign_4k.mp4', 'video/mp4', 104857600, 20, '2026-07-14 15:00:00'),
(1031, '/uploads/designs/', 'campaign_visuals_pack.zip', 'application/zip', 52428800, 30, '2026-07-13 14:00:00'),
(1037, '/uploads/designs/', 'press_kit_final.pdf', 'application/pdf', 10485760, 32, '2026-07-17 16:00:00'),
(1050, '/uploads/videos/', 'june_promo_reel.mp4', 'video/mp4', 11534336, 22, '2026-06-25 13:00:00');

INSERT INTO `task_revisions` (`task_id`, `revised_by`, `revision_reason`, `revision_date`) VALUES
(1001, 10, 'Font size too small, readability issues', '2026-07-06 09:15:00'),
(1005, 10, 'Re-editing for YouTube shorts format', '2026-07-06 16:00:00'),
(1053, 10, 'Mid-year review needs pacing adjustments', '2026-06-26 10:00:00'),
(1065, 10, 'Eid campaign timing and transition issues', '2026-05-26 11:00:00'),
(1068, 10, 'Late submission - first version rushed', '2026-05-27 09:00:00'),
(1071, 10, 'Special offer video needs more energy', '2026-05-28 10:00:00'),
(1034, 10, 'Landing page graphics color mismatch', '2026-07-14 11:00:00'),
(1040, 10, 'Ad creatives need A/B testing variations', '2026-07-15 09:00:00');

INSERT INTO `notifications` (`user_id`, `task_id`, `message`, `type`, `is_read`, `created_at`) VALUES
(20, 1000, 'New task assigned: Edit Instagram Reel - Summer Sale', 'task_assigned', 1, '2026-07-06 08:00:00'),
(30, 1001, 'New task assigned: Design Instagram Story Template', 'task_assigned', 1, '2026-07-05 08:00:00'),
(40, 1002, 'New task assigned: Schedule Facebook Posts Week 1', 'task_assigned', 1, '2026-07-04 08:00:00'),
(21, 1003, 'New task assigned: Create TikTok Content - Product Demo', 'task_assigned', 0, '2026-07-06 08:00:00'),
(31, 1004, 'New task assigned: Design LinkedIn Carousel', 'task_assigned', 0, '2026-07-06 08:00:00'),
(22, 1005, 'New task assigned: YouTube Shorts Editing', 'task_assigned', 0, '2026-07-06 08:00:00'),
(20, 1013, 'New task assigned: Video Ad - 15 Second Cut', 'task_assigned', 0, '2026-07-07 08:00:00'),
(21, 1015, 'New task assigned: Edit Instagram Reel - New Arrival', 'task_assigned', 0, '2026-07-12 08:00:00'),
(23, 1007, 'Reminder: Pinterest Pin Design due tomorrow', 'deadline_reminder', 0, '2026-07-10 08:00:00'),
(24, 1010, 'Reminder: Video Testimonials due in 2 days', 'deadline_reminder', 0, '2026-07-08 08:00:00'),
(22, 1018, 'Reminder: YouTube Video Editing due soon', 'deadline_reminder', 0, '2026-07-14 08:00:00'),
(20, 1000, 'Budi Santoso commented on your task', 'new_comment', 1, '2026-07-07 10:30:00'),
(30, 1001, 'Budi Santoso commented on your task', 'new_comment', 1, '2026-07-06 09:15:00'),
(21, 1003, 'Maya Sari commented on your task', 'new_comment', 0, '2026-07-07 14:00:00'),
(10, 1000, 'Task marked as done: Edit Instagram Reel - Summer Sale', 'status_update', 1, '2026-07-07 10:00:00'),
(10, 1001, 'Task marked as done: Design Instagram Story Template', 'status_update', 1, '2026-07-06 10:00:00'),
(10, 1002, 'Task marked as done: Schedule Facebook Posts Week 1', 'status_update', 1, '2026-07-05 10:00:00');

INSERT INTO `kpis` (`user_id`, `period_type`, `period_start`, `period_end`, `tasks_assigned`, `tasks_done`, `tasks_on_time`, `tasks_overdue`, `tasks_revised`, `completion_rate`, `ontime_rate`, `quality_avg`, `performance_score`) VALUES
(20, 'weekly', '2026-06-22', '2026-06-28', 8, 7, 6, 0, 1, 87.50, 85.71, 4.50, 85.00),
(20, 'weekly', '2026-06-29', '2026-07-05', 6, 5, 4, 1, 1, 83.33, 80.00, 4.20, 79.00),
(20, 'weekly', '2026-07-06', '2026-07-12', 5, 2, 2, 0, 0, 40.00, 100.00, 5.00, 72.00),

(21, 'weekly', '2026-06-22', '2026-06-28', 7, 6, 5, 1, 1, 85.71, 83.33, 4.30, 82.00),
(21, 'weekly', '2026-06-29', '2026-07-05', 5, 4, 4, 0, 1, 80.00, 100.00, 4.50, 80.00),
(21, 'weekly', '2026-07-06', '2026-07-12', 4, 1, 1, 0, 0, 25.00, 100.00, 5.00, 65.00),

(22, 'weekly', '2026-06-22', '2026-06-28', 6, 5, 4, 0, 0, 83.33, 80.00, 4.60, 82.00),
(22, 'weekly', '2026-06-29', '2026-07-05', 7, 6, 5, 1, 0, 85.71, 83.33, 4.40, 81.00),
(22, 'weekly', '2026-07-06', '2026-07-12', 5, 2, 2, 0, 0, 40.00, 100.00, 4.00, 68.00),

(23, 'weekly', '2026-06-22', '2026-06-28', 5, 4, 3, 1, 1, 80.00, 75.00, 4.20, 75.00),
(23, 'weekly', '2026-06-29', '2026-07-05', 6, 5, 4, 0, 1, 83.33, 80.00, 4.40, 79.00),
(23, 'weekly', '2026-07-06', '2026-07-12', 4, 1, 1, 0, 0, 25.00, 100.00, 4.50, 66.00),

(24, 'weekly', '2026-06-22', '2026-06-28', 4, 4, 3, 0, 0, 100.00, 75.00, 4.80, 87.00),
(24, 'weekly', '2026-06-29', '2026-07-05', 5, 5, 5, 0, 0, 100.00, 100.00, 4.90, 95.00),
(24, 'weekly', '2026-07-06', '2026-07-12', 3, 1, 1, 0, 0, 33.33, 100.00, 5.00, 70.00),

(30, 'weekly', '2026-06-22', '2026-06-28', 6, 5, 4, 0, 1, 83.33, 80.00, 4.50, 82.00),
(30, 'weekly', '2026-06-29', '2026-07-05', 5, 4, 4, 0, 1, 80.00, 100.00, 4.60, 81.00),
(30, 'weekly', '2026-07-06', '2026-07-12', 4, 2, 2, 0, 0, 50.00, 100.00, 4.80, 76.00),

(31, 'weekly', '2026-06-22', '2026-06-28', 5, 4, 3, 1, 1, 80.00, 75.00, 4.30, 76.00),
(31, 'weekly', '2026-06-29', '2026-07-05', 6, 5, 5, 0, 1, 83.33, 100.00, 4.50, 83.00),
(31, 'weekly', '2026-07-06', '2026-07-12', 3, 1, 1, 0, 0, 33.33, 100.00, 4.60, 67.00),

(32, 'weekly', '2026-06-22', '2026-06-28', 7, 6, 5, 0, 0, 85.71, 83.33, 4.70, 85.00),
(32, 'weekly', '2026-06-29', '2026-07-05', 5, 4, 4, 0, 0, 80.00, 100.00, 4.80, 82.00),
(32, 'weekly', '2026-07-06', '2026-07-12', 4, 2, 2, 0, 0, 50.00, 100.00, 4.90, 78.00),

(33, 'weekly', '2026-06-22', '2026-06-28', 4, 3, 2, 1, 0, 75.00, 66.67, 4.20, 71.00),
(33, 'weekly', '2026-06-29', '2026-07-05', 5, 4, 3, 0, 0, 80.00, 75.00, 4.40, 77.00),
(33, 'weekly', '2026-07-06', '2026-07-12', 3, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00),

(34, 'weekly', '2026-06-22', '2026-06-28', 6, 5, 4, 0, 1, 83.33, 80.00, 4.60, 83.00),
(34, 'weekly', '2026-06-29', '2026-07-05', 4, 4, 3, 0, 1, 100.00, 75.00, 4.70, 88.00),
(34, 'weekly', '2026-07-06', '2026-07-12', 3, 1, 1, 0, 0, 33.33, 100.00, 4.80, 70.00),

(40, 'weekly', '2026-06-22', '2026-06-28', 8, 7, 6, 0, 0, 87.50, 85.71, 4.70, 86.00),
(40, 'weekly', '2026-06-29', '2026-07-05', 6, 6, 5, 0, 0, 100.00, 83.33, 4.80, 91.00),
(40, 'weekly', '2026-07-06', '2026-07-12', 5, 3, 3, 0, 0, 60.00, 100.00, 5.00, 79.00),

(41, 'weekly', '2026-06-22', '2026-06-28', 5, 4, 3, 1, 0, 80.00, 75.00, 4.40, 78.00),
(41, 'weekly', '2026-06-29', '2026-07-05', 6, 5, 4, 0, 0, 83.33, 80.00, 4.50, 80.00),
(41, 'weekly', '2026-07-06', '2026-07-12', 4, 2, 2, 0, 0, 50.00, 100.00, 4.60, 72.00),

(42, 'weekly', '2026-06-22', '2026-06-28', 4, 3, 3, 0, 0, 75.00, 100.00, 4.50, 80.00),
(42, 'weekly', '2026-06-29', '2026-07-05', 5, 4, 4, 0, 0, 80.00, 100.00, 4.60, 82.00),
(42, 'weekly', '2026-07-06', '2026-07-12', 3, 1, 1, 0, 0, 33.33, 100.00, 4.70, 68.00),

(43, 'weekly', '2026-06-22', '2026-06-28', 6, 5, 4, 0, 1, 83.33, 80.00, 4.40, 81.00),
(43, 'weekly', '2026-06-29', '2026-07-05', 4, 4, 3, 0, 1, 100.00, 75.00, 4.50, 85.00),
(43, 'weekly', '2026-07-06', '2026-07-12', 3, 1, 1, 0, 0, 33.33, 100.00, 4.60, 67.00),

(44, 'weekly', '2026-06-22', '2026-06-28', 7, 6, 5, 0, 0, 85.71, 83.33, 4.60, 84.00),
(44, 'weekly', '2026-06-29', '2026-07-05', 5, 5, 4, 0, 0, 100.00, 80.00, 4.70, 89.00),
(44, 'weekly', '2026-07-06', '2026-07-12', 4, 2, 2, 0, 0, 50.00, 100.00, 4.80, 74.00),

(45, 'weekly', '2026-06-22', '2026-06-28', 5, 4, 4, 0, 0, 80.00, 100.00, 4.50, 82.00),
(45, 'weekly', '2026-06-29', '2026-07-05', 6, 5, 5, 0, 0, 83.33, 100.00, 4.60, 85.00),
(45, 'weekly', '2026-07-06', '2026-07-12', 3, 1, 1, 0, 0, 33.33, 100.00, 4.70, 68.00),

(46, 'weekly', '2026-06-22', '2026-06-28', 4, 3, 2, 1, 0, 75.00, 66.67, 4.30, 73.00),
(46, 'weekly', '2026-06-29', '2026-07-05', 5, 4, 3, 0, 0, 80.00, 75.00, 4.40, 77.00),
(46, 'weekly', '2026-07-06', '2026-07-12', 3, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00),

(47, 'weekly', '2026-06-22', '2026-06-28', 6, 5, 4, 0, 1, 83.33, 80.00, 4.50, 82.00),
(47, 'weekly', '2026-06-29', '2026-07-05', 4, 4, 3, 0, 1, 100.00, 75.00, 4.60, 87.00),
(47, 'weekly', '2026-07-06', '2026-07-12', 3, 1, 1, 0, 0, 33.33, 100.00, 4.70, 70.00);

SET FOREIGN_KEY_CHECKS = 1;

