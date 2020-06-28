<?php
/**
 * Copyright (C) 2020  Peter Lembke, CharZam soft
 * the program is distributed under the terms of the GNU General Public License
 *
 * Test.php is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Test.php is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Test.php.	If not, see <https://www.gnu.org/licenses/>.
 */

namespace Charzam\KeyValue\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class Read
 * @package Charzam\KeyValue\Console\Commands
 * Read from the key value table
 * Example: dox laravel keyvalue:read foobar
 */
class Read extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keyvalue:read {key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read a key';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        echo 'Key: ' . $this->argument('key') . "\n";
    }
}
