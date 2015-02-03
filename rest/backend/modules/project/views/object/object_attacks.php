<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $tempSign boolean
 * @var $objTypeId integer
 * @var $objectId integer
 */

use backend\modules\project\widgets\objectAttacks\ObjectAttacksWidget;

echo $tempSign
    ? ObjectAttacksWidget::widget(
        [
            'object_type_id' => $objTypeId,
            'temp_sign' => $objectId
        ]
    )
    : ObjectAttacksWidget::widget(
        [
            'object_type_id' => $objTypeId,
            'object_id' => $objectId
        ]
    );
