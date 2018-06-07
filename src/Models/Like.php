<?php

namespace SiranixSociety\HMLikes\Models;

use Illuminate\Database\Eloquent\Model;
use SiranixSociety\HMFramework\Traits\HMSettingsHelper;

class Like extends Model {

    /*
     * Traits and other things it uses
     */
    use HMSettingsHelper;

    public function __construct(){
        $this->setTable(config('HMLikes.TableNames.Likes'));
    }

    protected $primaryKey = 'ID';
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'Like', 'Liker_id', 'Liker_type'
    ];

    protected $casts = [
        'Like' => 'boolean'
    ];

    /*
     * Relationships
     */
    public function Likeable(){
        return $this->morphTo();
    }
    public function Liker(){
        return $this->morphTo();
    }

    /*
     * Scopes
     */
    // Like Scopes
    public function scopeLikes($query){
        return $query->where('Like', true);
    }
    public function scopeLikeCount($query){
        return $this->scopeLikes($query)->count();
    }

    // Dislike Scopes
    public function scopeDislikes($query){
        return $query->where('Like', false);
    }
    public function scopeDislikeCount($query){
        return $this->scopeDislikes($query)->count();
    }

    // Score Scopes
    public function scopeLikeScore(){
        $Dislikes = $this->DislikeCount();
        $Likes = $this->LikeCount();
        return $Likes - $Dislikes;
    }
}