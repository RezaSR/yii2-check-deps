<?php
namespace rsr\yii2\checkDeps\commands;

/**
 *
 * @author Reza Saberi Rad
 */
class MigrationController extends \yii\console\controllers\MigrateController
{

    /**
     *
     * {@inheritdoc}
     * @see \yii\base\Controller::beforeAction()
     */
    public function beforeAction($action)
    {
        if ($action->id == 'apply') {
            ob_start();
        }

        if (parent::beforeAction($action)) {
            $this->interactive = false;

            return true;
        }

        ob_end_flush();
        return false;
    }

    /**
     *
     * {@inheritdoc}
     * @see \yii\console\Controller::stdout()
     */
    public function stdout($string)
    {
        echo $string;
    }

    /**
     *
     * {@inheritdoc}
     * @see \yii\console\Controller::stderr()
     */
    public function stderr($string)
    {
        echo $string;
    }

    /**
     * Returns new migrations.
     *
     * @return array
     */
    public function actionGetNew()
    {
        return $this->getNewMigrations();
    }

    /**
     * Applies new migrations.
     *
     * @return array
     */
    public function actionApply()
    {
        $exitCode = $this->actionUp();
        $output = ob_get_clean();

        return [
            'exitCode' => empty($exitCode) ? 0 : $exitCode,
            'output' => $output
        ];
    }
}