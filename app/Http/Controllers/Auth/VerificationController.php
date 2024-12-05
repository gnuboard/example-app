<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $user = $request->user();
        $user->markEmailAsVerified();

        // 이메일 인증 후 level 변경
        $user->update(['level' => config('constants.user_levels.verified')]);

        return redirect()->route('dashboard')->with('message', '이메일 인증이 완료되었습니다.');
    }
} 