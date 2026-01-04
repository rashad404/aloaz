<?php
namespace common\components\rbac;

use Yii;
use yii\rbac\Rule;


class UserGroupRule extends Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {

        if (!Yii::$app->user->isGuest) {

            $group = Yii::$app->user->identity->role;
            if ($item->name === 'admin') {

                return $group == 1;

            } elseif ($item->name === 'user') {

                return $group == 2;
            }
        }
        return false;
    }
}