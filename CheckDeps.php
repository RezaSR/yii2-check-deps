<?php
namespace rsr\yii2\checkDeps;

use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use rsr\yii2\checkDeps\controllers\CheckDepsController;
use yii\base\InvalidConfigException;

/**
 *
 * @author Reza Saberi Rad
 */
class CheckDeps extends BaseObject implements BootstrapInterface
{

    /**
     * The controller class used to handle check-deps.
     *
     * @var string
     */
    protected $controllerClass = CheckDepsController::class;

    /**
     * The controller id used to handle check-deps.
     *
     * @var string
     */
    protected $controllerId = 'check-deps';

    /**
     * The controller action used to handle check-deps.
     *
     * @var string
     */
    protected $controllerAction = 'index';

    /**
     * The view path to be rendered in check-deps controller's action.
     *
     * @var string
     */
    protected $viewPath = '@rsrCheckDepsRoot/views/check-deps/index';

    /**
     * Returns the created instance of this component.
     *
     * @throws InvalidConfigException
     * @return static
     */
    public static function getComponentInstance()
    {
        foreach (\Yii::$app->getComponents() as $id => $component) {
            if ($component['class'] == self::class) {
                return \Yii::$app->$id;
            }
        }

        throw new InvalidConfigException('"' . self::class . '" is not defined in components.');
    }

    /**
     *
     * {@inheritdoc}
     * @see \yii\base\BootstrapInterface::bootstrap()
     */
    public function bootstrap($app)
    {
        if (YII_ENV_DEV && (! $app->request->isAjax) && $this->needAction()) {
            \Yii::setAlias('@rsrCheckDepsRoot', __DIR__);

            $app->controllerMap[$this->controllerId] = $this->controllerClass;
            $app->catchAll = [
                $this->controllerId . '/' . $this->controllerAction
            ];
        }
    }

    /**
     * Get the $viewPath.
     *
     * @return string
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * Check whether any action is needed or not.
     *
     * @return boolean
     */
    protected function needAction()
    {
        return true; // todo: check if migration/dependency handling is needed
    }
}
