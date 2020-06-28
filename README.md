# Key Value manager
Handles key-value data for moderate amount of data.
If your application need a key-value resource then call this package.

This is an example package that aim to show all basic features in Laravel.

## Install

Install from the github repo
```
dox composer require peterlembke/laravel_keyvalue
```

## Quick info
- Name: peterlembke/laravel_keyvalue
- Status: Generic (Custom or Generic)
- Note: Handles key-value for moderate amount of data
- Laravel version: 7.15.0
- Date: 2020-06-28

## Details
This is a small generic package to Laravel 7.x that you can call from your module.
This package create tables with prefix `charzam_keyvalue_`.

You can
* create/delete a table.
* read, read_many, read_pattern key to get values
* write, write_many, write_pattern key to put values

Can be used for
* Configuration
* Translation
* User
* Right
* Customer

Most data can be stored in key-value databases.
Anything you can retreive with a key or a key*

## Test

http://aktivbo-api.aktivbo.dev.local/charzam/keyvalue

http://aktivbo-api.aktivbo.dev.local/charzam/keyvalue/write/mykey/mydata

http://aktivbo-api.aktivbo.dev.local/charzam/keyvalue/read/mykey

## How it works



## Usage in your package

## Tech used

### Repository pattern
Read about Laravel [Repository pattern](https://www.larashout.com/how-to-use-repository-pattern-in-laravel).

This stores/reads/modifies the database.
Some say that a repository only reads data. Some says it also saves data. Magento 2 both save and load in the repository.
Point of a repository is to add an abstraction layer to get and put data without revealing underlying technologies.
The repository is also great to mock when you write automated tests on your business logic.
