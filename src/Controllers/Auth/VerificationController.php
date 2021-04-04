<?php

namespace Tadcms\Backend\Controllers\Auth;

use Tadcms\System\Models\User;
use Theanh\Lararepo\Controller;

class VerificationController extends Controller
{
    public function verification($token)
    {
        $user = User::where('verification_token', '=', $token)
            ->first();
        if ($user) {
            $user->update([
                'status' => 'active',
                'verification_token' => null,
            ]);
            
            return redirect()->route('auth.login');
        }
        
        return abort(404);
    }
}
