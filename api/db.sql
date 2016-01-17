DROP TABLE IF EXISTS scouting_domain;
CREATE TABLE scouting_domain (
   team_id INT UNSIGNED NOT NULL,
   id INT UNSIGNED NOT NULL AUTO_INCREMENT,
   domain_key VARCHAR(255) NOT NULL,
   domain_name VARCHAR(255) NOT NULL,
   active BOOLEAN NOT NULL DEFAULT 1,

   PRIMARY KEY (id)
);

DROP TABLE IF EXISTS team;
CREATE TABLE team (
   id INT UNSIGNED NOT NULL AUTO_INCREMENT,

   team_number VARCHAR(255) NOT NULL,
   team_name VARCHAR(255) NOT NULL,
   team_type VARCHAR(255) NOT NULL,
   owner_id INT UNSIGNED NOT NULL,

   active BOOLEAN NOT NULL DEFAULT 1,
   date_added DATETIME,

   PRIMARY KEY (id)
);

DROP TABLE IF EXISTS team_user;
CREATE TABLE team_user (
   team_id INT UNSIGNED NOT NULL,
   id INT UNSIGNED NOT NULL AUTO_INCREMENT,

   firstname VARCHAR(255) NOT NULL,
   lastname VARCHAR(255) NOT NULL,
   username VARCHAR(255) NOT NULL,
   password VARCHAR(255) NOT NULL,

   active BOOLEAN NOT NULL DEFAULT 1,
   date_added DATETIME,

   PRIMARY KEY (id)
);

DROP TABLE IF EXISTS scouting_entry;
CREATE TABLE scouting_entry (
   team_id INT UNSIGNED NOT NULL,
   scouting_domain_id INT UNSIGNED NOT NULL,
   id INT UNSIGNED NOT NULL AUTO_INCREMENT,

   name VARCHAR(255),
   api_url VARCHAR(255),
   summary TEXT,
   strengths TEXT,
   weaknesses TEXT,

   use_markdown BOOLEAN NOT NULL DEFAULT 1,
   active BOOLEAN NOT NULL DEFAULT 1,
   date_added DATETIME,

   PRIMARY KEY (id)
);

DROP TABLE IF EXISTS feed_entry;
CREATE TABLE feed_entry (
   team_id INT UNSIGNED NOT NULL,
   scouting_domain_id INT UNSIGNED NOT NULL,
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
   team_user_id INT UNSIGNED NOT NULL,
   data TEXT NOT NULL,
   date_expires DATETIME,

   active BOOLEAN NOT NULL DEFAULT 1,
   date_added DATETIME,

   PRIMARY KEY (token)
);

/* SETUP SQL */

TRUNCATE team;
TRUNCATE team_user;
TRUNCATE scouting_domain;

INSERT INTO team (
   id,
   team_number,
   team_name,
   team_type,
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

INSERT INTO team_user (
   team_id,
   id,
   firstname,
   lastname,
   username,
   password
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

INSERT INTO scouting_domain (
   team_id,
   domain_key,
   domain_name
) VALUES (
   1,
   "y2016",
   "2016 Season"
);
