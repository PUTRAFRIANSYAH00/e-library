<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public const STATUSES = [
        'Available' => 'Tersedia',
        'Unavailable' => 'Tidak tersedia',
        'Borrowed' => 'Dipinjam',
        'Pending' => 'Menunggu Persetujuan'
    ];

    protected $fillable = [
        'title',
        'synopsis',
        'publisher',
        'writer',
        'publish_year',
        'cover',
        'pdf_path',
        'category',
        'amount',
        'status',
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    // Di App\Models\Book

public function isAvailableForBorrow($requestedAmount)
{
    return $this->status === self::STATUSES['Available'] && $this->amount >= $requestedAmount;
}

public function hasPendingBorrow($userId)
{
    return $this->borrows()
        ->where('user_id', $userId)
        ->where('status', 'pending')
        ->exists();
}


}
