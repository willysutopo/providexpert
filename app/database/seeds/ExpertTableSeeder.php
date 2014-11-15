<?php

class ExpertTableSeeder extends Seeder {

    public function run()
    {
    	DB::table('experts')->delete();

			Expert::create(array(
				'id'								=> 50,
				'expert_name'				=> 'Dr. Sidhartawan Sugondho',
				'category_id'				=> 50,
				'expertises'				=> 'Diabetes',
				'phone'							=> '',
				'address'						=> '',
				'pic_link'					=> '',
				'published'					=> 1,
				'user_id' => 3000
			));

			Expert::create(array(
				'id'								=> 51,
				'expert_name'				=> 'Dr. Tjien Ronny',
				'category_id'				=> 50,
				'expertises'				=> 'Maternity, Pregnancy',
				'phone'							=> '',
				'address'						=> '',
				'pic_link'					=> '',
				'published'					=> 1,
				'user_id' => 3001
			));

			Expert::create(array(
				'id'								=> 52,
				'expert_name'				=> 'Dr. Andri Tangkilisan',
				'category_id'				=> 50,
				'expertises'				=> 'Child',
				'phone'							=> '',
				'address'						=> '',
				'pic_link'					=> '',
				'published'					=> 1,
				'user_id' => 3002
			));
    }

}