<?php

namespace SiranixSociety\HMLikes\Traits;

trait HMLikesHelper {
    /*
     * Setting Functions
     */
    public function HMLikeHasSettings()
    {
        return isset($this->HMLikeSettings);
    }

    public function HMLikeGetAllSettings()
    {
        if ($this->HMLikeHasSettings()) {
            $Settings = $this->HMLikeSettings;
            while ($this->HMHasReferences($Settings)) {
                $Settings = $this->HMFillReferences($Settings);
            }
            return $Settings;
        }
        return null;
    }

    public function HMLikeHasSetting($Setting = null)
    {
        return array_has($this->HMLikeGetAllSettings(), $Setting);
    }

    public function HMLikeGetSetting($Setting = null)
    {
        return array_get($this->HMLikeGetAllSettings(), $Setting);
    }

    /*
     * Default Setting Functions
     */
    public function HMLikeHasAutoFillDefault(){
        if($this->HMLikeHasSetting('DefaultSettings.AutoFillDefault')){
            return $this->HMLikeGetSetting('DefaultSettings.AutoFillDefault');
        }
        if ($this->HMHasConfigSetting('Likes.DefaultSettings.AutoFillDefault')) {
            return $this->HMGetConfigSetting('Likes.DefaultSettings.AutoFillDefault');
        }
        return false;
    }
    public function HMLikeGetDefault(){
        if($this->HMLikeHasSetting('DefaultSettings.Default')){
            return $this->HMLikeGetSetting('DefaultSettings.Default');
        }
        if ($this->HMHasConfigSetting('Likes.DefaultSettings.Default')) {
            return $this->HMGetConfigSetting('Likes.DefaultSettings.Default');
        }
        return true;
    }

    /*
     * Limitation Settings
     */
    public function HMLikeIsLimited()
    {
        if ($this->HMLikeHasSetting('Limitations.Enabled')) {
            return $this->HMLikeGetSetting('Limitations.Enabled');
        }
        if ($this->HMHasConfigSetting('Likes.Limitations.Enabled')) {
            return $this->HMGetConfigSetting('Likes.Limitations.Enabled');
        }
        return false;
    }
    public function HMLikeCanBeLimited(){
        if ($this->HMHasConfigSetting('Likes.Limitations.Enabled')) {
            return $this->HMGetConfigSetting('Likes.Limitations.Enabled');
        }
        return false;
    }
    public function HMLikeHasModelLimitation($Model)
    {
        if (is_string($Model)) {
            $Model = new $Model;
        }
        if ($this->HMLikeHasSetting('Limitations.Models.' . get_class($Model))) {
            return true;
        }
        if ($Model->HMLikeHasSetting('Limitations.Model.' . get_class($this))) {
            return true;
        }
        return false;
    }
    public function HMLikeGetLimitation($Model = null){
        //TODO: Alpha, behaviour might be weird
        if (is_string($Model) && !empty($Model)) {
            $Model = new $Model;
        }
        $Limitation = [];
        if($this->HMLikeHasSetting('Limitations.Models.'.get_class($Model))){
            $Limitation = array_merge($this->HMLikeGetSetting('Limitations.Models.'.get_class($Model)), $Limitation);
        }
        if(!empty($Model)){
            if($Model->HMLikeHasSetting('Limitations.Models.'.get_class($this))){
                $Limitation = array_merge($Model->HMLikeGetSetting('Limitations.Models.'.get_class($this)), $Limitation);
            }
            if($Model->HMLikeHasSetting('Limitations.Default')){
                $Limitation = array_merge($Model->HMLikeGetSetting('Limitations.Default'), $Limitation);
            }
        }
        if($this->HMLikeHasSetting('Limitations.Default')){
            $Limitation = array_merge($this->HMLikeGetSetting('Limitations.Default'), $Limitation);
        }
        if($this->HMHasConfigSetting('Likes.Limitations.Default')){
            $Limitation = array_merge($this->HMGetConfigSetting('Likes.Limitations.Default'), $Limitation);
        }
        return $Limitation;
    }

    /*
     * Functions
     */
    public function HMLikeIsEnabled($Model = null)
    {
        if (is_string($Model)) {
            $Model = new $Model;
        }
        if ($this->HMHasConfigSetting('Likes.Enabled')) {
            if (!$this->HMLikeGetSetting('Likes.Enabled')) {
                return false;
            }
        }
        if ($this->HMLikeHasSetting('Enabled')) {
            if (!$this->HMLikeGetSetting('Enabled')) {
                return false;
            }
        }
        if (!empty($Model)) {
            if ($this->HMLikeHasSetting('Limitations.Models.' . get_class($Model) . '.Enabled')) {
                if (!$this->HMLikeGetSetting('Limitations.Models.' . get_class($Model) . '.Enabled')) {
                    return false;
                }
            }
            if ($Model->HMLikeHasSetting('Enabled')) {
                if (!$Model->HMLikeGetSetting('Enabled')) {
                    return false;
                }
            }
            if ($Model->HMLikeHasSetting('Limitations.Models.' . get_class($this) . '.Enabled')) {
                if (!$Model->HMLikeGetSetting('Limitations.Models.' . get_class($this) . '.Enabled')) {
                    return false;
                }
            }
        }
        return true;
    }
}