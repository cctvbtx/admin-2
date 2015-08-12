<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\admin\actions;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Create action supports creation of the new model using web form.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class Create extends Action
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string name of the view, which should be rendered
     */
    public $view = 'create';
    /**
     * @var callable a PHP callable that will be called to create the new model.
     * If not set, [[newModel()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($action) {
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return the new model instance.
     */
    public $newModel;


    /**
     * Creates new model instance.
     * @return ActiveRecordInterface|Model new model instance.
     * @throws InvalidConfigException on invalid configuration.
     */
    public function newModel()
    {
        if ($this->newModel !== null) {
            return call_user_func($this->newModel, $this);
        } elseif ($this->controller->hasMethod('newModel')) {
            return call_user_func([$this->controller, 'newModel'], $this);
        }

        if ($this->modelClass === null) {
            throw new InvalidConfigException('"' . get_class($this) . '::modelClass" must be set.');
        }
        $modelClass = $this->modelClass;
        return new $modelClass();
    }

    /**
     * Creates new record.
     * @return mixed response
     */
    public function run()
    {
        $model = $this->newModel();
        $model->scenario = $this->scenario;

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->save()) {
                $url = array_merge(
                    ['view'],
                    Yii::$app->request->getQueryParams(),
                    ['id' => implode(',', array_values($model->getPrimaryKey(true)))]
                );
                return $this->controller->redirect($url);
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}