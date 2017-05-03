<?php

namespace Unisharp\Setting;

use Illuminate\Database\Eloquent\Model as Eloquent;

class EloquentStorage extends Eloquent implements SettingStorageInterface
{
    protected $fillable = ['key', 'value', 'locale', 'user_id', 'is_global'];

    protected $table = 'settings';

    public $timestamps = false;

    public static function retrieve($key, $lang = null, $user_id = null)
    {
        $setting = static::where('key', $key);

        if (!is_null($lang)) {
            $setting = $setting->where('locale', $lang);
        } else {
            $setting = $setting->whereNull('locale');
        }
        if (!is_null($user_id)) {
            $setting = $setting->where('user_id', $user_id);
        }

        return $setting->first();
    }

    public static function store($key, $value, $lang, $user_id)
    {
        $setting = ['key' => $key, 'value' => $value];

        if (!is_null($lang)) {
            $setting['locale'] = $lang;
        }
        if (!is_null($user_id)) {
            $setting['user_id'] = $user_id;
        }
        static::create($setting);
    }

    public static function modify($key, $value, $lang, $user_id)
    {
        if (!is_null($lang)) {
            $setting = static::where('locale', $lang);
        } else {
            $setting = new static();
        }

        $setting->where('key', $key)->where('user_id', $user_id)->update(['value' => $value]);
    }

    public static function forget($key, $lang, $user_id)
    {
        $setting = static::where('key', $key)->where('user_id', $user_id);

        if (!is_null($lang)) {
            $setting = $setting->where('locale', $lang);
        } else {
            $setting = $setting->whereNull('locale');
        }

        $setting->delete();
    }
}
