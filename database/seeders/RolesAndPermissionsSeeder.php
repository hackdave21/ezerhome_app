<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Rôles et permissions issus de la matrice du cahier des charges (§3.2, annexe E du guide).
 * Les rôles se cumulent. Le super-admin technique n'a aucune permission sur les contenus sensibles
 * (messages, demandes, notes) : uniquement la maintenance.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    public const MATRIX = [
        'consultant' => [
            'profile.manage', 'charter.sign', 'listening.request', 'listening.view-own', 'offers.register',
        ],
        'therapeute' => [
            'profile.manage', 'listening.view-assigned', 'meetings.schedule', 'meetings.notes',
            'meetings.notes.read-own', 'offers.register', 'offers.manage-own',
        ],
        'formateur_coach' => [
            'profile.manage', 'listening.view-assigned', 'meetings.schedule', 'meetings.notes',
            'meetings.notes.read-own', 'offers.register', 'offers.manage-own',
        ],
        'comite' => [
            'profile.manage', 'assistance.view', 'assistance.update-status', 'media.manage-assistance',
        ],
        'admin' => [
            'profile.manage', 'assistance.view', 'assistance.update-status', 'media.manage',
            'media.manage-assistance', 'org.manage', 'payments.confirm-manual', 'donations.view',
            'users.manage', 'accounts.deactivate', 'audit.view', 'settings.manage', 'applications.review',
            'assignments.manage', 'listening.view-any', 'meetings.follow', 'meetings.notes.read-closed',
            'offers.view-registrants',
        ],
        'super_admin' => [
            'settings.manage', 'audit.view', 'users.manage',
        ],
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect(self::MATRIX)->flatten()->unique();

        foreach ($permissions as $name) {
            Permission::findOrCreate($name, 'web');
        }

        foreach (self::MATRIX as $role => $perms) {
            Role::findOrCreate($role, 'web')->syncPermissions($perms);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
