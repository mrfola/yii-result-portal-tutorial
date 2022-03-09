<?php   

namespace app\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * Checks if user type matches
 */
class UserTypeRule extends Rule
{
    public $name = 'userType';

    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest)
        {
            $user_type = Yii::$app->user->identity->user_type;
            if ($item->name === 'admin')
            {
                return $user_type == 'admin';

            } elseif ($item->name === 'user')
            {
                return $user_type == 'user';
            }
        }
        return false;
    }
}
