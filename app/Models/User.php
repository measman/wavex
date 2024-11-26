<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'isAdmin',
    ];

    protected $with = [
        'settings'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime',
            'isAdmin' => 'boolean',
        ];
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function settings()
    {
        return $this->hasOne(Settings::class);
    }

    public function fetchall()
    {
        $data = User::all();
        foreach ($data as &$row) {
            $row->action_buttons = $this->generateActionButtons($row);
        }
        return $data;
    }

    public function generateActionButtons($row)
    {
        return '
        <button type="button" title="Edit" data-id="' . $row->id . '" 
                class="user-edit bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
            <i class="icon fa fa-edit mr-1"></i>Edit
        </button>
        <button type="button" title="Delete" data-id="' . $row->id . '" 
                class="user-delete bg-red-600 hover:bg-red-700 text-white font-medium py-1 px-2 rounded focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 ml-2">
            <i class="icon fa fa-trash mr-1"></i>Delete
        </button>';
    }

    public function updateuserinfo($request)
    {
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->isAdmin == 'true') {
            $user->isAdmin = true;
        }else {
            $user->isAdmin = false;
        }
        
        $user->save();
    }
}