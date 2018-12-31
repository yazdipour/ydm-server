<?php
    //settings
    $cache_ext  = '.json'; //file extension
    $cache_time     = 3600;  //Cache file expires afere these seconds (1 hour = 3600 sec)
    $cache_folder   = 'cache/'; //folder to store Cache files
    $ignore_pages   = array('', '');
    $dynamic_url    = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']; // requested dynamic page (full url)
    $cache_file     = $cache_folder.md5($dynamic_url).$cache_ext; // construct a cache file
    $ignore = (in_array($dynamic_url,$ignore_pages))?true:false;  //check if url is in ignore list
    //Turn on output buffering with gzip compression.
    ob_start('ob_gzhandler');
    ######## Your Website Content Starts Below #########
    $app = include_once('bootstrap.php');
    $app->runWithRoute('results');
    ######## Your Website Content Ends here #########
    if (!is_dir($cache_folder)) { //create a new folder if we need to
        mkdir($cache_folder);
    }
    if(!$ignore){
        $fp = fopen($cache_file, 'w');  //open file for writing
        fwrite($fp, ob_get_contents()); //write contents of the output buffer in Cache file
        fclose($fp); //Close file pointer
    }
    ob_end_flush(); //Flush and turn off output buffering