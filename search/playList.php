<?php
if(!isset($_GET['q']))exit('Invalid PlayListId');
// $playlistItem='PLxYD9HaZwsI5C0d8CivHvoI_-0rs8XMfc'
$playlistId=base64_decode(strrev(($_GET['q'])));
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
  throw new \Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ .'"');
}
require_once __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setDeveloperKey('AIzaSyAPjtKvTF11DHZiNyCsWHKOwnMuToHZTgE');
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), 'headers' => ['Referer' => '127.0.0.1' ]));        
$client->setHttpClient($guzzleClient);
$youtube = new Google_Service_YouTube($client);
$videos=[];$nextPageToken='';
try {
    do {  
        $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
        'playlistId' => $playlistId,'pageToken' => $nextPageToken));
    foreach ($playlistItemsResponse['items'] as $playlistItem) {
        $videos[]=[
        'Title'=>$playlistItem['snippet']['title'], 
        'Id'=>$playlistItem['snippet']['resourceId']['videoId'],
        'Channel'=>$playlistItem['snippet']['channelTitle']
        ];
    }
    $nextPageToken = $playlistItemsResponse['nextPageToken'];
    } while ($nextPageToken <> '');
    $videos=json_encode($videos);
    header('Content-Type: application/json');
    echo $videos;
} catch (Exception $e) {
    echo "Err!"; 
}