CREATE TABLE `calls` (
  `guichet` int NOT NULL PRIMARY KEY,
  `ticket` int NOT NULL ,
  `call_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
);