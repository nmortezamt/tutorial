<?php

return [
    'MediaTypeServices'=>[
        'image'=>[
            'extensions'=> [
                'png','jpeg','jpg'
            ],
            'handler' => Tutorial\Media\Services\ImageFileService::class
        ],
        'video'=>[
            'extensions'=> [
                'avi','mp4','mkv'
            ],
            'handler' => Tutorial\Media\Services\VideoFileService::class
        ],
        'zip' => [
            'extensions'=> [
                'zip','rar','tar'
            ],
            'handler' => Tutorial\Media\Services\ZipFileService::class
        ],
    ]
];

