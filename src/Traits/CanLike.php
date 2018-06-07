<?php

namespace SiranixSociety\HMLikes\Traits;

use SiranixSociety\HMLikes\Models\Like;

trait CanLike
{
    /*
     * Relationships
     */
    public function MyLikes()
    {
        return $this->morphMany(
            config('HelperModels.Structure.Models.Like'),
            'Liker'
        );
    }
    /*
     * Additional Functions
     */
    public function HasLiked($Model = null){
        if(empty($Model)){
            if($this->MyLikes()->count() > 0){
                return true;
            }
            return false;
        }
        $KeyName = $this->getKeyName();
        return $Model->Likes()->where('Liker_type', get_class())->where('Liker_id', $this->$KeyName)->exists();
    }
    public function GetLike($Model = null){
        if(empty($Model)){
            return $this->MyLikes()->orderBy('CreatedAt', 'DESC')->first();
        }
        $KeyName = $this->getKeyName();
        return $Model->Likes()->where('Liker_type', get_class())->where('Liker_id', $this->$KeyName)->orderBy('CreatedAt', 'DESC')->first();
    }
    public function GetLikes($Model = null){
        if(empty($Model)){
            return $this->MyLikes()->get();
        }
        $KeyName = $this->getKeyName();
        return $Model->Likes()->where('Liker_type', get_class())->where('Liker_id', $this->$KeyName)->get();
    }

    /*
     * Helper Functions
     */
    public function CanLike($Model = null){
        if(!$this->HMLikeIsEnabled() || empty($Model)){
            return $this->HMLikeIsEnabled();
        }
        if($this->HMLikeIsLimited() || $Model->HMLikeIsLimited()){
            $Limitation = $this->HMLikeGetLimitation($Model);
            if(isset($Limitation['Enabled'])){
                return $Limitation['Enabled'];
            }
            return false;
        }
        return true;
    }
    public function CanLikeNow($Model = null){
        if(!$this->HMLikeCanBeLimited()){
            return true;
        }
        if(!$this->CanLike($Model)){
            return false;
        }

        $Limitation = $this->HMLikeGetLimitation($Model);
        $LimitationMode = $this->HMGetLimitationMode($Limitation);
        $LimitationAmount = $this->HMGetLimitationAmount($Limitation);

        if($this->HMLimitationUsesTime($Limitation)){
            $LimitationTime = $this->HMGetLimitationTime($Limitation);
        }
        $ModelKeyName = $Model->getKeyName();

        if($this->MyLikes()->count() < $LimitationAmount || $LimitationAmount === 0){
            return true;
        }
        if(!empty($Model)) {
            if (!$this->CanLike($Model)) {
                return false;
            }
        }
        if($LimitationMode === 0){
            if(!$this->HMLimitationUsesTime($Limitation)){
                if($this->MyLikes()->count() < $LimitationAmount){
                    return true;
                }
                return false;
            }
            if($this->MyLikes()->where('CreatedAt', '>', $LimitationTime)->count() < $LimitationAmount){
                return true;
            }
        } elseif ($LimitationMode === 1){
            $Likes = $this->MyLikes()->where('Likeable_type', get_class($Model))->where('Likeable_id', $Model->$ModelKeyName);

            if(!$this->HMLimitationUsesTime($Limitation)){
                if($Likes->count() < $LimitationAmount){
                    return true;
                }
                return false;
            }
            if($Likes->where('CreatedAt', '<', $LimitationTime)->count() < $LimitationAmount){
                return true;
            }
        }
        return false;
    }

    /*
     * Actual Function
     */
    public function Like($Likeable, $Like = null){
        if(!$this->CanLikeNow($Likeable)){
            return false;
        }
        $KeyName = $this->getKeyName();

        $NewLike = new Like();
        if(is_null($Like) && !$Likeable->HMLikeHasAutoFillDefault()){
            return false;
        } elseif(is_null($Like)){
            $NewLike->Like = $Likeable->HMLikeGetDefault();
        }else {
            $NewLike->Like = $Like;
        }
        $NewLike->Liker_type = get_class();
        $NewLike->Liker_id = $this->$KeyName;


        $Likeable->Likes()->save($NewLike);
        return true;
    }

}