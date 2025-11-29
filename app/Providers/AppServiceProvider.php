<?php

namespace App\Providers;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Tugas;
use App\Policies\KelasPolicy;
use App\Policies\MateriPolicy;
use App\Policies\TugasPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Kelas::class => KelasPolicy::class,
        Materi::class => MateriPolicy::class,
        Tugas::class => TugasPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}