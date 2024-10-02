<?php 
// Project Model
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description'];

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_project');
    }
}