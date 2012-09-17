<?php
    $startTime = microtime(true);

    echo 'Started validation at '.date('h:i:s M d, Y').'...'."\n\n";

    $base = '/Users/dasfisch/cron_results/';
    $batchBase = '/Users/dasfisch/cron_results/batches/';

    $badDataFile = $base.'bad_data.xml'; // List of xml elements that have failed
    $errorFile = $base.'all_errors.txt';
    $nextDataSetFile = $base.'current_batch.txt';
    $readableErrorFile = $base.'our_errors.txt';

    $readFile = $base.'user_export.xml';

    $errorFileHandle = fopen($errorFile, 'a+');

    if(file_exists($nextDataSetFile)) {
        $batchHandle = fopen($nextDataSetFile, 'a+');

        $nextDataSet = fread($batchHandle, 1024);

        $nextDataSet = (int)trim($nextDataSet);

        ftruncate($batchHandle, 0);
    } else {
        $nextDataSet = 0;

        $batchHandle = fopen($nextDataSetFile, 'w+');
    }

    $finalPosition = ($nextDataSet >= 0) ? $nextDataSet + 10000 : 10000;

    $readableErrorFileHandle = fopen($batchBase.'errors/'.$nextDataSet.'-'.$finalPosition.'.error.xml', 'a+');
    $goodDataFile = $batchBase.$nextDataSet.'-'.$finalPosition.'.batch.xml';

    echo 'The next batch set starting point is array spot '.$nextDataSet."\n\n";

    if(file_exists($readFile)) {
        echo 'Starting file load at '.date('h:i:s M d, Y').'...'."\n";

        $xml = simplexml_load_file($readFile);

        echo 'Finished file load at '.date('h:i:s M d, Y').'!'."\n\n";
        echo 'Starting validation at '.date('h:i:s M d, Y').'...'."\n";

        $startTimeAfterFile = microtime(true);

        $badData = array();
        $goodData = array();

        echo count($xml->user).' is the total users<br/>'."\n";

        for($i = 0; $i < 10000; $i++) {
            $ourError = '';

            if(!isset($xml->user[$i]->email) || !preg_match('/^.+@.+?\.[a-zA-Z]{2,}$/', $xml->user[$i]->email)) {
                $xml->user[$i]->posInArray = $i;

                $badData[] = $xml->user[$i];

                $ourError .= 'ERROR:'."\n";
                $ourError .= 'Recorded at '.date('h:i:s M d, Y', strtotime('now'))."\n";
                $ourError .= 'User '.$i.' does not have a valid email: '.$xml->user[$i]->email."\n";
                $ourError .= "\n";

                echo $ourError;

                //do logging;
                fwrite($readableErrorFileHandle, $ourError);
                fwrite($errorFileHandle, $i.',');

                //user is not valid, no sense in further validating their shit.
                continue;
            }

            if(!isset($xml->user[$i]->screen_name) || strlen($xml->user[$i]->screen_name) > 18) {
                $xml->user[$i]->posInArray = $i;

                $badData[] = $xml->user[$i];

                $ourError .= 'SCREEN NAME ERROR:'."\n";
                $ourError .= 'Recorded at '.date('h:i:s M d, Y', strtotime('now'))."\n";
                $ourError .= 'User '.$i.' does not have a valid screenname: '.$xml->user[$i]->screen_name."\n";
                $ourError .= "\n";

                echo $ourError;

                //do logging;
                fwrite($readableErrorFileHandle, $ourError);
                fwrite($errorFileHandle, $i.',');

                //user is not valid, no sense in further validating their shit.
                continue;
            }

            if(!isset($xml->user[$i]->guid) || $xml->user[$i]->guid == '') {
                $xml->user[$i]->posInArray = $i;

                $badData[] = $xml->user[$i];

                $ourError .= 'GUID ERROR:'."\n";
                $ourError .= 'Recorded at '.date('h:i:s M d, Y', strtotime('now'))."\n";
                $ourError .= 'User '.$i.' does not have a valid guid: '.$xml->user[$i]->guid."\n";
                $ourError .= "\n";

                echo $ourError;

                //do logging;
                fwrite($readableErrorFileHandle, $ourError);
                fwrite($errorFileHandle, $i.',');

                //user is not valid, no sense in further validating their shit.
                continue;
            }

            if(!isset($xml->user[$i]->display_name) || $xml->user[$i]->display_name == '') {
                $xml->user[$i]->posInArray = $i;

                $badData[] = $xml->user[$i];

                $ourError .= 'DISPLAY NAME ERROR:'."\n";
                $ourError .= 'Recorded at '.date('h:i:s M d, Y', strtotime('now'))."\n";
                $ourError .= 'User '.$i.' does not have a valid display_name: '.$xml->user[$i]->display_name."\n";
                $ourError .= "\n";

                echo $ourError;

                fwrite($readableErrorFileHandle, $ourError);
                fwrite($errorFileHandle, $i.',');

                //user is technially valid; we can create this data
            }

            //XML is valid; insert it into insert_bash
            $goodData[] = $xml->user[$i];
        }

        echo 'Finished validation at '.date('h:i:s M d, Y').'!'."\n\n";

        $nextDataSet += 10000;

        fwrite($batchHandle, $nextDataSet);

        echo 'Starting writing the bad data XML '.date('h:i:s M d, Y').'...'."\n";

        if(isset($badData) && !empty($badData)) {
            // Create the XML with all data issues
            if(file_exists($badDataFile)) {
                $badDataXml = new DOMDocument();
                $badDataXml->loadXML(file_get_contents($badDataFile));

                $badDataXml->formatOutput = true;

                $usersRoot = $badDataXml->documentElement;
            } else {
                $badDataXml = new DOMDocument("1.0");

                $badDataXml->formatOutput = true;

                $usersRoot = $badDataXml->createElement("users");
                $badDataXml->appendChild($usersRoot);
            }

            foreach($badData as $key=>$val) {
                $user = $badDataXml->createElement('user');

                $displayNameNode = $badDataXml->createElement('pos_in_array', htmlentities($val->posInArray));
                $user->appendChild($displayNameNode);

                $displayNameNode = $badDataXml->createElement('screen_name', htmlentities($val->screen_name));
                $user->appendChild($displayNameNode);

                $emailNode = $badDataXml->createElement('email', htmlentities($val->id));
                $user->appendChild($emailNode);

                $firstNameNode = $badDataXml->createElement('first_name', htmlentities($val->first_name));
                $user->appendChild($firstNameNode);

                $guidNode = $badDataXml->createElement('guid', htmlentities($val->guid));
                $user->appendChild($guidNode);

                $lastNameNode = $badDataXml->createElement('last_name', htmlentities($val->last_name));
                $user->appendChild($lastNameNode);

                $screenNameNode = $badDataXml->createElement('screen_name', htmlentities($val->screen_name));
                $user->appendChild($screenNameNode);

                $screenNameNode = $badDataXml->createElement('location', htmlentities($val->location));
                $user->appendChild($screenNameNode);

                $screenNameNode = $badDataXml->createElement('zipcode', htmlentities($val->zipcode));
                $user->appendChild($screenNameNode);

                $usersRoot->appendChild($user);
            }

            $badDataXml->save($badDataFile);
        }

        echo 'Finished writing the bad data XML '.date('h:i:s M d, Y').'...'."\n\n";
        echo 'Starting writing the good data XML '.$goodDataFile.' '.date('h:i:s M d, Y').'!'."\n";

        if(isset($goodData) && !empty($goodData)) {
            // Create the XML with all data issues
            $goodDataXml = new DOMDocument('1.0', 'utf-8');

            $goodDataXml->formatOutput = true;

            $usersRoot = $goodDataXml->createElement("users");
            $goodDataXml->appendChild($usersRoot);

            foreach($goodData as $key=>$val) {
                $user = $goodDataXml->createElement('user');

                $displayNameNode = $goodDataXml->createElement('pos_in_array', htmlentities($val->posInArray));
                $user->appendChild($displayNameNode);

                $displayNameNode = $goodDataXml->createElement('screen_name', htmlentities($val->screen_name));
                $user->appendChild($displayNameNode);

                $emailNode = $goodDataXml->createElement('email', htmlentities($val->id));
                $user->appendChild($emailNode);

                $firstNameNode = $goodDataXml->createElement('first_name', htmlentities($val->first_name));
                $user->appendChild($firstNameNode);

                $guidNode = $goodDataXml->createElement('guid', htmlentities($val->guid));
                $user->appendChild($guidNode);

                $lastNameNode = $goodDataXml->createElement('last_name', htmlentities($val->last_name));
                $user->appendChild($lastNameNode);

                $screenNameNode = $goodDataXml->createElement('screen_name', htmlentities($val->screen_name));
                $user->appendChild($screenNameNode);

                $screenNameNode = $goodDataXml->createElement('location', htmlentities($val->location));
                $user->appendChild($screenNameNode);

                $screenNameNode = $goodDataXml->createElement('zipcode', htmlentities($val->zipcode));
                $user->appendChild($screenNameNode);

                $usersRoot->appendChild($user);
            }

            $goodDataXml->save($goodDataFile);
        }

        echo 'Finished writing the good data XML '.$goodDataFile.' '.date('h:i:s M d, Y').'!'."\n\n";

        echo (count($xml->user) - 10000).' users remain to be validate'."\n";
        echo 'Starting '.$readFile.' trimming at '.date('h:i:s M d, Y').'...'."\n";

        for($j = 0; $j < 10000; $j+=1) {
            unset($xml->user[0]);
        }

        $xml->asXML($readFile);

        echo 'Finished '.$readFile.' trimming at '.date('h:i:s M d, Y').'!'."\n\n";

        $endTime = microtime(true);
        echo $endTime - $startTimeAfterFile;
        echo ' is the elapsed time to validate out 10000 users'."\n";
        echo $endTime - $startTime;
        echo ' is the elapsed time to load the file and validate out 10000 users';
        exit(0);
    } else {
        echo 'there is no file to read!';
    }