<?php
/**
 * Copyright (C) 2020  Peter Lembke, CharZam soft
 * the program is distributed under the terms of the GNU General Public License
 *
 * KeyvalueRepository.php is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * KeyvalueRepository.php is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with KeyvalueRepository.php.	If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace PeterLembke\KeyValue\Repositories;

use PeterLembke\KeyValue\Repositories\MySQL;
use PeterLembke\KeyValue\Helper\Base;

/**
 * Class KeyValueRepository
 * @package PeterLembke\KeyValue\Repositories
 * Handles the database tables that store key value data.
 * This is an implementation if the KeyValueRepositoryInterface
 * and it is bound to the interface in BackendServiceProvider.php
 * I use SQL in here because I need to handle many tables.
 */
class KeyValueRepository implements KeyValueRepositoryInterface
{
    /** @var \PeterLembke\KeyValue\Repositories\MySQL */
    protected $mysql;

    /** @var Base */
    protected $base;

    public function __construct(
        MySQL $mySQL,
        Base $base
    )
    {
        $this->mysql = $mySQL;
        $this->base = $base;
    }

    /**
     * Read from a key value resource
     * @param string $resourceName
     * @param string $key
     * @return array
     */
    public function read(
        string $resourceName = '',
        string $key = '',
        array $default = []
    ): array
    {
        $readResponse = $this->mysql->read($resourceName, $key, $default);

        $responseDefault = [
            'answer' => false,
            'message' => '',
            'key' => '',
            'value_array' => [],
            'post_exist' => false
        ];
        $readResponse = $this->base->_Default($responseDefault, $readResponse);

        return $readResponse;
    }

    /**
     * Write to a key value resource
     * @param string $resourceName
     * @param string $key
     * @param array $value
     * @param string $mode | overwrite, merge, drop (leave key empty to drop the table)
     * @return array
     */
    public function write(
        string $resourceName = '',
        string $key = '', array
        $valueArray = [], string
        $mode = 'overwrite'
    ): array
    {
        $writeResponse = $this->mysql->write($resourceName, $key, $valueArray, $mode);

        $responseDefault = [
            'answer' => false,
            'message' => '',
            'new_post' => false
        ];
        $writeResponse = $this->base->_Default($responseDefault, $writeResponse);

        return $writeResponse;
    }
}
