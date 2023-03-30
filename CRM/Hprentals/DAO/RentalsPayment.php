<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.octopus8.hprentals/xml/schema/CRM/Hprentals/05RentalsPayment.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:6134df5631134d5481c429660646b52e)
 */
use CRM_Hprentals_ExtensionUtil as E;

/**
 * Database access object for the RentalsPayment entity.
 */
class CRM_Hprentals_DAO_RentalsPayment extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_o8_rental_payment';

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
    'add' => 'civicrm/rentals/payment',
    'update' => 'civicrm/rentals/invoice#?RentalsPayment=[id]',
  ];

  /**
   * Unique RentalsPayment ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Code
   *
   * @var string
   *   (SQL type: varchar(12))
   *   Note that values will be retrieved from the database as a string.
   */
  public $code;

  /**
   * FK to Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $tenant_id;

  /**
   * FK to payment method
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $method_id;

  /**
   * Amount
   *
   * @var float|string
   *   (SQL type: decimal(20,2))
   *   Note that values will be retrieved from the database as a string.
   */
  public $amount;

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
    $this->__table = 'civicrm_o8_rental_payment';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Rentals Payments') : E::ts('Rentals Payment');
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
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'method_id', 'civicrm_o8_rental_method', 'id');
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
          'description' => E::ts('Unique RentalsPayment ID'),
          'required' => TRUE,
          'where' => 'civicrm_o8_rental_payment.id',
          'table_name' => 'civicrm_o8_rental_payment',
          'entity' => 'RentalsPayment',
          'bao' => 'CRM_Hprentals_DAO_RentalsPayment',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'code' => [
          'name' => 'code',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Code'),
          'description' => E::ts('Code'),
          'required' => TRUE,
          'maxlength' => 12,
          'size' => CRM_Utils_Type::TWELVE,
          'import' => TRUE,
          'where' => 'civicrm_o8_rental_payment.code',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_rental_payment',
          'entity' => 'RentalsPayment',
          'bao' => 'CRM_Hprentals_DAO_RentalsPayment',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Code"),
          ],
          'add' => NULL,
        ],
        'tenant_id' => [
          'name' => 'tenant_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('FK to Contact'),
          'where' => 'civicrm_o8_rental_payment.tenant_id',
          'table_name' => 'civicrm_o8_rental_payment',
          'entity' => 'RentalsPayment',
          'bao' => 'CRM_Hprentals_DAO_RentalsPayment',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Tenant"),
          ],
          'add' => NULL,
        ],
        'method_id' => [
          'name' => 'method_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Method'),
          'description' => E::ts('FK to payment method'),
          'where' => 'civicrm_o8_rental_payment.method_id',
          'table_name' => 'civicrm_o8_rental_payment',
          'entity' => 'RentalsPayment',
          'bao' => 'CRM_Hprentals_DAO_RentalsPayment',
          'localizable' => 0,
          'FKClassName' => 'CRM_Hprentals_DAO_RentalsMethod',
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'table' => 'civicrm_o8_rental_method',
            'keyColumn' => 'id',
            'nameColumn' => 'name',
          ],
          'add' => NULL,
        ],
        'amount' => [
          'name' => 'amount',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => E::ts('Amount'),
          'description' => E::ts('Amount'),
          'required' => TRUE,
          'precision' => [
            20,
            2,
          ],
          'import' => TRUE,
          'where' => 'civicrm_o8_rental_payment.amount',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_rental_payment',
          'entity' => 'RentalsPayment',
          'bao' => 'CRM_Hprentals_DAO_RentalsPayment',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Amount"),
          ],
          'add' => NULL,
        ],
        'created_id' => [
          'name' => 'created_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Created By Contact ID'),
          'description' => E::ts('FK to civicrm_contact, who created this'),
          'where' => 'civicrm_o8_rental_payment.created_id',
          'table_name' => 'civicrm_o8_rental_payment',
          'entity' => 'RentalsPayment',
          'bao' => 'CRM_Hprentals_DAO_RentalsPayment',
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
          'where' => 'civicrm_o8_rental_payment.created_date',
          'table_name' => 'civicrm_o8_rental_payment',
          'entity' => 'RentalsPayment',
          'bao' => 'CRM_Hprentals_DAO_RentalsPayment',
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
          'where' => 'civicrm_o8_rental_payment.modified_id',
          'table_name' => 'civicrm_o8_rental_payment',
          'entity' => 'RentalsPayment',
          'bao' => 'CRM_Hprentals_DAO_RentalsPayment',
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
          'where' => 'civicrm_o8_rental_payment.modified_date',
          'table_name' => 'civicrm_o8_rental_payment',
          'entity' => 'RentalsPayment',
          'bao' => 'CRM_Hprentals_DAO_RentalsPayment',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'o8_rental_payment', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'o8_rental_payment', $prefix, []);
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
      'index_code' => [
        'name' => 'index_code',
        'field' => [
          0 => 'code',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_o8_rental_payment::1::code',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
