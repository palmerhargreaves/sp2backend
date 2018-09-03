<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 07.07.2017
 * Time: 18:04
 */

namespace backend\models;


use common\models\Settings;
use yii\base\Model;

class SettingsForm extends Model
{
    public $messages_per_page;
    public $friends_per_page;
    public $rooms_time_live;
    public $rooms_time_destroy;
    public $user_online_time_in_room;
    public $user_left_time_to_use_bonuses;
    public $user_votes_live_time_in_days;
    public $user_bonuses_give_in_days;
    public $user_bonuses_percent_to_site;
    public $user_bonuses_percent_self;
    public $user_bonuses_percent_to_help_label;
    public $user_bonuses_percent_to_speed_help_label;
    public $rooms_limit_count;

    public $currency;
    public $currency_conversion_to_bonuses;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->messages_per_page = Settings::getValue('messages_per_page');
        $this->friends_per_page = Settings::getValue('friends_per_page');
        $this->rooms_time_live = Settings::getValue('rooms_time_live');
        $this->rooms_time_destroy = Settings::getValue('rooms_time_destroy');
        $this->user_online_time_in_room = Settings::getValue('user_online_time_in_room');
        $this->user_left_time_to_use_bonuses = Settings::getValue('user_left_time_to_use_bonuses');
        $this->user_votes_live_time_in_days = Settings::getValue('user_votes_live_time_in_days');
        $this->user_bonuses_give_in_days = Settings::getValue('user_bonuses_give_in_days');

        $this->user_bonuses_percent_to_site = Settings::getValue('user_bonuses_percent_to_site');
        $this->user_bonuses_percent_self = Settings::getValue('user_bonuses_percent_self');
        $this->user_bonuses_percent_to_help_label = Settings::getValue('user_bonuses_percent_to_help_label');
        $this->user_bonuses_percent_to_speed_help_label = Settings::getValue('user_bonuses_percent_to_speed_help_label');

        $this->rooms_limit_count = Settings::getValue('rooms_limit_count');

        $this->currency = Settings::getValue('currency');
        $this->currency_conversion_to_bonuses = Settings::getValue('currency_conversion_to_bonuses');
    }

    public function rules()
    {
        return [
            [['messages_per_page', 'friends_per_page', 'rooms_time_live', 'rooms_time_destroy', 'user_online_time_in_room',
                'user_left_time_to_use_bonuses', 'user_votes_live_time_in_days', 'user_bonuses_give_in_days',
                'user_bonuses_percent_to_site', 'user_bonuses_percent_self', 'user_bonuses_percent_to_help_label', 'user_bonuses_percent_to_speed_help_label'], 'integer', 'min' => 1],
            [['messages_per_page', 'friends_per_page', 'rooms_time_live', 'rooms_time_destroy', 'user_online_time_in_room', 'user_left_time_to_use_bonuses',
                'user_votes_live_time_in_days', 'user_bonuses_give_in_days',
                'user_bonuses_percent_to_site', 'user_bonuses_percent_self', 'user_bonuses_percent_to_help_label', 'user_bonuses_percent_to_speed_help_label'], 'required'],

            [['user_bonuses_percent_to_site', 'user_bonuses_percent_self', 'rooms_limit_count', 'currency_conversion_to_bonuses'], 'integer', 'max' => 100],

            ['currency', 'string', 'min' => 1, 'max' => 10],
        ];
    }

    /**
     * Save settings data
     */
    public function save() {
        Settings::setValue('messages_per_page', $this->messages_per_page);
        Settings::setValue('friends_per_page', $this->friends_per_page);
        Settings::setValue('rooms_time_live', $this->rooms_time_live);
        Settings::setValue('rooms_time_destroy', $this->rooms_time_destroy);
        Settings::setValue('user_online_time_in_room', $this->user_online_time_in_room);
        Settings::setValue('user_left_time_to_use_bonuses', $this->user_left_time_to_use_bonuses);
        Settings::setValue('user_votes_live_time_in_days', $this->user_votes_live_time_in_days);
        Settings::setValue('user_bonuses_give_in_days', $this->user_bonuses_give_in_days);
        Settings::setValue('user_bonuses_percent_to_site', $this->user_bonuses_percent_to_site);
        Settings::setValue('user_bonuses_percent_self', $this->user_bonuses_percent_self);
        Settings::setValue('user_bonuses_percent_to_help_label', $this->user_bonuses_percent_to_help_label);
        Settings::setValue('user_bonuses_percent_to_speed_help_label', $this->user_bonuses_percent_to_speed_help_label);

        Settings::setValue('rooms_limit_count', $this->rooms_limit_count);

        Settings::setValue('currency', $this->currency);
        Settings::setValue('currency_conversion_to_bonuses', $this->currency_conversion_to_bonuses);
    }
}
