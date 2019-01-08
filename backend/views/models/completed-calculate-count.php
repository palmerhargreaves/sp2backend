<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 08.01.2019
 * Time: 10:10
 */
use common\utils\D;
use common\utils\Utils;

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
                                    <select>
                                        <?php foreach ($models_completed_count_util->getYearsList() as $year_item): ?>
                                            <option <?php echo $year_item['selected'] ? 'selected' : ''; ?>><?php echo $year_item['year']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="section">
                    <h6 class="header6">Выберите квартал(ы)</h6>
                    <div class="row">
                        <div class="col s12 m12 l12">
                            <div class="row">
                                <?php foreach ($models_completed_count_util->getQuartersList() as $quarter): ?>
                                    <div class="input-field col s2">
                                        <input type="checkbox" class="quarters" id="quarter_<?php echo $quarter; ?>" data-quarter="<?php echo $quarter; ?>"/>
                                        <label for="quarter_<?php echo $quarter; ?>"><?php echo sprintf('Квартал: %s', $quarter); ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="section">
                    <h6 class="header6">Выберите месяц(ы)</h6>
                    <div class="row">
                        <div class="col s12 m12 l12">
                            <div class="row">
                                <?php foreach ($models_completed_count_util->getMonthsList() as $quarter => $months): ?>
                                    <?php foreach ($months as $month): ?>
                                        <div class="input-field col s1">
                                            <input type="checkbox" class="months" id="month_<?php echo $month; ?>" data-month-quarter="<?php echo $quarter; ?>"/>
                                            <label for="month_<?php echo $month; ?>"><?php echo D::getMonthName($month); ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="section">
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field col s12">
                                <button id="js_make_filter" class="btn cyan waves-effect waves-light right"
                                        type="submit"
                                        name="action">Фильтр
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

    <div class="loading-progress" class="col s12 m8 l9">
        <div class="progress">
            <div class="indeterminate"></div>
        </div>
    </div>

    <div id="container-data" style="display: none;">
        <div class="col s12 m12 l12">
            <div class="card-panel">
                <h4 class="header">Проверки по заявкам за выбранный период</h4>
                <p></p>

                <div id="container-filter-models-list" class="col s12 m8 l12"></div>
            </div>

        </div>
    </div>

<?php $this->registerJs('
      new ModelsCompletedCount({}).start();
  
');
