<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Role-based dashboard route
        if ($user->hasRole('admin')) {
            $dashboardRoute = route('admin.dashboard');
        } elseif ($user->hasRole('manager')) {
            $dashboardRoute = route('manager.dashboard');
        } elseif ($user->hasRole('contributor')) {
            $dashboardRoute = route('contributor.dashboard');
        } else {
            $dashboardRoute = route('projects.index'); // default route
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended($dashboardRoute . '?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended($dashboardRoute . '?verified=1');
    }
}
