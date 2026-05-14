<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\HR\Users;

class OrderNote extends Model
{
    protected $table = 'order_notes';

    protected $fillable = ['notable_type', 'notable_id', 'user_id', 'body'];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'Users_ID');
    }
}
