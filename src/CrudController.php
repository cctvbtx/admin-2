<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin;

use Yii;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii2tech\admin\behaviors\ModelControlBehavior;

/**
 * CrudController implements a common set of actions for supporting CRUD for ActiveRecord.
 *
 * The class of the ActiveRecord should be specified via [[modelClass]], which must implement [[\yii\db\ActiveRecordInterface]].
 * By default, the following actions are supported:
 *
 * - `index`: list of models
 * - `view`: the details of a model
 * - `create`: create a new model
 * - `update`: update an existing model
 * - `delete`: delete an existing model
 *
 * You may disable some of these actions by overriding [[actions()]] and unsetting the corresponding actions.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class CrudController extends Controller
{
    /**
     * @var string the model class name. This property must be set.
     * The model class must implement [[ActiveRecordInterface]].
     */
    public $modelClass;
    /**
     * @var string class name of the model which should be used as search model.
     * If not set it will be composed using [[modelClass]].
     */
    public $searchModelClass;
    /**
     * @var string the scenario used for updating a model.
     * @see Model::scenarios()
     */
    public $updateScenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the scenario used for creating a model.
     * @see Model::scenarios()
     */
    public $createScenario = Model::SCENARIO_DEFAULT;


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'model' => [
                '__class' => ModelControlBehavior::class,
                'modelClass' => $this->modelClass,
                'searchModelClass' => $this->searchModelClass,
            ],
            'access' => [
                '__class' => AccessControl::class,
                'rules' => $this->accessRules(),
            ],
            'verbs' => [
                '__class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Returns the access rules for this controller.
     * This is method is a shortcut, allowing quick adjustment of the [[AccessControl]] filter attached at [[behaviors()]].
     * Be careful in case you override [[behaviors()]] method, since it may loose configuration provided by this method.
     * @return array list of access rules. See [[AccessControl::rules]] for details about rule specification.
     */
    public function accessRules()
    {
        return [
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                '__class' => actions\Index::class,
            ],
            'view' => [
                '__class' => actions\View::class,
            ],
            'create' => [
                '__class' => actions\Create::class,
                'scenario' => $this->createScenario,
            ],
            'update' => [
                '__class' => actions\Update::class,
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                '__class' => actions\Delete::class,
            ],
        ];
    }
}