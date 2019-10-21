<?php

namespace app\models\questions;

use app\models\answer\Answer;
use app\models\Quiz;
use app\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "questions".
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $name
 * @property string $hint
 * @property int $max_answers
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property Answer[] $answers
 * @property Quiz $quiz
 */
class Questions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' =>[
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                    ActiveRecord::EVENT_AFTER_UPDATE => ['updated_at']
                ],
            ],
        ];
    }
    public static function tableName()
    {
        return 'questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id', 'max_answers', 'created_at', 'updated_at','created_by','updated_by'], 'integer'],
            [['name','max_answers'],'required'],
            [['name', 'hint'], 'string', 'max' => 255],
            [['quiz_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quiz_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quiz_id' => 'Select quiz subject',
            'name' => 'Please input question',
            'hint' => 'Hint',
            'max_answers' => 'Max Answers',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By'
        ];
    }
    public function maxQuestions($param){
        $rows = (new \yii\db\Query())
            ->select(['max_question'])
            ->from('quiz')
            ->where(['id' => $param])
            ->scalar();
        $questions = (new \yii\db\Query())
            ->select(['id'])
            ->from('questions')
            ->where(['quiz_id' => $param])
            ->count();
        if($questions>=$rows){
            return false;
        }
        return true;


    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by'])->select('username')->scalar();
    }
    public function getUpdatedby()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by'])->select('username')->scalar();
    }
}
