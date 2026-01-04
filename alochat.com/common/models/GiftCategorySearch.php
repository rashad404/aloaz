<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GiftCategory;

/**
 * GiftCategorySearch represents the model behind the search form about `common\models\GiftCategory`.
 */
class GiftCategorySearch extends GiftCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name_az','name_ru','name_en','name_tr'], 'safe'],
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
        $query = GiftCategory::find();

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
        ]);

        $query->andFilterWhere(['like', 'name_az', $this->name_az]);
        $query->andFilterWhere(['like', 'name_ru', $this->name_ru]);
        $query->andFilterWhere(['like', 'name_en', $this->name_en]);
        $query->andFilterWhere(['like', 'name_tr', $this->name_tr]);

        return $dataProvider;
    }
}
