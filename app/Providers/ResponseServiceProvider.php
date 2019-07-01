<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     *
     * @author  ken <wahyu.dhiraashandy8@gmail.com>
     * @since  @version 0.1
     */
    public function boot(ResponseFactory $response)
    {
        $response->macro('successResponse', function (?float $time = null, $data = null, ?string $message = null) {
            return [
                'status_code' => 200,
                'execute_time' => $time,
                'message' => is_null($message) ? 'Request done.' : ucfirst($message),
                'data' => $data
            ];
        });

        $response->macro('failedResponse', function(?float $time = null, ?string $message = null){
            return [
                'status_code' => 500,
                'execute_time' => $time,
                'message' => is_null($message) ? 'Something when wrong, please retry again.' : ucfirst($message),
                'data' => []
            ];
        });

        $response->macro('notFoundResponse', function(?float $time = null, ?string $request = null){
            return [
                'status_code' => 404,
                'execute_time' => $time,
                'message' => is_null($request) ? "Request data {$request} not found." : ucfirst($request),
                'data' => []
            ];
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
