<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function salesPages(): HasMany
    {
        return $this->hasMany(SalesPage::class)->latest();
    }

    public function workspaces(): HasMany
    {
        return $this->hasMany(Workspace::class)->orderBy('created_at');
    }

    public function currentWorkspaceRelation(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'current_workspace_id');
    }

    /**
     * Returns the current workspace, auto-healing if it's missing, stale,
     * or never created (new user flow).
     */
    public function currentWorkspace(): Workspace
    {
        $ws = $this->current_workspace_id
            ? Workspace::where('id', $this->current_workspace_id)->where('user_id', $this->id)->first()
            : null;

        if (! $ws) {
            $ws = $this->workspaces()->first()
                ?? $this->workspaces()->create(['name' => 'Default', 'color' => 'brand']);

            $this->forceFill(['current_workspace_id' => $ws->id])->save();
        }

        return $ws;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
}
