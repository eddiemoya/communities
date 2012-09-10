<?php
    $startTime = microtime(true);

    echo 'Started user import at '.date('h:i:s M d, Y').'...'."\n";

    $base = '/Users/dasfisch/cron_results/';

    $errorFile = $base.'all_errors.txt';
    $nextDataSetFile = $base.'current_batch.txt';
    $insertErrors = $base.'insert_errors.txt';

    $readFile = $base.'user_export.xml';

    $errorFileHandle = fopen($errorFile, 'a+');
    $readableErrorFileHandle = fopen($readableErrorFile, 'a+');

    if(file_exists($nextDataSetFile)) {
        $batchHandle = fopen($nextDataSetFile, 'a+');

        $nextDataSet = fread($batchHandle, 1024);

        $nextDataSet = (int)trim($nextDataSet);

        ftruncate($batchHandle, 0);
    } else {
        $batchHandle = fopen($nextDataSetFile, 'w+');

        $nextDataSet = 0;
    }

    if(file_exists($errorFile)) {
        $errors = file_get_contents($errorFile);

        $errors = explode(',', $errors);
    }

    $finalPosition = ($nextDataSet >= 0) ? $nextDataSet + 10000 : 10000;

    echo 'The next batch set starting point is array spot '.$nextDataSet."\n";

    if(file_exists($readFile)) {
        echo 'Starting file load at '.date('h:i:s M d, Y').'...'."\n";

        $xml = simplexml_load_file($readFile);

        echo 'Finished file load at '.date('h:i:s M d, Y').'...'."\n";
        echo 'Starting user inserts at '.date('h:i:s M d, Y').'...'."\n";

        $startTimeAfterFile = microtime(true);

        $location = '/Users/dasfisch/communities/wordpress';

        require_once($location.'/wp-load.php');

        for($i = $nextDataSet; $i < $finalPosition; $i++) {
            $ourError = '';

            if(in_array($i, $errors)) {
                $ourError = 'ERROR:'."\n";
                $ourError .= 'Recorded at '.date('h:i:s M d, Y', strtotime('now'))."\n";
                $ourError .= 'Skipping over invalid user at '.$i."\n";
                $ourError .= "\n";

                echo $ourError;

                //do logging;
                fwrite($readableErrorFileHandle, $ourError);
                fwrite($errorFileHandle, $i.',');

                continue;
            }

//            echo '<pre>';
//            var_dump($xml->user[0]);
//            exit;

            $user_id = wp_insert_user(array(
                        'user_pass'		=> get_random_string('abcdefghijklmnopqrstuvwxyz0123456789', 10),
                        'user_email'	=> $xml->user[0]->email,
                        'user_login'	=> $xml->user[0]->screen_name,
                        'user_nicename'	=> $xml->user[0]->screen_name,
                        'display_name'	=> $xml->user[0]->screen_name,
                        'first_name'	=> $xml->user[0]->first_name,
                        'last_name'	    => $xml->user[0]->last_name,
                        'user_role'		=> 'subscriber')
                    );

            echo '<pre>';
            var_dump($user_id);
            exit;
        }

        echo 'Finished user inserts at '.date('h:i:s M d, Y').'...'."\n";

        $nextDataSet += 10000;
        
        fwrite($batchHandle, $nextDataSet);

        $endTime = microtime(true);
        echo $endTime - $startTimeAfterFile;
        echo ' is the elapsed time to insert users out 10000 users'."\n";
        echo $endTime - $startTime;
        echo ' is the elapsed time to load the file and insert 10000 users';
        exit(0);
    } else {
        echo 'there is no file to read!';
    }

    function get_random_string($valid_chars, $length)
    {
        // start with an empty random string
        $random_string = "";

        // count the number of chars in the valid chars string so we know how many choices we have
        $num_valid_chars = strlen($valid_chars);

        // repeat the steps until we've created a string of the right length
        for ($i = 0; $i < $length; $i++)
        {
            // pick a random number from 1 up to the number of valid chars
            $random_pick = mt_rand(1, $num_valid_chars);

            // take the random character out of the string of valid chars
            // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
            $random_char = $valid_chars[$random_pick-1];

            // add the randomly-chosen char onto the end of our string so far
            $random_string .= $random_char;
        }

        // return our finished random string
        return $random_string;
    }