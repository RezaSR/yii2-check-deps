<?php
namespace rsr\yii2\checkDeps\controllers;

use yii\web\Controller;
use rsr\yii2\checkDeps\CheckDeps;

/**
 *
 * @author Reza Saberi Rad
 */
class CheckDepsController extends Controller
{

    /**
     * Created instance of CheckDeps component.
     *
     * @var static
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
     * The action used to handle check-deps.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render($this->checkDeps->getViewPath());
    }
}