<?php

return [
    'default_disk' => 'local',

    'ffmpeg' => [
        //'binaries' => env('FFMPEG_BINARIES', 'ffmpeg'),
        'binaries' => '/usr/bin/ffmpeg',
        'threads' => 12,
    ],

    'ffprobe' => [
       // 'binaries' => env('FFPROBE_BINARIES', 'ffprobe'),
        'binaries' => '/usr/bin/ffprobe',
    ],

    'timeout' => 3600,
];
