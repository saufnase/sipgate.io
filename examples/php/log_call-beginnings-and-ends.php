<?php
/* Log the beginnings and ends of calls
 */

$event = $_POST['event'];           // is either "newCall" (beginning) or "hangup" (end)
$callId = $_POST['callId'];         // unique Id of this call
$timestamp = date("d.m.Y H:i:s");   // a timestamp for the log so that calls can be uniquely identified


if ($event == 'newCall') {

    $fromNumber = $_POST['from'];     // the number of the caller
    $toNumber = $_POST['to'];         // the number on which the call was received on
    $direction = $_POST['direction'];   // the direction of the call (either "in" or "out")

    // build the log row, example:
    // 23456123 - 17.09.2014 10:05:25 - from 4921100000000 to 4921100000000 - direction: in
    $logRow = $callId . " - " . $timestamp .
        " - from " . $fromNumber . " to " . $toNumber .
        " - direction: " . $direction . PHP_EOL;

    set_onHangup_url('http://localhost:3000/log_call-beginnings-and-ends.php'); // Call this script again on hangup

} else if ($event == 'hangup') {

    // build the log row, example:
    // 23456123 - 17.09.2014 10:05:25
    $logRow = $callId . " - " . $timestamp;

}

// append the log row to the callog.txt file, make sure this file is writeable (e.g. create the file and chmod 777 it)
file_put_contents("callog.txt",$logRow,FILE_APPEND);

die("Thanks - here's a motivational squirrel for you! https://www.youtube.com/watch?v=m3d03-sSiBE");




// Create XML Response that sets Url to be called when call ends (hangup)
function set_onHangup_url($onHangup_url)
{
// Create new DOM Document for the response
    $dom = new DOMDocument('1.0', 'UTF-8');

// Add response child
    $response = $dom->createElement('Response');
    $dom->appendChild($response);

    $response->setAttribute('onHangup', $onHangup_url);

    header('Content-type: application/xml');
    echo $dom->saveXML();
}
