CREATE TABLE fe_users (
    doi_hash varchar(80) NOT NULL DEFAULT '',
    privacy TINYINT(1) NOT NULL DEFAULT '0',
    salutation varchar(6) NOT NULL DEFAULT ''
);