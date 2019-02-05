<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'user_id',
        'product_ID',
        'quantity',
        'is_discounted',
        'invoice_ID'
    ];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'invoice_items';
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_ID');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Invoice', 'invoice_ID', 'ID');
    }
}
