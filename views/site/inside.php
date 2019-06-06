<?php
    echo '<h1><br>', Yii::$app->session['token'], '<br></h1><br>';
    debug($model);
    ?>
<?php if ( isset(Yii::$app->session['error']) and Yii::$app->session['error'] ): ?>
    <?php
    debug(Yii::$app->session['error']);
    Yii::$app->session->destroy();
    ?>
<?php endif; ?>

