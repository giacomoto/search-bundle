<?php

namespace Luckyseven\Bundle\LuckysevenSearchBundle\Class;

class QueryStructure
{
    const LEFT_JOIN = 'left join';
    const RIGHT_JOIN = 'right join';
    const INNER_JOIN = 'inner join';

    public function __construct(
        protected array           $select = [],
        protected string          $from = "",
        protected array           $join = [],
        protected array           $where = [],
        protected array           $order = [],
        protected string|int|null $limit = null,
        protected string|int|null $offset = null,
        protected array           $parameters = []
    )
    {
    }

    public function addSelect(string $select): self
    {
        $this->select[] = $select;
        return $this;
    }

    public function setFrom(string $from): self
    {
        $this->from = $from;
        return $this;
    }

    public function addJoin(string $direction, string $target, string $condition): self
    {
        $this->join[] = "$direction $target $condition";
        return $this;
    }

    public function andWhere(string $where): self
    {
        $this->where[] = "AND $where";
        return $this;
    }

    public function addOrderBy(string $order): self
    {
        $this->order[] = $order;
        return $this;
    }

    public function setLimit(string|int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function setOffset(string|int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function getSql(): string
    {
        $select = "SELECT " . implode(', ', $this->select);
        $from = " FROM $this->from";
        $join = count($this->join) > 0
            ? " " . implode(' ', $this->join)
            : "";
        $where = count($this->where) > 0
            ? " WHERE 1=1 " . implode(' ', $this->where)
            : "";
        $order = count($this->order) > 0
            ? " ORDER BY " . implode(', ', $this->order)
            : '';
        $limit = $this->limit
            ? " LIMIT " . $this->limit
            : '';
        $offset = $this->offset
            ? " OFFSET " . $this->offset
            : '';

        return "$select$from$join$where$order$limit$offset";
    }

    public function addParameter(string $key, string|int $val): self {
        $this->parameters[$key] = $val;
        return $this;
    }

    public function getParameters(): array {
        return $this->parameters;
    }
}
