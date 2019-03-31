# Simple File Search
This app simply finds files by searching their content.
# Instalation
*Empty*
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
    ->contain(#sentence I am looking for#)
    ->contain(#^[a-z]+$#)
    ->contain(['#[A-Z]+#', '#some text I know#'])
    ->find();

/** @var \SplFileInfo $item */
foreach ($result as $item) {
    var_dump($item->getRealPath());
}
```
Restrict the search to some extensions with `SimpleFileSearch::extension()`.
```php
$result = $simpleFileSearch->contain(#[a-z]+#)->extension('txt')->find();
```
Restrict the recursion depth of the search.
```php
$result = $simpleFileSearch->contain(#[a-z]+#)->depth(3, 10)->find();
```
Specify directories, part of the base directory, for the search to be
performed on or skipped.
```php
$result = $simpleFileSearch
    ->contain(#[a-z]+#)
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
container port is set to be 127.0.0.1:8080 in docker-compose.yaml file.
If that port is already in use please change the port to something else
that it's free.
```bash
docker-compose up
```
## Console Demo
SSH into the container and run the console application.
```bash
docker exec -it simple-file-search-web bash
# Run console
/var/www/html/bin/console search "your sentence"
# See the help for more options and usages
/var/www/html/bin/console search -h
```
## UI Demo
See the demo here [127.0.0.1:8080](http://127.0.0.1:8080/)
 
