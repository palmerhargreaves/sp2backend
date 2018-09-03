<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 12.12.2017
 * Time: 5:17
 */

?>

    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card-panel">
                <h4 class="header2">Выберите год / квартал </h4>
                <div class="row">
                    <div class="col s12">
                        <div class="row">
                            <div class="input-field col s6">
                                <select id="sb_slot_year" data-url="<?php echo \yii\helpers\Url::to(["/mandatory-activities/change-year-quarter"]); ?>">
                                    <?php foreach ($years as $year): ?>
                                        <option value="<?php echo $year; ?>" <?php echo $current_year == $year ? "selected" : ""; ?>><?php echo $year; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="input-field col s6">
                                <select id="sb_slot_quarter" data-url="<?php echo \yii\helpers\Url::to(["/mandatory-activities/change-year-quarter"]); ?>">
                                    <?php foreach ($quarters as $quarter): ?>
                                        <option value="<?php echo $quarter; ?>" <?php echo $current_quarter == $quarter ? "selected" : ""; ?>><?php echo $quarter; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <!--card widgets start-->
    <div class="row">
        <div id="container-slots-list" class="col s12 m12 l12"><?php echo $slots_list; ?></div>
    </div>


    <div class="fixed-action-btn" style="bottom: 50px; right: 100px;">
        <a class="btn-floating btn-large">
            <i class="mdi-action-stars"></i>
        </a>
        <ul>
            <li>
                <a href="#!" id="js_add_slot"
                   data-quarter="<?php echo $quarter; ?>"
                   data-year="<?php echo $year; ?>"
                   data-url="<?php echo \yii\helpers\Url::to(['/mandatory-activities/add-slot']); ?>"
                   class="btn-floating red tooltipped" data-position="top"
                   data-delay="50"
                   data-tooltip="<?php echo Yii::t('app', 'Добавить слот'); ?>" style="transform: scaleY(0.4) scaleX(0.4) translateY(40px) translateX(0px); opacity: 0;">
                    <i class="large mdi-content-add"></i>
                </a>
            </li>
        </ul>
    </div>

    <div id="modal-slot-config" class="modal bottom-sheet" style="max-height: 60%;">
        <div class="modal-content">
        </div>
    </div>



<?php $this->registerJs('
    $(document).on("change", "#sb_slot_year, #sb_slot_quarter", function(event) {
        $.post($(this).data("url"), {
            slot_year: $("#sb_slot_year").val(),
            slot_quarter: $("#sb_slot_quarter").val()
        }, function(result) {
            $("#container-slots-list").html(result.slots_list);
            
            reinitEvents();
        });
    });
    
    $(document).on("change", ".ch-slot-config-activity-item", function(event) {
        var target = $(event.currentTarget);
        
        $.post(target.data("url"), {
            activity_id: target.data("id"),
            slot_id: target.data("slot-id"),
            set: target.is(":checked") ? 1 : 0
        }, function(result) {
            $("#container-slot-activities-list-" + target.data("slot-id")).html(result.content);
        });
    });
    
    $(document).on("click", ".modal-trigger", function(event) {
        var element = $(event.currentTarget);
        
        $.post(element.data("url"), 
            {
                id: element.data("id")
            }, function(result) {
                $(".modal-content").html(result.content);
            }
        );
    }); 
    
    $(document).on("click", "#js_add_slot", function(event) {
        var target = $(event.currentTarget);
        
        $.post(target.data("url"), {
            slot_quarter: $("#sb_slot_quarter").val(),
            slot_year: $("#sb_slot_year").val()
        }, function(result) {
            $("#container-slots-list").html(result.content);
            
            Materialize.toast("Новый слот успешно добавлен.", 2500);
            
            reinitEvents();
        });    
    });
    
    $(document).on("click", ".slot-item-remove", function(event) {
        var target = $(event.currentTarget);
        
        swal(
            {    
                title: "Удалить слот ?",
                text: "Привязанные данные будут утеряны без возможности восстановления!",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Удалить",
                cancelButtonText: "Отмена",   
                closeOnConfirm: false 
            }, 
            function(){   
                $.post(target.data("url"), {
                    id: target.data("id")
                }, function(result) {
                    if (result.success) {
                        $(".slot-" + target.data("id")).remove();
                        swal("Удалено!", "Слот успешно удален.", "success");
                    } else {
                        swal("Ошибка при удалении.", "Обратитесь к разработчикам.", "error");
                    }
                });                 
            });

    });
    
    function reinitEvents() {
        $(\'.collapsible-accordion\').collapsible({
            accordion: false
        });
        
        $(".tooltipped-slots").tooltip({ delay: 50 });
        
        initLeanModal(".modal-trigger");
    }   
             
    $(".tooltipped-slots").tooltip({ delay: 50 });
   
', \yii\web\View::POS_READY);
