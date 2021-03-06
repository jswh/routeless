<?php

namespace Routeless\Core;

use Illuminate\Database\Query\Builder;
use Routeless\Services\DB;

abstract class Model
{
    protected $table;

    protected $columns;

    public function __construct($args = [])
    {
        $this->columns = publicMembers($this);
        $this->load($args);
    }

    final public function load($args)
    {
        $this->setArgs($args);
        $this->afterLoad();
    }

    public function setArgs($args)
    {
        if (!$args) return;
        foreach ($args as $k => $v) {
            if (in_array($k, $this->columns)) {
                $this->{$k} = $v;
            }
        }
    }

    public function afterLoad()
    {
    }

    /**
     * get
     *
     * @param mixed $id
     * @return static
     */
    public static function get($id)
    {
        $d = new static();
        $data = DB::table($d->getTable())
            ->where($d->getPrimaryKey(), $id)
            ->first();
        if (!$data) {
            return null;
        }
        $d->load($data);

        return $d;
    }

    /**
     * @param array $query
     *
     * @return static[]
     */
    public static function find($query, $one = false)
    {
        $q = static::query();
        foreach ($query as $key => $value) {
            if (is_scalar($value)) {
                $q->where($key, $value);
            } elseif (is_array($value) && count($value) == 2) {
                $op = current($value);
                $val = end($value);
                if ($op == 'in') {
                    $q->whereIn($key, $val);
                } else {
                    $q->where($key, $op, $val);
                }
            }
        }
        if ($one) $q->limit(1);
        $result = static::read($q);
        return $one ? $result->first() : $result;
    }

    /**
     * @param Builder $query
     *
     * @return static
     */
    public static function findOne($query)
    {
        return self::find($query, true);
    }

    /**
     * @param Builder $query
     *
     * @return \Illuminate\Support\Collection
     */
    public static function read(Builder $query)
    {
        return $query->from((new static())->getTable())
            ->get()
            ->map(function ($row) {
                return new static($row);
            });
    }

    public function save()
    {
        $this->beforeSave();

        $re = $this->isNew() ? $this->performInsert() : $this->performUpdate();

        $this->afterSave();

        return $re;
    }

    public function beforeSave()
    {
    }

    public function isNew()
    {
        return !$this->{$this->getPrimaryKey()};
    }

    public function getPrimaryKey()
    {
        return 'id';
    }

    private function performInsert()
    {
        if ($this->timestamps ?? false) {
            $this->updatedTime = $this->createdTime = time();
        }
        $args = obj2Array($this);
        $id = DB::table($this->getTable())
            ->insertGetId($args);
        $this->{$this->getPrimaryKey()} = $id;
    }

    public function getTable()
    {
        return $this->table;
    }

    private function performUpdate()
    {
        if ($this->timestamps) {
            $this->updatedTime = time();
        }
        $args = obj2Array($this);
        return DB::table($this->getTable())
            ->where($this->getPrimaryKey(), '=', $this->id())
            ->update($args);
    }

    public function id()
    {
        return $this->{$this->getPrimaryKey()};
    }

    public function afterSave()
    {
    }

    public function remove()
    {
        if ($this->isNew()) {
            return;
        }
        return self::query()
            ->where($this->getPrimaryKey(), $this->id())
            ->delete();
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public static function query()
    {
        return DB::table((new static())->getTable());
    }

    public function toArray($hidden = [])
    {
        $d = obj2Array($this);
        foreach ($hidden as $h) {
            unset($d[$h]);
        }
        return $d;
    }
}
