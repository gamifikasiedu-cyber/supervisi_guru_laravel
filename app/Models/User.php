<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_KEPALA_SEKOLAH = 'kepala_sekolah';
    public const ROLE_SUPERVISOR = 'supervisor';
    public const ROLE_PENGAWAS = 'pengawas';
    public const ROLE_GURU = 'guru';

    public const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_KEPALA_SEKOLAH => 'Kepala Sekolah',
        self::ROLE_SUPERVISOR => 'Supervisor',
        self::ROLE_PENGAWAS => 'Pengawas',
        self::ROLE_GURU => 'Guru',
    ];

    /**
     * Urutan prioritas untuk menentukan role utama (kolom `role`).
     * Semakin kecil angkanya, semakin tinggi prioritasnya.
     */
    public const ROLE_PRIORITY = [
        self::ROLE_ADMIN => 0,
        self::ROLE_KEPALA_SEKOLAH => 1,
        self::ROLE_SUPERVISOR => 2,
        self::ROLE_GURU => 3,
        self::ROLE_PENGAWAS => 4,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'mata_pelajaran',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Selalu ikutkan relasi role agar tidak terjadi N+1 query.
     *
     * @var array<int, string>
     */
    protected $with = ['userRoles'];

    /**
     * Attributes to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['role_label', 'role_list', 'role_labels'];

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    /**
     * Daftar role yang dimiliki user (berdasarkan hierarki `role`).
     *
     * @return array<int, string>
     */
    public function getRoleListAttribute(): array
    {
        $roles = $this->userRoles->pluck('role')->filter()->all();

        if (empty($roles)) {
            $roles = [$this->role];
        }

        return $this->sortRoles($roles);
    }

    /**
     * Daftar label role yang dimiliki user.
     *
     * @return array<int, string>
     */
    public function getRoleLabelsAttribute(): array
    {
        return array_map(
            fn (string $role) => self::ROLES[$role] ?? $role,
            $this->role_list
        );
    }

    public function hasRole(string ...$roles): bool
    {
        return count(array_intersect($this->role_list, $roles)) > 0;
    }

    /**
     * Menentukan apakah user memegang role tertentu (single check).
     */
    public function hasAnyRoleOf(array $roles): bool
    {
        return $this->hasRole(...$roles);
    }

    /**
     * Menetapkan daftar role untuk user sekaligus menyinkronkan
     * kolom `role` (role utama) dengan role berprioritas tertinggi.
     *
     * @param  array<int, string>  $roles
     */
    public function setRoles(array $roles): void
    {
        $roles = $this->sortRoles(array_values(array_unique(array_filter($roles))));
        $roles = array_values(array_intersect($roles, array_keys(self::ROLES)));

        $primary = $roles[0] ?? self::ROLE_GURU;

        $this->userRoles()->delete();
        foreach ($roles as $role) {
            $this->userRoles()->create(['role' => $role]);
        }

        $this->update(['role' => $primary]);
        $this->load('userRoles');
    }

    public function assignRole(string $role): void
    {
        if (!array_key_exists($role, self::ROLES)) {
            return;
        }

        $this->userRoles()->firstOrCreate(['role' => $role]);
        $this->load('userRoles');
    }

    /**
     * @param  array<int, string>  $roles
     * @return array<int, string>
     */
    private function sortRoles(array $roles): array
    {
        usort($roles, function (string $a, string $b) {
            return (self::ROLE_PRIORITY[$a] ?? 9) <=> (self::ROLE_PRIORITY[$b] ?? 9);
        });

        return $roles;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function isKepalaSekolah(): bool
    {
        return $this->hasRole(self::ROLE_KEPALA_SEKOLAH);
    }

    public function isSupervisor(): bool
    {
        return $this->hasRole(self::ROLE_SUPERVISOR);
    }

    public function isPengawas(): bool
    {
        return $this->hasRole(self::ROLE_PENGAWAS);
    }

    public function isGuru(): bool
    {
        return $this->hasRole(self::ROLE_GURU);
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    // ==== SCOPES ====
    public function scopeStaffPengawas($query)
    {
        return $query->whereHas('userRoles', fn ($q) => $q->where('role', self::ROLE_KEPALA_SEKOLAH));
    }

    public function scopeGuru($query)
    {
        return $query->whereHas('userRoles', fn ($q) => $q->where('role', self::ROLE_GURU));
    }

    public function scopeSupervisor($query)
    {
        return $query->whereHas('userRoles', fn ($q) => $q->where('role', self::ROLE_SUPERVISOR));
    }

    public function scopePengawas($query)
    {
        return $query->whereHas('userRoles', fn ($q) => $q->where('role', self::ROLE_PENGAWAS));
    }

    public function scopeKepalaSekolah($query)
    {
        return $query->whereHas('userRoles', fn ($q) => $q->where('role', self::ROLE_KEPALA_SEKOLAH));
    }

    // ==== RELATIONSHIPS ====
    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }
    // Dokumen perangkat ajar yang diunggah guru
    public function teachingDocuments()
    {
        return $this->hasMany(TeachingDocument::class, 'user_id');
    }

    // Dokumen yang direview oleh supervisor
    public function reviewedDocuments()
    {
        return $this->hasMany(TeachingDocument::class, 'reviewed_by');
    }

    // Observasi yang dilakukan supervisor
    public function observations()
    {
        return $this->hasMany(Observation::class, 'supervisor_id');
    }

    // Observasi yang dialami sebagai guru
    public function observationsReceived()
    {
        return $this->hasMany(Observation::class, 'teacher_id');
    }

    // Supervisi sebagai guru
    public function supervisionsReceived()
    {
        return $this->hasMany(Supervision::class, 'teacher_id');
    }

    // Supervisi sebagai supervisor
    public function supervisions()
    {
        return $this->hasMany(Supervision::class, 'supervisor_id');
    }
}
