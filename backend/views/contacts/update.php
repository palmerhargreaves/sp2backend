<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Contacts */

?>
<div id="profile-page" class="section">
    <!-- profile-page-header -->
    <div id="profile-page-header" class="card">
        <div class="card-image waves-effect waves-block waves-light">
            <img class="activator" src="images/user-profile-bg.jpg" alt="user background">
        </div>
        <figure class="card-profile-image">
            <img src="files/<?php echo $model->photo; ?>" alt="<?php echo $model->name; ?>"
                 class="circle z-depth-2 responsive-img activator">
        </figure>

        <div class="card-content">
            <div class="row">
                <div class="col s3 offset-s2">
                    <h4 class="card-title grey-text text-darken-4"><?php echo $model->name; ?></h4>
                    <p class="medium-small grey-text"><?php echo $model->duties; ?></p>
                </div>
                <div class="col s2 center-align">
                    <h4 class="card-title grey-text text-darken-4">0+</h4>
                    <p class="medium-small grey-text">Новых сообщений</p>
                </div>
                <div class="col s2 center-align">
                    <h4 class="card-title grey-text text-darken-4">0</h4>
                    <p class="medium-small grey-text">Всего сообщений</p>
                </div>
                <div class="col s2 center-align">
                    <h4 class="card-title grey-text text-darken-4"></h4>
                    <p class="medium-small grey-text"></p>
                </div>

            </div>
        </div>

    </div>
    <!--/ profile-page-header -->

    <!-- profile-page-content -->
    <div id="profile-page-content" class="row">
        <!-- profile-page-sidebar-->
        <div id="profile-page-sidebar" class="col s12 m4">
            <!-- Profile About  -->
            <div class="card light-blue">
                <div class="card-content white-text">
                    <span class="card-title">Описание</span>
                    <p><?php echo $model->description; ?></p>
                </div>
            </div>
            <!-- Profile About  -->


        </div>
        <!-- profile-page-sidebar-->

        <!-- profile-page-wall -->
        <div id="profile-page-wall" class="col s12 m8">
            <!-- profile-page-wall-share -->
            <div id="profile-page-wall-share" class="row">
                <div class="col s12">
                    <ul class="tabs tab-profile z-depth-1 light-blue" style="width: 100%;">
                        <li class="tab col s3">
                            <a class="white-text waves-effect waves-light active" href="#profile"><i
                                        class="mdi-editor-border-color"></i>Профиль</a>
                        </li>
                        <div class="indicator" style="right: 485px; left: 0px;"></div>
                    </ul>

                    <!-- UpdateStatus-->
                    <div id="profile" class="tab-content col s12  grey lighten-4" style="display: block;">

                        <?php $form = ActiveForm::begin(['id' => 'form-contact-add', 'fieldConfig' => [
                            'template' => '{input}{error}'
                        ], 'options' => ['class' => 'col s12']]); ?>
                        <div class="row">
                            <div class="row">
                                <div class="input-field col s12">
                                    <?php echo $form->field($model, 'name')->textInput(['class' => '', 'placeholder' => 'Имя', 'disabled' => false]); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <?php echo $form->field($model, 'name_parental_case')->textInput(['class' => '', 'placeholder' => 'Имя (род. падеж)', 'disabled' => false]); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <?php echo $form->field($model, 'duties')->textInput(['class' => '', 'placeholder' => 'Должность', 'disabled' => false]); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <?php echo $form->field($model, 'description')->textarea(['class' => 'materialize-textarea', 'placeholder' => 'Описание ', 'disabled' => false]); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" id="colors-color" name="Contacts[color]" class="colorpicker" value="<?php echo $model ? $model->color : '#FF9900'; ?>"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>Файл</span>
                                        <?php echo $form->field($model, 'photo')->fileInput(['class' => 'file btn-primary', 'multiple' => false]); ?>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" placeholder="Загрузите файл">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <button type="submit" class="btn cyan waves-effect waves-light right">
                                    <i class="mdi-content-save right"></i>&nbsp;<?= Yii::t('app', 'Сохранить') ?>
                                </button>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>

                </div>
            </div>

            <?php if ($model->getMessagesList()): ?>
            <div id="card-widgets" class="section">
                <div class="row">

                    <div class="col s12 m12 l12">
                        <h4 class="header"><?php echo Yii::t('app', 'Сообщения'); ?></h4>
                        <p>Список последних сообщений</p>

                        <div class="row">
                            <div class="card">
                                <div class="col s12 m8 l12">
                                    <table class="table responsive-table">
                                        <thead>
                                        <tr>
                                            <th data-field="month">От</th>
                                            <th data-field="item-sold">Кому</th>
                                            <th data-field="item-price">Сообщение</th>
                                            <th data-field="total-profit">Дата</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>January</td>
                                            <td>122</td>
                                            <td>100</td>
                                            <td>$122,00.00</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <?php endif; ?>

        </div>
        <!--/ profile-page-wall -->

    </div>
</div>

<?php
