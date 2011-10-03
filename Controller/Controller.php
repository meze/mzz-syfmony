<?php
namespace Mzz\MzzBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Mzz\MzzBundle\Response\JsonResponseMessage;
use Mzz\MzzBundle\Templating\ViewTemplateResolver;

class Controller extends ContainerAware
{
    protected $request;
    protected $templateExtension = "html.twig";

    /**
     *
     * @param \Mzz\MzzBundle\Request\Request $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Creates a Response instance that closes the current JIP window
     *
     * @param string  $content The Response body
     * @param integer $status  The status code
     * @param array   $headers An array of HTTP headers
     *
     * @return Response A Response instance
     */
    public function jipClose($timeout = 1500, $status = 200, array $headers = array())
    {
        $content = 'jipWindow.close({after: ' . (int)$timeout . '});';
     	return $this->javascript($content, $status, $headers);
    }

    /**
     * Creates a Response instance that contains javascript code to be executed by the browser
     *
     * @param string  $content The Response body
     * @param integer $status  The status code
     * @param array   $headers An array of HTTP headers
     *
     * @return Response A Response instance
     */
    public function javascript($content = '', $status = 200, array $headers = array())
    {
     	return $this->createResponse($content, $status,
     		array_merge(array('Content-Type' => 'application/javascript'), $headers));
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param  string  $name       The name of the route
     * @param  array   $parameters An array of parameters
     * @param  Boolean $absolute   Whether to generate an absolute URL
     *
     * @return string The generated URL
     */
    public function url($route, array $parameters = array(), $absolute = false)
    {
        return $this->container->get('router')->generate($route, $parameters, $absolute);
    }

    /**
     * Forwards the request to another controller.
     *
     * @param  string  $controller The controller name (a string like Blog:Post:index)
     * @param  array   $path       An array of path parameters
     * @param  array   $query      An array of query parameters
     *
     * @return Response A Response instance
     */
    public function forward($controller, array $path = array(), array $query = array())
    {
        return $this->container->get('controller_resolver')->forward($controller, $path, $query);
    }

    /**
     * Returns an HTTP redirect Response.
     *
     * @return Response A Response instance
     */
    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view       The view name
     * @param array  $parameters An array of parameters to pass to the view
     *
     * @return string The renderer view
     */
    public function renderView($view, array $parameters = array())
    {
        return $this->container->get('templating')->render($view, $parameters);
    }

    /**
     * Renders a view.
     *
     * @param string   $view The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->container->get('templating')->renderResponse($view, $parameters, $response);
    }

    /**
     * Returns the content encoded into JSON
     *
     * @param mixed $content
     * @param integer $status
     * @param array $headers
     * @return Response
     */
    public function json($content = '', $status = 200, array $headers = array())
    {
        if ($content instanceof JsonResponseMessage)
            $content = $content->__toString();
        if (!\is_scalar($content))
            $content = \json_encode ($content);

     	return $this->createResponse($content, $status,
     		array_merge(array('Content-Type' => 'application/json'), $headers));
    }

    /**
     * Creates a Response instance.
     *
     * @param string  $content The Response body
     * @param integer $status  The status code
     * @param array   $headers An array of HTTP headers
     *
     * @return Response A Response instance
     */
    public function createResponse($content = '', $status = 200, array $headers = array())
    {
        $response = new Response;
        $response->setContent($content);
        $response->setStatusCode($status);
        foreach ($headers as $name => $value) {
            $response->headers->set($name, $value);
        }

        return $response;
    }

    /**
     * Renders a view.
     *
     * @param string   $view The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response A response instance
     *
     * @return Response A Response instance
     */
    public function view(array $parameters = array(), Response $response = null, $extension = '')
    {
        $extension = !empty($extension) ? $extension : $this->templateExtension;
        $view = ViewTemplateResolver::resolve($this->request->get('_controller'), get_called_class());
        return $this->render($view . '.' . $extension, $parameters, $response);
    }

    /**
     * Binds the current request to a form and validates the given form
     *
     *
     * @param \Symfony\Component\Form\Form $form
     * @param boolean $post_only only do validation if the request method is POST
     * @return boolean
     */
    public function isValidForm($form, $post_only = true)
    {
        if (($post_only && !$this->request->isPost()) || !$post_only)
            return;

        $form->bindRequest($this->request);
        return $form->isValid();
    }
}