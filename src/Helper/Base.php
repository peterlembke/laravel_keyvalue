<?php
declare(strict_types=1);

namespace PeterLembke\KeyValue\Helper;

class Base
{
    public function _VersionBase(): array
    {
        return array(
            'date' => '2016-01-26',
            'version' => '1.0.0',
            'checksum' => '{{base_checksum}}',
            'class_name' => 'infohub_base',
            'note' => 'Parent class in ALL plugins. Manages the traffic in the plugin',
            'status' => 'normal',
            'SPDX-License-Identifier' => 'GPL-3.0-or-later'
        );
    }

    /**
     * Version data. Implement this function in your plugin
     * @return array
     */
    protected function _Version(): array
    {
        return array(
            'date' => '1970-01-01',
            'version' => '0.0.0',
            'class_name' => get_class($this),
            'checksum' => '{{checksum}}',
            'note' => 'Please implement this function in your plugin',
            'status' => 'emerging'
        );
    }

    // *****************************************************************************
    // * The private functions, add your own in your plugin
    // * These functions can be used directly in your functions.
    // * Name: _CamelCaseData
    // *****************************************************************************

    /**
     * Makes sure you get all default variables with at least default values, and the right data type.
     * Used by: EVERY function.
     * The $default variables, You can only use: array, string, integer, float, null
     * The $in variables, You can only use: array, string, integer, float
     * @example: $in = _Default($default,$in);
     * @version 2016-01-25
     * @since   2013-09-05
     * @author  Peter Lembke
     * @param $default
     * @param $in
     * @return array
     */
    public function _Default(array $default = array(), array $in = array()): array
    {
        if (is_array($default) === false and is_array($in) === true) {
            return $in;
        }
        if (is_array($default) === true and is_array($in) === false) {
            return $default;
        }

        if (isset($in[0]) === true) {
            // This is a traditional array, we just let it pass
            $answer = $in;
        } else {
            // On this level: Remove all variables that are not in default. Add all variables that are only in default.
            $answer = array_intersect_key(array_merge($default, $in), $default);
        }

        // Check the data types
        foreach ($default as $key => $data) {
            if (gettype($answer[$key]) !== gettype($default[$key])) {
                if (is_null($default[$key]) === false) {
                    $answer[$key] = $default[$key];
                }
                continue;
            }
            if (is_null($default[$key]) === true and is_null($answer[$key]) === true) {
                $answer[$key] = '';
                continue;
            }
            if (is_array($default[$key]) === false) {
                continue;
            }
            if (count($default[$key]) === 0) {
                continue;
            }
            $answer[$key] = $this->_Default($default[$key], $answer[$key]);
        }

        return $answer;
    }

    /**
     * Merge two arrays together
     * @param array $default
     * @param array $in
     * @return array
     */
    public function _Merge(array $default = array(), array $in = array()): array
    {
        if (is_array($default) === false and is_array($in) === false) {
            return array();
        }
        if (is_array($default) === false and is_array($in) === true) {
            return $in;
        }
        if (is_array($default) === true and is_array($in) === false) {
            return $default;
        }

        $data = array_merge($default, $in);

        return $data;
    }

    /**
     * Return current time stamp as a string in the format "yyyy-mm-dd hh:mm:ss"
     * Give 'c' to also get the time zone offset.
     * @param string $in
     * @return bool|string
     */
    public function _TimeStamp(string $in = ''): string
    {
        if ($in === 'c') {
            $date = date('c');
        } else {
            $date = date('Y-m-d H:i:s');
        }

        return $date;
    }

    /**
     * Return a datetime with microseconds.
     * Used for logging purpose where seconds is not enough.
     * From: https://stackoverflow.com/questions/169428/php-datetime-microseconds-always-returns-0
     * @return string
     */
    public function _TimeStampMicro(): string
    {
        $microTime = microtime(true);
        $microSeconds = sprintf("%06d",($microTime - floor($microTime)) * 1000000);
        $date = date('Y-m-d H:i:s', (int)$microTime) . '.' . $microSeconds;

        return $date;
    }

    /**
     * Return current time since EPOC (1970-01-01 00:00:00),
     * as seconds and fraction of seconds.
     * @return float
     */
    public function _MicroTime(): float
    {
        return microtime(true);
    }

    public function _Empty($object): string
    {
        if (empty($object) === false) {
            return 'false';
        }

        return 'true';
    }

    public function _IsSet($object): string
    {
        if (isset($object) === false) {
            return 'false';
        }

        return 'true';
    }

    /**
     * Wrapper so it is easier to change the places where json is used.
     * @param $data
     * @return string
     */
    public function _JsonEncode(array $data = array()): string
    {
        $options = JSON_PRETTY_PRINT + JSON_PRESERVE_ZERO_FRACTION;
        $row = json_encode($data, $options);

        return $row;
    }

    /**
     * Wrapper so it is easier to change the places where json is used.
     * @param $row string
     * @return string
     */
    public function _JsonDecode(string $row = ''): array
    {
        if (substr($row, 0, 1) !== '{' && substr($row, 0, 1) !== '[') {
            return array();
        }
        $data = json_decode($row, $asArray = true);

        return $data;
    }

    /**
     * Read value from any data collection
     * Name can be 'just_a_name' or 'some/deep/level/data'
     * @param $in
     * @return mixed
     */
    public function _GetData(array $in = array())
    {
        $default = array(
            'name' => '',
            'default' => null,
            'data' => array(),
            'split' => '/'
        );
        $in = $this->_Default($default, $in);

        $names = explode($in['split'], $in['name']);
        $length = count($names);
        $answer = $in['data'];
        for ($i = 0; $i < $length; $i++) {
            if (isset($answer[$names[$i]]) === true) {
                $answer = $answer[$names[$i]];
            } else {
                return $in['default'];
            }
        }

        if (gettype($answer) !== gettype($in['default'])) {
            $answer = $in['default'];
        }

        if (is_array($in['default']) === true && empty($in['default']) === false) {
            $answer = $this->_Default($in['default'], $answer);
        }

        return $answer;
    }

    /**
     * Takes the first found key data from the object and gives it to you, removing it from the object.
     * Used in loops when sending one item at the time in a subcall.
     * @param $in
     * @return array
     */
    public function _Pop(array $in = array()): array
    {
        foreach ($in as $key => $data) {
            unset($in[$key]);
            return array('key'=> $key, 'data'=> $data, 'object'=> $in );
        }

        return array(
            'key'=> '',
            'data'=> '',
            'object'=> array()
        );
    }

    /**
     * Get the class name in this plugin
     * @return string
     */
    public function _GetClassName(): string
    {
        return get_class($this);
    }

    /**
     * Return version date of plugin class, base class, php version, server version
     * @version 2015-09-20
     * @since   2011-09-10
     * @author  Peter Lembke
     * @param array $in
     * @return array
     */
    public function version(array $in = array()): array
    {
        $default = array(
            'date' => '',
            'version' => '',
            'checksum' => '',
            'class_name' => '',
            'note' => '',
            'status' => ''
        );

        $versionPlugin = $this->_Default($default, $this->_Version());
        $versionBase = $this->_Default($default, $this->_VersionBase());

        $serverInfo = array(
            'php_version' => PHP_VERSION,
            'server_version' => $_SERVER["SERVER_SOFTWARE"]
        );

        return array(
            'answer' => 'true',
            'message' => 'Here are the data',
            'plugin' => $versionPlugin,
            'base' => $versionBase,
            'server_info' => $serverInfo,
            'version_code' => md5($versionPlugin['checksum'] . $versionBase['checksum'])
        );
    }

    /**
     * Return names of all methods in this class
     * @version 2013-05-05
     * @since   2012-04-01
     * @author  Peter Lembke
     * @param array $in
     * @return array
     */
    public function function_names(array $in = array()): array
    {
        $answer = array(
            'answer' => 'true',
            'message' => 'All function names in this plugin',
            'data' => get_class_methods($this)
        );

        return $answer;
    }

    /**
     * Dummy function ping that return a pong
     * Useful for getting a pong or for sending messages in a sub call.
     * @version 2020-04-22
     * @since 2020-04-22
     * @author Peter Lembke
     * @param $in
     * @returns {{answer: string, message: string}}
     */
    public function ping(array $in = array()): array
    {
        $answer = array(
            'answer' => 'true',
            'message' => 'pong'
        );

        return $answer;
    }
}
