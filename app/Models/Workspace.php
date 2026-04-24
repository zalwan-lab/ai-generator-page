<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    protected $fillable = ['user_id', 'name', 'description', 'color', 'emoji'];

    public const COLORS = ['brand', 'emerald', 'rose', 'amber', 'sky', 'fuchsia'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salesPages(): HasMany
    {
        return $this->hasMany(SalesPage::class)->latest();
    }

    public function initial(): string
    {
        return $this->emoji ?: strtoupper(mb_substr($this->name, 0, 1));
    }

    /**
     * @return array{bg:string, bg50:string, text:string, border:string, ring:string}
     */
    public function colorClasses(): array
    {
        $c = in_array($this->color, self::COLORS, true) ? $this->color : 'brand';

        // These strings must appear literally in the rendered HTML for Tailwind CDN
        // to pick them up; the color picker modal renders all of them so we're safe.
        return [
            'brand'   => ['bg'=>'bg-brand-500',  'bg50'=>'bg-brand-50',  'text'=>'text-brand-700',  'border'=>'border-brand-200',  'ring'=>'ring-brand-500'],
            'emerald' => ['bg'=>'bg-emerald-500','bg50'=>'bg-emerald-50','text'=>'text-emerald-700','border'=>'border-emerald-200','ring'=>'ring-emerald-500'],
            'rose'    => ['bg'=>'bg-rose-500',   'bg50'=>'bg-rose-50',   'text'=>'text-rose-700',   'border'=>'border-rose-200',   'ring'=>'ring-rose-500'],
            'amber'   => ['bg'=>'bg-amber-500',  'bg50'=>'bg-amber-50',  'text'=>'text-amber-700',  'border'=>'border-amber-200',  'ring'=>'ring-amber-500'],
            'sky'     => ['bg'=>'bg-sky-500',    'bg50'=>'bg-sky-50',    'text'=>'text-sky-700',    'border'=>'border-sky-200',    'ring'=>'ring-sky-500'],
            'fuchsia' => ['bg'=>'bg-fuchsia-500','bg50'=>'bg-fuchsia-50','text'=>'text-fuchsia-700','border'=>'border-fuchsia-200','ring'=>'ring-fuchsia-500'],
        ][$c];
    }
}
