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

/**
 * Class KeyValueRepository
 * @package PeterLembke\KeyValue\Repositories
 * Handles the database tables
 * This is an implementation if the KeyValueRepositoryInterface
 * and it is bound to the interface in BackendServiceProvider.php
 */
class KeyValueRepository implements KeyValueRepositoryInterface
{
    /**
     * @param string $resourceName
     * @param string $key
     * @return array
     */
    public function read(string $resourceName = '', string $key = ''): array
    {
        // TODO: Implement read() method.

        return [
            'answer'=> true,
            'message' => 'Here are the data',
            'data' => [
                'title' => 'My title'
            ]
        ];
    }

    /**
     * @param string $resourceName
     * @param string $key
     * @param array $value
     * @return array
     */
    public function write(string $resourceName = '', string $key = '', array $value = []): array
    {
        // TODO: Implement write() method.

        return [
            'answer'=> true,
            'message' => 'Data stored'
        ];
    }
}
