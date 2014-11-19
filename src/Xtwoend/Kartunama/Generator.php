<?php namespace Xtwoend\Kartunama;
    	
/**
 * Part of the package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    
 * @version    0.1
 * @author     Abdul Hafidz Anshari
 * @license    BSD License (3-clause)
 * @copyright  (c) 2014 
 */

use Intervention\Image\ImageManager;
use Illuminate\Filesystem\Filesystem;

class Generator {	
	

	protected $intervention;

	protected $file;

	/**
	 * 
	 * @params
	 */	
	public function __construct()
	{
		$this->intervention = new ImageManager(array('driver' => 'imagick'));
		$this->file 		= new Filesystem;
	}


	/**
	 * @doc
	 * 
	 */
	public function create(array $entities=array(), $width=650, $height=352)
	{	
		$name 		= isset($entities['name'])? $entities['name'] : 'Guest Name';
		$jobtitle 	= isset($entities['jobtitle'])? $entities['jobtitle'] : 'Job Title';
		$city		= isset($entities['city'])? $entities['city'] : 'Eart';

		$foto 		= isset($entities['photo'])? $this->file->get($entities['photo']) : $this->file->get(\Config::get('kartunama::defautfoto'));
		
		$baseimage 	= \Config::get('kartunama::base_image');

		$final_image = str_random(8).'.jpg';
		//dd($baseimage);

		$img 	= $this->intervention->make($baseimage);
		$foto 	= $this->intervention->make($foto);
		$foto->resize(190, null, function ($constraint) {
		    $constraint->aspectRatio();
		});
		$foto->crop(190,210, 0, 0);
		// write text
		// use callback to define details
		$img->text($name, 400, 305, function($font) {
		    $font->file(__DIR__ . '/fonts/Arimo-Bold.ttf');
		    $font->size(60);
		    $font->color('#414141');
		    $font->align('left');
		    $font->valign('center');
		});

		$img->text($jobtitle, 400, 360, function($font) {
		    $font->file(__DIR__ . '/fonts/Arimo-Regular.ttf');
		    $font->size(38);
		    $font->color('#595959');
		    $font->align('left');
		    $font->valign('center');
		});

		$img->text($city, 400, 400, function($font) {
		    $font->file(__DIR__ . '/fonts/Arimo-Bold.ttf');
		    $font->size(30);
		    $font->color('#217188');
		    $font->align('left');
		    $font->valign('center');
		});
		
		$img->insert($foto, 'top-left', 165, 245);
		
		$img->resize($width, null, function ($constraint) {
		    $constraint->aspectRatio();
		});
		
		$img->save(\Config::get('kartunama::base_path').$final_image);

		return \Config::get('kartunama::base_path').$final_image;
	}
	
}