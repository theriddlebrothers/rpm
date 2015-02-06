/* ACTIVITIES */
CREATE TABLE `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(500) NOT NULL,
  `activity_date` datetime NOT NULL,
  `activity_type` varchar(45) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `activities_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* ISSUES */
CREATE TABLE `issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(300) NOT NULL,
  `date_reported` datetime NOT NULL,
  `date_due` datetime DEFAULT NULL,
  `category` varchar(45) NOT NULL,
  `priority` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `component` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `steps` text NOT NULL,
  `attachment` varchar(300) DEFAULT NULL,
  `browser` varchar(150) DEFAULT NULL,
  `operating_system` varchar(150) DEFAULT NULL,
  `visibility` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

CREATE TABLE `issues_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `issue_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

CREATE TABLE `issues_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reported_issue_id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `assigned_issue_id` int(11) DEFAULT NULL,
  `assignee_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_date` datetime NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

CREATE TABLE `comments_issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

CREATE TABLE `comments_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/* ACTION KEYS */
CREATE TABLE `action_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_key` varchar(300) NOT NULL,
  `expiration_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/* PARENT PROJECTS */
CREATE TABLE `parentprojects_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `parentproject_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;