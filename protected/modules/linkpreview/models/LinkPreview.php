<?php

namespace humhub\modules\linkpreview\models;

use Yii;

/**
 * This is the model class for table "linkpreview".
 *
 * @property integer $id
 * @property string $class
 * @property integer $pk
 * @property string $title
 * @property string $url
 * @property string $image
 * @property string $description
 */
class LinkPreview extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'linkpreview';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pk'], 'integer'],
            [['description'], 'string'],
            [['class', 'title', 'url', 'image'], 'string', 'max' => 255],
            [['class', 'pk'], 'unique', 'targetAttribute' => ['class', 'pk'], 'message' => 'The combination of Class and Pk has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class' => 'Class',
            'pk' => 'Pk',
            'title' => 'Title',
            'url' => 'Url',
            'image' => 'Image',
            'description' => 'Description',
        ];
    }
    
    /**
     * Returns the corresponding linkpreview instance of the given $record.
     * 
     * @param \yii\db\ActiveRecord $record
     * @return LinkPreview
     */
    public static function findByRecord(\yii\db\ActiveRecord $record) {
        return self::findOne(['class' => $record->className(), 'pk' => $record->getPrimaryKey()]);
    }

    public function getImageUrl() {
        return $this->image;
    }
}
