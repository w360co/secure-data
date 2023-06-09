<?php

namespace W360\SecureData;


use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Arr;


class SecureDataQueryBuilder extends Builder
{

    /**
     * current model
     * @var
     */
    private $model;

    /**
     * @param ConnectionInterface $connection
     * @param $model
     */
    public function __construct(ConnectionInterface $connection, $model)
    {
        parent::__construct($connection, $connection->getQueryGrammar(), $connection->getPostProcessor());
        $this->model = $model;
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param \Closure|string|array $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if (is_array($column)) {
            return $this->addArrayOfWheres($column, $boolean);
        }
        if (method_exists($this->model, 'getSecureDecryptAttributes')) {
            $attributes = $this->model->getSecureDecryptAttributes();
            if (array_key_exists($column, $attributes)) {
                $column = $attributes[$column];
            }
        }
        return parent::where($column, $operator, $value, $boolean);
    }

    /**
     * Create a new query instance for nested where condition.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function forNestedWhere()
    {
        return $this->model->newBaseQueryBuilder();
    }

    /**
     * Add a "where in" clause to the query.
     *
     * @param string $column
     * @param mixed $values
     * @param string $boolean
     * @param bool $not
     * @return $this
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        if (method_exists($this->model, 'getSecureDecryptAttributes')) {
            $attributes = $this->model->getSecureDecryptAttributes();
            if (array_key_exists($column, $attributes)) {
                $column = $attributes[$column];
            }
        }

        return parent::whereIn($column, $values, $boolean, $not);
    }


    /**
     * Insert new records into the database.
     *
     * @param array $values
     * @return bool
     */
    public function insert(array $values)
    {
        if (empty($values)) {
            return true;
        }

        if (method_exists($this->model, 'getSecureEncryptAttributes') && !empty($values)) {
            $values = array_merge($values, $this->model->getSecureEncryptAttributes($values));
        }

        return parent::insert($values);
    }

    /**
     * Update records in the database.
     *
     * @param  array  $values
     * @return int
     */
    public function update(array $values)
    {
        $this->applyBeforeQueryCallbacks();

        $values = array_filter(array_merge($values, $this->model->getSecureEncryptAttributes($values)), function ($item){
             return !empty($item) or $item === 0;
        });

        $sql = $this->grammar->compileUpdate($this, $values);

        return $this->connection->update($sql, $this->cleanBindings(
            $this->grammar->prepareBindingsForUpdate($this->bindings, $values)
        ));
    }

    /**
     * @param string[] $columns
     * @return string[]
     */
    protected function prepareSecureSelectDecrypt($columns = ['*'])
    {
        if (method_exists($this->model, 'getSecureSelectDecryptAttributes') && method_exists($this->model, 'getTableColumns')) {
            if ($columns === ['*']) {
                $columns = $this->model->getTableColumns();
            }
            $encryptKeys = $this->model->getSecureSelectDecryptAttributes();
            foreach ($columns as $index => $key) {
                if(is_string($key)){
                    if (array_key_exists($key, $encryptKeys)) {
                        $columns[$index] = $encryptKeys[$key];
                    }
                }
            }
        }

        return $columns;
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param array|string $columns
     * @return \Illuminate\Support\Collection
     */
     public function get($columns = ['*'])
    {
        $columns = $this->prepareSecureSelectDecrypt($columns);
        return parent::get($columns);
    }

    /**
     * Execute an aggregate function on the database.
     *
     * @param  string  $function
     * @param  array  $columns
     * @return mixed
     */
    public function aggregate($function, $columns = ['*'])
    {
        $columns = $this->withoutSelectAliases($columns);

        $results = $this->cloneWithout($this->unions || $this->havings ? [] : ['columns'])
            ->cloneWithoutBindings($this->unions || $this->havings ? [] : ['select'])
            ->setAggregate($function, $columns)
            ->get($columns);

        if (! $results->isEmpty()) {
            return array_change_key_case((array) $results[0])['aggregate'];
        }
    }

    /**
     * Get a collection instance containing the values of a given column.
     *
     * @param  string  $column
     * @param  string|null  $key
     * @return \Illuminate\Support\Collection
     */
    public function pluck($column, $key = null)
    {
        $newColumn = $this->model->getSecureSelectDecryptColumn($column);
        // First, we will need to select the results of the query accounting for the
        // given columns / key. Once we have the results, we will be able to take
        // the results and get the exact data that was requested for the query.
        $queryResult = $this->onceWithColumns(
            is_null($key) ? [$newColumn] : [$newColumn, $key],
            function () {
                return $this->processor->processSelect(
                    $this, $this->runSelect()
                );
            }
        );

        if (empty($queryResult)) {
            return collect();
        }

        // If the columns are qualified with a table or have an alias, we cannot use
        // those directly in the "pluck" operations since the results from the DB
        // are only keyed by the column itself. We'll strip the table out here.
        $column = $this->stripTableForPluck($column);

        $key = $this->stripTableForPluck($key);

        return is_array($queryResult[0])
            ? $this->pluckFromArrayColumn($queryResult, $column, $key)
            : $this->pluckFromObjectColumn($queryResult, $column, $key);
    }


    /**
     * Set the columns to be selected.
     *
     * @param  array|mixed  $columns
     * @return $this
     */
    public function select($columns = ['*'])
    {
        $this->columns = [];
        $this->bindings['select'] = [];

        if($columns === ['*'] or $columns === '*'){
            $columns = $this->model->getTableColumns();
        }

        $columns = is_array($columns) ? $columns : func_get_args();
        foreach ($columns as $as => $column) {
            if (is_string($as) && $this->isQueryable($column)) {
                $this->selectSub($column, $as);
            } else {
                $this->columns[] = $this->model->getSecureSelectDecryptColumn($column);
            }
        }

        return $this;
    }

    /**
     * Add a new select column to the query.
     *
     * @param  array|mixed  $column
     * @return $this
     */
    public function addSelect($column)
    {
        $columns = is_array($column) ? $column : func_get_args();
        foreach ($columns as $as => $column) {
            if (is_string($as) && $this->isQueryable($column)) {
                if (is_null($this->columns)) {
                    $this->select($this->from.'.*');
                }
                $this->selectSub($column, $as);
            } else {
                 if($column instanceof Expression){
                     $this->columns[] = $column;
                     continue;
                 }

                if (str_contains($column, '*')){
                    $parts = explode('*', $column);
                    if(count($parts) > 1){
                        $table = $parts[0];
                        $relatedColumns = $this->model->getTableColumns();
                        foreach ($relatedColumns as $related){
                            $this->columns[] = $this->model->getSecureSelectDecryptColumn($table.$related." as ".$related);
                        }
                    }
                }else {
                    $this->columns[] = $this->model->getSecureSelectDecryptColumn($column);
                }
            }
        }
        return $this;
    }

    /**
     * Remove the column aliases since they will break count queries.
     *
     * @param  array  $columns
     * @return array
     */
    protected function withoutSelectAliases(array $columns)
    {
        return array_map(function ($column) {
            return $this->model->getSecureSelectDecryptColumn($column, true);
        }, $columns);
    }

    /**
     * Force the query to only return distinct results.
     *
     * @return $this
     */
    public function distinct()
    {
        $columns = func_get_args();
        if (count($columns) > 0) {
            $this->distinct = is_array($columns[0]) || is_bool($columns[0]) ? $columns[0] : $columns;
        } else {
            $this->distinct = true;
        }
        return $this;
    }

    /**
     * Add an "order by" clause to the query.
     *
     * @param  \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Expression|string  $column
     * @param  string  $direction
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function orderBy($column, $direction = 'asc')
    {

        if ($this->isQueryable($column)) {
            [$query, $bindings] = $this->createSub($column);
            $column = new Expression('('.$query.')');
            $this->addBinding($bindings, $this->unions ? 'unionOrder' : 'order');
        }

        $direction = strtolower($direction);
        if (! in_array($direction, ['asc', 'desc'], true)) {
            throw new \InvalidArgumentException('Order direction must be "asc" or "desc".');
        }

        $this->{$this->unions ? 'unionOrders' : 'orders'}[] = [
            'column' => $this->model->getSecureSelectDecryptColumn($column, true),
            'direction' => $direction,
        ];

        return $this;
    }

    /**
     * Insert a new record and get the value of the primary key.
     *
     * @param  array  $values
     * @param  string|null  $sequence
     * @return int
     */
    public function insertGetId(array $values, $sequence = null)
    {
        $this->applyBeforeQueryCallbacks();

        $values = array_merge($values, $this->model->getSecureEncryptAttributes($values));

        $sql = $this->grammar->compileInsertGetId($this, $values, $sequence);
        $values = $this->cleanBindings($values);

        return $this->processor->processInsertGetId($this, $sql, $values, $sequence);
    }

    /**
     * Add a "group by" clause to the query.
     *
     * @param  array|string  ...$groups
     * @return $this
     */
    public function groupBy(...$groups)
    {
        foreach ($groups as $group) {
            $group = $this->model->getSecureSelectDecryptColumn($group, true);
            $this->groups = array_merge(
                (array) $this->groups,
                Arr::wrap($group)
            );
        }
        return $this;
    }

}