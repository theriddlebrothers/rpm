/* Persistent logins */
ALTER TABLE `users` ADD `series` varchar(300) NULL DEFAULT NULL  AFTER `role`;
ALTER TABLE `users` ADD `token` varchar(300) NULL DEFAULT NULL  AFTER `cookie`;
