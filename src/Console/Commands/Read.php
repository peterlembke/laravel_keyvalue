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

namespace PeterLembke\KeyValue\Console\Commands;

use Illuminate\Console\Command;
use PeterLembke\KeyValue\MyLogic\MyLogicInterface;

/**
 * Class Read
 * @package PeterLembke\KeyValue\Console\Commands
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

    protected $myLogicTest;

    /**
     * Read constructor.
     * @param MyLogicInterface $test
     */
    public function __construct(
        MyLogicInterface $test
    )
    {
        $this->myLogicTest = $test;

        parent::__construct(); // Classes that extend another class should call the parent constructor.
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        echo 'Key: ' . $this->argument('key') . "\n";
        echo 'Title: ' . $this->myLogicTest->getTitle() . "\n";
    }
}
