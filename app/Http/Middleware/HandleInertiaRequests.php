<?php

namespace App\Http\Middleware;

use App\Fhir\Base\Resource\DomainResource\Practitioner;
use App\Sessions\SelectedPatientSession;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    public function __construct(private readonly SelectedPatientSession $selectedPatientSession) {}

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        if (! ($user = $request->user()) || $user->authable_type !== Practitioner::class) {
            auth()->logout();
            redirect()->route('login');

            return [];
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => Practitioner::where('id', $user->authable_id)
                    ->first()
                    ->only(['id', 'name', 'identifier']),
            ],
            'patient' => $this->selectedPatientSession->get(),
        ];
    }
}
