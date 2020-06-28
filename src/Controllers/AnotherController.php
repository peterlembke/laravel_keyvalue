<?php
/**
 * Copyright (C) 2020  Peter Lembke, CharZam soft
 * the program is distributed under the terms of the GNU General Public License
 *
 * AnotherController.php is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * AnotherController.php is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AnotherController.php.	If not, see <https://www.gnu.org/licenses/>.
 */

namespace PeterLembke\KeyValue\Controllers;

use App\Http\Controllers\Controller;

class AnotherController extends Controller
{
    /**
     * Example controller action
     * URL: http://aktivbo-api.aktivbo.dev.local/foobar
     */
    public function index()
    {
        return response('Hello World', 200)
            ->header('Content-Type', 'text/plain');
    }
}
