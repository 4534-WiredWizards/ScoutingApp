DROP TABLE IF EXISTS organization_domain;
CREATE TABLE organization_domain (
   organization_id INT UNSIGNED NOT NULL,
   id INT UNSIGNED NOT NULL AUTO_INCREMENT,
   domain_key VARCHAR(255) NOT NULL,
   domain_name VARCHAR(255) NOT NULL,
   active BOOLEAN NOT NULL DEFAULT 1,

   PRIMARY KEY (id)
);

DROP TABLE IF EXISTS organization;
CREATE TABLE organization (
   id INT UNSIGNED NOT NULL AUTO_INCREMENT,

   organization_number VARCHAR(255) NOT NULL,
   organization_name VARCHAR(255) NOT NULL,
   organization_type VARCHAR(255) NOT NULL,
   owner_id INT UNSIGNED NOT NULL,

   active BOOLEAN NOT NULL DEFAULT 1,
   date_added DATETIME,

   PRIMARY KEY (id)
);

DROP TABLE IF EXISTS organization_user;
CREATE TABLE organization_user (
   organization_id INT UNSIGNED NOT NULL,
   id INT UNSIGNED NOT NULL AUTO_INCREMENT,

   firstname VARCHAR(255) NOT NULL,
   lastname VARCHAR(255) NOT NULL,
   username VARCHAR(255) NOT NULL,
   password VARCHAR(255) NOT NULL,

   active BOOLEAN NOT NULL DEFAULT 1,
   date_added DATETIME,

   PRIMARY KEY (id)
);

DROP TABLE IF EXISTS team;
CREATE TABLE team (
   organization_id INT UNSIGNED NOT NULL,
   organization_domain_id INT UNSIGNED NOT NULL,
   id INT UNSIGNED NOT NULL AUTO_INCREMENT,

   team_number VARCHAR(255),
   team_name VARCHAR(255),
   team_type VARCHAR(255),

   api_url VARCHAR(255),
   summary TEXT,
   score DECIMAL(2),
   strengths TEXT,
   weaknesses TEXT,

   questions_json TEXT,
   scores_json TEXT,


   use_markdown BOOLEAN NOT NULL DEFAULT 1,
   active BOOLEAN NOT NULL DEFAULT 1,
   date_added DATETIME,

   PRIMARY KEY (id)
);

DROP TABLE IF EXISTS feed_entry;
CREATE TABLE feed_entry (
   organization_id INT UNSIGNED NOT NULL,
   organization_domain_id INT UNSIGNED NOT NULL,
   id INT UNSIGNED NOT NULL AUTO_INCREMENT,

   name VARCHAR(255),
   api_url VARCHAR(255),
   entry TEXT,

   use_markdown BOOLEAN NOT NULL DEFAULT 1,
   active BOOLEAN NOT NULL DEFAULT 1,
   date_added DATETIME,

   PRIMARY KEY (id)
);

DROP TABLE IF EXISTS auth_token;
CREATE TABLE auth_token (
   token VARCHAR(32) NOT NULL,
   organization_user_id INT UNSIGNED NOT NULL,
   data TEXT NOT NULL,
   date_expires DATETIME,

   active BOOLEAN NOT NULL DEFAULT 1,
   date_added DATETIME,

   PRIMARY KEY (token)
);

/* SETUP SQL */

TRUNCATE organization;
TRUNCATE organization_user;
TRUNCATE organization_domain;

INSERT INTO organization (
   id,
   organization_number,
   organization_name,
   organization_type,
   owner_id,
   date_added
) VALUES (
   1,
   4534,
   "Wired Wizards",
   "FRC",
   1,
   NOW()
);

INSERT INTO organization_user (
   organization_id,
   id,
   firstname,
   lastname,
   username
) VALUES (
   1,
   1,
   "Daniel",
   "Wilson",
   "daniel"
), (
   1,
   2,
   "Brandon",
   "Dyer",
   "bdn"
);

INSERT INTO organization_domain (
   organization_id,
   domain_key,
   domain_name
) VALUES (
   1,
   "y2016",
   "2016 Season"
);
