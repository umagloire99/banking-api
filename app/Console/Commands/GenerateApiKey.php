<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new api key';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $key = Str::random(64);

        if (file_exists($path = $this->envPath()) === false) {
            $this->displayKey($key);
            return;
        }
        if (Str::contains(file_get_contents($path), 'API_KEY') === false) {
            // create new entry
            file_put_contents($path, PHP_EOL."API_KEY=$key".PHP_EOL, FILE_APPEND);
        } else {
            // update existing entry
            file_put_contents($path, str_replace(
                'API_KEY='.$this->laravel['config']['app.api_key'],
                'API_KEY='.$key, file_get_contents($path)
            ));
        }
        $this->displayKey($key);
    }

    /**
     * Display the key.
     *
     * @param string $key
     * @return void
     */
    protected function displayKey(string $key)
    {
        $this->laravel['config']['app.api_key'] = $key;

        $this->info("Api Key [$key] set successfully.");
    }

    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath(): string
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        // check if laravel version Less than 5.4.17
        if (version_compare($this->laravel->version(), '5.4.17', '<')) {
            return $this->laravel->basePath().DIRECTORY_SEPARATOR.'.env';
        }

        return $this->laravel->basePath('.env');
    }
}
