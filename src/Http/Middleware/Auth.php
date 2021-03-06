<?php

namespace Nodes\Backend\Http\Middleware;

use Closure;
use Nodes\Backend\Support\FlashRestorer;

/**
 * Class Auth.
 */
class Auth
{
    /**
     * Check to see if user is authenticated.
     * If not, redirect user to login.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure                  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Route is protected and requires a user session
        //
        // If user is not already logged in, we'll try and
        // look for the user in sessions and cookies.
        if (! backend_user_check()) {
            try {
                backend_user_authenticate();
            } catch (\Exception $e) {
                // Create redirect response
                $redirectResponse = redirect()->route('nodes.backend.login.form', [
                    'redirect_url' => $request->url()
                ])->with('warning', 'Oops! You\'re not logged in.');

                // Apply existing flash messages
                (new FlashRestorer)->apply($redirectResponse);

                // Redirect with cookie
                return $redirectResponse;
            }
        }

        return $next($request);
    }
}
