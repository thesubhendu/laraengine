<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    public function cruds(): HasMany
    {
        return $this->hasMany(Crud::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generatedCodeDirectoryName(){
        return 'generated_code/' . \Str::snake($this->user->name) . '/' . \Str::snake($this->name);
    }

    public function generatedCodeDirectoryPath(){
        return public_path($this->generatedCodeDirectoryName());
    }

    public function zipName()
    {
        return $this->generatedCodeDirectoryPath().'/'.\Str::snake($this->name).'.zip';

    }
}
