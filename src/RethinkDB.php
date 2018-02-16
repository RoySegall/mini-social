<?php

namespace Social;

class RethinkDB {

  /**
   * @var \r\Connection
   */
  protected $connection;

  /**
   * @var \r\Queries\Dbs\Db
   */
  protected $db;

  /**
   * @var array
   */
  protected $info;

  public function __construct(array $info = []) {
    $info += [
      'host' => 'localhost',
      'port' => '28015',
      'db' => 'social',
      'api_key' => '',
      'timeout' => 30,
    ];
    $this->info = $info;
    $this->db = \r\db($info['db']);
    $this->connection = \r\connect($info['host'], $info['port'], $info['db'], $info['api_key'], $info['timeout']);
  }

  /**
   * @return \r\Queries\Dbs\Db
   */
  public function getDb() {
    return $this->db;
  }

  /**
   * @return \r\Connection
   */
  public function getConnection() {
    return $this->connection;
  }

  /**
   * Create a DB.
   *
   * @param string $db
   *   The name of the DB.
   *
   * @return array|\ArrayObject|bool|\DateTime|null|\r\Cursor
   */
  public function createDb($db = NULL) {
    if (!$db) {
      $db = $this->info['db'];
    }
    return \r\dbCreate($db)->run($this->connection);
  }

  /**
   * Create a DB.
   *
   * @param string $table
   *   The name of the table.
   *
   * @return array|\ArrayObject|bool|\DateTime|null|\r\Cursor
   */
  public function createTable($table) {
    return \r\tableCreate($table)->run($this->connection);
  }

  /**
   * Get the table object.
   *
   * @param string $table
   *   The table name.
   *
   * @return \r\Queries\Tables\Table
   */
  public function getTable($table) {
    return \r\table($table);

  }

}
