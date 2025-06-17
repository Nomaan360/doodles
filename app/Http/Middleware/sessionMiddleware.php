<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use function App\Helpers\is_mobile;

class sessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $type = $request->input("type");

        if($request->header('x-forwarded-for') == "173.239.214.169")
        {
            $res['status_code'] = 0;
            $res['message'] = "Something went wrong.";

            return is_mobile($type, "flogout", $res);
        }

        if (stripos($request->header('User-Agent'), 'macintosh') !== false) {
            $res['status_code'] = 0;
            $res['message'] = "Something went wrong.";

            return is_mobile($type, "flogout", $res);
        }
        if ($type != "API") {
            $user_id = $request->session()->get('user_id');
            if (empty($user_id)) {
                $data['status_code'] = "0";
                $data['message'] = "Session Expired Please Login Again.";
                return redirect()->route("flogin")->with(['data' => $data]);
            }
        }
        return $next($request);
    }
}
