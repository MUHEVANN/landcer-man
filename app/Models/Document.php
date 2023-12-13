<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $table = 'document';
    protected $fillable = ['document', 'purpose_id'];
    public function purposes()
    {
        return $this->belongsTo(Purposes::class, 'purpose_id', 'id');
    }
}
