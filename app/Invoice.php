<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'address_ID',
        'price',
        'date',
        'status',
        'trackingcode',
        'user_id'
    ];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'invoices';

    public static function boot()
    {
        parent::boot();

        static::deleting(function($model) {
            // remove relations to products
            $model->items()->delete();
        });
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo('App\Address', 'address_ID', 'ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\InvoiceItem', 'invoice_ID', 'ID');
    }

}
