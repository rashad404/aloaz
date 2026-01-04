<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ConversationReply;

/**
 * ConversationReplySearch represents the model behind the search form about `common\models\ConversationReply`.
 */
class ConversationReplySearch extends ConversationReply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'conversation_id', 'photo_id', 'read', 'deleted_by', 'time'], 'integer'],
            [['reply'], 'safe'],
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
        $query = ConversationReply::find();

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
            'user_id' => $this->user_id,
            'conversation_id' => $this->conversation_id,
            'photo_id' => $this->photo_id,
            'read' => $this->read,
            'deleted_by' => $this->deleted_by,
            'time' => $this->time,
        ]);

        $query->andFilterWhere(['like', 'reply', $this->reply]);
        $query->andFilterWhere([ '!=' ,'user_id',Yii::$app->params['adminUserId']]);
        $query->orderBy(['id' => SORT_DESC]);
        return $dataProvider;
    }
}
