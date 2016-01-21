<?php
namespace App\Middleware;

class Auth
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        // before


        if (false) {
            // Not authenticated and must be authenticated to access this resource
            return $response->withStatus(401);
        }

        // pass onto the next callable
        $response = $next($request, $response);

        // after


        return $response;
    }
}
