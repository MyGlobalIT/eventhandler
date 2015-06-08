/*TABLE STRUCTURE OF "events" table*/
CREATE TABLE `events` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `data` text NOT NULL COMMENT 'callback data received',
  `dated` datetime DEFAULT NULL COMMENT 'Date of event getting stored/updated',
  `status` varchar(45) DEFAULT NULL COMMENT 'status of event',
  `sync` tinyint(4) DEFAULT '0' COMMENT 'Is data sync with main database',
  `origin` varchar(45) DEFAULT NULL COMMENT 'Whether from SendGrid or Twilio',
  `token` varchar(100) DEFAULT NULL COMMENT 'Key of event as received from SendGrid or Twilio',
  `organization_id` int(11) DEFAULT NULL,
  `sync_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organization_id` (`organization_id`),
  KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

