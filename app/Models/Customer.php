<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Bills;

class Customer extends Model
{
    use HasFactory;
    protected $table="customer";
    public function bills(): Hasmany
    {
        return $this->hasmany(Bills::class,'id_customer','id');
    }
}
