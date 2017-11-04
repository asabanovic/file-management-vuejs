<?php

namespace App\FileManagement;

class Props
{	
	/**
	 * Define all your props for all of your components so they are all centralized
	 * 
	 * @return array 
	 */
	public static function get()
	{
		return [
			'file_management' => [
				'upload_files' => route('store-attachments'),
			]
		];
	}
}