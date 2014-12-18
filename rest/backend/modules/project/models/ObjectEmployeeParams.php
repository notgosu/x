<?php

namespace backend\modules\project\models;

use Yii;

/**
 * This is the model class for table "object_employee_params".
 *
 * @property integer $id
 * @property integer $object_id
 * @property integer $employee_id
 * @property integer $access_type_id
 * @property integer $is_active
 *
 * @property Employee $employee
 * @property EmployeeAccessType $accessType
 * @property Object $object
 */
class ObjectEmployeeParams extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_employee_params';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'employee_id', 'access_type_id', 'is_active'], 'required'],
            [['object_id', 'employee_id', 'access_type_id', 'is_active'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_id' => 'Об\'єкт',
            'employee_id' => 'Співробітник',
            'access_type_id' => 'Тип доступу',
            'is_active' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessType()
    {
        return $this->hasOne(EmployeeAccessType::className(), ['id' => 'access_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObject()
    {
        return $this->hasOne(Object::className(), ['id' => 'object_id']);
    }

	/**
	 * @param $objectId
	 * @param $companyId
	 * @param null $tempSign
	 */
	public static function checkForExistParams($objectId, $companyId, $tempSign = null){
		$employees = Employee::find()->where('company_id=:cid', [':cid' => $companyId])->all();

		foreach ($employees as $employee){
			if ($objectId){
				$existData = self::find()
					->where('object_id=:oid', [':oid' => $objectId])
					->andWhere('employee_id=:eid', [':eid' => $employee->id])
					->exists();
			}
			else{
				//New record
				$existData = self::find()
					->where('temp_sign=:ts', [':ts' => $tempSign])
					->andWhere('employee_id=:eid', [':eid' => $employee->id])
					->exists();
			}


			if (!$existData){
				$model = new self();
				$model->employee_id = $employee->id;
				$model->object_id = $objectId;
				$model->temp_sign = $tempSign;
				$model->save(false);
			}
		}
	}
}
