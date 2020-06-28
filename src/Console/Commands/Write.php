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
 * Class Write
 * @package PeterLembke\KeyValue\Console\Commands
 * Write data to the key value table
 * Example: dox laravel keyvalue:write foobar my_data
 */
class Write extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keyvalue:write {key} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Write data to a key';

    protected $myLogicTest;

    /**
     * Write constructor.
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
        $key = $this->argument('key');
        echo 'Key: ' . $key . "\n";

        $value = $this->argument('value');
        $this->myLogicTest->setTitle($value);
        echo 'Value: ' . $value . "\n";
    }
}
