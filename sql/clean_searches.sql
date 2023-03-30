-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from drop.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
---- /*******************************************************
-- *
-- * Clean up the existing tables-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DELETE FROM civicrm_saved_search where `name` like "%08";
DELETE FROM civicrm_saved_display where `name` like "%08";
SET FOREIGN_KEY_CHECKS=1;