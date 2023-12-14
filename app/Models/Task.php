<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = "tasks";
    protected $fillable = ['issue', 'description', 'startdate', 'endtdate' , 'user_id'];
    


    use HasFactory ;
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
