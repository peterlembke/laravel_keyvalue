# Logic

Standard logic are separate from controllers, commands and repositories.

You can create any folder and put any normal classes in there.
Just remember that you always use an interface and bind that interface to your class in the ServiceProvider.

If you have written functions that do not use random or time then you can easily test them with automated tests since you then know the outcome of a function by its input values.

You can use repositories in your logic classes.
Do not use [Eloquent ORM](https://laravel.com/docs/7.x/eloquent) in a logic class, instead have that code in the repository.
