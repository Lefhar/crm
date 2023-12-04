-- Doctrine Migration File Generated on 2023-12-04 13:28:03

-- Version DoctrineMigrations\Version20231204132751
ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
ALTER TABLE user CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE zipcode zipcode VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL;
