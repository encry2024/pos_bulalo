<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use Hash;

/**
 * Class AccountController.
 */
class AccountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.user.account');
    }

    public function verifyAdmin($key) {
    	$count  = 0;
    	$admins =  User::with(['roles' => function($q) {
		    		$q->whereIn('name', ['Administrator', 'POS Administrator']);
		    	}])
		    	->whereHas('roles', function($q) { 
		    		$q->whereIn('name', ['Administrator', 'POS Administrator']);
		    	})->get();

		foreach($admins as $admin)
		{
			if(Hash::check($key, $admin->password))
			{
				$count++;
			}
		}

		return $count;
    }
}
