<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 16.01.2018
 * Time: 22:58
 */

use richardfan\sortable\SortableGridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>


    <div id="card-widgets" class="section">
        <div class="row">

            <div class="col s12 m12 l12">
                <h4 class="header"><?php echo Yii::t('app', 'Контакты'); ?></h4>
                <p>Список контактов</p>

                <?php $view = $this; ?>
                <div class="row">
                    <div class="card">
                        <div class="col s12 m8 l12">
                            <?php Pjax::begin(); ?>
                            <?= SortableGridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchProvider,
                                'sortUrl' => Url::to([ 'sortItem' ]),
                                'columns' => [
                                    [
                                        'format' => 'raw',
                                        'attribute' => 'photo',
                                        'value' => function ( $model ) use ( $view ) {
                                            return "<img style='width: 90px; border-radius: 50%;' src='files/".$model->photo."' title='".$model->name."'>";
                                        },
                                        'options' => [
                                            'width' => '10%'
                                        ]
                                    ],
                                    [
                                        'format' => 'raw',
                                        'attribute' => 'name',
                                        'value' => function ( $model ) use ( $view ) {
                                            if ($model->user) {
                                                return sprintf('%s %s', $model->user->name, $model->user->surname);
                                            }

                                            return $model->name;
                                        },
                                        'options' => [
                                            'width' => '20%'
                                        ]
                                    ],
                                    [
                                        'format' => 'raw',
                                        'attribute' => 'duties',
                                        'value' => function ( $model ) use ( $view ) {
                                            return $model->duties;
                                        },
                                        'options' => [
                                            'width' => '15%'
                                        ]
                                    ],
                                    [
                                        'format' => 'raw',
                                        'attribute' => 'description',
                                        'value' => function ( $model ) use ( $view ) {
                                            return $model->description;
                                        },
                                        'options' => [
                                            'width' => '40%'
                                        ]
                                    ],
                                    [
                                        'format' => 'raw',
                                        'attribute' => Yii::t('app', 'Действия'),
                                        'value' => function ( $model ) {
                                            return '
                                            <a href="' . Url::to([ '/contacts/update', 'id' => $model->id ]) . '" class="btn-floating waves-effect waves-light blue tooltipped" data-position="top" data-delay="50" data-tooltip="' . Yii::t('app', 'Редактировать') . '"><i class="mdi-editor-mode-edit"></i></a>
                                            <a href="javascript:;" data-url="'.Url::to([ '/contacts/delete', 'id' => $model->id ]) . '" class="btn-floating waves-effect waves-light red tooltipped btn-delete-contact" data-position="top" data-delay="50" data-tooltip="' . Yii::t('app', 'Удалить') . '"><i class="mdi-content-clear"></i></a>';
                                        },
                                        'options' => [
                                            'width' => '15%'
                                        ]
                                    ],

                                ],
                                'tableOptions' => [
                                    'class' => 'table responsive-table'
                                ],
                                'layout' => "{items}\n{summary}\n{pager}"
                            ]); ?>
                            <?php Pjax::end(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-config-statistic-settings" class="modal">
            <div class="model-email-content" style="padding-top: 0px;">

            </div>
        </div>
    </div>

    <!-- Compose Email Trigger -->
    <div class="fixed-action-btn" style="bottom: 50px; right: 19px;">
        <a class="btn-floating btn-large red modal-trigger" href="#modal-add-contact"
           data-position="top"
           data-delay="50"
           data-tooltip="<?php echo Yii::t('app', 'Добавить контакт'); ?>">
            <i class="mdi-content-add-circle"></i>
        </a>
    </div>

    <!-- Compose Email Structure -->
    <div id="modal-add-contact" class="modal">
        <?php $form = ActiveForm::begin([ 'id' => 'form-contact-add', 'fieldConfig' => [
            'template' => '{input}{error}'
        ], 'options' => [ 'class' => 'col s12' ] ]); ?>

        <div class="modal-content">
            <nav class="red">
                <div class="nav-wrapper">
                    <div class="left col s12 m12 l12">
                        <h4 style="margin-top: 10px; margin-left: 20px;"><?php echo Yii::t('app', 'Новый контакт'); ?></h4>
                    </div>
                </div>
            </nav>
        </div>

        <div class="model-email-content">
            <div class="model-email-content" style="padding-top: 0px;">
                <div class="row">
                    <div class="row">
                        <div class="input-field col s12">
                            <?php echo $form->field($contactModel, 'name')->textInput([ 'class' => 'user-search', 'placeholder' => 'Имя', 'disabled' => false ]); ?>
                        </div>

                        <div id="user-search-result"></div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <?php echo $form->field($contactModel, 'name_parental_case')->textInput([ 'class' => '', 'placeholder' => 'Имя (род. падеж)', 'disabled' => false ]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <?php echo $form->field($contactModel, 'duties')->textInput([ 'class' => '', 'placeholder' => 'Должность', 'disabled' => false ]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <?php echo $form->field($contactModel, 'description')->textarea([ 'class' => 'materialize-textarea', 'placeholder' => 'Описание ', 'disabled' => false ]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="file-field input-field">
                            <div class="btn">
                                <span>Файл</span>
                                <?php echo $form->field($contactModel, 'photo')->fileInput([ 'class' => 'file btn-primary', 'multiple' => false ]); ?>
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="Загрузите файл">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <button type="submit" class="btn cyan waves-effect waves-light right">
                        <i class="mdi-content-save right"></i>&nbsp;<?= Yii::t('app', 'Добавить') ?>
                    </button>
                </div>
            </div>
        </div>

        <?php echo $form->field($contactModel, 'user_id')->hiddenInput()->label(false); ?>

        <?php ActiveForm::end(); ?>
    </div>


<?php $this->registerJS("
    $(document).on('click', '.js-add-new-contact', function(event) {
        $('#form-contact-add').submit();
    });
    
    $(document).on('change', '.js-bind-user-contact', function(event) {
        $('#contacts-user_id').val($(this).data('id'));
        
        console.log($('#contacts-user_id').val());
    });
    
    $(document).on('input', '.user-search', function(event) {
        var user_name = $.trim($(this).val()); 
        if (user_name.length > 3) {
            $.post('".Url::to(['/contacts/search-user-by-name'])."', 
            {
                user_name: user_name
            }, function(result) {
                $('#user-search-result').html(result.users_list);
            });
        }
    });
    
    $(document).on('click', '.collection-item', function() {
        var item = $(this);
         
        $('.collection-item').removeClass('selected');
        item.addClass('selected');
        
        $.post(item.data('url'), {
            item_id: item.data('id')
        }, function(result) {
            
        });
    });
    
    $(document).on('click', '.btn-delete-contact', function(event) {
        var target = $(event.currentTarget);
        
        swal(
            {    
                title: \"Удалить контакт ?\",
                text: \"Данные контакта будут удалены!\",   
                type: \"warning\",   
                showCancelButton: true,   
                confirmButtonColor: \"#DD6B55\",   
                confirmButtonText: \"Удалить\",
                cancelButtonText: \"Отмена\",   
                closeOnConfirm: false 
            }, 
            function(){   
                $.post(target.data(\"url\"), {
                    id: target.data(\"id\")
                }, function(result) {
                    if (result.success) {
                        window.location.reload();
                    } else {
                        swal(\"Ошибка при удалении.\", \"Обратитесь к разработчикам.\", \"error\");
                    }
                });                 
            });
    });
    
    $('.collection-item:first').trigger('click');
", \yii\web\View::POS_READY);
