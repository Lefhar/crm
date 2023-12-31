-- Doctrine Migration File Generated on 2023-12-08 20:24:51

-- Version DoctrineMigrations\Version20231208202441
ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F5774FDDC;
ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9D86650F;
DROP INDEX IDX_B6BD307F9D86650F ON message;
DROP INDEX IDX_B6BD307F5774FDDC ON message;
ALTER TABLE message ADD ticket_id INT NOT NULL, ADD user_id INT NOT NULL, DROP ticket_id_id, DROP user_id_id;
ALTER TABLE message ADD CONSTRAINT FK_B6BD307F700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id);
ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
CREATE INDEX IDX_B6BD307F700047D2 ON message (ticket_id);
CREATE INDEX IDX_B6BD307FA76ED395 ON message (user_id);
