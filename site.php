<?php

namespace TF;

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Twig_Function;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Symfony\Component\VarDumper;

/**
 * Sets up barebones routing and templating
 */
class Site
{
    /**
     * Twig environment
     * @var Twig_Environment
     */
    public $twig;

    /**
     * Twig loader
     * @var Twig_Loader_Filesystem
     */
    public $loader;

    /**
     * Router
     * @var RouteCollector
     */
    public $router;

    /**
     * The path of the url
     * @var string
     */
    public $page;

    /**
     * Name of the template to load
     * @var string
     */
    public $template;

    /**
     * Server request method
     * @var string
     */
    public $requestMethod;

    public function __construct()
    {
        $this->loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
        $this->twig = new Twig_Environment($this->loader);
        $this->router = new RouteCollector();
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->page = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->template = $this->getTemplateFromUrl();
    }

    /**
     * Fire it up
     */
    public function start()
    {
        $this->twigFunctions();
        $this->setupRouting();
    }

    /**
     * Add any custom Twig functions
     */
    private function twigFunctions()
    {
        /**
         * Updates an assets url if using hot module reloading
         * @var Twig_Function
         */
        $assetHelper = new Twig_Function('assets', function ($path) {
            if (file_exists(__DIR__ . '/hot')) {
                return 'http://localhost:8080/public' . $path;
            }

            return $path;
        });

        /**
         * Fancier way to dump things
         * @var Twig_Function
         */
        $dump = new Twig_Function('dump', function ($arg) {
            return dump($arg);
        });

        $this->twig->addFunction($assetHelper);
        $this->twig->addFunction($dump);
    }

    /**
     * Handles routing, only basic GET requests
     */
    private function setupRouting()
    {
        $this->router->get($this->page, function () {
            return $this->twig->render($this->template);
        });

        $dispatcher = new Dispatcher($this->router->getData());

        // listen for a request to generate a response
        $response = $dispatcher->dispatch($this->requestMethod, $this->page);

        echo $response;
    }

    /**
     * Figures out the template to use from the url
     * @return string
     */
    private function getTemplateFromUrl()
    {
        $segments = explode('/', $this->page);
        $lastSegment = array_pop($segments);
        return $lastSegment === '' ? 'index.twig' : $lastSegment . '.twig';
    }
}
