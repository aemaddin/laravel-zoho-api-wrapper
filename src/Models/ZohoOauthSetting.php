<?php

namespace Aemaddin\Zoho\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder where(string $string, $client_id)
 * @method static Builder updateOrCreate(array $array, array $array1)
 * @method static Builder orderBy(string $string, string $string1)
 * @method static ZohoOauthSetting find($config_id)
 * @property  string access_token
 * @property  string expires_in
 * @property int expires_in_sec
 */
class ZohoOauthSetting extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'client_secret', 'access_token', 'refresh_token', 'protocol',
        'token_type', 'expire_in', 'expire_in_sec', 'client_domain', 'api_domain',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
