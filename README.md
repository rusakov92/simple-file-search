# Simple File Search
This app simply finds files by searching their content.
# Instalation
Install via composer.
```bash
composer require rusakov92/simple-file-search
```
# Usage
You have to first create a new instance of the class and specify the base
directory path of the folder you want to scan files for.
```php
$simpleFileSearch = new SimpleFileSearch('/path/to/base/directory');
```
You can use the class to find a files the contain a specific string. To do
that you can use regular expresion with *#* delimiter and call the
`SimpleFileSearch::find()` method in the end. The returned result will be an
`\Iterator` that you can iterate over.
```php
/** @var \Iterator $result */
$result = $simpleFileSearch
    ->contain('#sentence I am looking for#')
    ->contain('#^[a-z]+$#')
    ->contain(['#[A-Z]+#', '#some text I know#'])
    ->find();

/** @var \SplFileInfo $item */
foreach ($result as $item) {
    var_dump($item->getRealPath());
}
```
Restrict the search to some extensions with `SimpleFileSearch::extension()`.
```php
$result = $simpleFileSearch->contain('#[a-z]+#')->extension('txt')->find();
```
Restrict the recursion depth of the search.
```php
$result = $simpleFileSearch->contain('#[a-z]+#')->depth(3, 10)->find();
```
Specify directories, part of the base directory, for the search to be
performed on or skipped.
```php
$result = $simpleFileSearch
    ->contain('#[a-z]+#')
    ->in('path/to/specific/dir')
    ->skip('path/to/specific/dir/skip')
    ->find();
```
# Demo
To see the demo please first install [docker](https://www.docker.com/) on
your machine, once you are done please follow the guide below:

Clone this repository in a preferred location, now we need to build our
docker image by changing the directory into the cloned repository and then
running the docker build command to build an image.
```bash
cd /path/to/simple-file-search
docker build -t simple-file-search .
```
Now we need to start our container using docker-compose. Note that the
container port is set to be `127.0.0.1:8080` in `docker-compose.yaml` file.
If that port is already in use please change the port to something else
that it's free.
```bash
docker-compose up
```
SSH into the container and run the `composer install` command in the
`symfony_demo` folder.
```bash
docker exec -it simple-file-search-web bash
cd symfony_demo
composer install
# When asked enter the default values for the parameters.yml file
```
Now you can try out the demo. The test files are located in
`public/demo_files` and you can use regular expresion or simple string to
find files.
#### Console Demo
Run the console application.
```bash
# Run console
bin/console search "your sentence"
# Run the console with a regular expresion
bin/console search "#[a-z]#"
# See the help for more options and usages
bin/console search -h
```
#### UI Demo
See the demo here [127.0.0.1:8080](http://127.0.0.1:8080/). The UI form
accepts one or multiple string and they can be separated by comma.
#### Tests
You can run the PHPUnit tests by simple going back to the root dir and
then run the composer script.
```bash
cd ..
composer run phpunit
```
To run Codeception tests:
```bash
composer run codecept
```
