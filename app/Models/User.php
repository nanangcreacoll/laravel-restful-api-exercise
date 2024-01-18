<?php

namespace App\Models;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    public $fillable = [
        'username',
        'password',
        'name'
    ];

    public function contacts() : HasMany {
        return $this->hasMany(Contact::class, "user_id", "id");
    }
}
