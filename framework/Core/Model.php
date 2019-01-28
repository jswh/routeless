<?php

namespace Core;

use function Couchbase\defaultDecoder;
use Illuminate\Database\Query\Builder;

abstract class Model
{
    protected $table;

    protected $columns;

    public function __construct($args = [])
    {
        $this->columns = publicMembers($this);
        $this->load($args);
    }

    final public function load($args) {
        $this->setArgs($args);
        $this->afterLoad();
    }

    public function afterLoad() {}

    public function setArgs($args) {
        if (!$args) return;
        foreach ($args as $k => $v) {
            if (in_array($k, $this->columns)) {
                $this->{$k} = $v;
            }
        }
    }

    public function id() {
        return $this->{$this->getPrimaryKey()};
    }

    public function save() {
        $this->beforeSave();

        $re = $this->isNew() ? $this->performInsert() : $this->performUpdate();

        $this->afterSave();

        return $re;
    }

    public function remove() {
        if ($this->isNew()) {
            return;
        }
        return self::query()
            ->where($this->getPrimaryKey(), $this->id())
            ->delete();
    }

    private function performUpdate() {
        if ($this->timestamps) {
            $this->updatedTime = time();
        }
        $args = array_filter(obj2Array($this));
        return DB::table($this->getTable())
            ->where($this->getPrimaryKey(), '=', $this->id())
            ->update($args);
    }

    private function performInsert() {
        if ($this->timestamps) {
            $this->updatedTime = $this->createdTime = time();
        }
        $args = array_filter(obj2Array($this));
        $id = DB::table($this->getTable())
            ->insertGetId($args);
        $this->{$this->getPrimaryKey()} = $id;
    }

    public function beforeSave() {}
    public function afterSave() {}

    public function getTable() {
        return $this->table;
    }

    public function getPrimaryKey() {
        return 'id';
    }

    public function isNew() {
        return !$this->{$this->getPrimaryKey()};
    }

    public function toArray($hidden = []) {
        $d = obj2Array($this);
        foreach ($hidden as $h) {
            unset($d[$h]);
        }
        return $d;
    }
    /**
     * @param Builder $query
     *
     * @return \Illuminate\Support\Collection
     */
    public static function read(Builder $query) {
        return $query->from((new static())->getTable())
            ->get()
            ->map(function ($row) {
                return new static($row);
            });
    }

    public static function get($id) {
        $d = new static();
        $data = DB::table($d->getTable())
            ->where($d->getPrimaryKey(), $id)
            ->first();
        $d->load($data);

        return $d;
    }

    public static function query() {
         return DB::table((new static())->getTable());
    }

    public static function find($query, $one = false) {
        $q = static::query();
        foreach ($query as $key => $value) {
            if (is_scalar($value)) {
                $q->where($key, $value);
            } elseif (is_array($value) && count($query) == 2) {
                $q->where($key, current($value), end($value));
            }
        }
        if ($one) $q->limit(1);
        $result = static::read($q);
        return $one ? $result->first() : $result;
    }
}