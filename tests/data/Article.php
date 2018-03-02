<?php

namespace yii2tech\tests\unit\admin\data;

use yii\db\ActiveRecord;
use yii2tech\ar\variation\VariationBehavior;

/**
 * @property integer $id
 * @property string $name
 */
class Article extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Article';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'translations' => [
                '__class' => VariationBehavior::class,
                'variationsRelation' => 'translations',
                'defaultVariationRelation' => 'defaultTranslation',
                'variationOptionReferenceAttribute' => 'languageId',
                'optionModelClass' => Language::class,
                'defaultVariationOptionReference' => 1,
                'variationAttributeDefaultValueMap' => [
                    'title' => 'name',
                    'content' => null,
                ],
            ],
        ];
    }

    /**
     * [@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'required'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(ArticleTranslation::class, ['articleId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefaultTranslation()
    {
        return $this->hasDefaultVariationRelation();
    }
}