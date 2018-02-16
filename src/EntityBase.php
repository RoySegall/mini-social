<?php

namespace Social;

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
   *
   * @param \Social\RethinkDB $db\
   */
  public function __construct(RethinkDB $db) {
    $this->db = $db;
  }

  public function createTable() {
    return $this->db->createTable($this->table);
  }

  /**
   * @param $object
   *
   * @return \Social\EntityBase
   */
  final private function initObject($object) {

    $self = $this;

    array_walk($object, function($key, $value) use($self) {
      $self->{$key} = $value;
    });

    return $self;
  }

  /**
   * Get the table handler.
   *
   * @return \r\Queries\Tables\Table
   */
  public function getTable() {
    return $this->db->getDb()->table($this->table);
  }

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

  public function load($id) {
    $table = $this->db->getTable($this->table);

    return $this->initObject($table->get($id)->run($this->db->getConnection())->getIterator()->getArrayCopy());
  }

  public function loadMultiple(array $ids = []) {
    $query = $this->getTable();

    if ($ids) {
      $query->getAll(\r\args($ids));
    }

    return array_map(function($item) {
      return $this->initObject($item->getArrayCopy());
    }, $query->run($this->db->getConnection())->toArray());
  }

  public function update($document) {
    $this->getTable()->get($document['id'])->update($document)->run($this->db->getConnection());
    return $document;
  }

  public function delete($id) {
    $this->deleteMultiple([$id]);
  }

  public function deleteMultiple(array $ids = []) {
    $query = $this->getTable();

    if ($ids) {
      $query->getAll(\r\args($ids));
    }

    $query->delete()->run($this->db->getConnection());
  }


}
