# Controllers

You can browser to Laravel with routes.
See routes/web.php

A route can be connected to a controller.
A controller can have one or more controller actions (public functions)

Documentation: [Controller](https://laravel.com/docs/7.x/controllers)

Please use Controllers __instead__ of using a closure in the routes/web.php class.

Do not put logic in controllers. You can show a View, call a logic class, use a repository.

If you put logic in separate classes then those classes can be reused, overridden, tested. If you put logic in a controller then it is stuck there.

When you use if-statements and loops in a controller action then be careful so you do not add important logic.

If you want to use an extra function then avoid that because that is logic.
