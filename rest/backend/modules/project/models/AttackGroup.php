<?php

namespace backend\modules\project\models;

use Yii;
use kartik\builder\Form;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%attack_group}}".
 *
 * @property integer $id
 * @property string $label
 * @property integer $position
 */
class AttackGroup extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attack_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label'], 'required'],
            [['position'], 'integer'],
            [['label'], 'string', 'max' => 255],
            [['id', 'label', 'position'], 'safe', 'on' => 'search']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery[]
     */
    public function getAttack()
    {
        return $this->hasMany(Attack::className(), ['group_id' =>'id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Назва',
            'position' => 'Позицiя',
        ];
    }

    /**
    * @param bool $viewAction
    *
    * @return array
    */
    public function getViewColumns($viewAction = false)
    {
        return $viewAction
            ? [
                'id',
                'label',
                'position',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'label',
                [
                    'attribute' => 'position',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],

                [
                    'class' => \yii\grid\ActionColumn::className()
                ]
            ];
    }

    /**
    * @return array
    */
    public function getFormRows()
    {
        return [
            
            'label' => [
                'type' => Form::INPUT_TEXT,
            ],
            'position' => [
                'type' => Form::INPUT_TEXT,
            ],

        ];
    }

    /**
    * @inheritdoc
    */
    public function getColCount()
    {
        return 2;
    }

    /**
    * @return string
    */
    public function getBreadCrumbRoot()
    {
        return 'AttackGroup';
    }

    /**
     * @inheritdoc
     */
    public function search($params)
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!empty($params)){
            $this->load($params);
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'label', $this->label]);
        $query->andFilterWhere(['like', 'position', $this->position]);

        return $dataProvider;
    }
}
