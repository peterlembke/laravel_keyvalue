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

namespace Charzam\KeyValue\MyLogic;

use Charzam\KeyValue\Repositories\KeyValueRepositoryInterface;

/**
 * Class KeyValueRepository
 * @package Charzam\KeyValue\MyLogic
 * Show how you can add standard logic classes to your package
 * These classes are not repositories, not controllers - you just put your logic in these
 */
class MyLogic implements MyLogicInterface
{
    protected $keyValueRepository;

    /**
     * Test constructor.
     * Here you do all dependency injections.
     * All classes you need can be injected here trough their interface class.
     * @param KeyValueRepositoryInterface $keyValueRepository
     */
    public function __construct(
        KeyValueRepositoryInterface $keyValueRepository
    )
    {
        $this->keyValueRepository = $keyValueRepository;
    }

    /**
     * @param string $format
     * @return string
     */
    public function getTime(string $format = ''): string
    {
        return 'Time';
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        $valueArray = $this->keyValueRepository->read('config', 'my_title');
        return $valueArray['title'];
    }

    /**
     * @param string $title
     * @return string
     */
    public function setTitle(string $title = ''): void
    {
        $valueArray = [
            'title' => $title
        ];

        $this->keyValueRepository->write('config', 'my_title', $valueArray);
    }

}
