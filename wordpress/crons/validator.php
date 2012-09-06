<?php
    $base = '/Users/dasfisch/cron_results/';

    $badDataFile = $base.'bad_data.xml';
    $errorFile = $base.'all_errors.txt';
    $nextDataSetFile = $base.'current_batch.txt';
    $readableErrorFile = $base.'our_errors.txt';

    $readFile = $base.'user_export.xml';

    if(file_exists($nextDataSetFile)) {
        $batchHandle = fopen($nextDataSetFile, 'r+');

        $nextDataSet = fread($batchHandle, 1024);

        $nextDataSet = (int)trim($nextDataSet);
    } else {
        $nextDataSet = 0;
    }

    if(file_exists($readFile)) {
        $xml = simplexml_load_file($readFile);
    } else {
        echo 'there is no file to read!';
    }