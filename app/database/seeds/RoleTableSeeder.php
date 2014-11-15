<?php

class RoleTableSeeder extends Seeder {

    public function run()
    {
    	DB::table('assigned_roles')->delete();
    	DB::table('roles')->delete();

    	$roleUser = new Role;
    	$roleUser->name = 'User';
    	$roleUser->save();

    	$roleExpert = new Role;
    	$roleExpert->name = 'Expert';
    	$roleExpert->save();
			
    }

}