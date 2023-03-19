-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from schema.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the existing tables - this section generated from drop.tpl
-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_o8_rental_service`;

SET FOREIGN_KEY_CHECKS=1;
-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * civicrm_o8_rental_service
-- *
-- * Rental Service (Type)
-- *
-- *******************************************************/
CREATE TABLE `civicrm_o8_rental_service` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Rental Service ID',
  `name` varchar(255) NOT NULL COMMENT 'Name',
  `frequency` varchar(25) NOT NULL DEFAULT "once_off",
  `is_refund` tinyint DEFAULT 0 COMMENT 'Is Refund?',
  `is_prorate` tinyint DEFAULT 1 COMMENT 'Is prorate?',
  `amount` decimal(20,2) NOT NULL COMMENT 'Amount',
  `created_id` int unsigned COMMENT 'FK to civicrm_contact, who created this',
  `created_date` datetime COMMENT 'Date and time this was created.',
  `modified_id` int unsigned COMMENT 'FK to civicrm_contact, who modified this',
  `modified_date` datetime COMMENT 'Date and time this was modified.',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `index_name`(name),
  CONSTRAINT FK_civicrm_o8_rental_service_created_id FOREIGN KEY (`created_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_o8_rental_service_modified_id FOREIGN KEY (`modified_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;
