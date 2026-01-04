<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'role', 'sex', 'age', 'city_id', 'country_id', 'profile_photo_id', 'last_login', 'last_activity', 'activity_coin_time', 'level', 'social_login', 'coins','verify'], 'integer'],
            [['full_name','nickname', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'phone', 'profile_photo','regfrom','onfrom'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'role' => $this->role,
            'sex' => $this->sex,
            'age' => $this->age,
            'city_id' => $this->city_id,
            'country_id' => $this->country_id,
            'profile_photo_id' => $this->profile_photo_id,
            'last_login' => $this->last_login,
            'last_activity' => $this->last_activity,
            'activity_coin_time' => $this->activity_coin_time,
            'level' => $this->level,
            'social_login' => $this->social_login,
            'coins' => $this->coins,
            'block_time' => $this->block_time,
            'verify' => $this->verify,

        ]);

        $query->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'profile_photo', $this->profile_photo])
            ->andFilterWhere(['like', 'onfrom', $this->onfrom])
            ->andFilterWhere(['like', 'regfrom', $this->regfrom]);
           // ->orderBy(['id'=>SORT_DESC]);
            //->orderBy('id',SORT_DESC);

        return $dataProvider;
    }


    public function blockUser($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'role' => $this->role,
            'sex' => $this->sex,
            'age' => $this->age,
            'city_id' => $this->city_id,
            'country_id' => $this->country_id,
            'profile_photo_id' => $this->profile_photo_id,
            'last_login' => $this->last_login,
            'last_activity' => $this->last_activity,
            'activity_coin_time' => $this->activity_coin_time,
            'level' => $this->level,
            'social_login' => $this->social_login,
            'coins' => $this->coins,
            'block_time' => $this->block_time,


        ]);

        $query->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'full_name', $this->nickname])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'profile_photo', $this->profile_photo])
            ->andFilterWhere(['>', 'block_time', 0])
             ->andFilterWhere(['<','`block_time`+`block_begin_time`',time()])
            ->orderBy(['id'=>SORT_DESC]);
        //->orderBy('id',SORT_DESC);

        return $dataProvider;
    }
}
