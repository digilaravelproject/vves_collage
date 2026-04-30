<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static void addGlobalScope(\Illuminate\Database\Eloquent\Scope|string $scope, \Closure $implementation = null)
 */
trait HasInstitutionScope
{
    protected static function bootHasInstitutionScope()
    {
        static::addGlobalScope('institution_scope', function (Builder $builder) {
            // Check if user is authenticated inside the scope to ensure it works in all contexts
            if (!Auth::hasUser()) {
                return;
            }

            $user = Auth::user();

            // Admins bypass the scope
            if ($user->hasRole(['Super Admin', 'Admin', 'admin'])) {
                return;
            }

            // Apply scope for Makers and Approvers (Restricted Users)
            // Ensure $user is an actual User model to prevent property access errors on generic Authenticatable
            if (!($user instanceof \App\Models\User)) {
                return;
            }

            // Use withoutGlobalScope to prevent infinite recursion
            $institutionIds = $user->institutions()->withoutGlobalScope('institution_scope')->pluck('institutions.id')->toArray();
            $model = $builder->getModel();
            $table = $model->getTable();

            $builder->where(function ($query) use ($institutionIds, $user, $model, $table) {
                // 1. If the model IS an Institution, scope by its own ID
                if ($model instanceof \App\Models\Institution) {
                    $query->whereIn("{$table}.id", $institutionIds);
                }
                // 2. If the model HAS an institution_id column, scope by it
                // We use a property or method check if possible, or fallback to Schema
                elseif (method_exists($model, 'hasInstitutionId') && $model->hasInstitutionId()) {
                    $query->whereIn("{$table}.institution_id", $institutionIds);
                } elseif (Schema::hasColumn($table, 'institution_id')) {
                    $query->whereIn("{$table}.institution_id", $institutionIds);
                }

                // 3. Special case for PendingAction: Makers should always see their own requests
                if ($model instanceof \App\Models\PendingAction) {
                    $query->orWhere("{$table}.maker_id", $user->id);
                }
            });
        });
    }
}
