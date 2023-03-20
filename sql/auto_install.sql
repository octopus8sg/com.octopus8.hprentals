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

DROP TABLE IF EXISTS `civicrm_o8_rental_method`;
DROP TABLE IF EXISTS `civicrm_o8_rental_expense`;

SET FOREIGN_KEY_CHECKS=1;
-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * civicrm_o8_rental_expense
-- *
-- * Rental expense (Type)
-- *
-- *******************************************************/
CREATE TABLE `civicrm_o8_rental_expense` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Rental Expense ID',
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
  CONSTRAINT FK_civicrm_o8_rental_expense_created_id FOREIGN KEY (`created_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_o8_rental_expense_modified_id FOREIGN KEY (`modified_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_o8_rental_method
-- *
-- * Rental Payment Method
-- *
-- *******************************************************/
CREATE TABLE `civicrm_o8_rental_method` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Rental Payment Method ID',
  `name` varchar(255) NOT NULL COMMENT 'Name',
  `created_id` int unsigned COMMENT 'FK to civicrm_contact, who created this',
  `created_date` datetime COMMENT 'Date and time this was created.',
  `modified_id` int unsigned COMMENT 'FK to civicrm_contact, who modified this',
  `modified_date` datetime COMMENT 'Date and time this was modified.',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `index_name`(name),
  CONSTRAINT FK_civicrm_o8_rental_method_created_id FOREIGN KEY (`created_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL,
  CONSTRAINT FK_civicrm_o8_rental_method_modified_id FOREIGN KEY (`modified_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB;
