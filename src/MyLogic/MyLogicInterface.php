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

namespace PeterLembke\KeyValue\MyLogic;

/**
 * Interface KeyValueRepositoryInterface
 * @package PeterLembke\KeyValue\Repositories
 * Read and write to a key-value storage
 * This is the interface that others will use in their dependency injections.
 * Laravel will check what class to use by the bind to this interface.
 */
interface MyLogicInterface
{
    /**
     * Get time in different formats
     * @param string $format
     * @return string
     */
    public function getTime(string $format = ''): string;

    /**
     * Get the stored title
     * @return string
     */
    public function getTitle(): string;

    /**
     * Set a title
     * @param string $title
     */
    public function setTitle(string $title = ''): void;
}
