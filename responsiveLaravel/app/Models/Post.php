<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
 /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'commentaire', 'dateDeCreation', 'dateDeModification',
    ];

    /**
     * Get the media associated with the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media()
    {
        return $this->hasMany(Media::class);
    }}
