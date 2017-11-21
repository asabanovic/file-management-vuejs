<?php

namespace App\FileManagement\Repositories\Attachment;

use App\FileManagement\Repositories\BaseRepository;
use App\FileManagement\Repositories\AttachmentCategory\AttachmentCategoryRepository;
use App\Attachment;
use Auth;
use Storage;

class AttachmentRepository extends BaseRepository
{	
	/**
	 * Setup source
	 *
	 * @var App\Attachment
	 **/
	public $model;

	/**
	 * Setup AttachmentCategory
	 *
	 * @var App\AttachmentCategoryRepository
	 **/
	public $attachmentCategoryRepo;

	function __construct(Attachment $model, AttachmentCategoryRepository $attachmentCategoryRepo)
	{
		$this->model = $model;
		$this->attachmentCategoryRepo = $attachmentCategoryRepo;
		$this->lookup_param = 'path';
		$this->object_param = 'path';
	}

	/**
	 * Define the storage folder
	 * 
	 * @return string 
	 */
	public function getStorageLocation()
	{
		return 'public/attachments';
	}

	/**
	 * Define the public URL path
	 * 
	 * @return string 
	 */
	public function getPublicUrlPath($name)
	{
		return 'storage/attachments/' . $name;
	}	

	/**
	 * Method to define each repo field preparation
	 *
	 * @param  array List of all attachments (files)
	 * @return void 
	 */
	public function prepareArray($attachments = [])
	{	
		$return = [];

		foreach ($attachments as $attachment) {
			$temp = [];
			$temp['name'] = $attachment->getClientOriginalName();
			$this->storeAttachment($attachment); // Store file pysically 
			$temp['path'] = $this->getPublicUrlPath($attachment->hashName());
			$temp['type'] = $attachment->getMimeType();
			$temp['category_id'] = $attachment->category_id;
			$temp['size'] = $attachment->getClientSize();
			$temp['user_id'] = Auth::user()->id;
			array_push($return, $temp);
		}
		
		return $return;
	}

	/**
	 * Remove all attachment - profile relation prior to linking them back again
	 * 
	 * @param  int $investment_id 
	 * @return boolean             
	 */
	public function clearAttachments($investment_id)
	{	
		return $this->model->where('investment_id', $investment_id)->update(['investment_id' => NULL]);

	}

	/**
	 * Saving contacts in § from ajax
	 * 
	 * @param  JSON object $contacts We will need to process this into array
	 * @return array Array of all new attachment IDs           
	 */
	public function saveInBulk($attachments)
	{	
		$attachments = $this->prepareArray($attachments);

		$ids = [];

		foreach ($attachments as $key => $attachment) {
			$id = $this->saveOrUpdate($attachment);
			array_push($ids, $id);
		}

		return $ids;
	}

	/**
	 * Store attachment in storage/public/attachments
	 * 
	 * @param  UploadedFile::class $attachment 
	 * @return string        
	 */
	public function storeAttachment($attachment)
	{	
		return $attachment->store($this->getStorageLocation());
	}

	/**
	 * Remove attachment from database / storage
	 * 
	 * @param  integer $attachment_id 
	 * @return boolean                
	 */
	public function delete($attachment_id)
	{
		$attachment = $this->getById(intval($attachment_id));

		if (!$attachment) {
			return false;
		}

		$path = $attachment->path;

		$path = str_replace('storage/', 'public/', $path); // Prepare the file path so it can be found and deleted
		 
		Storage::delete($path); // Delete physical file

		$attachment->delete(); // Delete DB entry

		return true;
	}

	/**
	 * Load all attachment categories
	 *
	 * @param  integer $user_id 
	 * @return Collection                                               
	 */
	public function categories($user_id)
	{
		return $this->attachmentCategoryRepo->withCount(['documents' => function($query) use ($user_id){
			$query->where('user_id', $user_id);
		}])->all()->toArray();
	}

	/**
	 * Load all attachment categories including multiple people
	 *
	 * @param  array $users 
	 * @return Collection                                               
	 */
	public function categoriesByUsers($users)
	{
		return $this->attachmentCategoryRepo->withCount(['documents' => function($query) use ($users){
			$query->whereIn('user_id', $users->map(function($user) { return $user->id; }));
		}])->all()->toArray();
	}

	/**
	 * Retrieve all documents by a category ID and User ID
	 * 
	 * @param  integer $category_id 
	 * @param  integer $user_id     
	 * @return Collection              
	 */
	public function documents($category_id, $user_id)
	{
		$category = $this->attachmentCategoryRepo->getById(intval($category_id));

		if ($category) {
			return $category->documents->where('user_id', $user_id)->toArray();
		}
	}

	/**
	 * Retrieve all documents by a category ID and multiple users
	 * 
	 * @param  integer $category_id 
	 * @param  array $users     
	 * @return Collection              
	 */
	public function documentsByUsers($category_id, $users)
	{
		$category = $this->attachmentCategoryRepo->getById(intval($category_id));

		if ($category) {
			return $category->documents->whereIn('user_id', $users->map(function($user) { return $user->id; }))->toArray();
		}
	}

}