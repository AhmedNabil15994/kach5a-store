<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class UserFireBaseToken extends Model
{
    protected $table = 'user_firebase_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'firebase_token', 'device_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
