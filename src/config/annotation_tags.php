<?php

return [

    /*
    | Path to the Python executable.
    */
    'python' => '/usr/bin/python3',

    /*
    | Path to the script.
    */
    'script' => __DIR__.'/../resources/scripts/test.py',

    /*
    | Specifies which queue should be used for which job.
    */
    'annotation_tags_queue' => env('ANNOTATION_TAGS_QUEUE', 'high'),

    'tag_report_storage_disk' => env('TAG_REPORT_STORAGE_DISK', 'tag_report'),

    'tag_report_tmp_storage_disk' => env('TAG_REPORT_TMP_STORAGE_DISK', 'tag_report_tmp'),
];
