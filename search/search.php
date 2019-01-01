<?php
// ?q,maxResults
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
  throw new \Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ .'"');
}
require_once __DIR__ . '/vendor/autoload.php';
if (isset($_GET['q']) && isset($_GET['maxResults'])) {
  $client = new Google_Client();
  $client->setDeveloperKey('AIzaSyAPjtKvTF11DHZiNyCsWHKOwnMuToHZTgE');
  $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), 'headers' => ['Referer' => '127.0.0.1' ]));        
  $client->setHttpClient($guzzleClient);
  $youtube = new Google_Service_YouTube($client);
  try {
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'type' => 'video','q' => $_GET['q'],'maxResults' => $_GET['maxResults'],
    ));
    $videos = '';
    foreach ($searchResponse['items'] as $searchResult)
      if ($searchResult['id']['kind'] == 'youtube#video')
          $videos[]= [
            'Title'=>$searchResult['snippet']['title'],
            'Id'=> $searchResult['id']['videoId'],
            'Channel'=>$searchResult['snippet']['channelTitle']
            ];
    $videos=json_encode($videos);
    header('Content-Type: application/json');
    echo $videos;
  } catch (Google_Service_Exception $e) {
    echo sprintf('Err A service error occurred: %s',htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    echo sprintf('Err An client error occurred: %s',htmlspecialchars($e->getMessage()));
  }
}
