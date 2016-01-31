<?php
/**
 * A view for Slim v3 that uses Windwalker's view for multiple templates (e.g. Blade)
 */
namespace Wordup\View;

use Psr\Http\Message\ResponseInterface;
use Windwalker\Renderer\BladeRenderer;

/**
 * Php View
 *
 * Render PHP view scripts into a PSR-7 Response object
 */
class PhpRenderer
{
    /**
     * @var string
     */
    protected $templatePath;

    /**
     * SlimRenderer constructor.
     *
     * @param string $templatePath
     */
    public function __construct($templatePath = "")
    {
        $this->templatePath = $templatePath;
    }

    /**
     * Render a template
     * @param \ResponseInterface $response
     * @param                    $template
     * @param array              $data
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, $template, array $data = [])
    {
        ob_start();
        $renderer = new BladeRenderer(array(
            $this->templatePath,
        ), array('cache_path' => APPLICATION_PATH . '/../data/cache/blade'));
        echo $renderer->render($template, $data);
        $output = ob_get_clean();

        $response->getBody()->write($output);

        return $response;
    }
}
