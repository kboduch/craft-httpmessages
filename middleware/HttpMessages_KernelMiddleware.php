<?php

namespace Craft;

use Craft\HttpMessages_CraftRequest as Request;
use Craft\HttpMessages_CraftResponse as Response;

class HttpMessages_KernelMiddleware
{
    /**
     * __invoke Magic Method
     *
     * @param Request  $request  Request
     * @param Response $response Response
     * @param callable $next     Next
     *
     * @return Response Response
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $controller = $request->getRoute()->getController();
        $method = $request->getRoute()->getMethod();

        $controller = new $controller();

        $response = $controller->$method($request, $response);

        return $response;
    }
}