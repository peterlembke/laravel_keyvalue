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
use PeterLembke\KeyValue\Repositories\KeyValueRepositoryInterface;
use PeterLembke\KeyValue\Helper\Base;

/**
 * Class Read
 * @package PeterLembke\KeyValue\Console\Commands
 * Read from the key value table
 * Example: dox laravel keyvalue:read config background/color
 */
class Read extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keyvalue:read {resource_name} {key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read a key';

    /** @var KeyValueRepositoryInterface  */
    protected $keyValueRepository;

    /** @var Base  */
    protected $base;

    /**
     * Read constructor.
     * @param KeyValueRepositoryInterface $keyValueRepository
     */
    public function __construct(
        KeyValueRepositoryInterface $keyValueRepository,
        Base $base
    )
    {
        $this->keyValueRepository = $keyValueRepository;
        $this->base = $base;

        parent::__construct(); // Classes that extend another class should call the parent constructor.
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $resourceName = $this->argument('resource_name');
        $key = $this->argument('key');

        $response = $this->keyValueRepository->read($resourceName, $key);
        if ($response['answer'] === false) {
            echo $response['message'];
            return;
        }

        echo $this->base->_JsonEncode($response['data']);
    }
}
