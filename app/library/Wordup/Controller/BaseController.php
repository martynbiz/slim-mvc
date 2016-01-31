<?php
namespace Wordup\Controller;

use MartynBiz\Slim3Controller\Controller;

abstract class BaseController extends Controller
{
    /**
     * @var User
     */
    protected $currentUser;

    /**
     * Render the view from within the controller
     * @param string $file Name of the template/ view to render
     * @param array $args Additional varables to pass to the view
     * @param Response?
     */
    protected function render($file, $args=array())
    {
        $container = $this->app->getContainer();

        // attach the current user to the view variables
        $args['current_user'] = $this->get('auth')->getCurrentUser();

        // attach any flash messages
        $args['flash_messages'] = $this->get('flash')->flushMessages();

        return $container->renderer->render($this->response, $file, $args);
    }
}
