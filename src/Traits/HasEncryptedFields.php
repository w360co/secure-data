<?php

namespace W360\SecureData\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use W360\SecureData\Casts\Secure;
use W360\SecureData\Database\Eloquent\Query\BelongsToMany;
use W360\SecureData\SecureDataQueryBuilder;
use W360\SecureData\Support\Binary;

trait HasEncryptedFields
{


    /**
     * support encrypt type
     * @var string[]
     */
    protected $supportEncryptType = ['AES', 'DES'];


    /**
     * Get secure encrypt attributes
     *
     * @return array
     * @throws Exception
     */
    protected function getSecureAttributes(\Closure $function){

        $secureAttributes = [];

        $secretKey = config('secure-data.secret-key');
        $encryptType = config('secure-data.encrypt-type');

        if(in_array($encryptType, $this->supportEncryptType)) {
            foreach ($this->casts as $key => $val){
                if($val === Secure::class) {
                    $secureAttributes[$key] = $function($secretKey, $encryptType, $key);
                }
            }
            return $secureAttributes;
        }

        throw new Exception(' Encrypt type:' . $encryptType . ' is Invalid');
    }

    /**
     * @param $column
     * @param \Closure $function
     * @return mixed
     */
    protected function getSecureAttribute($column, \Closure $function){
        $secretKey = config('secure-data.secret-key');
        $encryptType = config('secure-data.encrypt-type');
        $table = "";
        $parts = explode(".", $column);
        $as = null;
        $key = end($parts);
        $partTwo = explode(" as ", strtolower($key));
        $castAttributes = $this->casts;

        if(is_array($partTwo) && count($parts) > 1){
            $table = $parts[0];
            $partTwo = explode(" as ", strtolower($parts[1]));
            if(is_array($partTwo) && count($partTwo) > 1){
                $key = $partTwo[0];
                $as = $partTwo[1];
            }

            if($table !== $this->getTable()){
                $currentModel = Str::ucfirst(Str::singular($this->getTable()));
                $relationModel = Str::ucfirst(Str::singular($table));
                $class = str_replace($currentModel, $relationModel, $this->getMorphClass());
                if(class_exists($class)){
                    $model = new $class;
                    $castAttributes = $model->getCasts();
                }
            }
        }

        if(in_array($encryptType, $this->supportEncryptType)) {
            if (array_key_exists($key, $castAttributes)){
                return $function($secretKey, $encryptType, $key, $table, $as);
            }
        }
        return $column;
    }

    /**
     * Get secure decrypt attributes
     *
     * @return array
     * @throws Exception
     */
    public function getSecureEncryptAttributes($values=[])
    {
        return $this->getSecureAttributes( function($secretKey, $encryptType, $key) use ($values) {
            if(array_key_exists($key, $values) && !Binary::checkIs($values[$key])) {
                return DB::raw('' . $encryptType . '_ENCRYPT("' . $values[$key] . '","' . $secretKey . '")');
            }elseif(array_key_exists($key, $values) && Binary::checkIs($values[$key])){
                return $values[$key];
            }
        });
    }

    /**
     * Get secure decrypt attributes
     *
     * @return array
     * @throws Exception
     */
    public function getSecureDecryptAttributes()
    {
        return $this->getSecureAttributes(function($secretKey, $encryptType, $key){
            return DB::raw($encryptType . '_DECRYPT(' . $key . ',"' . $secretKey . '")');
        });
    }


    /**
     * Get secure decrypt attributes
     *
     * @return array
     * @throws Exception
     */
    public function getSecureSelectDecryptAttributes()
    {
        return $this->getSecureAttributes(function($secretKey, $encryptType, $key){
            return DB::raw($encryptType . '_DECRYPT(' . $key . ',"' . $secretKey . '") as `' . $key . '`');
        });
    }

    /**
     * Get secure decrypt attributes
     *
     * @return array
     * @throws Exception
     */
    public function getSecureSelectDecryptAttribute($column, $withoutAliases=false)
    {
        return $this->getSecureAttribute($column, function($secretKey, $encryptType, $key, $table, $as) use ($withoutAliases) {
            $alias = "";
            if(!$withoutAliases){
                if(!empty($table)) {
                    $alias = ' as `'. ($as ?? ($table . '`.`' . $key)) . '`';
                }else{
                    $alias = ' as `' . ($as ?? $key) . '`';
                }
            }
            if(!empty($table)) {
                return DB::raw($encryptType . '_DECRYPT(`' . $table . '`.`' . $key . '`,"' . $secretKey . '")'.$alias);
            } else {
                return DB::raw($encryptType . '_DECRYPT(`' . $key . '`,"' . $secretKey . '")'.$alias);
            }
        });
    }

    /**
     * Get secure encrypt attributes
     *
     * @param $column
     * @param false $withoutAliases
     * @return mixed
     */
    public function getSecureSelectEncryptAttribute($column, $withoutAliases=false)
    {
        return $this->getSecureAttribute($column, function($secretKey, $encryptType, $key, $table, $as) use ($withoutAliases){
            $alias = "";
            if(!$withoutAliases){
                if(!empty($table)) {
                    $alias = ' as `'. ($as ?? ($table . '`.`' . $key)) . '`';
                }else{
                    $alias = ' as `' . ($as ?? $key) . '`';
                }
            }
            if(!empty($table)) {
                return DB::raw($encryptType . '_ENCRYPT(`' . $table . '`.`' . $key . '`,"' . $secretKey . '")'.$alias);
            } else {
                return DB::raw($encryptType . '_ENCRYPT(`' . $key . '`,"' . $secretKey . '")'.$alias);
            }
        });
    }


    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();
        return new SecureDataQueryBuilder($connection, $this);
    }

    /**
     * Write code on Method
     *
     * @return array
     */
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
/**    public function fill(array $attributes)
    {

        if (method_exists($this, 'getSecureEncryptAttributes') && !empty($attributes)) {
            $encript = $this->getSecureEncryptAttributes($attributes);
            $attributes = array_merge($attributes, $encript);
        }

        return parent::fill($attributes);
    } **/








}