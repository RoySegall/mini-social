<?php

namespace Social;

use r\ValuedQuery\RVar;

abstract class EntityBase {

  /**
   * @var \Social\RethinkDB
   */
  protected $db;

  /**
   * @var string
   */
  protected $table;

  /**
   * EntityBase constructor.
   */
  public function __construct() {
    $this->db = Social::getDb();
  }

  public function createTable() {
    return $this->db->createTable($this->table);
  }

  /**
   * Get the table handler.
   *
   * @return \r\Queries\Tables\Table
   */
  public function getTable() {
    return $this->db->getDb()->table($this->table);
  }

  /**
   * Saving a document to the DB.
   *
   * @param array $document
   *   The array to save in the DB.
   *
   * @return mixed
   */
  public function save($document) {

    if (!isset($document['time'])) {
      $document['time'] = time();
    }

    $result = $this->getTable()->insert($document)->run($this->db->getConnection())->getArrayCopy();

    if (!isset($document['id'])) {
      $document['id'] = isset($result['generated_keys']) ? reset($result['generated_keys']) : $result['id'];
    }

    return $document;
  }

  /**
   * Load an item from the DB.
   *
   * @param string $id
   *   The ID of the object.
   *
   * @return mixed
   */
  public function load($id) {
    $table = $this->db->getTable($this->table);

    return $table->get($id)->run($this->db->getConnection())->getIterator()->getArrayCopy();
  }

  /**
   * Return a list of objects from the DB by the given array.
   *
   * @param array $ids
   *   List of IDs. If not given, all the items will be loaded.
   *
   * @return array[]
   */
  public function loadMultiple(array $ids = []) {
    $query = $this->getTable();

    if ($ids) {
      $row = function(RVar $doc) use ($ids) {
        return \r\expr($ids)->contains($doc->getField('id'));
      };

      $query = $query->filter($row);
    }

    return array_map(function($item) {
      return $item->getArrayCopy();
    }, $query->run($this->db->getConnection())->toArray());
  }

  /**
   * Update an entity in the DB.
   *
   * @param $document
   *   The object to load.
   *
   * @return mixed
   */
  public function update($document) {
    $this->getTable()->get($document['id'])->update($document)->run($this->db->getConnection());
    return $document;
  }

  /**
   * Delete an item from the DB.
   *
   * @param string $id
   *   The entity to load.
   */
  public function delete($id) {
    $this->deleteMultiple([$id]);
  }

  /**
   * Delete multiple entities from the DB.
   *
   * @param array $ids
   *   The list of IDs to delete.
   */
  public function deleteMultiple(array $ids = []) {
    $query = $this->getTable();

    if ($ids) {
      $query->getAll(\r\args($ids));
    }

    $query->delete()->run($this->db->getConnection());
  }

}
