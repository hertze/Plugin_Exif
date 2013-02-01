<?php
class Plugin_exif extends Plugin {

    var $meta = array(
        'name'       => 'Exif',
        'version'    => '0.1',
        'author'     => 'Joakim Hertze',
        'author_url' => 'http://hertze.com'
    );

    public function index()
    {
      $imagepath = $this->fetch_param('imagepath', null, false, false, false);
      
      if ($imagepath) {
      
      	try { 
      		$raw = exif_read_data($_SERVER['DOCUMENT_ROOT'].$imagepath, 0, true); 
      		} catch (Exception $e) { 
	      		var_dump($e->__toString()); 
	      	} 

        return array(
          'imagewidth' => $raw['IFD0']['ImageWidth'],
          'imageheight' => $raw['IFD0']['ImageLength'], 
          'datetime' => $raw['IFD0']['DateTime'],	
          'model' => $raw['IFD0']['Model'],
          'make' => $raw['IFD0']['Make'],
          'camera' => preg_replace(array('/\ IMAGING\ CORP\./', '/\ CORPORATION/'), '', $raw['IFD0']['Make']).''.preg_replace(array('/NIKON\ /'), '', $raw['IFD0']['Model']),
          'software' => $raw['IFD0']['Software'].'s',
          'aperture' => $raw['COMPUTED']['ApertureFNumber'],
          'exposure' => $raw['EXIF']['ExposureTime'].'s',
          'flenght' => str_replace('/1','mm',$raw['EXIF']['FocalLength']),
          'iso' => 'ISO '.$raw['EXIF']['ISOSpeedRatings']
        );  
      }
    }
}