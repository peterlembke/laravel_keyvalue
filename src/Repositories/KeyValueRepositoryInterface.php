<?php
/**
 * Copyright (C) 2020  Peter Lembke, CharZam soft
 * the program is distributed under the terms of the GNU General Public License
 *
 * KeyvalueRepositoryInterface.php is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * KeyvalueRepositoryInterface.php is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with KeyvalueRepositoryInterface.php.	If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace PeterLembke\KeyValue\Repositories;

/**
 * Interface KeyValueRepositoryInterface
 * @package PeterLembke\KeyValue\Repositories
 * Read and write to a key-value storage
 * This is the interface that others will use in their dependency injections.
 * Laravel will check what class to use by the bind to this interface.
 */
interface KeyValueRepositoryInterface
{
    /**
     * Read from a key value resource
     * @param string $resourceName | To get the right table name: charzam_keyvalue_{resourceName}
     * @param string $key | what/ever_you/like
     * @return array | You get answer, message, data, post_exist in an array
     * @param array $default | An array with properties and datatypes you expect in the response
     * @return array
     */
    public function read(
        string $resourceName = '', 
        string $key = '', 
        array $default = []
    ): array;
    
    /**
     * Write to a key value resource
     * @param string $resourceName
     * @param string $key
     * @param array $value | The array data you want to write
     * @param string $mode | overwrite, merge, drop (leave key empty to drop the table)
     * @return array | You get answer, message, new_post in an array
     */
    public function write(
        string $resourceName = '',
        string $key = '', array
        $value = [], string
        $mode = 'overwrite'
    ): array;
}
