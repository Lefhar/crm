-- Doctrine Migration File Generated on 2023-12-08 20:21:49

-- Version DoctrineMigrations\Version20231208202136
CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, ticket_id_id INT NOT NULL, user_id_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_B6BD307F5774FDDC (ticket_id_id), INDEX IDX_B6BD307F9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE message ADD CONSTRAINT FK_B6BD307F5774FDDC FOREIGN KEY (ticket_id_id) REFERENCES ticket (id);
ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id);
