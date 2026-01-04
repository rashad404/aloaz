<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CompotetionImages;

/**
 * CompotetionImagesSearch represents the model behind the search form about `common\models\CompotetionImages`.
 */
class CompotetionImagesSearch extends CompotetionImages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'compotetion_id', 'user_id', 'user_image_id', 'like_count', 'status', 'image_time'], 'integer'],
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
    public function search($params,$compotetion_id)
    {
        $query = CompotetionImages::find();

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
            'compotetion_id' => $compotetion_id,
            'user_id' => $this->user_id,
            'user_image_id' => $this->user_image_id,
            'like_count' => $this->like_count,
            'status' => $this->status,
            'image_time' => $this->image_time,
        ]);

        $query->orderBy(["image_time" => SORT_DESC]);

        return $dataProvider;
    }
}
