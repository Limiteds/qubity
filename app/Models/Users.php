<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'password',
        'refID',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $table = 'users';

    public function referrals()
    {
        return $this->hasMany('App\Models\Referrals', 'user_id', 'id');
    }

    public function clicks()
    {
        return $this->hasMany('App\Models\Clicks', 'ref_id', 'refID');
    }
}
