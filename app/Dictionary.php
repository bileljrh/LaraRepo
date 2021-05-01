<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Dictionary extends Model
{
    //protected $table="dictionaries";
    
    //protected $table="dictionaries";


    protected $fillable = ['user_id', 'word', 'slug','definition','is_approved'];
    

    public function user()
    {
        return $this->belongsTo(User::class);

    }
}
