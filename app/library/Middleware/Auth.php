<?php
namespace App\Middleware;

use App\Auth\AuthInterface;

class Auth
{
    /**
     * @var App\Auth\Auth
     */
    protected $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

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
        // check if user is authenticated, otherwise return 401/ redirect/ etc
        if (! $this->auth->isAuthenticated() ) {
            // return $response->withStatus(401);
            return $response->withRedirect('/session/login', 401);
        }

        // pass onto the next callable
        $response = $next($request, $response);

        return $response;
    }
}
