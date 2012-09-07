<?php
    $startTime = microtime(true);

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
        $startTimeAfterFile = microtime(true);

//        foreach($xml as $key0 => $value){
        for($i = 0; $i < 100000; $i++) {
            if(!isset($xml->user[$i]->email) || !preg_match('/^.+@.+?\.[a-zA-Z]{2,}$/', $xml->user[$i]->email)) {
                echo 'User '.$i.' does not have a valid email: '.$xml->user[$i]->email."\n";

                //do logging;

                //user is not valid, no sense in further validating their shit.
                continue;
            }

            if(!isset($xml->user[$i]->screen_name) || strlen($xml->user[$i]->screen_name) > 18) {
                echo 'User '.$i.' does not have a valid screenname: '.$xml->user[$i]->screen_name."\n";

                //do logging;

                //user is not valid, no sense in further validating their shit.
                continue;
            }

            if(!isset($xml->user[$i]->guid) || $xml->user[$i]->guid == '') {
                echo 'User '.$i.' does not have a valid guid: '.$xml->user[$i]->guid."\n";

                //do logging;

                //user is not valid, no sense in further validating their shit.
                continue;
            }

            if(!isset($xml->user[$i]->display_name) || $xml->user[$i]->display_name == '') {
                echo 'User '.$i.' does not have a valid display_name: '.$xml->user[$i]->display_name."\n";

                //user is technially valid; we can create this data
            }
        }

        $endTime = microtime(true);
        echo $endTime - $startTimeAfterFile;
        echo ' is the elapsed time to validate out '.$i.' users'."\n";
        echo $endTime - $startTime;
        echo ' is the elapsed time to load the file and validate out '.$i.' users';
        exit(0);
    } else {
        echo 'there is no file to read!';
    }