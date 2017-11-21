<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttachmentCategory extends Model
{
     /**
     * Guard properties
     *
     * @var array
     **/
    protected $guarded = ['id'];

    /**
     * Define relation with documents. Each category can have many documents
     * 
     * @return Relation 
     */
    public function documents()
    {
    	return $this->hasMany('App\Attachment', 'category_id');
    }
}
