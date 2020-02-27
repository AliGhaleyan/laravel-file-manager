<?php

return [
    "type" => "default",

    "types" => [
        "default" => [
            "provider"                => \AliGhale\FileManager\Types\File::class,
            "path"                    => "default_files/test/",
            "private"                 => false,
            "date_time_prefix"        => true,
            "use_file_name_to_upload" => false,
            "secret"                  => "ashkdsjka#sdkdjfsj22188455$$#$%dsDFsdf",
            "download_link_expire"    => 160, // minutes
        ],

        "image"   => [
            "provider" => \AliGhale\FileManager\Types\Image::class,
            "path"     => "images/upload/documents/",
            "sizes"    => ["16", "24", "32", "64", "128", "320"],
            "thumb"    => "320"
        ],
        "profile" => [
            "parent"           => "image",
            "path"             => "images/upload/profiles/",
            "date_time_prefix" => false,
        ],
    ],
];
