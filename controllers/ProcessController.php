<?php
namespace rsr\yii2\checkDeps\controllers;

use yii\web\Controller;
use rsr\yii2\checkDeps\CheckDeps;
use yii\web\Response;

/**
 *
 * @author Reza Saberi Rad
 */
class ProcessController extends Controller
{

    /**
     * The created instance of CheckDeps component.
     *
     * @var CheckDeps
     */
    private $checkDeps;

    /**
     *
     * {@inheritdoc}
     * @see \yii\base\BaseObject::init()
     */
    public function init()
    {
        parent::init();

        $this->checkDeps = CheckDeps::getComponentInstance();
    }

    /**
     *
     * {@inheritdoc}
     * @see \yii\web\Controller::beforeAction()
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ((! \Yii::$app->request->isAjax) && (! $this->checkDeps->getNeededProcess())) {
                $this->goHome();
                return false;
            }

            if ($action->id != 'index') {
                \Yii::$app->response->format = Response::FORMAT_JSON;
            }

            return true;
        }

        return false;
    }

    /**
     * Shows the needed process.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render($this->checkDeps->getViewPath());
    }

    /**
     * Applies the new migrations
     *
     * @return array
     */
    public function actionApply()
    {
        return $this->checkDeps->applyMigrations();
    }

    /**
     * Ignores the given migrations.
     *
     * @return boolean
     */
    public function actionIgnore()
    {
        if ($data = \Yii::$app->request->post('data', false)) {
            return $this->checkDeps->ignoreMigrations($data);
        } else {
            return true;
        }
    }
}