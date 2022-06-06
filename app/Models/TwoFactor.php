<?php

namespace App\Models;

use App\Jobs\SendSms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoFactor extends Model
{
    use HasFactory;

    private const  CODE_EXPIRY = 60; // seconds

    protected $table = 'two_factors';
    protected $fillable = [
        'user_id', 'code'
    ];

    public static function generateCodeFor($user)
    {
        $user->code()->delete();

        return static::create([
            'user_id' => $user->id,
            'code' => random_int(1000, 9999)
        ]);
    }

    public function send()
    {
        SendSms::dispatch($this->user, $this->code_to_send);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCodeToSendAttribute()
    {
        return __('auth.code to send',[
            'code' => $this->code
        ]);
    }

    public function isExpired()
    {
       return $this->created_at->diffInSeconds(now()) > static::CODE_EXPIRY;
    }

    public function isEqualWith(string $code)
    {
        return $this->code == $code;
    }
}
