/* User Roles */
ALTER TABLE `users` ADD `role` varchar(45) NULL DEFAULT NULL  AFTER `email`;

/* Company to user relationships */
CREATE TABLE `companies_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/* Company Status*/
ALTER TABLE `companies` ADD `email` varchar(300) NULL DEFAULT NULL  AFTER `info`;
ALTER TABLE `companies` ADD `status` varchar(45) NULL DEFAULT NULL  AFTER `email`;

/* Document types / creative brief */

CREATE TABLE `docfields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `label` text NOT NULL,
  `type` varchar(45) NOT NULL,
  `help_text` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
INSERT INTO `docfields` (`id`,`name`,`label`,`type`,`help_text`,`order`)
VALUES
	(1, 'overview', 'Overview', 'textarea', 'Provide a brief overview of the whole project.', 1),
	(2, 'current_situation', 'Current Situation', 'textarea', 'What is working or not working, what needs to be improved, why is the project needed, what is hoped to be achieved?', 2),
	(3, 'target_audiences', 'Target Audiences', 'textarea', 'Who does the project target? Are there any specific characterstics of these audiences? Age, income, hobbies, likes/dislikes, dreams, cars, jobs, size of house, shopping preferences, perception of Client, psychology, etc.', 3),
	(4, 'goals', 'Goals', 'textarea', 'Primary goals of the project.', 4),
	(5, 'communication', 'Communication Strategy', 'textarea', 'How wil this project be promoted and communicated? What is the timing for each promotion/communication and who is involved?', 6),
	(6, 'Timing', 'Timing', 'textarea', 'What is the deadline for the project? Are there any milestones that must be met?', 7),
	(7, 'sponsor', 'Project Sponsor', 'textarea', 'Who is the main sponsor and who will be signing-off the project?', 8),
	(8, 'stakeholders', 'Stakeholders', 'textarea', 'Who is involved in the project from an oversight and team perspective?', 9),
	(9, 'requirements', 'Requirements', 'textarea', 'Are there any specific requirements that must be incorporated?', 5);


CREATE TABLE `docfields_doctypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `docfield_id` int(11) NOT NULL,
  `doctype_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
INSERT INTO `docfields_doctypes` (`id`,`docfield_id`,`doctype_id`)
VALUES
	(1, 1, 2),
	(2, 2, 2),
	(3, 3, 2),
	(4, 4, 2),
	(5, 5, 2),
	(6, 6, 2),
	(7, 7, 2),
	(8, 8, 2),
	(9, 9, 2);
	
CREATE TABLE `docfields_docfieldvalues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `docfield_id` int(11) NOT NULL,
  `docfieldvalue_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

CREATE TABLE `docfieldvalues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

CREATE TABLE `docfieldvalues_docs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) NOT NULL,
  `docfieldvalue_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

CREATE TABLE `doctypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `doctypes` (`id`,`name`)
VALUES
	(1, 'Requirements Specification'),
	(2, 'Creative Brief');

CREATE TABLE `docs_doctypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) NOT NULL,
  `doctype_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
