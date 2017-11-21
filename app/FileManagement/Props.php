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
				'pull_categories' => route('pull-categories'),
				'pull_attachments' => route('pull-attachments'),
				'delete_attachment' => route('delete-attachment'),
			]
		];
	
	}

}