<?php

namespace W360\SecureData\Traits;

use Exception;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use W360\SecureData\Casts\Secure;
use W360\SecureData\Casts\SecureChar;
use W360\SecureData\Casts\SecureDate;
use W360\SecureData\Casts\SecureDateTime;
use W360\SecureData\Casts\SecureFloat;
use W360\SecureData\Casts\SecureInt;
use W360\SecureData\Casts\SecureIntUnsigned;
use W360\SecureData\Casts\SecureTime;
use W360\SecureData\SecureDataQueryBuilder;
use W360\SecureData\Support\Binary;

trait HasEncryptedFields
{

    /**
     * support encrypt type
     * @var string[]
     */
    protected array $supportEncryptType = ['AES', 'DES'];

    /**
     * @var
     */
    protected $encryptType;

    /**
     * @var
     */
    protected $secretKey;

    /**
     * cats type encrypted
     *
     * @var array
     */
    protected array $castTypes = [
        Secure::class => null,
        SecureFloat::class => 'DECIMAL',
        SecureChar::class => 'CHAR',
        SecureTime::class => 'TIME',
        SecureDate::class => 'DATE',
        SecureDateTime::class => 'DATETIME',
        SecureInt::class => 'SIGNED',
        SecureIntUnsigned::class => 'UNSIGNED',
    ];

    public function __construct(array $attributes = [])
    {
        $encryptType = config('secure-data.encrypt-type');
        $secretKey = config('secure-data.secret-key');

        if (array_key_exists($encryptType, $this->supportEncryptType)) {
            throw new Exception(' Encrypt type:' . $encryptType . ' is Invalid');
        }

        if (empty($secretKey)) {
            throw new Exception(' Encrypt Secret Key is Invalid');
        }

        $this->setEncryptType($encryptType);
        $this->setSecretKey($secretKey);

        parent::__construct($attributes);
    }

    /**
     * @return mixed
     */
    public function getEncryptType()
    {
        return $this->encryptType;
    }

    /**
     * @param mixed $encryptType
     */
    public function setEncryptType($encryptType): void
    {
        $this->encryptType = $encryptType;
    }

    /**
     * @return mixed
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param mixed $secretKey
     */
    public function setSecretKey($secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @param $column
     * @param string $type
     * @return string
     */
    protected function encapsulateSecureColumn($column, string $type = 'ENCRYPT'): string
    {
        return $this->encryptType . '_' . $type . '(' . $column . ',"' . $this->secretKey . '")';
    }

    /**
     * encapsulate cast type column
     *
     * @param $castType
     * @param string $column
     * @return string
     */
    protected function encapsulateCastType($castType, string $column): string
    {
        if (isset($this->castTypes[$castType]) and $this->castTypes[$castType] !== null) {
            $column = 'CAST(' . $column . ' as ' . $this->castTypes[$castType] . ')';
        }
        return $column;
    }

    /**
     * Get secure encrypt attributes
     *
     * @return array
     * @throws Exception
     */
    protected function getSecureAttributes(\Closure $function)
    {
        $secureAttributes = [];
        foreach ($this->casts as $key => $type) {
            if (array_key_exists($type, $this->castTypes)) {
                $secureAttributes[$key] = $function($key, $type);
            }
        }
        return $secureAttributes;
    }

    /**
     * @param $column
     * @param \Closure $function
     * @return mixed
     */
    protected function getSecureColumn($column, \Closure $function)
    {
        $table = "";
        $parts = explode(".", $column);
        $as = null;
        $key = end($parts);
        $partTwo = explode(" as ", strtolower($key));
        $castAttributes = $this->casts;

        if (is_array($partTwo) && count($parts) > 1) {
            $table = $parts[0];
            $partTwo = explode(" as ", strtolower($parts[1]));
            if (is_array($partTwo) && count($partTwo) > 1) {
                $key = $partTwo[0];
                $as = $partTwo[1];
            }

            if ($table !== $this->getTable()) {
                $currentModel = Str::ucfirst(Str::singular($this->getTable()));
                $relationModel = Str::ucfirst(Str::singular($table));
                $class = str_replace($currentModel, $relationModel, $this->getMorphClass());
                if (class_exists($class)) {
                    $model = new $class;
                    $castAttributes = $model->getCasts();
                }
            }
        }

        if (array_key_exists($key, $castAttributes)) {
            return $function($key, $table, $as, $castAttributes[$key]);
        }
        return $column;
    }

    /**
     * Get secure decrypt attributes
     *
     * @return array
     * @throws Exception
     */
    public function getSecureEncryptAttributes($values = [])
    {
        return $this->getSecureAttributes(function ($key, $type) use ($values) {
            if (array_key_exists($key, $values) && !Binary::checkIs($values[$key])) {
                $column = '"' . $values[$key] . '"';
                return DB::raw($this->encapsulateSecureColumn($column));
            } elseif (array_key_exists($key, $values) && Binary::checkIs($values[$key])) {
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
        return $this->getSecureAttributes(function ($key, $type) {
            return DB::raw(
                $this->encapsulateCastType(
                    $type,
                    $this->encapsulateSecureColumn($key, 'DECRYPT')
                )
            );
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
        return $this->getSecureAttributes(function ($key, $type) {
            return DB::raw(
                $this->encapsulateCastType(
                    $type,
                    $this->encapsulateSecureColumn($key, 'DECRYPT')
                ) . ' as `' . $key . '`'
            );
        });
    }

    /**
     * prepare column query
     *
     * @param $key
     * @param $table
     * @param $as
     * @param $withoutAliases
     * @param $castType
     * @param string $encryptType
     * @return Expression
     */
    public function getSecureSelectPrepareColumn($key, $table, $as, $withoutAliases, $castType, string $encryptType = 'ENCRYPT'): Expression
    {
        $alias = "";
        if (!$withoutAliases) {
            $alias = !empty($table) ? ' as `' . ($as ?? ($table . '`.`' . $key)) . '`' : ' as `' . ($as ?? $key) . '`';
        }
        $column = !empty($table) ? '`' . $table . '`.`' . $key . '`' : '`' . $key . '`';
        return DB::raw(
            $encryptType === 'ENCRYPT' ? $this->encapsulateSecureColumn($column, $encryptType) . $alias :
                $this->encapsulateCastType(
                    $castType,
                    $this->encapsulateSecureColumn($column, $encryptType)
                ) . $alias
        );
    }

    /**
     * Get secure decrypt attributes
     *
     * @return array
     * @throws Exception
     */
    public function getSecureSelectDecryptColumn($column, $withoutAliases = false)
    {
        return $this->getSecureColumn($column, function ($key, $table, $as, $type) use ($withoutAliases) {
            return $this->getSecureSelectPrepareColumn($key, $table, $as, $withoutAliases, $type, 'DECRYPT');
        });
    }

    /**
     * Get secure encrypt attributes
     *
     * @param $column
     * @param false $withoutAliases
     * @return mixed
     */
    public function getSecureSelectEncryptColumn($column, $withoutAliases = false)
    {
        return $this->getSecureColumn($column, function ($key, $table, $as, $type) use ($withoutAliases) {
            return $this->getSecureSelectPrepareColumn($key, $table, $as, $withoutAliases, $type);
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
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

}