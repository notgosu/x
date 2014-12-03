<?php

namespace backend\modules\project\models;

use Yii;

/**
 * This is the model class for table "company_category_attribute_value".
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $category_id
 * @property integer $attribute_id
 *
 * @property CompanyAttribute $attribute
 * @property CompanyAttributeCategory $category
 * @property Company $company
 */
class CompanyCategoryAttributeValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_category_attribute_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'category_id', 'attribute_id'], 'required'],
            [['company_id', 'category_id', 'attribute_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'category_id' => 'Category ID',
            'attribute_id' => 'Attribute ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeRel()
    {
        return $this->hasOne(CompanyAttribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CompanyAttributeCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
