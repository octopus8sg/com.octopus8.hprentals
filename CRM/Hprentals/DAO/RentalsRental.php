<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.octopus8.hprentals/xml/schema/CRM/Hprentals/03RentalsRental.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:781feae5eb0970cd71a44b0d189ad3d6)
 */
use CRM_Hprentals_ExtensionUtil as E;

/**
 * Database access object for the RentalsRental entity.
 */
class CRM_Hprentals_DAO_RentalsRental extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_o8_rental_rental';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Paths for accessing this entity in the UI.
   *
   * @var string[]
   */
  protected static $_paths = [
    'add' => 'civicrm/rentals/rental',
    'update' => 'civicrm/rentals/rental#?RentalsRental=[id]',
  ];

  /**
   * Unique ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * FK to Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $tenant_id;

  /**
   * Admission date
   *
   * @var string
   *   (SQL type: date)
   *   Note that values will be retrieved from the database as a string.
   */
  public $admission;

  /**
   * Discharge date
   *
   * @var string
   *   (SQL type: date)
   *   Note that values will be retrieved from the database as a string.
   */
  public $discharge;

  /**
   * FK to civicrm_contact, who created this
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_id;

  /**
   * Date and time this was created.
   *
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_date;

  /**
   * FK to civicrm_contact, who modified this
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $modified_id;

  /**
   * Date and time this was modified.
   *
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $modified_date;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_o8_rental_rental';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Rentals Rentals') : E::ts('Rentals Rental');
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'tenant_id', 'civicrm_contact', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'created_id', 'civicrm_contact', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'modified_id', 'civicrm_contact', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('Unique ID'),
          'required' => TRUE,
          'where' => 'civicrm_o8_rental_rental.id',
          'table_name' => 'civicrm_o8_rental_rental',
          'entity' => 'RentalsRental',
          'bao' => 'CRM_Hprentals_DAO_RentalsRental',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'tenant_id' => [
          'name' => 'tenant_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('FK to Contact'),
          'where' => 'civicrm_o8_rental_rental.tenant_id',
          'table_name' => 'civicrm_o8_rental_rental',
          'entity' => 'RentalsRental',
          'bao' => 'CRM_Hprentals_DAO_RentalsRental',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Tenant"),
          ],
          'add' => NULL,
        ],
        'admission' => [
          'name' => 'admission',
          'type' => CRM_Utils_Type::T_DATE,
          'title' => E::ts('Admission'),
          'description' => E::ts('Admission date'),
          'required' => TRUE,
          'where' => 'civicrm_o8_rental_rental.admission',
          'table_name' => 'civicrm_o8_rental_rental',
          'entity' => 'RentalsRental',
          'bao' => 'CRM_Hprentals_DAO_RentalsRental',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDate',
          ],
          'add' => NULL,
        ],
        'discharge' => [
          'name' => 'discharge',
          'type' => CRM_Utils_Type::T_DATE,
          'title' => E::ts('Discharge'),
          'description' => E::ts('Discharge date'),
          'required' => TRUE,
          'where' => 'civicrm_o8_rental_rental.discharge',
          'table_name' => 'civicrm_o8_rental_rental',
          'entity' => 'RentalsRental',
          'bao' => 'CRM_Hprentals_DAO_RentalsRental',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDate',
          ],
          'add' => NULL,
        ],
        'created_id' => [
          'name' => 'created_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Created By Contact ID'),
          'description' => E::ts('FK to civicrm_contact, who created this'),
          'where' => 'civicrm_o8_rental_rental.created_id',
          'table_name' => 'civicrm_o8_rental_rental',
          'entity' => 'RentalsRental',
          'bao' => 'CRM_Hprentals_DAO_RentalsRental',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Created By"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'created_date' => [
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Created Date'),
          'description' => E::ts('Date and time this was created.'),
          'where' => 'civicrm_o8_rental_rental.created_date',
          'table_name' => 'civicrm_o8_rental_rental',
          'entity' => 'RentalsRental',
          'bao' => 'CRM_Hprentals_DAO_RentalsRental',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'label' => E::ts("Created Date"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'modified_id' => [
          'name' => 'modified_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Modified By Contact ID'),
          'description' => E::ts('FK to civicrm_contact, who modified this'),
          'where' => 'civicrm_o8_rental_rental.modified_id',
          'table_name' => 'civicrm_o8_rental_rental',
          'entity' => 'RentalsRental',
          'bao' => 'CRM_Hprentals_DAO_RentalsRental',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Modified By"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'modified_date' => [
          'name' => 'modified_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Modified Date'),
          'description' => E::ts('Date and time this was modified.'),
          'where' => 'civicrm_o8_rental_rental.modified_date',
          'table_name' => 'civicrm_o8_rental_rental',
          'entity' => 'RentalsRental',
          'bao' => 'CRM_Hprentals_DAO_RentalsRental',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'label' => E::ts("Modified Date"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'o8_rental_rental', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'o8_rental_rental', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'idx_tenant_admission_discharge' => [
        'name' => 'idx_tenant_admission_discharge',
        'field' => [
          0 => 'tenant_id',
          1 => 'admission',
          2 => 'discharge',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_o8_rental_rental::1::tenant_id::admission::discharge',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
