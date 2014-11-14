<?php

class CategoryTableSeeder extends Seeder {

    public function run()
    {
    	DB::table('categories')->delete();

			Category::create(array(
				'id'								=> 50,
				'category_alias'		=> 'health',
				'category_name'			=> 'Health',
				'pic_link'					=> 'health.jpg',
				'published'					=> 1
			));

			Category::create(array(
				'id'								=> 51,
				'category_alias'		=> 'property',
				'category_name'			=> 'Property',
				'pic_link'					=> 'property.jpg',
				'published'					=> 1
			));

			Category::create(array(
				'id'								=> 52,
				'category_alias'		=> 'food',
				'category_name'			=> 'Food',
				'pic_link'					=> 'food.jpg',
				'published'					=> 1
			));

			Category::create(array(
				'id'								=> 53,
				'category_alias'		=> 'love',
				'category_name'			=> 'Love',
				'pic_link'					=> 'love.jpg',
				'published'					=> 1
			));

			Category::create(array(
				'id'								=> 54,
				'category_alias'		=> 'education',
				'category_name'			=> 'Education',
				'pic_link'					=> 'education.jpg',
				'published'					=> 1
			));

    }

}