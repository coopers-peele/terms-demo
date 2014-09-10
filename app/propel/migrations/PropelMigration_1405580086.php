<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1405580086.
 * Generated on 2014-07-17 08:54:46
 */
class PropelMigration_1405580086
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `cp_tos_terms`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `version` VARCHAR(20) NOT NULL,
    `description` TEXT,
    `finalized_at` DATETIME,
    `cloned_from_id` INTEGER,
    `cloned_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `version` (`version`),
    INDEX `FI_terms_clone` (`cloned_from_id`),
    CONSTRAINT `cp_terms_clone`
        FOREIGN KEY (`cloned_from_id`)
        REFERENCES `cp_tos_terms` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `cp_tos_section`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `terms_id` INTEGER NOT NULL,
    `title` VARCHAR(125),
    `content` TEXT,
    `tree_lft` INTEGER,
    `tree_rgt` INTEGER,
    `level` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `FI_terms_section` (`terms_id`),
    CONSTRAINT `cp_terms_section`
        FOREIGN KEY (`terms_id`)
        REFERENCES `cp_tos_terms` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `cp_tos_agreement`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `terms_id` INTEGER NOT NULL,
    `agreed_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `agreement` (`user_id`, `terms_id`),
    INDEX `FI_terms_tos_agreement` (`terms_id`),
    CONSTRAINT `FI_terms_tos_agreement`
        FOREIGN KEY (`terms_id`)
        REFERENCES `cp_tos_terms` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `user_profile`
(
    `id` INTEGER NOT NULL,
    `gender` TINYINT(1) NOT NULL,
    `given_names` VARCHAR(30),
    `surname` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `user_profile_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `fos_user` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `fos_user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255),
    `username_canonical` VARCHAR(255),
    `email` VARCHAR(255),
    `email_canonical` VARCHAR(255),
    `enabled` TINYINT(1) DEFAULT 0,
    `salt` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `last_login` DATETIME,
    `locked` TINYINT(1) DEFAULT 0,
    `expired` TINYINT(1) DEFAULT 0,
    `expires_at` DATETIME,
    `confirmation_token` VARCHAR(255),
    `password_requested_at` DATETIME,
    `credentials_expired` TINYINT(1) DEFAULT 0,
    `credentials_expire_at` DATETIME,
    `roles` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fos_user_U_1` (`username_canonical`),
    UNIQUE INDEX `fos_user_U_2` (`email_canonical`)
) ENGINE=InnoDB;

CREATE TABLE `fos_group`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `roles` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `fos_user_group`
(
    `fos_user_id` INTEGER NOT NULL,
    `fos_group_id` INTEGER NOT NULL,
    PRIMARY KEY (`fos_user_id`,`fos_group_id`),
    INDEX `fos_user_group_FI_2` (`fos_group_id`),
    CONSTRAINT `fos_user_group_FK_1`
        FOREIGN KEY (`fos_user_id`)
        REFERENCES `fos_user` (`id`),
    CONSTRAINT `fos_user_group_FK_2`
        FOREIGN KEY (`fos_group_id`)
        REFERENCES `fos_group` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `cp_tos_terms`;

DROP TABLE IF EXISTS `cp_tos_section`;

DROP TABLE IF EXISTS `cp_tos_agreement`;

DROP TABLE IF EXISTS `user_profile`;

DROP TABLE IF EXISTS `fos_user`;

DROP TABLE IF EXISTS `fos_group`;

DROP TABLE IF EXISTS `fos_user_group`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}
