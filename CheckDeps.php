<?php
namespace rsr\yii2\checkDeps;

use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use rsr\yii2\checkDeps\controllers\ProcessController;
use yii\base\InvalidConfigException;
use rsr\yii2\checkDeps\commands\MigrationController;
use yii\console\Application;

/**
 *
 * @author Reza Saberi Rad
 */
class CheckDeps extends BaseObject implements BootstrapInterface
{

    /**
     * The base path of file storage
     *
     * @var string
     */
    protected $storageBasePath = '@runtime/rsr/checkDeps/';

    /**
     * The file name to save ignored migrations
     *
     * @var string
     */
    protected $ignoredMigrationsFile = 'ignoredMigrations.data';

    /**
     * The controller class used to handle the needed process.
     *
     * @var string
     */
    protected $controllerClass = ProcessController::class;

    /**
     * The controller id to handle the needed process.
     *
     * @var string
     */
    protected $controllerId = 'process';

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
    protected $viewBasePath = '@rsrCheckDepsRoot/views/process/';

    /**
     * Store the needed process
     *
     * @var array|false
     */
    protected $neededProcess = false;

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
     * @param Application $app
     *            the application currently running
     * @see \yii\base\BootstrapInterface::bootstrap()
     */
    public function bootstrap($app)
    {
        if (YII_ENV_DEV) {
            \Yii::setAlias('@rsrCheckDepsRoot', __DIR__);
            $this->storageBasePath = \Yii::getAlias($this->storageBasePath);
            $app->controllerMap[$this->controllerId] = $this->controllerClass;

            if (! $app->request->isAjax) {
                if (! is_dir($this->storageBasePath)) {
                    if (! mkdir($this->storageBasePath, 0775, true)) {
                        throw new \Exception('Can not create storage directory: "' . $this->storageBasePath . '".');
                    }
                }

                if (($this->needProcess()) !== false) {
                    $app->catchAll = [
                        $this->controllerId . '/' . $this->controllerAction
                    ];
                }
            }
        }
    }

    /**
     * Returns the view path.
     *
     * @param string $viewName
     * @return string
     */
    public function getViewPath($viewName = 'index')
    {
        return $this->viewBasePath . $viewName;
    }

    /**
     * Returns the needed process
     *
     * @return array|false
     */
    public function getNeededProcess()
    {
        return $this->neededProcess;
    }

    /**
     * Returns the controller id for process handling.
     *
     * @return string
     */
    public function getControllerId()
    {
        return $this->controllerId;
    }

    /**
     * Marks the given migrations as ignored.
     *
     * @param string $migrations
     * @return boolean
     */
    public function ignoreMigrations($migrations)
    {
        return (file_put_contents($this->getIgnoredMigrationsFilePath(), $migrations) !== false);
    }

    /**
     * Applies the new migrations.
     *
     * @return array
     */
    public function applyMigrations()
    {
        $migrateController = new MigrationController('migration', \Yii::$app);
        $return = $migrateController->runAction('apply');

        if (file_exists($this->getIgnoredMigrationsFilePath()) && isset($return['exitCode']) && ($return['exitCode'] == 0)) {
            unlink($this->getIgnoredMigrationsFilePath());
        }

        return $return;
    }

    /**
     * Returns the file path of saved ingored migrations.
     *
     * @return string
     */
    protected function getIgnoredMigrationsFilePath()
    {
        return $this->storageBasePath . $this->ignoredMigrationsFile;
    }

    /**
     * Returns the ignored migrations
     *
     * @return array
     */
    protected function getIgnoredMigrations()
    {
        $filePath = $this->getIgnoredMigrationsFilePath();
        if (file_exists($filePath)) {
            $newMigrations = unserialize(file_get_contents($filePath));
            return is_array($newMigrations) ? $newMigrations : [];
        } else {
            return [];
        }
    }

    /**
     * Returns whether any process is needed or not.
     *
     * @return boolean
     */
    protected function needProcess()
    {
        $needProcess = false;

        $migrateController = new MigrationController('migration', \Yii::$app);
        $newMigrations = $migrateController->runAction('get-new');

        if ($this->getIgnoredMigrations() != $newMigrations) {
            $needProcess = true;
            $this->neededProcess['newMigrations'] = $newMigrations;
        }

        return $needProcess;
    }
}
