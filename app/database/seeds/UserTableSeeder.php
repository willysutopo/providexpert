<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
    	DB::table('users')->delete();

    		$roleUser = Role::where('name', '=', 'User')->first();
    		$roleExpert = Role::where('name', '=', 'Expert')->first();

    		$userTest = new User;
    		$userTest->id = 5000;
    		$userTest->email = 'test@example.com';
    		$userTest->password = Hash::make('123456');
    		$userTest->save();
    		$userTest->roles()->attach($roleUser->id);

    		$expert1 = new User;
    		$expert1->id = 3000;
    		$expert1->email = 'ssugondho@example.com';
    		$expert1->password = Hash::make('123456');
    		$expert1->save();
    		$expert1->roles()->attach($roleUser->id);

    		$expert2 = new User;
    		$expert2->id = 3001;
    		$expert2->email = 'tjien.ronny@example.com';
    		$expert2->password = Hash::make('123456');
    		$expert2->save();
    		$expert2->roles()->attach($roleUser->id);

    		$expert3 = new User;
    		$expert3->id = 3002;
    		$expert3->email = 'andri.t@example.com';
    		$expert3->password = Hash::make('123456');
    		$expert3->save();
    		$expert3->roles()->attach($roleUser->id);
    		

    }

}