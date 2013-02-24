CREATE TABLE Mass (id INT AUTO_INCREMENT NOT NULL, church_id INT DEFAULT NULL, start_time INT NOT NULL, details VARCHAR(255) NOT NULL, day_mon TINYINT(1) NOT NULL, day_tue TINYINT(1) NOT NULL, day_wed TINYINT(1) NOT NULL, day_thu TINYINT(1) NOT NULL, day_fri TINYINT(1) NOT NULL, day_sat TINYINT(1) NOT NULL, day_sun TINYINT(1) NOT NULL, INDEX IDX_CC31F458C1538FD4 (church_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE City (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, slug VARCHAR(128) NOT NULL, foto VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE District (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, slug VARCHAR(128) NOT NULL, INDEX IDX_C8B736D18BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE Church (id INT AUTO_INCREMENT NOT NULL, district_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, address VARCHAR(64) NOT NULL, latitude TINYTEXT DEFAULT NULL, longitude TINYTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, foto VARCHAR(64) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_9761D873B08FA272 (district_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE Mass ADD CONSTRAINT FK_CC31F458C1538FD4 FOREIGN KEY (church_id) REFERENCES Church (id);
ALTER TABLE District ADD CONSTRAINT FK_C8B736D18BAC62AF FOREIGN KEY (city_id) REFERENCES City (id);
ALTER TABLE Church ADD CONSTRAINT FK_9761D873B08FA272 FOREIGN KEY (district_id) REFERENCES District (id);
