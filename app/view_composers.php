<?php

// Do this when view 'frontend.header' loaded
View::composer('layouts.top', function($view)
{
	if (Entrust::hasRole('Expert')) {
		$currentUserName = Auth::user()->expert->expert_name;
	} else {
		$currentUserName = Auth::user()->fullname;	
	}
	
	$view
		->with('currentUserName', $currentUserName)
	;
});