<?php

namespace W360\SecureData\Commands;

use Exception;
use Illuminate\Console\Command;
use W360\SecureData\Support\Hex;

class SecureKeyCommand  extends Command
{

    /**
     * @var string
     */
    protected $signature = 'secure:key {--bytes=32}';

    /**
     * @var string
     */
    protected $description = 'Generates a key to encrypt the secure fields of the models';

    /**
     * @throws Exception
     */
    public function handle()
    {
        $bytesNumber = $this->option('bytes') ?? 32;
        $encryptKey = Hex::encode(random_bytes($bytesNumber));

        $this->info('Here is your new encryption key');
        $this->info('');
        $this->info($encryptKey);
        $this->info('');
        $this->info('First, you should encrypt your model values using this command');
        $this->info("secure:encrypt <MODEL-CLASS> {$encryptKey}");
        $this->info('');
        $this->info('Next, you should add this line to your .env file');
        $this->info("SECURE_SECRET_KEY={$encryptKey}");
        echo "SECURE_SECRET_KEY={$encryptKey}";
    }

}