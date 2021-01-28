<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuardAccess
{
    const ADMIN = 'admin';
    const PUPIL = 'pupil';
    const TEACHER = 'teacher';
    private $auth;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->auth = auth()->user();
        $userTypeByUrl = substr($request->path(), 0, strpos($request->path(), '/'));
        $userType = $this->auth->roles->first()->name;
        switch ($userType) {
            case 'admin':
                if ($userType === self::ADMIN && $userTypeByUrl === self::ADMIN) {
                    return $next($request);
                }
                return redirect('/admin/index');

            case 'pupil':
                if ($userType === self::PUPIL && $userTypeByUrl === self::PUPIL) {
                    return $next($request);
                }
                return redirect('/pupil/events');

            case 'teacher':
                if ($userType === self::TEACHER && $userTypeByUrl === self::TEACHER) {
                    return $next($request);
                }
                return redirect('/teacher/pupils');

        }
    }
}
