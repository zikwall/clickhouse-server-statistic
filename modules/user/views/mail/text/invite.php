<?php

/*
 * 
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * .md
 * 
 */

use yii\helpers\Html;

/**
 * @var app\modules\user\Module $module
 * @var \app\modules\user\models\InviteCode $invite
 */

?>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Yii::t('user', 'Hello') ?>,
</p>

<p>
    Мы рады сообщить Вам, что ваш ключ активации успешно создан, пожалуйста, перейдите по следующей ссылке, чтобы продолжить процедуру регистрации
</p>
<p>
    <?= Html::a('Ссылка на регистрацию в личном кабинете limehd.tv', $invite->getInviteUrl()); ?>
</p>

