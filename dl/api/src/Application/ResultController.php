<?php

namespace YoutubeDownloader\Application;

/**
* The result controller
*/
class ResultController extends ControllerAbstract
{
	/**
    * Excute the Controller
    *
    * @param string $route
    * @param YoutubeDownloader\Application\App $app
    *
    * @return void
    */
	public function execute()
	{
		$config = $this->get('config');
		$template = $this->get('template');
		$toolkit = $this->get('toolkit');
        
		if( ! isset($_GET['i']) )
        {
            $this->responseWithErrorMessage('Err ,No video id passed in');
        }
        
        $my_id = $_GET['i'];
        
		$youtube_provider = \YoutubeDownloader\Provider\Youtube\Provider::createFromConfigAndToolkit(
			$config,
			$toolkit
		);
        
		if ( $youtube_provider instanceOf \YoutubeDownloader\Cache\CacheAware )
		{
			$youtube_provider->setCache($this->get('cache'));
		}
        
		if ( $youtube_provider instanceOf \YoutubeDownloader\Logger\LoggerAware )
		{
			$youtube_provider->setLogger($this->get('logger'));
		}
        
		if ( $youtube_provider->provides($my_id) === false )
		{
			$this->responseWithErrorMessage('Invalid url');
        }
        
        
        $template_data = [
            'app_version' => $this->getAppVersion(),
        ];
        
        $video_info = $youtube_provider->provide($my_id);
        
        if ($video_info->getStatus() == 'fail')
        {
            $message = 'Error in video ID: ' . $video_info->getErrorReason();
            
            if ($config->get('debug'))
            {
                $message .= 'Err' . var_dump($video_info);
            }
            
            $this->responseWithErrorMessage($message);
        }
        
        $my_title = $video_info->getTitle();
        $cleanedtitle = $video_info->getCleanedTitle();
        
        $template_data['video_title'] = $video_info->getTitle();
        
        if (count($video_info->getFormats()) == 0)
        {
            $this->responseWithErrorMessage('Err No format stream map found - was the video id correct?');
        }
        
        if ($config->get('debug'))
        {
            $debug1 = '';
            
            if ($config->get('multipleIPs') === true)
            {
                $debug1 .= 'Outgoing IP: ' . print_r($toolkit->getRandomIp($config), true);
            }
            
            $template_data['show_debug1'] = true;
            $template_data['debug1'] = @var_export($video_info, true);
        }
        
        $magicServer ='c.doc-0-0-sj.sj.googleusercontent';
        
        if ( !isset($_GET['format']) ){
            $template_data['Id'] = $_GET['i'];
            $details=$video_info->getDetails();
            $template_data['Duration'] = $details[0];
            $template_data['Views'] = $details[1];
        }
        $template_data['formats'] = [];
        foreach ($video_info->getFormats() as $avail_format)
        {
            $count=count($template_data['formats']);
            $directlink = $avail_format->getUrl();
            $directlink=str_replace('googlevideo', $magicServer, $directlink);
            $directlink .= '&title=' . $cleanedtitle;

            $template_data['formats'][] = [
                'url'=> $directlink,
                'type'=> $avail_format->getType(),
                'quality'=> $avail_format->getQuality(),
                'tag'=> $count
            ];
            if (isset($_GET['format'])){
                if ($_GET['format']==$count) {
                    header("Location: $directlink");
                    exit();
                }else continue;
            }
        }
        foreach ($video_info->getAdaptiveFormats() as $avail_format)
        {
            $directlink = $avail_format->getUrl();
            if(strpos($avail_format->getType(),'audio')!== false){
                $count=count($template_data['formats']);
                $directlink=str_replace('googlevideo', $magicServer, $directlink);
                $directlink .= '&title=' . $cleanedtitle;
                $template_data['formats'][] = [
                    'url'=> $directlink,
                    'type'=> $avail_format->getType(),
                    'quality'=> $avail_format->getQuality(),
                    'tag'=> $count
                ];
                if ( isset($_GET['format']) ){
                    if ($_GET['format']==$count) {
                        header("Location: $directlink");
                        exit();
                    }else continue;
                }
            }
        }
        // if($config->get('MP3Enable'))
        // {
        //     $mp3_url = sprintf(
        //         'download.php?mime=audio/mp3&token=%s&title=%s&getmp3=true',
        //         base64_encode($my_id),
        //         $cleanedtitle
        //     );
            
        //     $template_data['showMP3Download'] = true;
        //     $template_data['mp3_download_url'] = $mp3_url;
        //     $template_data['mp3_download_quality'] = $config->get('MP3Quality');
        // }
        
        echo $template->render('getvideo.php', $template_data);
    }
}
