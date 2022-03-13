# PHP Zipper
[![GitHub issues](https://img.shields.io/github/issues/waithawoo/phpzipper)](https://github.com/waithawoo/phpzipper/issues)
[![GitHub stars](https://img.shields.io/github/stars/waithawoo/phpzipper)](https://github.com/waithawoo/phpzipper/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/waithawoo/phpzipper)](https://github.com/waithawoo/phpzipper/network)
[![GitHub license](https://img.shields.io/github/license/waithawoo/phpzipper)](https://github.com/waithawoo/phpzipper/blob/main/LICENSE)

## To zip/unzip files/folders easily

Easy to use PHP's native ZipArchive class
 - **[Installation](#installation)**
 - **[Usage](#usage)**

## Installation

Install this package via [Composer](https://getcomposer.org/).

```
composer require waithaw/phpzipper
```

## Usage

### Create Zip object

```php
use WaiThaw\PhpZipper\Zip;

$zip = new Zip();
```
### Creating a Zip file from Single file or multiple files with no password
```
You can create an archive zip file from single file or multiple files.

1st parameter – output zip path
2nd parameter – a file or files to be zipped
3rd parameter – setting a password

e.g. 
$filelists= [
    'D:\testfolder\test1.txt',
    'D:\testfolder\test1folder\test2.txt'
];

$file = 'D:\testfolder\test1.txt';
```
```php
$zip->createFromFiles('backup.zip', $file);

//OR

$zip->createFromFiles('backup.zip', $filelists);
```
### Creating a Zip file from Single file or multiple files with password
```php
$zip->createFromFiles('backup.zip', $file ,'password');

//OR

$zip->createFromFiles('backup.zip', $filelists,'password');
```
### Creating a Zip file from a directory including sub directories.
```php
// Without a password
$zip->createFromDir('backup.zip','D:\testfolder');

// With a password
$zip->createFromDir('backup.zip','D:\testfolder','password');
```
### Extracting a simple or password-protected zip file
```php
// Extracting a simple zip file.
$zip->extractTo('backup.zip','D:\outputpath');

//Extracting a password-protected zip file
$zip->extractTo('backup.zip','D:\outputpath', 'password');
```

### Downloading zip files

```php
// You can download the zip file at once archiving.
$zip->createFromFiles('backup.zip', $file)->download();

$zip>createFromDir('backup.zip','D:\testfolder')->download();

// And you can also delete the zip file after downloaded, by passing ‘delete’ string in download() method.

$zip->createFromFiles('backup.zip', $file)->download('delete');

$zip>createFromDir('backup.zip','D:\testfolder')->download('delete');
```
## Security

If you discover any security related issues, please email them to [waithawoocw@gmail.com](mailto:waithawoocw@gmail.com) instead of using the issue tracker.

## License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.
