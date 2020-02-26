## Laravel File Manager

### Installation:
```
composer require alighale/laravel-file-manager
```

You must add the service provider to `config/app.php`
``` php
'providers' => [
	 // for laravel 5.8 and below
	 \Serjik\FileManager\FileManagerServiceProvider::class,
];
```

**Publish your config file and migrations**

```
php artisan vendor:publish
```
<hr>

### Config:
> config/filemanager.php
``` php
return [  
  "type" => "default",  
  
  "types" => [  
	  "default" => [  
		  "provider" => \Serjik\FileManager\Types\File::class,  
		  "path" => "default_files/test/",  
		  "private" => false,  
		  "date_time_prefix" => true,  
		  "use_file_name_to_upload" => false,  
		  "secret" => "ashkdsjka#sdkdjfsj22188455$$#$%dsDFsdf",  
		  "download_link_expire" => 160, // minutes  
	  ],
//	we add another types    
//        "image"   => [  
//            //  
//        ],  
  ],  
];
```

### Config Parameters


| name          | type         | description               |
|---------------|--------------|---------------------------|
| provider      | `string (class name)`| provider class name, must be extended of `Serjik\FileManager\BaseType`                           |
|path           | `string`     | file upload path          |
|private        | `boolean`    | is private or no if is `true` so upload file in storage folder else if is `false` so upload file in public folder |
|date_time_prefix|`boolean`    | if is `true` so upload file with `/{year}/{month}/{day}` prefix|
|use_file_name_to_upload| `boolean`| if is `true` we use of the file original name else we generate a random name|
|secret         |`string`      | secret key for generate download link and download file|
|download_link_expire|`boolean`|generated download link expire time|



## Lets start to use:

#### Upload a file:
```php
$file = request()->file('filename');
$upload = File::upload($file);

//	get file uploaded path
$filePath = $upload->getFilePath();

//	get file name  
$fileName = $upload->getName();
```

#### You can use of this methods:

| method                       		   |description              					 |
|--------------------------------------|---------------------------------------------|
| `useFileNameToUpload($status = true)`|if is `true` we use of the file original name else we generate a random name|
|`getFile($name = null)`       		   |get file by name and return a `\Serjik\FileManager\Models\File`|
| `setPath($path)`                     |set file upload path                	     |
| `getUploadPath()`                    |get upload path                	        	 |
| `dateTimePrefix($value = true)`      |if is `true` so upload file with `/{year}/{month}/{day}` prefix|
| `setName(string $name)`              |set file name                	        				 |
| `setFormat(string $format)`          |set format for file upload                	 |
| `isPrivate()`                        | if you call this so upload file in storage folder and your you don't have permission to access this file|
| `isPublic()`                         |if you call this so upload file in public folder and has access to this file|


### Examples:
```php
$file = request()->file('filename');  
$upload = File::setName('your specific name')  
	 ->isPrivate()  
	 ->setFormat('png')  
	 ->dateTimePrefix()  
	 ->upload($file);
//	get file uploaded path => if is public you can use it for download
dd($upload->getFilePath());
```
```php
$file = File::getFile("file uploaded name");  
$file->name;  
$file->path;  
$file->type; // config file selected type  
$file->isPrivate;  
$file->isPublic;  
$file->generateLink();  
  
// return response download  
// $file->download();
```
