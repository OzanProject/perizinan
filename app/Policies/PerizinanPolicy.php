<?php

namespace App\Policies;

use App\Models\Perizinan;
use App\Models\User;
use App\Enums\PerizinanStatus;
use Illuminate\Auth\Access\Response;

class PerizinanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user, Perizinan $perizinan): bool
    {
        if ($user->hasRole('super_admin')) {
            return $user->dinas_id === $perizinan->dinas_id;
        }
        return $user->lembaga_id === $perizinan->lembaga_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin_lembaga');
    }

    public function update(User $user, Perizinan $perizinan): bool
    {
        return $user->lembaga_id === $perizinan->lembaga_id &&
            in_array($perizinan->status, [PerizinanStatus::DRAFT->value, PerizinanStatus::PERBAIKAN->value]);
    }

    public function confirmTaken(User $user, Perizinan $perizinan): bool
    {
        return $user->lembaga_id === $perizinan->lembaga_id &&
            $perizinan->status === PerizinanStatus::SIAP_DIAMBIL->value;
    }

    public function verify(User $user, Perizinan $perizinan): bool
    {
        return $user->hasRole('super_admin') &&
            $user->dinas_id === $perizinan->dinas_id &&
            in_array($perizinan->status, [
                PerizinanStatus::DIAJUKAN->value,
                PerizinanStatus::DISETUJUI->value,
                PerizinanStatus::SIAP_DIAMBIL->value,
                PerizinanStatus::SELESAI->value
            ]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Perizinan $perizinan): bool
    {
        return $user->lembaga_id === $perizinan->lembaga_id &&
            in_array($perizinan->status, [PerizinanStatus::DRAFT->value, PerizinanStatus::PERBAIKAN->value]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Perizinan $perizinan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Perizinan $perizinan): bool
    {
        return false;
    }
}
