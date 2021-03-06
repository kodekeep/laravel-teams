<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Teams.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\Teams\Http\Middleware;

class VerifyUserHasTeam
{
    public function handle($request, $next)
    {
        $user = $request->user();

        if ($user && ! $user->hasTeams()) {
            return back();
        }

        return $next($request);
    }
}
