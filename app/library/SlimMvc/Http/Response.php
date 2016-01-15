<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @link      https://github.com/slimphp/Slim
 * @copyright Copyright (c) 2011-2015 Josh Lockhart
 * @license   https://github.com/slimphp/Slim/blob/3.x/LICENSE.md (MIT License)
 */
namespace SlimMvc\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Response
 *
 * This class represents an HTTP response. It manages
 * the response status, headers, and body
 * according to the PSR-7 standard.
 *
 * @link https://github.com/php-fig/http-message/blob/master/src/MessageInterface.php
 * @link https://github.com/php-fig/http-message/blob/master/src/ResponseInterface.php
 */
class Response extends \Slim\Http\Response implements ResponseInterface
{

    /**
     * @var string Name of last controller called
     */
    protected $controllerName;

    /**
     * @var string Name of last action called
     */
    protected $actionName;

    /**
     * @var string Status code of last request
     */
    protected $statusCode;

    /**
     * Get the last controller called
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * Set the last controller called
     */
    public function setControllerName($controllerName)
    {
        return $this->controllerName = $controllerName;
    }

    /**
     * Get the last controller called
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * Set the last controller called
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;
    }
}
