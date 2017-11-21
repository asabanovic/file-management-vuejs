<?php

namespace App\FileManagement\Repositories\AttachmentCategory;

use App\FileManagement\Repositories\BaseRepository;
use App\AttachmentCategory;

class AttachmentCategoryRepository extends BaseRepository
{	
	/**
	 * Setup source
	 *
	 * @var App\AttachmentCategory
	 **/
	public $model;

	function __construct(AttachmentCategory $model)
	{
		$this->model = $model;
		$this->lookup_param = 'id';
		$this->object_param = 'id';
	}

}