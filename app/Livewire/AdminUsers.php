<?php

namespace App\Livewire;

use App\Enums\DeactivationReason;
use App\Enums\UserStatus;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

/** Utilisateurs (Admin) : recherche, gestion des rôles, suspension et réactivation avec motif. */
class AdminUsers extends Component
{
    public string $search = '';

    /** Suspension en cours : formulaire de motif ouvert pour un utilisateur. */
    public ?int $suspendingId = null;

    public string $reason = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('users.manage'), 403);
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->when(trim($this->search) !== '', function ($q) {
                $term = '%'.trim($this->search).'%';
                $q->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term)->orWhere('phone', 'like', $term));
            })
            ->with('roles')
            ->orderBy('name')
            ->limit(100)
            ->get();
    }

    public function roles(): array
    {
        return array_keys(RolesAndPermissionsSeeder::MATRIX);
    }

    public function toggleRole(int $userId, string $role): void
    {
        abort_unless(auth()->user()->can('users.manage'), 403);
        abort_unless(in_array($role, $this->roles(), true), 422);

        $user = User::findOrFail($userId);
        $user->hasRole($role) ? $user->removeRole($role) : $user->assignRole($role);

        unset($this->users);
    }

    public function startSuspend(int $userId): void
    {
        $this->suspendingId = $userId;
        $this->reason = '';
        $this->resetErrorBag();
    }

    public function cancelSuspend(): void
    {
        $this->suspendingId = null;
        $this->reason = '';
    }

    public function suspend(): void
    {
        $user = User::findOrFail($this->suspendingId);
        abort_unless(Gate::allows('deactivate', $user), 403);

        $this->validate(['reason' => ['required', 'string', 'max:255']], [
            'reason.required' => 'Indiquez le motif de la suspension.',
        ]);

        $user->update([
            'status' => UserStatus::Suspended,
            'deactivated_at' => now(),
            'deactivation_reason' => DeactivationReason::Admin,
        ]);

        activity()->performedOn($user)->causedBy(auth()->user())->event('suspended')
            ->withProperties(['motif' => $this->reason])->log('Compte suspendu');

        $this->cancelSuspend();
        unset($this->users);
    }

    public function reactivate(int $userId): void
    {
        $user = User::findOrFail($userId);
        abort_unless(Gate::allows('deactivate', $user), 403);

        $user->update([
            'status' => UserStatus::Active,
            'reactivated_at' => now(),
            'deactivated_at' => null,
            'deactivation_reason' => null,
        ]);

        activity()->performedOn($user)->causedBy(auth()->user())->event('reactivated')->log('Compte réactivé');

        unset($this->users);
    }

    public function render()
    {
        return view('livewire.admin-users');
    }
}
