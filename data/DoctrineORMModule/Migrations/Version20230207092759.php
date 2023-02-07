<?php

declare(strict_types=1);

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207092759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tRating DROP FOREIGN KEY trating_ibfk_2');
        $this->addSql('ALTER TABLE tRating DROP FOREIGN KEY trating_ibfk_1');
        $this->addSql('DROP TABLE tRating');
        $this->addSql('ALTER TABLE tPasswort DROP INDEX kUser, ADD UNIQUE INDEX UNIQ_FF676DF92425172A (kUser)');
        $this->addSql('ALTER TABLE tPasswort MODIFY kID INT NOT NULL');
        $this->addSql('ALTER TABLE tPasswort DROP FOREIGN KEY tpasswort_ibfk_1');
        $this->addSql('DROP INDEX `primary` ON tPasswort');
        $this->addSql('ALTER TABLE tPasswort CHANGE kUser kUser INT DEFAULT NULL, CHANGE kID id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE tPasswort ADD CONSTRAINT FK_FF676DF92425172A FOREIGN KEY (kUser) REFERENCES tUser (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tPasswort ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE tPostedRides MODIFY kID INT NOT NULL');
        $this->addSql('ALTER TABLE tPostedRides DROP FOREIGN KEY tpostedrides_ibfk_1');
        $this->addSql('DROP INDEX `primary` ON tPostedRides');
        $this->addSql('ALTER TABLE tPostedRides DROP FOREIGN KEY tpostedrides_ibfk_1');
        $this->addSql('ALTER TABLE tPostedRides CHANGE dDatumUhrzeit dDatumUhrzeit VARCHAR(255) NOT NULL, CHANGE kErsteller kErsteller INT DEFAULT NULL, CHANGE bIsStorniert bIsStorniert TINYINT(1) NOT NULL, CHANGE kID id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE tPostedRides ADD CONSTRAINT FK_35B148F33AA0CF05 FOREIGN KEY (kErsteller) REFERENCES tUser (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tPostedRides ADD PRIMARY KEY (id)');
        $this->addSql('DROP INDEX kersteller ON tPostedRides');
        $this->addSql('CREATE INDEX IDX_35B148F33AA0CF05 ON tPostedRides (kErsteller)');
        $this->addSql('ALTER TABLE tPostedRides ADD CONSTRAINT tpostedrides_ibfk_1 FOREIGN KEY (kErsteller) REFERENCES tUser (kID)');
        $this->addSql('ALTER TABLE tUser MODIFY kID INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON tUser');
        $this->addSql('ALTER TABLE tUser ADD gender VARCHAR(255) NOT NULL, DROP bGeschlecht, CHANGE bRaucher bRaucher TINYINT(1) NOT NULL, CHANGE bTierhaare bTierhaare TINYINT(1) NOT NULL, CHANGE bMaskenpflicht bMaskenpflicht TINYINT(1) NOT NULL, CHANGE cFreiInfo cFreiInfo VARCHAR(255) NOT NULL, CHANGE cHeimatsTreffpunkt cHeimatsTreffpunkt VARCHAR(255) NOT NULL, CHANGE kID id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE tUser ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE tUserRides MODIFY kID INT NOT NULL');
        $this->addSql('ALTER TABLE tUserRides DROP FOREIGN KEY tuserrides_ibfk_1');
        $this->addSql('ALTER TABLE tUserRides DROP FOREIGN KEY tuserrides_ibfk_2');
        $this->addSql('DROP INDEX `primary` ON tUserRides');
        $this->addSql('ALTER TABLE tUserRides DROP FOREIGN KEY tuserrides_ibfk_1');
        $this->addSql('ALTER TABLE tUserRides DROP FOREIGN KEY tuserrides_ibfk_2');
        $this->addSql('ALTER TABLE tUserRides CHANGE kRide kRide INT DEFAULT NULL, CHANGE kUser kUser INT DEFAULT NULL, CHANGE kID id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE tUserRides ADD CONSTRAINT FK_3F3E8EC328BBDB3 FOREIGN KEY (kRide) REFERENCES tPostedRides (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tUserRides ADD CONSTRAINT FK_3F3E8EC2425172A FOREIGN KEY (kUser) REFERENCES tUser (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tUserRides ADD PRIMARY KEY (id)');
        $this->addSql('DROP INDEX kride ON tUserRides');
        $this->addSql('CREATE INDEX IDX_3F3E8EC328BBDB3 ON tUserRides (kRide)');
        $this->addSql('DROP INDEX kuser ON tUserRides');
        $this->addSql('CREATE INDEX IDX_3F3E8EC2425172A ON tUserRides (kUser)');
        $this->addSql('ALTER TABLE tUserRides ADD CONSTRAINT tuserrides_ibfk_1 FOREIGN KEY (kRide) REFERENCES tPostedRides (kID)');
        $this->addSql('ALTER TABLE tUserRides ADD CONSTRAINT tuserrides_ibfk_2 FOREIGN KEY (kUser) REFERENCES tUser (kID)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tRating (kID INT AUTO_INCREMENT NOT NULL, nRating INT NOT NULL, kUserGetRating INT NOT NULL, kUserGiveRating INT NOT NULL, INDEX kUserGetRating (kUserGetRating), INDEX kUserGiveRating (kUserGiveRating), PRIMARY KEY(kID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tRating ADD CONSTRAINT trating_ibfk_2 FOREIGN KEY (kUserGiveRating) REFERENCES tUser (kID)');
        $this->addSql('ALTER TABLE tRating ADD CONSTRAINT trating_ibfk_1 FOREIGN KEY (kUserGetRating) REFERENCES tUser (kID)');
        $this->addSql('ALTER TABLE tPasswort DROP INDEX UNIQ_FF676DF92425172A, ADD INDEX kUser (kUser)');
        $this->addSql('ALTER TABLE tPasswort MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE tPasswort DROP FOREIGN KEY FK_FF676DF92425172A');
        $this->addSql('DROP INDEX `PRIMARY` ON tPasswort');
        $this->addSql('ALTER TABLE tPasswort CHANGE kUser kUser INT NOT NULL, CHANGE id kID INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE tPasswort ADD CONSTRAINT tpasswort_ibfk_1 FOREIGN KEY (kUser) REFERENCES tUser (kID)');
        $this->addSql('ALTER TABLE tPasswort ADD PRIMARY KEY (kID)');
        $this->addSql('ALTER TABLE tPostedRides MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE tPostedRides DROP FOREIGN KEY FK_35B148F33AA0CF05');
        $this->addSql('DROP INDEX `PRIMARY` ON tPostedRides');
        $this->addSql('ALTER TABLE tPostedRides DROP FOREIGN KEY FK_35B148F33AA0CF05');
        $this->addSql('ALTER TABLE tPostedRides CHANGE dDatumUhrzeit dDatumUhrzeit DATETIME NOT NULL, CHANGE bIsStorniert bIsStorniert TINYINT(1) DEFAULT 0 NOT NULL, CHANGE kErsteller kErsteller INT NOT NULL, CHANGE id kID INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE tPostedRides ADD CONSTRAINT tpostedrides_ibfk_1 FOREIGN KEY (kErsteller) REFERENCES tUser (kID)');
        $this->addSql('ALTER TABLE tPostedRides ADD PRIMARY KEY (kID)');
        $this->addSql('DROP INDEX idx_35b148f33aa0cf05 ON tPostedRides');
        $this->addSql('CREATE INDEX kErsteller ON tPostedRides (kErsteller)');
        $this->addSql('ALTER TABLE tPostedRides ADD CONSTRAINT FK_35B148F33AA0CF05 FOREIGN KEY (kErsteller) REFERENCES tUser (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tUser MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON tUser');
        $this->addSql('ALTER TABLE tUser ADD bGeschlecht VARCHAR(255) DEFAULT NULL, DROP gender, CHANGE bRaucher bRaucher TINYINT(1) DEFAULT NULL, CHANGE bTierhaare bTierhaare TINYINT(1) DEFAULT NULL, CHANGE bMaskenpflicht bMaskenpflicht TINYINT(1) DEFAULT NULL, CHANGE cFreiInfo cFreiInfo VARCHAR(255) DEFAULT NULL, CHANGE cHeimatsTreffpunkt cHeimatsTreffpunkt VARCHAR(255) DEFAULT NULL, CHANGE id kID INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE tUser ADD PRIMARY KEY (kID)');
        $this->addSql('ALTER TABLE tUserRides MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE tUserRides DROP FOREIGN KEY FK_3F3E8EC328BBDB3');
        $this->addSql('ALTER TABLE tUserRides DROP FOREIGN KEY FK_3F3E8EC2425172A');
        $this->addSql('DROP INDEX `PRIMARY` ON tUserRides');
        $this->addSql('ALTER TABLE tUserRides DROP FOREIGN KEY FK_3F3E8EC328BBDB3');
        $this->addSql('ALTER TABLE tUserRides DROP FOREIGN KEY FK_3F3E8EC2425172A');
        $this->addSql('ALTER TABLE tUserRides CHANGE kRide kRide INT NOT NULL, CHANGE kUser kUser INT NOT NULL, CHANGE id kID INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE tUserRides ADD CONSTRAINT tuserrides_ibfk_1 FOREIGN KEY (kRide) REFERENCES tPostedRides (kID)');
        $this->addSql('ALTER TABLE tUserRides ADD CONSTRAINT tuserrides_ibfk_2 FOREIGN KEY (kUser) REFERENCES tUser (kID)');
        $this->addSql('ALTER TABLE tUserRides ADD PRIMARY KEY (kID)');
        $this->addSql('DROP INDEX idx_3f3e8ec2425172a ON tUserRides');
        $this->addSql('CREATE INDEX kUser ON tUserRides (kUser)');
        $this->addSql('DROP INDEX idx_3f3e8ec328bbdb3 ON tUserRides');
        $this->addSql('CREATE INDEX kRide ON tUserRides (kRide)');
        $this->addSql('ALTER TABLE tUserRides ADD CONSTRAINT FK_3F3E8EC328BBDB3 FOREIGN KEY (kRide) REFERENCES tPostedRides (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tUserRides ADD CONSTRAINT FK_3F3E8EC2425172A FOREIGN KEY (kUser) REFERENCES tUser (id) ON DELETE CASCADE');
    }
}
