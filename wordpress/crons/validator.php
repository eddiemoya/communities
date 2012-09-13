<?php
    $startTime = microtime(true);

    echo 'Started validation at '.date('h:i:s M d, Y').'...'."\n";

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
        $i = 0;

        echo 'Starting file load at '.date('h:i:s M d, Y').'...'."\n";

        $dom = new DomDocument();

        $badDataXml->formatOutput = true;

        $dom->load($readFile);

        $users = $dom->getElementsByTagName('user');

        echo 'Finished file load at '.date('h:i:s M d, Y').'!'."\n\n";
        echo $users->length.' is the total users'."\n\n";
        echo 'Starting validation at '.date('h:i:s M d, Y').'...'."\n\n";

        $startTimeAfterFile = microtime(true);

        $badData = array();
        $goodData = array();

        foreach($users as $user) {
            if($i == 9999) {
                break;
            }

            $ourError = '';

            if(!isset($user->getElementsByTagName('email')->item(0)->nodeValue) || !preg_match('/^.+@.+?\.[a-zA-Z]{2,}$/', $user->getElementsByTagName('email')->item(0)->nodeValue)) {
                $posInArrayNode = $dom->createElement('posInArray', $i);
                $user->appendChild($posInArrayNode);

                $badData[] = $user;

                $ourError .= 'ERROR:'."\n";
                $ourError .= 'Recorded at '.date('h:i:s M d, Y', strtotime('now'))."\n";
                $ourError .= 'User '.$i.' does not have a valid email: '.$user->getElementsByTagName('email')->item(0)->nodeValue."\n";
                $ourError .= "\n";

                echo $ourError;

                //do logging;
                fwrite($readableErrorFileHandle, $ourError);
                fwrite($errorFileHandle, $i.',');

                //user is not valid, no sense in further validating their shit.
                continue;
            }

            if(!isset($user->getElementsByTagName('screen_name')->item(0)->nodeValue) || strlen($user->getElementsByTagName('screen_name')->item(0)->nodeValue) > 18) {
                $posInArrayNode = $dom->createElement('posInArray', $i);
                $user->appendChild($posInArrayNode);

                $badData[] = $xml->user[$i];

                $ourError .= 'SCREEN NAME ERROR:'."\n";
                $ourError .= 'Recorded at '.date('h:i:s M d, Y', strtotime('now'))."\n";
                $ourError .= 'User '.$i.' does not have a valid screenname: '.$user->getElementsByTagName('screen_name')->item(0)->nodeValue."\n";
                $ourError .= "\n";

                echo $ourError;

                //do logging;
                fwrite($readableErrorFileHandle, $ourError);
                fwrite($errorFileHandle, $i.',');

                //user is not valid, no sense in further validating their shit.
                continue;
            }

            if(!isset($user->getElementsByTagName('guid')->item(0)->nodeValue) || $user->getElementsByTagName('guid')->item(0)->nodeValue == '') {
                $posInArrayNode = $dom->createElement('posInArray', $i);
                $user->appendChild($posInArrayNode);

                $badData[] = $xml->user[$i];

                $ourError .= 'GUID ERROR:'."\n";
                $ourError .= 'Recorded at '.date('h:i:s M d, Y', strtotime('now'))."\n";
                $ourError .= 'User '.$i.' does not have a valid guid: '.$user->getElementsByTagName('guid')->item(0)->nodeValue."\n";
                $ourError .= "\n";

                echo $ourError;

                //do logging;
                fwrite($readableErrorFileHandle, $ourError);
                fwrite($errorFileHandle, $i.',');

                //user is not valid, no sense in further validating their shit.
                continue;
            }

            if(!isset($user->getElementsByTagName('display_name')->item(0)->nodeValue) || $user->getElementsByTagName('display_name')->item(0)->nodeValue == '') {
                $posInArrayNode = $dom->createElement('posInArray', $i);
                $user->appendChild($posInArrayNode);

                $badData[] = $xml->user[$i];

                $ourError .= 'DISPLAY NAME ERROR:'."\n";
                $ourError .= 'Recorded at '.date('h:i:s M d, Y', strtotime('now'))."\n";
                $ourError .= 'User '.$i.' does not have a valid display_name: '.$user->getElementsByTagName('display_name')->item(0)->nodeValue."\n";
                $ourError .= "\n";

                echo $ourError;

                fwrite($readableErrorFileHandle, $ourError);
                fwrite($errorFileHandle, $i.',');

                //user is technially valid; we can create this data
            }

            //XML is valid; insert it into insert_bash
            $goodData[] = $user;

            $i++;
        }

        echo 'Finished validation at '.date('h:i:s M d, Y').'!'."\n\n";

        $nextDataSet += 10000;

        fwrite($batchHandle, $nextDataSet);

        echo 'There are '.count($badData).' users that are invalid'."\n";

        echo 'Starting writing the bad data XML '.date('h:i:s M d, Y').'...'."\n";

        if(isset($badData) && !empty($badData)) {
            // Create the XML with all data issues
            if(file_exists($badDataFile)) {
                $badDataXml = new DOMDocument($badDataFile, LIBXML_NOBLANKS);

                $badDataXml->formatOutput = true;
            } else {
                $badDataXml = new DOMDocument("1.0");

                $badDataXml->formatOutput = true;

                $usersRoot = $badDataXml->createElement("users");
                $badDataXml->appendChild($usersRoot);
            }

            foreach($badData as $key=>$val) {
                $email = $val->getElementsByTagName('email')->item(0)->nodeValue;
                $firstName = $val->getElementsByTagName('first_name')->item(0)->nodeValue;
                $guid = $val->getElementsByTagName('guid')->item(0)->nodeValue;
                $lastName = $val->getElementsByTagName('last_name')->item(0)->nodeValue;
                $screenName = $val->getElementsByTagName('screen_name')->item(0)->nodeValue;

                $user = $badDataXml->createElement('user');

                $displayNameNode = $badDataXml->createElement('screen_name', $screenName);
                $user->appendChild($displayNameNode);

                $emailNode = $badDataXml->createElement('email', $email);
                $user->appendChild($emailNode);

                $firstNameNode = $badDataXml->createElement('first_name', $firstName);
                $user->appendChild($firstNameNode);

                $guidNode = $badDataXml->createElement('guid', $guid);
                $user->appendChild($guidNode);

                $lastNameNode = $badDataXml->createElement('last_name', $lastName);
                $user->appendChild($lastNameNode);

                $screenNameNode = $badDataXml->createElement('screen_name', $screenName);
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

            echo 'There are '.count($goodData).' users that are valid'."\n";

            foreach($goodData as $key=>$val) {
                $email = $val->getElementsByTagName('email')->item(0)->nodeValue;
                $firstName = $val->getElementsByTagName('first_name')->item(0)->nodeValue;
                $guid = $val->getElementsByTagName('guid')->item(0)->nodeValue;
                $lastName = $val->getElementsByTagName('last_name')->item(0)->nodeValue;
                $screenName = $val->getElementsByTagName('screen_name')->item(0)->nodeValue;

                $user = $goodDataXml->createElement('user');

                $displayNameNode = $goodDataXml->createElement('screen_name', htmlentities($screenName));
                $user->appendChild($displayNameNode);

                $emailNode = $goodDataXml->createElement('email', htmlentities($email));
                $user->appendChild($emailNode);

                $firstNameNode = $goodDataXml->createElement('first_name', htmlentities($firstName));
                $user->appendChild($firstNameNode);

                $guidNode = $goodDataXml->createElement('guid', htmlentities($guid));
                $user->appendChild($guidNode);

                $lastNameNode = $goodDataXml->createElement('last_name', htmlentities($lastName));
                $user->appendChild($lastNameNode);

                $screenNameNode = $goodDataXml->createElement('screen_name', htmlentities($screenName));
                $user->appendChild($screenNameNode);

                $usersRoot->appendChild($user);
            }

            $goodDataXml->save($goodDataFile);
        }

        echo 'Finished writing the good data XML '.$goodDataFile.' '.date('h:i:s M d, Y').'!'."\n\n";
        echo $users->length.' users remain to be validate'."\n\n";
        echo 'Starting '.$readFile.' trimming at '.date('h:i:s M d, Y').'...'."\n\n";

        $i = 0;

        foreach($users as $user) {
            if($i == 9999) {
                break;
            }

            $user->parentNode->removeChild($dom->getElementsByTagName('user')->item(0));

            $i++;
        }

        $dom->save($readFile);

        echo 'Finished '.$readFile.' trimming at '.date('h:i:s M d, Y').'!'."\n";

        $endTime = microtime(true);
        echo $endTime - $startTimeAfterFile;
        echo ' is the elapsed time to validate out 10000 users'."\n";
        echo $endTime - $startTime;
        echo ' is the elapsed time to load the file and validate out 10000 users';
        exit(0);
    } else {
        echo 'there is no file to read!';
    }
