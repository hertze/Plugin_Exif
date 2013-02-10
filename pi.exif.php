<?php
class Plugin_exif extends Plugin {

    var $meta = array(
        'name'       => 'Exif extractor',
        'version'    => '0.1',
        'author'     => 'Joakim Hertze',
        'author_url' => 'http://magiso.com'
    );

    public function index()
    {
      $imagepath = $this->fetch_param('imagepath', null, false, false, false);
      
      if ($imagepath) {
      	
      	// We need to rid ourselves of any leading slashes for this to work
      	$imagepath = trim($imagepath, '/');
      	      	
      	try { 
      		$raw = exif_read_data($_SERVER['DOCUMENT_ROOT'].$imagepath, 0, true); 
      		} catch (Exception $e) { 
	      		var_dump($e->__toString()); 
	      	} 
	      	
	    // The following is to prevent division by zero when no Exif data is embedded in the image.	      
	    if (strlen($raw['EXIF']['FocalLength'])>0) {
      			$flength = round(floatval(substr($raw['EXIF']['FocalLength'], 0, strpos($raw['EXIF']['FocalLength'],'/')))/floatval(substr($raw['EXIF']['FocalLength'], strpos($raw['EXIF']['FocalLength'],'/')+1, strlen($raw['EXIF']['FocalLength'])))).'mm';
      	}
      	
      	// Unfortunately, inconsistencies is naming between camera makers makes it necessary to customize
      	// the cleaning up of exif data. The following takes care of Nikon and Olympus cameras. Feel free to
      	// add more as you find necessary. 
      	$make_pattern = array('/(\sIMAGING\sCORP\.)(\s)*/', '/\sCORPORATION/');
      	$make_replace = array('','');
      	$model_pattern = array('/NIKON/','/(\s)*/');
      	$model_replace = array('','');
      	
      	// For extra credit, determine if the camera name should be preceeded by 'a' or 'an'.
      	if ( preg_match('/^[AOUEY]/', preg_replace($make_pattern, $make_replace, $raw['IFD0']['Make'])) == 1 ) {
	      	$indefinite_article = 'an';
      	} else {
	      	$indefinite_article = 'a';
      	}
      	
        return array(
          'imagewidth' => $raw['IFD0']['ImageWidth'],
          'imageheight' => $raw['IFD0']['ImageLength'], 
          'datetime' => $raw['IFD0']['DateTime'],	
          'model' => $raw['IFD0']['Model'],
          'make' => $raw['IFD0']['Make'],
          'camera' => ucfirst(strtolower(preg_replace($make_pattern, $make_replace, $raw['IFD0']['Make']))).' '.preg_replace($model_pattern, $model_replace, $raw['IFD0']['Model']),
          'software' => $raw['IFD0']['Software'].'s',
          'aperture' => $raw['COMPUTED']['ApertureFNumber'],
          'exposure' => $raw['EXIF']['ExposureTime'].'s',
          'flenght' => $flength,
          'iso' => 'ISO '.$raw['EXIF']['ISOSpeedRatings'],
          'indefinite_article' => $indefinite_article
        );  
      }
    }
}