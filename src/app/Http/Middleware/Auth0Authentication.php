<?php

namespace App\Http\Middleware;

use App\Services\AuthenticationService;
use App\Services\JWTService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class Auth0Authentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $service = App::make(AuthenticationService::class);
            $jwtService = App::make(JWTService::class);
            if ($service instanceof AuthenticationService && $jwtService instanceof JWTService) {
                $userId = $jwtService->parseUserId($request->bearerToken());
                $user = $service->getUserById($userId);
                $request->request->add(['user' => $user]);
                return $next($request);
            }
            return response(Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'code' => Response::HTTP_UNAUTHORIZED,
                'status' => 'unauthorized',
                'data' => '',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
