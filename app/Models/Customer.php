<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name','company','phone','country', 'email'];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'customer_project');
    }
}