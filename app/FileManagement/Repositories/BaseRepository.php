<?php

namespace App\FileManagement\Repositories;

class BaseRepository
{
	/**
	 * Model 
	 *
	 * @var Object
	 **/
	public $model;

	/**
	 * Model Value - Actual DB table entry value, to automatically lookup under defined $this->lookup_param
	 * the given model
	 *
	 * @var Object
	 **/
	public $object_param;

	/**
	 * Lookup Param - Basically a DB field to use when searching if there is any entries for the given model
	 *
	 * @var Object
	 **/
	public $lookup_param;

	/**
	 * Object value - This is an object value passed to the Model such as a concrete user, role from the source etc
	 *
	 * @var Object
	 **/
	public $object_value;

	/**
	 * Set deleted flag 
	 *
	 * @var Boolean
	 **/
	public $deleted;

	/**
	 * In case any repository/model needs to prepare fields 
	 * 
	 * @return array Changed model with updated/modified fields
	 */
	public function prepareFields(){
		// Override in other repositories if needed
	}

	/**
	 * Save new object in case it doesn't exists, or update if it does
	 * 
	 * @param  array $object Object_value from the source
	 * @return Object       
	 */
	public function saveOrUpdate($object)
	{	
		$this->object_value = $object;

		if(!$this->exists()) {
			return $this->store();
		}

		return $this->update();
	}

	/**
	 * Check if model exists according to special parameters
	 * 
	 * @return boolean	         
	 */
	public function exists()
	{	
		if (!isset($this->object_value) || !isset($this->object_param)) {
			return false;
		}
		
		if (!isset($this->object_value[$this->object_param])) {
			return false;
		}

		return $this->model->where($this->lookup_param, $this->object_value[$this->object_param])->exists();
	}

	/**
	 * Get the first entry for the given model based on the object_param and lookup_param
	 * It will/should be called after checking that it really exists
	 * 
	 * @return Object Model
	 */
	public function getDBModelEntry()
	{	
		return $this->model->where($this->lookup_param, $this->object_value[$this->object_param])->first();
	}

	/**
	 * Store object in the table with prior field preparation like password hashing, transforming the value etc..
	 * 
	 * @param  Object $object 
	 * @return Object       Model model
	 */
	public function store()
	{	
		$this->prepareFields();
		
		return $this->model->create($this->object_value);
	}

	/**
	 * Update user in case it exists in the database 
	 * 
	 * @param  array $user Object retrieved from the source (config)
	 * @return Object       
	 */
	public function update()
	{
		$this->prepareFields();
		$model = $this->getDBModelEntry();
		$model->fill($this->object_value);
		$model->update();
		
		return $model;
	}

	/**
	 * Update in bulk, per IDs
	 * 
	 * @param  array $ids          
	 * @param  array $update_array 
	 * @return Collection               
	 */
	public function updateBulk($ids, $update_array)
	{
		return $this->model->whereIn('id', $ids)->update($update_array);
	}

	/**
	 * Get all model entries
	 * 
	 * @return Collection 
	 */
	public function all()
	{
		return $this->model->get();
	}

	/**
	 * Get model by id
	 * @param  integer $id 
	 * @return Model     
	 */
	public function getById($id)
	{	
	 	return $this->model->find($id);
	}

	/**
	 * Query models by any fields and value
	 * 
	 * @param  string $column 
	 * @param  mixed $value  
	 * @return Collection         
	 */
	public function getByColumn($column, $value)
	{
		return $this->model->where($column, $value)->get();	
	}

	/**
	 * Enable partial search
	 * @param  string $column 
	 * @param  string $value  
	 * @return Model        
	 */
	public function getByPartialSearch($column, $value)
	{
		return $this->model->where($column, $value)->orWhere($column, 'like', '%' . $value . '%')->first();
	}

	/**
	 * Include trashed entries in the result. 
	 * Call this method when instantiating a repository for a model that has soft deleting enabled (has column deleted_at and the trait)
	 * 
	 * @return Collection 
	 */
	public function withTrashed()
	{
		$this->model = $this->model->withTrashed();

		return $this;
	}

	/**
	 * Dynamically load relations
	 * 
	 * @param  array $relations 
	 * @return Object            
	 */
	public function with($relations)
	{
		$this->model = $this->model->with($relations);

		return $this;
	}

	/**
	 * Dynamically load relation count
	 * 
	 * @param  array $relations 
	 * @return Object            
	 */
	public function withCount($relations)
	{
		$this->model = $this->model->withCount($relations);

		return $this;
	}

	/**
	 * Retrieve archived model entries only
	 * 
	 * @return Collection 
	 */
	public function deleted()
	{
		return $this->model::onlyTrashed()->get();
	}

	/**
	 * Order results
	 *
	 * @param  string Column 
	 * @param  string Order - DESC / ASC 
	 * @return Object            
	 */
	public function orderBy($column, $order)
	{
		$this->model = $this->model->orderBy($column, $order);

		return $this;
	}

	/**
	 * Where clause	
	 *
	 * @param  string Column 
	 * @param  string  
	 * @return Object            
	 */
	public function where($key, $value)
	{
		$this->model = $this->model->where($key, $value);

		return $this;
	}

	/**
	 * WhereIn clause	
	 *
	 * @param  string Column 
	 * @param  array  
	 * @return Object            
	 */
	public function whereIn($key, $array)
	{
		$this->model = $this->model->whereIn($key, $array);

		return $this;
	}
}