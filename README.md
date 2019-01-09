# YDM.Server

PHP + Heroku

## API Mini Doc

Base Url: https://ydm.herokuapp.com

Request Type | Description | URL 
---- | ------ | ------
GET | Get Available Qualities for a Video | /getvideo.php?i={VIDEO_ID}
GET | Downloadable Links with no expiration date | /getvideo.php?i={VIDEO_ID}&tag={TAG_NUM}
GET | Video thumbnail image | /getimage.php?i={VIDEO_ID}&q={QUALITY_ID}
GET | Search for Video | /search/search.php?q={SEARCH_QUERY}
GET | Get Playlist Videos | /search/playlist.php?q={PLAYLIST_ID}


### Thumbnail image quality

QUALITY_ID | Description | Size
---- | ------ | ------
0 | Player Background Thumbnail | (480x360px)
default | Normal Quality Thumbnail | (120x90px)
mqdefault | Medium Quality Thumbnail | (320x180px)
hqdefault | High Quality Thumbnail | (480x360px)
1 | Start Thumbnail | (120x90px)
2 | Middle Thumbnail | (120x90px)
3 | End Thumbnail | (120x90px)
sddefault | SD Quality | (640x480px)
maxresdefault | Max Quality | (1280x720px)