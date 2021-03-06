<?php

namespace Nodes\Backend\Auth\Contracts;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

/**
 * Interface ProviderInterface.
 *
 * @interface
 */
interface Provider
{
    /**
     * Authenticate the request and return the authenticated user instance.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Routing\Route $route
     * @return mixed
     */
    public function authenticate(Request $request, Route $route);
}
