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

namespace PeterLembke\KeyValue\Logic;

use PeterLembke\KeyValue\Repositories\KeyValueRepositoryInterface;

/**
 * Class KeyValue
 * @package PeterLembke\KeyValue\Logic
 * Show how you can add standard logic classes to your package
 * These classes are not repositories, not controllers - you just put your logic in these
 * Benefit is that you can test these classes. They can be overridden.
 * They can be used by other packages trough the interface.
 */
class KeyValue implements KeyValueInterface
{
    const RESOURCE_NAME = 'config';
    const TITLE_KEY = 'my_title';

    /** @var KeyValueRepositoryInterface  */
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
        $valueArray = $this->keyValueRepository->read(self::RESOURCE_NAME, self::TITLE_KEY);
        $title = $valueArray['data']['title'];
        return $title;
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

        $response = $this->keyValueRepository->write(self::RESOURCE_NAME, self::TITLE_KEY, $valueArray);
    }

}
