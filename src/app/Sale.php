<?php

namespace App;
use App\SaleEntry;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Sale extends Model
{
    //
    protected $table = 'sales';
    protected $fillable = ['reference_code', 'creator_id', 'customer_id'];

    public function entries() 
    {
        return $this->hasMany('App\SaleEntry');
    }

    public function currency()
    {
        return $this->belongsTo('App\currency');
    }

    public function payment()
    {
        return $this->hasOne('App\PaymentIn');
    }

    public function creator()
    {
        return $this->belongsTo('App\User');
    }

}
