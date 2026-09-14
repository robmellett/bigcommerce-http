<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Commands;

use Illuminate\Console\Command;

class BigcommerceHttpCommand extends Command
{
    public $signature = 'bigcommerce-http';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
