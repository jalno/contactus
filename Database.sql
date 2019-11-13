/*
*	ContactUS
*	https://git.jeyserver.com/abedi/contactus
*/

CREATE TABLE `contactus_letter` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`date` int(11) NOT NULL,
	`ip` varchar(15) NOT NULL,
	`name` varchar(100) NOT NULL,
	`email` varchar(100) NOT NULL,
	`subject` varchar(100) NOT NULL,
	`text` text NOT NULL,
	`status` tinyint(4) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `contactus_letter_reply` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`letter` int(11) NOT NULL,
	`date` int(11) NOT NULL,
	`sender` int(11) NOT NULL,
	`email` int(11) NOT NULL,
	`text` text NOT NULL,
	PRIMARY KEY (`id`),
	KEY `sender` (`sender`),
	KEY `email` (`email`),
	KEY `letter` (`letter`),
	CONSTRAINT `contactus_letter_reply_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `userpanel_users` (`id`) ON DELETE CASCADE,
	CONSTRAINT `contactus_letter_reply_ibfk_2` FOREIGN KEY (`email`) REFERENCES `email_senders_addresses` (`id`) ON DELETE CASCADE,
	CONSTRAINT `contactus_letter_reply_ibfk_3` FOREIGN KEY (`letter`) REFERENCES `contactus_letter_reply` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT INTO `userpanel_usertypes_permissions` (`type`, `name`) VALUES
(1, 'contactus_letter_delete'),
(1, 'contactus_letter_search'),
(1, 'contactus_letter_view');
