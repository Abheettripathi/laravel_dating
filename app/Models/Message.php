<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages'; // Ensure this matches your database

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'receiver_name',
        'title',
        'description',
    ];
}
