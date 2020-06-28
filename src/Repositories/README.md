# Repositories

A repository exchange data with another system.

A repository is a layer between your logic and your storage.
A repository is just a normal PHP class and interface.

In your repository you can use [Eloquent ORM](https://laravel.com/docs/7.x/eloquent) to connect to a database or use HTTP to an external API to get/put data.

## Usage
A repository is just a normal class with an interface.
The logic classes are the same thing.

You can use a repository from another package in a so called dependency injection. Then you reference the interface class in your constructor and you will get the class that are bound to the interface.

This feature makes it simple to bind another implementation to the repository interface.
