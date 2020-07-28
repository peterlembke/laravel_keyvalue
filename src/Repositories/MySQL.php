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
    const TABLE_PREFIX = 'charzam_keyvalue_';

    const MAX_RESOURCE_NAME_LENGTH = 24;

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

        $readResponse = $this->internal_Read($tabelName, $key);

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

        $readResponse['value'] = $this->base->_JsonDecode($readResponse['value']);

        if (empty($default) === false) {
            $readResponse['value'] = $this->base->_Default($default, $readResponse['value']);
        }

        return [
            'answer' => $readResponse['answer'],
            'message' => $readResponse['message'],
            'key' => $readResponse['key'],
            'value' => $readResponse['value'],
            'post_exist' => $readResponse['post_exist']
        ];
    }

    /**
     * Write to a key value resource
     * @param string $resourceName
     * @param string $key
     * @param array $value
     * @param string $mode |overwrite, merge, drop (key must be empty)
     * @return array
     */
    public function write(
        string $resourceName = '',
        string $key = '',
        array $value = [],
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

        if ($readResponse['answer'] === false) {
            return $readResponse;
        }

        $newPost = true;
        If ($readResponse['post_exist'] === true) {
            $newPost = false;
        }

        if ($newPost === true) {
            $valueJson = $this->base->_JsonEncode($value);
            $response = $this->internal_Insert($tableName, $key, $valueJson);

            return [
                'answer' => $response['answer'],
                'message' => $response['message'],
                'new_post' => true
            ];
        }

        if ($mode === 'merge') {
            $readResponse['value'] = $this->base->_JsonDecode($readResponse['value']);
            $value = array_merge($readResponse['value'], $value);
        }

        $valueJson = $this->base->_JsonEncode($readResponse['value']);
        $response = $this->internal_Update($tableName, $key, $valueJson);

        return [
            'answer' => $response['answer'],
            'message' => $response['message'],
            'new_post' => false
        ];
    }

    /**
     * @param string $resourceName
     * @return array
     */
    protected function setupResource(string $resourceName = ''): array
    {
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
            return [
                'answer' => true,
                'message' => 'Ready to read or write to the resourceName',
                'table_name' => $tableName,
                'table_exist' => true
            ];
        }

        $tableCreated = $this->internal_CreateTableIfNotExist($tableName);

        if ($tableCreated === true) {
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
        $result = DB::select('SHOW TABLES LIKE ?', [$tableName]);

        if (count($result) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Return true if the table had to be created
     * @param string $resourceName
     * @return bool
     */
    protected function internal_CreateTableIfNotExist(string $tableName = ''): bool
    {
        $sql = <<<'EOD'
CREATE TABLE IF NOT EXISTS $tableName (
    `key` varchar(127) COLLATE utf8_unicode_ci NOT NULL PRIMARY KEY COMMENT 'The unique key',
    `value` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Your json textdata up to 16Mb'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Charzam_KeyValue module';
EOD;

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
        $result = DB::select("select * from $tableName where key = ?", [$key]);

        $postExist = false;
        if (count($result) > 0) {
            $postExist = true;
        }

        $value = $result[0]['value'];

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
        $result = DB::insert("insert into $tableName (key, value) values (?, ?)", [$key, $value]);
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
        $affectedId = DB::update("update $tableName set value = ? where key = ?", [$value, $key]);
        return $affectedId;
    }
}
