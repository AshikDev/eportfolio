<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\autopatch\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\autopatch\components\PatchManager;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;

class AdminController extends Controller
{

    public function actionIndex()
    {
        $patchManager = new PatchManager();

        $patches = $patchManager->getAvailable();
        return $this->render('index', ['patchesInfo' => $patches]);
    }


    public function actionApply($id)
    {
        $this->forcePostRequest();

        $patchManager = new PatchManager();
        $patchInfo = $patchManager->getById($id);

        if ($patchInfo === null) {
            throw new HttpException(404, 'Requested patch not found!');
        }

        $patch = $patchInfo->getPatch();

        if ($patch === null) {
            throw new HttpException(404, 'Could not initalize patch!');
        }

        if ($patch->apply()) {
            $this->view->success(Yii::t('AutoPatchModule.base', 'Patch sucessfully applied!'));
            $patch->markAsApplied();
        } else {
            Yii::$app->session->setFlash('patchError', true);

            if (empty($patch->errorMessage)) {
                $patch->errorMessage = Yii::t('AutoPatchModule.base', 'Please check the log files for more details.');
            }

            Yii::$app->session->setFlash('patchErrorMessage', $patch->errorMessage);
        }

        return $this->redirect(Url::to(['index']));
    }

}
