DROP TABLE IF EXISTS `co_security_nodes`;
CREATE TABLE `co_security_nodes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `login_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `access_class` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_access` datetime NOT NULL,
  `read_security_id` bigint(20) DEFAULT NULL,
  `write_security_id` bigint(20) DEFAULT NULL,
  `object_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `access_class_context` mediumtext,
  `ids` varchar(4095) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `co_security_nodes` (`id`, `login_id`, `access_class`, `last_access`, `read_security_id`, `write_security_id`, `object_name`, `access_class_context`, `ids`) VALUES
(1, NULL, 'CO_Security_Node', '1970-01-01 00:00:00', -1, -1, NULL, NULL, NULL),
(2, 'admin', 'CO_Security_Login', '1970-01-01 00:00:00', 2, 2, 'Default Admin', 'a:2:{s:4:"lang";s:2:"en";s:15:\"hashed_password\";s:4:\"JUNK\";}', NULL),
(3, 'norm', 'CO_Security_Login', '1970-01-01 00:00:00', 3, 3, 'Low-Level 1', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', NULL),
(4, 'bob', 'CO_Security_Login', '1970-01-01 00:00:00', 4, 4, 'Low-Level 2', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', NULL),
(5, 'cobra', 'CO_Cobra_Login', '1970-01-01 00:00:00', 5, 5, 'Normal COBRA Login (cobra)', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', NULL),
(6, 'krait', 'CO_Cobra_Login', '1970-01-01 00:00:00', 6, 6, 'Normal COBRA Login (krait)', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', '2'),
(7, 'asp', 'CO_Login_Manager', '1970-01-01 00:00:00', 7, 7, 'Boss COBRA Manager (asp)', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', '3,4,5'),
(8, 'king-cobra', 'CO_Login_Manager', '1970-01-01 00:00:00', 8, 8, 'Boss COBRA Manager (king)', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', '2,6');

ALTER TABLE `co_security_nodes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_id` (`login_id`),
  ADD KEY `access_class` (`access_class`),
  ADD KEY `last_access` (`last_access`),
  ADD KEY `read_security_id` (`read_security_id`),
  ADD KEY `write_security_id` (`write_security_id`),
  ADD KEY `object_name` (`object_name`);

ALTER TABLE `co_security_nodes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;