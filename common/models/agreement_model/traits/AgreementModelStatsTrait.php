<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 23.11.2017
 * Time: 2:47
 */

namespace common\models\agreement_model\traits;


use common\models\agreement_model\AgreementModel;
use common\models\logs\Log;
use common\utils\Utils;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Font;

trait AgreementModelStatsTrait
{
    /**
     * @param $date
     * @param $status
     * @return array|int|string|\yii\db\ActiveRecord[]
     */
    public static function getModelsCount($date = null, $status = null) {
        return self::getModels(true, $date, $status);
    }

    /**
     * @param bool $count
     * @param null $date
     * @param null $status
     * @return array|int|string|\yii\db\ActiveRecord[]
     */
    public static function getModels($count = true, $date = null, $status = null) {
        $query = AgreementModel::find();

        $date = is_null($date) ? date('Y-m') : $date;

        $query->andWhere(['like', 'created_at', $date]);
        if (!is_null($status)) {
            is_array($status) ? $query->andWhere(['in', 'status', $status]) : $query->andWhere(['status' => $status]);
        }

        return $count ? $query->count() : $query->all();
    }

    /**
     * Получить статиску по моделям за выбранный период и тип заявок
     */
    public static function agreementPeriodByDays() {
        $agreement_period_type = \Yii::$app->request->post("filter_models_check") == Log::CHECK_TYPE_FULL_AGREEMENT_PERIOD ? true : false;

        return Log::getModelsReportsData($agreement_period_type);
    }

    /**
     * Получить статистику по моделям за выбранный период (в  разрезе часов)
     */
    public static function agreementPeriodDaysByHours() {
        return Log::getModelsReportsDataByHours();
    }

    /**
     * Получить статистика по моделям за выбранный период (полная проверка заявок)
     */
    public static function agreementPeriodAllDays() {
        $agreement_period_type = \Yii::$app->request->post("filter_models_check") == Log::CHECK_TYPE_FULL_AGREEMENT_PERIOD ? true : false;

        return Log::getModelsReportsData($agreement_period_type);
    }

    /**
     * Получить статистику по просроченным заявкам (больше 24-х часов)
     */
    public static function agreementPeriodByDesigner() {
        return Log::getModelsReportsDataByHours(AgreementModel::DESIGNER_MAX_WORK_HOURS, true);
    }

    /**
     * Експорт данных по заявкам (период выполнения)
     */
    public static function exportAgreementPeriodByDays() {
        $result = Log::getModelsReportsData();

        $models = $result['models_list'];
        $min_days = $result['min_days'];
        $models_count = $result['count'];
        $max_days = $result['max_days'];

        $pExcel = new \PHPExcel();

        $pExcel->setActiveSheetIndex(0);
        $aSheet = $pExcel->getActiveSheet();
        $aSheet->setTitle('Расширенная статистика');


        $headers = array();
        $headers[] = 'Заявка';

        $column = 1;
        $days_cells = [];
        foreach (range($min_days, $max_days) as $day) {
            $headers[] = sprintf("%s д%s", $day, \common\utils\NumbersHelper::numberEnd($day, array( 'ень', 'ня', 'ней' )));

            $days_cells[$day] = $column++;
        }

        $aSheet->fromArray($headers, null, 'A1');

        $header_font = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '9',
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $header_quarter_font = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '12',
                'bold' => true,
                'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $header_font_with_underline = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '8',
                'bold' => true,
                'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $last_letter = PHPExcel_Cell::stringFromColumnIndex(count($headers) - 1);

        $aSheet->getRowDimension('1')->setRowHeight(45);
        $aSheet->getStyle('A1:' . $last_letter . '1')->getAlignment()->setWrapText(true);

        $aSheet->getStyle('A1:' . $last_letter . '1')->applyFromArray($header_font);
        $aSheet->getStyle('A1:A1')->applyFromArray($header_font_with_underline);

        $cellIterator = $aSheet->getRowIterator()->current()->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $aSheet->getStyle('A1:' . $last_letter . '1')->getAlignment()->setWrapText(true);

        /** @var PHPExcel_Cell $cell */
        foreach ($cellIterator as $cell) {
            $aSheet->getColumnDimension($cell->getColumn())->setWidth(40);
        }

        $aSheet->getColumnDimension('A')->setWidth(25);
        $aSheet->freezePane('B3');

        $row = 3;

        foreach ($models as $model_id => $steps_models_list) {
            $aSheet->setCellValueByColumnAndRow(0, $row, sprintf('# %s', $model_id));
            $aSheet->getStyle('A'.$row.':A' . $row)->applyFromArray($header_quarter_font);

            foreach ($steps_models_list as $step_key => $step_data) {
                $aSheet->setCellValueByColumnAndRow(1, $row,
                    mb_substr($step_data[ 'steps_action' ][ 'first' ][ 'data' ][ 'description' ],0, 50));
                $aSheet->setCellValueByColumnAndRow($days_cells[$step_data['days']], $row,
                    mb_substr($step_data[ 'steps_action' ][ 'last' ][ 'data' ][ 'description' ],0, 50));

                $aSheet->getStyle('B'.$row.':' . $last_letter . $row)->applyFromArray($header_font);

                $row++;
            }
        }

        $column = 1;
        $row += 2;

        $aSheet->setCellValueByColumnAndRow(0, $row, 'Итого:');
        foreach (range($min_days, $max_days) as $day) {
            $aSheet->setCellValueByColumnAndRow($column++, $row, isset($models_count[$day]) ? $models_count[$day]['count'] : 0);
        }

        $aSheet->getStyle('A'.$row.':' . $last_letter . $row)->applyFromArray($header_font);

        $file_name = 'export_models_timeline_agreement.xlsx';

        $objWriter = PHPExcel_IOFactory::createWriter($pExcel, "Excel2007");
        $objWriter->save(\Yii::$app->getBasePath() . '/web/downloads/' . $file_name);

        return [ 'success' => count($models) ? true : false, 'url' => 'downloads/' . $file_name ];
    }

    /**
     * Экспорт данных по моделям при полном периоде проврки заявок
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public static function exportAgreementPeriodAllDays() {
        $result = Log::getModelsReportsData(Log::CHECK_TYPE_FULL_AGREEMENT_PERIOD);

        $models = $result['models_list'];
        $min_days = $result['min_days'];
        $models_count = $result['count'];
        $max_days = $result['max_days'];
        $models_count_by_days = isset($result['models_count_by_days']) ? $result['models_count_by_days'] : [];

        $pExcel = new \PHPExcel();

        $pExcel->setActiveSheetIndex(0);
        $aSheet = $pExcel->getActiveSheet();
        $aSheet->setTitle('Расширенная статистика');

        $headers = array();
        $headers[] = 'Заявка';

        $column = 1;
        $days_cells = [];

        if (!empty($models_count_by_days)) {
            foreach ($models_count_by_days as $day => $count) {
                $headers[] = sprintf("%s д%s", $day, \common\utils\NumbersHelper::numberEnd($day, array( 'ень', 'ня', 'ней' )));

                $days_cells[ $day ] = $column++;
            }
        } else {
            foreach (range($min_days, $max_days) as $day) {
                $headers[] = sprintf("%s д%s", $day, \common\utils\NumbersHelper::numberEnd($day, array( 'ень', 'ня', 'ней' )));

                $days_cells[ $day ] = $column++;
            }
        }

        $aSheet->fromArray($headers, null, 'A1');
        $header_font = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '9',
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $header_quarter_font = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '12',
                'bold' => true,
                'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $header_font_with_underline = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '8',
                'bold' => true,
                'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $last_letter = PHPExcel_Cell::stringFromColumnIndex(count($headers) - 1);

        $aSheet->getRowDimension('1')->setRowHeight(45);
        $aSheet->getStyle('A1:' . $last_letter . '1')->getAlignment()->setWrapText(true);

        $aSheet->getStyle('A1:' . $last_letter . '1')->applyFromArray($header_font);
        $aSheet->getStyle('A1:A1')->applyFromArray($header_font_with_underline);

        $cellIterator = $aSheet->getRowIterator()->current()->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $aSheet->getStyle('A1:' . $last_letter . '1')->getAlignment()->setWrapText(true);

        /** @var PHPExcel_Cell $cell */
        foreach ($cellIterator as $cell) {
            $aSheet->getColumnDimension($cell->getColumn())->setWidth(20);
        }

        $aSheet->getColumnDimension('A')->setWidth(25);
        $aSheet->freezePane('B3');

        $row = 3;

        foreach ($models as $model_id => $steps_models_list) {
            $aSheet->setCellValueByColumnAndRow(0, $row, sprintf('# %s', $model_id));
            $aSheet->getStyle('A'.$row.':A' . $row)->applyFromArray($header_quarter_font);

            foreach ($steps_models_list as $step_key => $step_data) {
                if (!empty($step_data[ 'steps_action' ][ 'last' ][ 'data' ])) {
                    if ($step_data[ 'steps_action' ][ 'last' ][ 'data' ][ 'action' ] == 'accepted') {
                        //Utils::drawExcelImage('hand_up.png', PHPExcel_Cell::stringFromColumnIndex($days_cells[ $step_data[ 'days' ] ]) . $row, $pExcel, 50);
                        $aSheet->setCellValueByColumnAndRow($days_cells[ $step_data[ 'days' ] ], $row, 'согл.');
                    }

                    $aSheet->getStyle('B' . $row . ':' . $last_letter . $row)->applyFromArray($header_font);
                }

                $row++;
            }
        }

        $column = 1;
        $row += 2;

        $aSheet->setCellValueByColumnAndRow(0, $row, 'Итого:');

        if (!empty($models_count_by_days)) {
            foreach ($models_count_by_days as $day => $count) {
                $aSheet->setCellValueByColumnAndRow($column++, $row, $count);
            }
        } else {
            foreach (range($min_days, $max_days) as $day) {
                $aSheet->setCellValueByColumnAndRow($column++, $row, isset($models_count[ $day ]) ? $models_count[ $day ][ 'count' ] : 0);
            }
        }

        $aSheet->getStyle('A'.$row.':' . $last_letter . $row)->applyFromArray($header_font);

        $file_name = 'export_models_timeline_agreement.xlsx';

        $objWriter = PHPExcel_IOFactory::createWriter($pExcel, "Excel2007");
        $objWriter->save(\Yii::$app->getBasePath() . '/web/downloads/' . $file_name);

        return [ 'success' => count($models) ? true : false, 'url' => 'downloads/' . $file_name ];
    }

    /**
     * Экспорт данных по моделям при полном периоде проврки заявок
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public static function exportAgreementPeriodDaysByHours() {
        return self::makeExportDataByHours();
    }

    /**
     * Получить статистику по просроченным заявкам (больше 24-х часов)
     */
    public static function exportAgreementPeriodByDesigner() {
        return self::makeExportDataByHours(AgreementModel::DESIGNER_MAX_WORK_HOURS);
    }

    /**
     * @param int $max_work_hours
     * @return array
     */
    private static function makeExportDataByHours($max_work_hours = 0) {
        $result = Log::getModelsReportsDataByHours($max_work_hours);

        $models = $result['models_list'];
        $models_count = $result['count'];

        $pExcel = new \PHPExcel();

        $pExcel->setActiveSheetIndex(0);
        $aSheet = $pExcel->getActiveSheet();
        $aSheet->setTitle('Расширенная статистика');

        $headers = array();
        $headers[] = 'Заявка';

        $column = 1;
        $days_cells = [];

        foreach ($models_count as $hour => $count) {
            $headers[] = sprintf("%s ча%s", $hour, \common\utils\NumbersHelper::numberEnd($hour, array( 'с', 'са', 'сов' )));
            $days_cells[ $hour ] = $column++;
        }


        $aSheet->fromArray($headers, null, 'A1');
        $header_font = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '9',
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $header_quarter_font = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '12',
                'bold' => true,
                'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $header_font_with_underline = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '8',
                'bold' => true,
                'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $last_letter = PHPExcel_Cell::stringFromColumnIndex(count($headers) - 1);

        $aSheet->getRowDimension('1')->setRowHeight(45);
        $aSheet->getStyle('A1:' . $last_letter . '1')->getAlignment()->setWrapText(true);

        $aSheet->getStyle('A1:' . $last_letter . '1')->applyFromArray($header_font);
        $aSheet->getStyle('A1:A1')->applyFromArray($header_font_with_underline);

        $cellIterator = $aSheet->getRowIterator()->current()->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $aSheet->getStyle('A1:' . $last_letter . '1')->getAlignment()->setWrapText(true);

        /** @var PHPExcel_Cell $cell */
        foreach ($cellIterator as $cell) {
            $aSheet->getColumnDimension($cell->getColumn())->setWidth(20);
        }

        $aSheet->getColumnDimension('A')->setWidth(25);
        $aSheet->freezePane('B3');

        $row = 3;

        foreach ($models as $model_id => $steps_models_list) {
            $aSheet->setCellValueByColumnAndRow(0, $row, sprintf('# %s', $model_id));
            $aSheet->getStyle('A'.$row.':A' . $row)->applyFromArray($header_quarter_font);

            foreach ($steps_models_list as $step_key => $step_data) {
                if (!empty($step_data[ 'steps_action' ][ 'last' ][ 'data' ])) {
                    $aSheet->setCellValueByColumnAndRow($days_cells[ $step_data[ 'days' ] ], $row, 'согл.');

                    $aSheet->getStyle('B' . $row . ':' . $last_letter . $row)->applyFromArray($header_font);
                }

                $row++;
            }
        }

        $column = 1;
        $row += 2;

        $aSheet->setCellValueByColumnAndRow(0, $row, 'Итого:');
        foreach ($models_count as $hour => $count) {
            $aSheet->setCellValueByColumnAndRow($column++, $row, $models_count[$hour]["count"]);
        }

        $aSheet->getStyle('A'.$row.':' . $last_letter . $row)->applyFromArray($header_font);

        $file_name = 'export_models_timeline_agreement.xlsx';

        $objWriter = PHPExcel_IOFactory::createWriter($pExcel, "Excel2007");
        $objWriter->save(\Yii::$app->getBasePath() . '/web/downloads/' . $file_name);

        return [ 'success' => count($models) ? true : false, 'url' => 'downloads/' . $file_name ];
    }
}
