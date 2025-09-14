<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Cors implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    // public function before(RequestInterface $request, $arguments = null)
    // {
    //     //
    //     header("Access-Control-Allow-Origin: *");
    //     header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Requested-Method, Authorization");
    //     header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, PUT, DELETE");
    //     header("Access-Control-Max-Age: 86400");
    //     $method = $_SERVER['REQUEST_METHOD'];
    //     if ($method == "OPTIONS") {
    //         # code...
    //         die();
    //     }
    // }

    // public function before(RequestInterface $request, $arguments = null)
    // {
    //     $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    //     $allowedOrigins = ['http://192.168.25.199'];

    //     if (in_array($origin, $allowedOrigins)) {
    //         header("Access-Control-Allow-Origin: $origin");
    //     } else {
    //         header("Access-Control-Allow-Origin: http://localhost");
    //     }

    //     header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Requested-Method, Authorization");
    //     header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, PUT, DELETE");
    //     header("Access-Control-Max-Age: 86400");

    //     if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    //         header("HTTP/1.1 204 No Content");
    //         exit;
    //     }
    // }

    // public function before(RequestInterface $request, $arguments = null)
    // {
    //     // Set header CORS
    //     header("Access-Control-Allow-Origin: *");
    //     header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    //     header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    //     // Untuk preflight request
    //     if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    //         header("HTTP/1.1 200 OK");
    //         exit;
    //     }
    // }

    public function before(RequestInterface $request, $arguments = null)
    {
        // CORS headers
        header("Permissions-Policy: accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=()");

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Max-Age: 86400");

        // Untuk preflight request (OPTIONS)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            // Bisa juga pakai: http_response_code(204);
            header("HTTP/1.1 204 No Content");
            exit;
        }
    }


    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }

    // public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    // {
    //     $response->setHeader('Access-Control-Allow-Origin', '*')
    //         ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PATCH, PUT, DELETE')
    //         ->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Authorization');
    // }
}
