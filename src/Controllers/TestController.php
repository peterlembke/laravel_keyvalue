<?php
/**
 * Copyright (C) 2020  Peter Lembke, CharZam soft
 * the program is distributed under the terms of the GNU General Public License
 *
 * TestController.php is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TestController.php is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TestController.php.	If not, see <https://www.gnu.org/licenses/>.
 */

namespace Charzam\KeyValue\Controllers;

use App\Http\Controllers\Controller;
use Charzam\KeyValue\MyLogic\MyLogicInterface;

/**
 * Class TestController
 * @package Charzam\KeyValue\Controllers
 * The controller does very little. It can view a view, return a value from a repository, run logic classes.
 * If you have if statements and loops in here then be careful.
 * You should not have extra functions in a controller that is logic and should be in a logic class.
 */
class TestController extends Controller
{
    protected $myLogicTest;

    public function __construct(
        MyLogicInterface $test
    )
    {
        $this->myLogicTest = $test;
    }

    /**
     * Example controller action
     * URL: http://aktivbo-api.aktivbo.dev.local/charzam/keyvalue
     */
    public function index()
    {
        return response('Hello', 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Another controller action
     * URL: http://aktivbo-api.aktivbo.dev.local/charzam/keyvalue/read/mykey
     * @param string $key
     */
    public function read(string $key = '')
    {
        $title = $this->myLogicTest->getTitle();
        $out = [
            'key' => $key,
            'title' => $title
        ];
        $outJson = json_encode($out);

        $response = response($outJson, 200)
            ->header('Content-Type', 'application/json');

        return $response;
    }

    /**
     * And a third controller action
     * URL: http://aktivbo-api.aktivbo.dev.local/charzam/keyvalue/write/mykey/mydata
     * @param string $key
     * @param string $value
     */
    public function write(string $key = '', string $value = '')
    {
        $this->myLogicTest->setTitle($value);

        $out = [
            'key' => $key,
            'value' => $value
        ];
        $outJson = json_encode($out);

        $response = response($outJson, 200)
            ->header('Content-Type', 'application/json');

        return $response;
    }
}
