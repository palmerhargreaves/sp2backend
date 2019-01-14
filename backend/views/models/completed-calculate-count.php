<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 08.01.2019
 * Time: 10:10
 */
use common\utils\D;
use common\utils\Utils;
use yii\helpers\Url;

?>
    <div id="modal-model-logs-history" class="modal modal-fixed-footer">
        <div class="modal-content">
            <!-- Vertical Timeline -->
            <section id="conference-timeline">
                <div class="loading-progress loading-progress-model-timeline" class="col s12 m8 l9">
                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card-panel">
                <div class="section">
                    <h4 class="header2">Параметры фильтра</h4>
                    <div class="row">
                        <div class="col s12 m12 l12">
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="filter_date" type="date" class="datepicker_new">
                                    <label for="filter_date" class="">По дате</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="section">
                    <h6 class="header6">Выберите год</h6>
                    <div class="row">
                        <div class="col s12 m12 l12">
                            <div class="row">
                                <div class="input-field col s6">
                                    <select id="filter_year" data-url="<?php echo Url::to(['/models/on-change-year']); ?>">
                                        <?php foreach ($models_completed_count_util->getYearsList() as $year_item): ?>
                                            <option <?php echo $year_item['selected'] ? 'selected' : ''; ?>><?php echo $year_item['year']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                    $quarters_list = $models_completed_count_util->getQuartersList();
                ?>
                <div class="divider"></div>
                <div class="section">
                    <h6 class="header6">Выберите квартал(ы)</h6>
                    <div class="row">
                        <div class="col s12 m12 l12">
                            <div id="container-quarters" class="row">
                                <?php echo Yii::$app->controller->renderPartial('partials/_quarters_list', [ 'quarters_list' => $quarters_list ]); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="section">
                    <h6 class="header6">Выберите месяц(ы)</h6>
                    <div class="row">
                        <div class="col s12 m12 l12">
                            <div id="container-months" class="row">
                                <?php echo Yii::$app->controller->renderPartial('partials/_months_list', [ 'quarters_list' => $quarters_list ]); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="section">
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field col s12">
                                <button id="js-make-filter" class="btn cyan waves-effect waves-light right"
                                        type="button"
                                        name="action"
                                        data-url="<?php echo Url::to(['/models/completed-calculate-count']); ?>">Фильтр
                                    <i class="mdi-action-search right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="loading-progress" class="col s12 m8 l9" style="display: none;">
        <div class="progress">
            <div class="indeterminate"></div>
        </div>
    </div>

    <div id="container-data">

    </div>

<?php $this->registerJs('
      new ModelsCompletedCount({}).start();
  
');
