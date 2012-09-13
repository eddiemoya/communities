<?php
    $startTime = microtime(true);

    echo 'Started validation at '.date('h:i:s M d, Y').'...'."\n";

    $base = '/Users/dasfisch/cron_results/';
    $batchBase = '/Users/dasfisch/cron_results/batches/';

    $badDataFile = $base.'bad_data.xml'; // List of xml elements that have failed
    $errorFile = $base.'all_errors.txt';
    $nextDataSetFile = $base.'current_batch.txt';
    $readableErrorFile = $base.'our_errors.txt';

    $readFile = $base.'short.xml';

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

    echo 'The next batch set starting point is array spot '.$nextDataSet."\n";

    if(file_exists($readFile)) {
        $i = 0;

        echo 'Starting file load at '.date('h:i:s M d, Y').'...'."\n";

        $dom = new DomDocument();
        $dom->load($readFile);
        $users = $dom->getElementsByTagName('user');

        echo 'Finished file load at '.date('h:i:s M d, Y').'!'."\n";
        echo 'Starting validation at '.date('h:i:s M d, Y').'...'."\n";

        $startTimeAfterFile = microtime(true);

        $badData = array();
        $goodData = array();

        echo $users->length.' is the total users'."\n";

        foreach($users as $user) {
            $ourError = '';

            if(!isset($user->getElementsByTagName('email')->item(0)->nodeValue) || !preg_match('/^.+@.+?\.[a-zA-Z]{2,}$/', $user->getElementsByTagName('email')->item(0)->nodeValue)) {
                // need to set this element -> $xml->user[$i]->posInArray = $i;

                $badData[] = $user->getElementsByTagName('email')->item(0)->nodeValue;

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
            } else {
                echo $user->getElementsByTagName('email')->item(0)->nodeValue.' is a valid user'."\n";
            }

            /*if(!isset($xml->user[$i]->screen_name) || strlen($xml->user[$i]->screen_name) > 18) {
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
            }*/

            //XML is valid; insert it into insert_bash
            $goodData[] = $user;
        }

        exit; 'Finished validation at '.date('h:i:s M d, Y').'!'."\n";

        $nextDataSet += 10000;
        
        fwrite($batchHandle, $nextDataSet);

        echo 'Starting writing the bad data XML '.date('h:i:s M d, Y').'...'."\n";

        if(isset($badData) && !empty($badData)) {
            // Create the XML with all data issues
            if(file_exists($badDataFile)) {
                $badDataXml = new DOMDocument($badDataFile);
            } else {
                $badDataXml = new DOMDocument("1.0");

                $usersRoot = $badDataXml->createElement("toppings");
                $badDataXml->appendChild($usersRoot);
            }

            echo 'There are '.count($badData).' users that are invalid'."\n";

            foreach($badData as $key=>$val) {
                $user = $badDataXml->createElement('user');

                $user->addChild('posInArray', $val->posInArray);
                $user->addChild('id', $val->id);
                $user->addChild('screen_name', $val->screen_name);
                $user->addChild('email', $val->email);
                $user->addChild('display_name', $val->display_name);
                $user->addChild('first_name', $val->first_name);
                $user->addChild('last_name', $val->last_name);
                $user->addChild('guid', $val->guid);
            }

            // Saving pretty XML
            $dom = new DOMDocument('1.0');

            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($badDataXml->asXML());

            $dom->save($badDataFile);
        }

        echo 'Finished writing the bad data XML '.date('h:i:s M d, Y').'...'."\n";
        echo 'Starting writing the good data XML '.$goodDataFile.' '.date('h:i:s M d, Y').'!'."\n";

        if(isset($goodData) && !empty($goodData)) {
            // Create the XML with all data issues
            $goodDataXml = new SimpleXMLElement('<users/>');

            echo 'There are '.count($goodData).' users that are valid'."\n";

            foreach($goodData as $key=>$val) {
                $user = $goodDataXml->addChild('user');

                $user->addChild('id', $val->id);
                $user->addChild('screen_name', $val->screen_name);
                $user->addChild('email', $val->email);
                $user->addChild('display_name', $val->display_name);
                $user->addChild('first_name', $val->first_name);
                $user->addChild('last_name', $val->last_name);
                $user->addChild('guid', $val->guid);
            }

            // Saving pretty XML
            $dom = new DOMDocument('1.0');

            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($goodDataXml->asXML());

            $dom->save($goodDataFile);
        }

        echo 'Finished writing the good data XML '.$goodDataFile.' '.date('h:i:s M d, Y').'!'."\n";

//        for($j = 0; $j < 10000; $j+=1) {
////            unset($xml->user[$i]);
//            $dom=dom_import_simplexml($xml->user[$j]);
//            $dom->parentNode->removeChild($dom);
//        }

        $xml->user = array_slice($xml->user, 10000);

        echo count($xml->user).' users remain to be validate'."\n";
        echo 'Starting '.$readFile.' trimming at '.date('h:i:s M d, Y').'...'."\n";

//        $dom = new DOMDocument('1.0');
//
//        $dom->preserveWhiteSpace = false;
//        $dom->formatOutput = true;
//        $dom->loadXML($xml->asXML());
//
//        $dom->save($readFile);

        $xml->asXML($readFile);

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
