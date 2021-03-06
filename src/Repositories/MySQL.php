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

use PeterLembke\KeyValue\Helper\Base;
use Illuminate\Support\Facades\DB;

/**
 * Class MySQL
 * @package PeterLembke\KeyValue\Database
 * Low level class that use direct SQL queries to handle the MySQL database.
 */
class MySQL implements MySQLInterface
{
    const TABLE_PREFIX = 'keyvalue_';

    const MAX_RESOURCE_NAME_LENGTH = 24;

    /** @var array key=resourceName, value=table_name */
    protected $setupResourceDone = [];

    /** @var Base  */
    protected $base;

    public function __construct(
        Base $base
    )
    {
        $this->base = $base;
    }

    /**
     * Read from a key value resource
     * @param string $resourceName
     * @param string $key
     * @param array $default | Associated array with properties and datatypes you want in the data
     * @return array
     */
    public function read(
        string $resourceName = '',
        string $key = '',
        array $default = []
    ): array
    {
        $setupResponse = $this->setupResource($resourceName);
        $defaultResponse = [
            'answer' => false,
            'message' => '',
            'table_name' => ''
        ];
        $setupResponse = $this->base->_Default($defaultResponse, $setupResponse);

        if ($setupResponse['answer'] === false) {
            return $setupResponse;
        }

        $tableName = $setupResponse['table_name'];

        $readResponse = $this->internal_Read($tableName, $key);

        $defaultResponse = [
            'answer' => false,
            'message' => '',
            'post_exist' => false,
            'key' => '',
            'value' => '' // json data string
        ];
        $readResponse = $this->base->_Default($defaultResponse, $readResponse);

        if ($setupResponse['answer'] === false) {
            return $setupResponse;
        }

        if ($readResponse['value'] === '') {
            $readResponse['value'] = '{}';
        }

        $readResponse['value_array'] = $this->base->_JsonDecode($readResponse['value']);

        if (empty($default) === false) {
            $readResponse['value_array'] = $this->base->_Default($default, $readResponse['value_array']);
        }

        return [
            'answer' => $readResponse['answer'],
            'message' => $readResponse['message'],
            'key' => $readResponse['key'],
            'value_array' => $readResponse['value_array'],
            'post_exist' => $readResponse['post_exist']
        ];
    }

    /**
     * Write to a key value resource
     * @param string $resourceName
     * @param string $key
     * @param array $valueArray
     * @param string $mode |overwrite, merge, drop (key must be empty)
     * @return array
     */
    public function write(
        string $resourceName = '',
        string $key = '',
        array $valueArray = [],
        string $mode = 'overwrite'
    ): array
    {
        $setupResponse = $this->setupResource($resourceName);
        $defaultResponse = [
            'answer' => false,
            'message' => '',
            'table_name' => ''
        ];
        $setupResponse = $this->base->_Default($defaultResponse, $setupResponse);

        if ($setupResponse['answer'] === false) {
            return $setupResponse;
        }

        $tableName = $setupResponse['table_name'];

        if ($mode === 'drop' && $key === '')
        {
            $response = $this->internal_DropTableIfExist($tableName);

            unset($this->setupResourceDone[$resourceName]);

            return [
                'answer' => $response['answer'],
                'message' => $response['message'],
                'new_post' => false
            ];
        }

        $readResponse = $this->internal_Read($tableName, $key);

        $defaultResponse = [
            'answer' => false,
            'message' => '',
            'post_exist' => false,
            'key' => '',
            'value' => ''
        ];
        $readResponse = $this->base->_Default($defaultResponse, $readResponse);

        $newPost = true;
        If ($readResponse['post_exist'] === true) {
            $newPost = false;
        }

        if ($newPost === true) {
            $valueJson = $this->base->_JsonEncode($valueArray);
            $response = $this->internal_Insert($tableName, $key, $valueJson);

            if ($response === true) {
                return [
                    'answer' => true,
                    'message' => 'inserted the new post into the table',
                    'new_post' => true
                ];
            }

            return [
                'answer' => false,
                'message' => 'failed to insert the new post in the table',
                'new_post' => false,
            ];
        }

        if ($mode === 'merge') {
            $existingValueArray = $this->base->_JsonDecode($readResponse['value']);
            $valueArray = array_merge($existingValueArray, $valueArray);
        }

        $valueJson = $this->base->_JsonEncode($valueArray);

        if ($valueJson === $readResponse['value']) {
            return [
                'answer' => true,
                'message' => 'Did not have to update the table because this data is already there',
                'new_post' => false
            ];
        }

        $affectedId = $this->internal_Update($tableName, $key, $valueJson);

        if ($affectedId > 0) {
            return [
                'answer' => true,
                'message' => 'updated the existing post in the table',
                'new_post' => false
            ];
        }

        return [
            'answer' => false,
            'message' => 'failed to update the existing post in the table',
            'new_post' => false
        ];
    }

    /**
     * @param string $resourceName
     * @return array
     */
    protected function setupResource(string $resourceName = ''): array
    {
        if (isset($this->setupResourceDone[$resourceName]) === true) {
            return [
                'answer' => true,
                'message' => 'Resource already setup. Ready to read or write to the resourceName',
                'table_name' => $this->setupResourceDone[$resourceName],
                'table_exist' => true
            ];
        }

        $tableName = $this->getTableName($resourceName);

        if ($tableName === '') {
            return [
                'answer' => false,
                'message' => 'resourceName can only contain letters a-z',
                'table_name' => $tableName,
                'table_exist' => false
            ];
        }

        $tableExist = $this->internal_TableExist($tableName);

        if ($tableExist === true) {
            $this->setupResourceDone[$resourceName] = $tableName;
            return [
                'answer' => true,
                'message' => 'Ready to read or write to the resourceName',
                'table_name' => $tableName,
                'table_exist' => true
            ];
        }

        $tableCreated = $this->internal_CreateTableIfNotExist($tableName);

        if ($tableCreated === true) {
            $this->setupResourceDone[$resourceName] = $tableName;
            return [
                'answer' => true,
                'message' => 'Table created. Ready to read or write to the resourceName',
                'table_name' => $tableName,
                'table_exist' => true
            ];
        }

        return [
            'answer' => true,
            'message' => 'Could not create the resourceName',
            'table_name' => $tableName,
            'table_exist' => false
        ];
    }

    /**
     * Return the full table name for the resource you want to read/write
     * @param string $resourceName
     * @return string
     */
    protected function getTableName(string $resourceName = ''): string
    {
        $resourceNameValid = $this->containOnlyLowerCaseLetters($resourceName);
        if ($resourceNameValid === false) {
            return '';
        }

        $tableName = self::TABLE_PREFIX . $resourceName;

        return $tableName;
    }

    /**
     * Return true if the $row only contain lower case letters a-z
     * It is not enough to test only for the current locale.
     * @param string $row
     * @return bool
     */
    protected function containOnlyLowerCaseLetters(string $row = ''): bool
    {
        $length = strlen($row);
        if ($length > self::MAX_RESOURCE_NAME_LENGTH) {
            return false; // we need to limit the table name length
        }

        $result = $this->containOnlyLowerCaseLettersInCurrentLocale($row);
        if ($result === false) {
            return false;
        }

        $minAscii = ord('a');
        $maxAscii = ord('z');

        for ($position = 0; $position < $length; $position = $position +1)
        {
            $ascii = ord($row[$position]);

            if ($ascii < $minAscii) {
                return false;
            }

            if ($ascii > $maxAscii) {
                return false;
            }
        }

        return true;
    }

    /**
     * We test if the string contain lower case letters in the current locale.
     * @see https://www.php.net/manual/en/function.ctype-lower.php
     * @param string $row
     * @return bool
     */
    protected function containOnlyLowerCaseLettersInCurrentLocale(string $row = ''): bool
    {
        $result = ctype_lower($row);
        return $result;
    }

    /**
     * Return true if the table exist
     * @param string $resourceName
     * @return bool
     */
    protected function internal_TableExist(string $tableName = ''): bool
    {
        $sql = 'SHOW TABLES LIKE "?"';
        $sql = str_replace('?', $tableName, $sql);

        $result = DB::select($sql);

        $exist = false;
        if (count($result) > 0) {
            $exist = true;
        }

        return $exist;
    }

    /**
     * Return true if the table had to be created
     * @param string $resourceName
     * @return bool
     */
    protected function internal_CreateTableIfNotExist(string $tableName = ''): bool
    {
        $sql = <<<'EOD'
CREATE TABLE IF NOT EXISTS {table_name} (
    `key` varchar(127) COLLATE utf8_unicode_ci NOT NULL PRIMARY KEY COMMENT 'The unique key',
    `value` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Your json textdata up to 16Mb'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Charzam_KeyValue module';
EOD;

        $sql = str_replace('{table_name}', $tableName, $sql);

        $result = DB::statement($sql);

        return $result;
    }

    /**
     * Remove the table if it exists
     * Return true if the table was dropped
     * @param string $tableName
     * @return bool
     */
    protected function internal_DropTableIfExist(string $tableName = ''): bool
    {
        $result = DB::statement("drop table if exists $tableName");

        return $result;
    }

    /**
     * Read a key value from the table
     * @param string $tableName
     * @param string $key
     * @return array
     */
    protected function internal_Read(string $tableName = '', string $key = ''): array
    {
        $sql = 'select * from {table_name} where `key` = "{key}"';
        $sql = str_replace('{table_name}', $tableName, $sql);
        $sql = str_replace('{key}', $key, $sql);

        $result = DB::select($sql);

        $postExist = false;
        if (count($result) > 0) {
            $postExist = true;
        }

        $value = '';
        if (isset($result[0]) === true) {
            $value = $result[0]->value;
            if (empty($value) === true) {
                $value = '';
            }
        }

        return [
            'answer' => $postExist,
            'message' => 'Here are the result of the read',
            'post_exist' => $postExist,
            'key' => $key,
            'value' => $value
        ];
    }

    /**
     * Insert a key value in the table
     * @param string $tableName
     * @param string $key
     * @param string $value
     * @return bool
     */
    protected function internal_Insert(string $tableName = '', string $key = '', string $value = ''): bool
    {
        $sql = "insert into {table_name} (`key`, `value`) values ('{key}', '{value}')";
        $sql = str_replace('{table_name}', $tableName, $sql);
        $sql = str_replace('{key}', $key, $sql);
        $sql = str_replace('{value}', $value, $sql);

        $result = DB::insert($sql);

        return $result;
    }

    /**
     * Update a key value in the table
     * @param string $tableName
     * @param string $key
     * @param string $value
     * @return int
     */
    protected function internal_Update(string $tableName = '', string $key = '', string $value = ''): int
    {
        $sql = "update {table_name} set `value` = '{value}' where `key` = '{key}'";
        $sql = str_replace('{table_name}', $tableName, $sql);
        $sql = str_replace('{key}', $key, $sql);
        $sql = str_replace('{value}', $value, $sql);

        $affectedId = DB::update($sql);

        return $affectedId;
    }
}
