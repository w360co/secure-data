<?php

namespace W360\SecureData\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use W360\SecureData\Contracts\SecureDataEncrypted;
use W360\SecureData\Support\Binary;

class SecureEncryptCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'secure:encrypt {model} {sort=asc}';

    /**
     * @var string
     */
    protected $description = 'Encrypt secure fields of a model';


    public function handle(): int
    {

        $model = $this->getModel();
        if ($model === false) {
            return self::INVALID;
        }


        $this->encryptSecureFields($model);

        return self::SUCCESS;
    }

    protected function getModel()
    {
        /** @var class-string<\W360\SecureData\Contracts\SecureDataEncrypted> $modelClass */
        $modelClass = $this->argument('model');

        if (!class_exists($modelClass)) {
            $this->error("Model {$modelClass} does not exist");
            echo "Model {$modelClass} does not exist";
            return false;
        }

        $newClass = (new $modelClass());

        if (! $newClass instanceof SecureDataEncrypted) {
            $this->error("Model {$modelClass} does not implement CipherSweetEncrypted");
            echo "Model {$modelClass} does not implement CipherSweetEncrypted";

            return false;
        }

        return $newClass;
    }

    protected function encryptSecureFields($model)
    {
        if (method_exists($model, 'getSecureAttributes')) {
            $updatedRows = 0;
            $sort = $this->argument('sort');

            $this->getOutput()->progressStart(DB::table($model->getTable())->count());

            DB::table($model->getTable())
                ->orderBy($model->getKeyName(), $sort)
                ->each(function (object $obj) use ($model, &$updatedRows) {

                    $attributes = $model->getSecureEncryptAttributes((array) $obj);
                    DB::table($model->getTable())
                            ->where($model->getKeyName(), $obj->{$model->getKeyName()})
                            ->update($attributes);

                    $updatedRows++;

                    $this->getOutput()->progressAdvance();
                });

            $this->getOutput()->progressFinish();

            $this->info("Updated {$updatedRows} rows.");
            $this->info("You can now set your config key to the new key.");
        } else {
            $this->alert("Model does not have secure fields to encrypt");
        }
    }

}