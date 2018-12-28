<?php
// https://shahriar.in/app/ydm/dl/api/getvideo.php?videoid=IdneKLhsWOQ
// <!-- /getvideo.php?videoid=youtube.com%2Fwatch%3Fv%3DIdneKLhsWOQ&type=Download -->
// $this->inc('header.php', ['title' => 'Youtube Downloader Results']);
if ($this->get('no_stream_map_found', false) === true) {
    echo $this->get('no_stream_map_found_dump'); 
}
else{
    // if ($this->get('showMP3Download', false) === true) {
    //         echo $this->get('mp3_download_url');
    //         echo $this->get('mp3_download_quality');
    // }
    $arr=['Id'=>$_GET['i'],'Title'=>$this->get('video_title'),'Duration'=>$this->get('Duration'),'Views'=>$this->get('Views')];
    $arr2=$this->get('formats', []);
    echo json_encode($arr).'[*]'.json_encode($arr2);
    if (!isset($_GET['f'])) {
        if (!is_numeric($_GET['id'])) die('Err 367ATG');
        $db=new DB();
        $db->exec_query("UPDATE usrs set nrCanDownload=nrCanDownload-1,nrDownloaded=nrDownloaded+1  where id=".$_GET['id']);
    }
}
