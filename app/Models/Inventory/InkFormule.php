<?php

namespace App\Models\Inventory;

use App\Models\HR\User;
use App\Models\Inventory\InkFormuleDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InkFormule extends Model
{
    protected $fillable = [
    'final_color',
    'total_kg',
    'created_by_user_id',
    'updated_by_user_id', // si piensas usarlo luego
];

    public function details(): HasMany
    {
        return $this->hasMany(InkFormuleDetail::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
