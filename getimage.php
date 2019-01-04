<?PHP
        /**
         * Player Background Thumbnail (480x360px) :    http://i1.ytimg.com/vi/VIDEO_ID/0.jpg
         * Normal Quality Thumbnail (120x90px) :    http://i1.ytimg.com/vi/VIDEO_ID/default.jpg
         * Medium Quality Thumbnail (320x180px) :   http://i1.ytimg.com/vi/VIDEO_ID/mqdefault.jpg
         * High Quality Thumbnail (480x360px) : http://i1.ytimg.com/vi/VIDEO_ID/hqdefault.jpg
         * Start Thumbnail (120x90px) :   http://i1.ytimg.com/vi/VIDEO_ID/1.jpg
         * Middle Thumbnail (120x90px) :   http://i1.ytimg.com/vi/VIDEO_ID/2.jpg
         * End Thumbnail (120x90px) :   http://i1.ytimg.com/vi/VIDEO_ID/3.jpg

         * sddefault.jpg","width": 640,"height": 480
         * maxresdefault.jpg","width": 1280,"height": 720
         */
         // getimage.php?i=xxx
if(isset($_GET['i'])) {
    $my_id=$_GET['i'];
    if(strlen($my_id)>11){
        $url = parse_url($my_id);
        $my_id = NULL;
        if( is_array($url) && count($url)>0 && isset($url['query']) && !empty($url['query']) ){
            $parts = explode('&',$url['query']);
            if( is_array($parts) && count($parts) > 0 ){
                foreach( $parts as $p ){
                    $pattern = '/^v\=/';
                    if( preg_match($pattern, $p) ){
                        $my_id = preg_replace($pattern,'',$p);
                        break;
                }
            }
        }
        if( !$my_id ){
            echo 'Err: No video id passed in';
            exit;
        }
    }else{
        echo 'Err: Invalid url';
        exit;
    }
}
} else {
    echo 'Err: No video id passed in';
    exit;
}

$szName='hqdefault';
if(isset($_GET['q'])) $szName=$_GET['q'];
$thumbnail_url="http://i1.ytimg.com/vi/".$my_id."/".$szName.".jpg"; // make image link
header("Content-Type: image/jpeg");
readfile($thumbnail_url); 
?>