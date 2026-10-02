<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['title', 'description'])]
#[Hidden(['user_id'])]
#[Table('lists')]
class TaskList extends Model
{
    use HasUuids, HasFactory;

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
