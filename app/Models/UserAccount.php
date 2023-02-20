<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAccount extends Model
{
    use HasFactory;

    protected $table = "users_accounts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_date',
        'end_date',
        'user_id',
        'account_id',
    ];

    public function users(): BelongsTo 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function accounts(): BelongsTo 
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
