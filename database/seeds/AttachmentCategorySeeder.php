<?php

use Illuminate\Database\Seeder;
use App\AttachmentCategory;

class AttachmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Config::get('setup.categories');
    	if (App::isLocal())
		{
	    	DB::statement('SET FOREIGN_KEY_CHECKS=0');
	     
			// Delete all entries 
			AttachmentCategory::truncate();

			DB::statement('SET FOREIGN_KEY_CHECKS=1');

			foreach ($categories as $key => $category) {
				AttachmentCategory::create([ 'name' => $category ]);
			}
		}
    }
}
