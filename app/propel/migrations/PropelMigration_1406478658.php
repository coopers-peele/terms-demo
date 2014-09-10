<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1406478658.
 * Generated on 2014-07-27 18:30:58 by op
 */
class PropelMigration_1406478658
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

DROP TABLE IF EXISTS `user_profile`;

ALTER TABLE `cp_tos_section` CHANGE `tree_lft` `tree_lft` INTEGER;

ALTER TABLE `cp_tos_section` CHANGE `tree_rgt` `tree_rgt` INTEGER;

ALTER TABLE `cp_tos_section` CHANGE `level` `level` INTEGER;

ALTER TABLE `cp_tos_section` ADD CONSTRAINT `cp_terms_section`
    FOREIGN KEY (`terms_id`)
    REFERENCES `cp_tos_terms` (`id`)
    ON DELETE CASCADE;

ALTER TABLE `cp_tos_terms` CHANGE `created_at` `created_at` DATETIME;

ALTER TABLE `cp_tos_terms` CHANGE `updated_at` `updated_at` DATETIME;

ALTER TABLE `cp_tos_terms` ADD CONSTRAINT `cp_terms_clone`
    FOREIGN KEY (`cloned_from_id`)
    REFERENCES `cp_tos_terms` (`id`)
    ON DELETE SET NULL;

CREATE TABLE `user_session`
(
    `id` VARCHAR(64) NOT NULL,
    `data` TEXT NOT NULL,
    `time` INTEGER NOT NULL,
    PRIMARY KEY (`id`)
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

DROP TABLE IF EXISTS `user_session`;

ALTER TABLE `cp_tos_section` DROP FOREIGN KEY `cp_terms_section`;

ALTER TABLE `cp_tos_section` CHANGE `tree_lft` `tree_lft` INTEGER NOT NULL;

ALTER TABLE `cp_tos_section` CHANGE `tree_rgt` `tree_rgt` INTEGER NOT NULL;

ALTER TABLE `cp_tos_section` CHANGE `level` `level` INTEGER DEFAULT 0 NOT NULL;

ALTER TABLE `cp_tos_terms` DROP FOREIGN KEY `cp_terms_clone`;

ALTER TABLE `cp_tos_terms` CHANGE `created_at` `created_at` DATETIME NOT NULL;

ALTER TABLE `cp_tos_terms` CHANGE `updated_at` `updated_at` DATETIME NOT NULL;

CREATE TABLE `user_profile`
(
    `id` INTEGER NOT NULL,
    `gender` TINYINT(1) NOT NULL,
    `given_names` VARCHAR(30) NOT NULL,
    `surname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `email` (`email`(100))
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}
