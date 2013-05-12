<?php
namespace Mzz\MzzBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Mzz\MzzBundle\Response\JsonResponseMessage;
use Mzz\MzzBundle\Templating\ViewTemplateResolver;
use Mzz\MzzBundle\Orm\Pagination;
USE Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class Controller extends ContainerAware
{
    protected $templateExtension = "html.twig";

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
     * @param string $controller The controller name (a string like BlogBundle:Post:index)
     * @param array  $path       An array of path parameters
     * @param array  $query      An array of query parameters
     *
     * @return Response A Response instance
     */
    public function forward($controller, array $path = array(), array $query = array())
    {
        return $this->container->get('http_kernel')->forward($controller, $path, $query);
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
        $view = ViewTemplateResolver::resolve($this->get('request')->get('_controller'), get_called_class());
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
        if (($post_only && $this->get('request')->getMethod() !== 'POST') || !$post_only)
            return;

        $form->bindRequest($this->get('request'));
        return $form->isValid();
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string|FormTypeInterface $type    The built type of the form
     * @param mixed $data                       The initial data for the form
     * @param array $options                    Options for the form
     *
     * @return Form
     */
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }

    /**
     * Creates and returns a form builder instance
     *
     * @param mixed $data               The initial data for the form
     * @param array $options            Options for the form
     *
     * @return FormBuilder
     */
    public function createFormBuilder($data = null, array $options = array())
    {
        return $this->container->get('form.factory')->createBuilder('form', $data, $options);
    }

    /**
     * Shortcut to return the request service.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->container->get('request');
    }

    /**
     * Shortcut to return the Doctrine Registry service.
     *
     * @return Registry
     *
     * @throws \LogicException If DoctrineBundle is not available
     */
    public function getDoctrine()
    {
        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not installed in your application.');
        }

        return $this->container->get('doctrine');
    }

    /**
     * Returns true if the service id is defined.
     *
     * @param  string  $id The service id
     *
     * @return Boolean true if the service id is defined, false otherwise
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * Gets a service by id.
     *
     * @param  string $id The service id
     *
     * @return object The service
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    public function getUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }

    protected function createPagination($currentPage)
    {
        $perPage = $this->container->getParameter('pagination.default_items_per_page');
        $pagination = new Pagination($perPage);
        $pagination->setCurrentPage($currentPage);
        return $pagination;
    }
}
