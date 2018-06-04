DROP TABLE IF EXISTS co_security_nodes;
DROP SEQUENCE IF EXISTS element_id_seq;
CREATE SEQUENCE element_id_seq;
CREATE TABLE co_security_nodes (
  id BIGINT NOT NULL DEFAULT nextval('element_id_seq'),
  login_id VARCHAR(255) DEFAULT NULL,
  access_class VARCHAR(255) NOT NULL,
  last_access TIMESTAMP NOT NULL,
  read_security_id BIGINT DEFAULT NULL,
  write_security_id BIGINT DEFAULT NULL,
  object_name VARCHAR(255) DEFAULT NULL,
  access_class_context VARCHAR(4095) DEFAULT NULL,
  ids VARCHAR(4095) DEFAULT NULL
);

INSERT INTO co_security_nodes (login_id, access_class, last_access, read_security_id, write_security_id, object_name, access_class_context, ids) VALUES
(NULL, 'CO_Security_Node', '1970-01-01 00:00:00', -1, -1, NULL, NULL, NULL),
('admin', 'CO_Security_Login', '1970-01-01 00:00:00', 2, 2, 'Default Admin', 'a:2:{s:4:"lang";s:2:"en";s:15:\"hashed_password\";s:4:\"JUNK\";}', NULL),
('norm', 'CO_Security_Login', '1970-01-01 00:00:00', 3, 3, 'Low-Level 1', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', NULL),
('bob', 'CO_Security_Login', '1970-01-01 00:00:00', 4, 4, 'Low-Level 2', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', NULL),
('cobra', 'CO_Cobra_Login', '1970-01-01 00:00:00', 5, 5, 'Normal COBRA Login (cobra)', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', NULL),
('krait', 'CO_Cobra_Login', '1970-01-01 00:00:00', 6, 6, 'Normal COBRA Login (krait)', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', '2'),
('asp', 'CO_Login_Manager', '1970-01-01 00:00:00', 7, 7, 'Boss COBRA Manager (asp)', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', '3,4,5'),
('king-cobra', 'CO_Login_Manager', '1970-01-01 00:00:00', 8, 8, 'Boss COBRA Manager (king)', 'a:2:{s:4:"lang";s:2:"en";s:15:"hashed_password";s:13:"62oAxuuttfjZI";}', '2,6');
