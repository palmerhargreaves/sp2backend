<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 24.11.2017
 * Time: 22:38
 */

namespace common\models\activity\traits;


use common\models\activity\Activity;
use common\models\activity\ActivityExtendedStatisticFieldsData;
use common\models\activity\ActivityExtendedStatisticSections;
use common\models\activity\fields\ActivityExtendedStatisticFields;
use common\models\activity\steps\ActivityExtendedStatisticSteps;
use common\models\agreement_model\AgreementModelSettings;
use common\models\dealers\Dealers;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Font;
use Yii;
use yii\helpers\ArrayHelper;

trait ActivityExportStatisticTrait
{
    protected static function conceptDate ( $concept_id, &$concept_dates )
    {
        if (!array_key_exists($concept_id, $concept_dates)) {
            $concept_date = AgreementModelSettings::find()->where([ 'model_id' => $concept_id ])->one();

            $concept_dates[ $concept_id ] = '';
            if ($concept_date) {
                $concept_dates[ $concept_id ] = $concept_date->certificate_date_to;
            }
        }
    }

    public function exportStatisticData ()
    {
        $activity_id = \Yii::$app->request->get('activity_id') ? Yii::$app->request->get('activity_id') : Yii::$app->request->post('activity_id');
        $quarter = \Yii::$app->request->get('q') ? Yii::$app->request->get('q') : Yii::$app->request->post('q');
        $steps_ids = \Yii::$app->request->get('steps') ? Yii::$app->request->get('steps') : Yii::$app->request->post('steps');

        if (!is_null($steps_ids)) {
            foreach ($steps_ids as $key => $step) {
                $steps_ids[] = $step[ 'step_id' ];
            }
        }

        if (!is_null(Yii::$app->request->post('q'))) {
            $quarter = Yii::$app->request->post('q');
        }

        $quarters = !empty($quarter) ? [ $quarter ] : [ 1, 2, 3, 4 ];

        //Получаем шаг(и) по активности
        $steps = empty($steps_ids) || is_null($steps_ids)
            ? ActivityExtendedStatisticSteps::find()->where([ 'activity_id' => $activity_id ])->orderBy([ 'position' => SORT_ASC ])->all()
            : ActivityExtendedStatisticSteps::find()->where([ 'activity_id' => $activity_id ])
                ->andWhere([ 'in', 'id', $steps_ids ])->orderBy([ 'position' => SORT_ASC ])->all();

        $dealers = [];
        foreach (Dealers::find()->select([ 'id', 'name', 'number' ])->where([ 'status' => true ])->orderBy([ 'id' => SORT_ASC ])->all() as $dealer) {
            $dealers[ $dealer->id ] = $dealer;
        }

        $fields = [];
        $fields_headers = [];
        $concept_dates = [];

        foreach ($steps as $step) {
            //Получаем список разделов привязанных к шагам
            $fields_items = ActivityExtendedStatisticFields::find()->where([ 'step_id' => $step->id ])->andWhere([ '!=', 'value_type', 'text' ])->orderBy([ 'parent_id' => SORT_ASC ])->all();

            $sections = [];
            $sections_ids = [];
            $sections_fields = [];

            foreach ($fields_items as $field_item) {
                $sections[ $field_item->parent_id ][ 'fields_ids' ][] = $field_item->id;
                $sections_ids[ $field_item[ 'parent_id' ] ] = $field_item[ 'parent_id' ];
            }

            $sections_list = ActivityExtendedStatisticSections::find()->where([ 'in', 'id', $sections_ids ])->orderBy([ 'position' => SORT_ASC ])->all();
            foreach ($sections_list as $section) {
                $section_fields = $sections[ $section->id ];

                $section_fields_items = ActivityExtendedStatisticFields::find()->andWhere([ 'in', 'id', $section_fields[ 'fields_ids' ] ])->orderBy([ 'position' => SORT_ASC ])->all();
                foreach ($section_fields_items as $section_field) {
                    $sections_fields[] = $section_field;
                }
            }

            foreach ($quarters as $quarter) {
                foreach ($dealers as $dealer_id => $dealer) {
                    foreach ($sections_fields as $field_item) {
                        $value = null;

                        //Заполняем список полей (заголовки)
                        if (!array_key_exists($field_item->id, $fields_headers)) {
                            $fields_headers[ $field_item->id ] = $field_item->header;
                        }

                        //Проверка на обычное поле или расчитываемое
                        if ($field_item->isCalcField()) {
                            $values = $field_item->getCalcValue($dealer->id, $activity_id, $step->id, $quarter);

                            foreach ($values as $concept_id => $value) {
                                self::conceptDate($concept_id, $concept_dates);

                                $fields[ $quarter ][ $dealer[ 'id' ] ][ $concept_id ][ $field_item->id ] = [ 'field' => $field_item->id, 'value' => $value, 'is_calc' => true ];
                            }
                        } else {
                            $field_datas = ActivityExtendedStatisticFieldsData::find()
                                ->where([ 'dealer_id' => $dealer->id, 'activity_id' => $activity_id, 'field_id' => $field_item->id, 'step_id' => $step->id, 'quarter' => $quarter ])->all();

                            foreach ($field_datas as $field_data) {
                                self::conceptDate($field_data->concept_id, $concept_dates);

                                $value = $field_data->value;
                                //Если тип поля деньги, то вычисляем значение
                                if ($field_item->value_type == ActivityExtendedStatisticFields::FIELD_TYPE_MONEY && strpos($value, ":") !== FALSE && !empty($value)) {
                                    $value = explode(":", $value);
                                    $value = isset($value[ 1 ]) ? sprintf('%s руб. %s коп.', $value[ 0 ], $value[ 1 ]) : sprintf("%s руб.", $value[ 0 ]);
                                }

                                $fields[ $quarter ][ $dealer[ 'id' ] ][ $field_data->concept_id ][ $field_item->id ] = [ 'field' => $field_item->id, 'value' => $value, 'is_calc' => false ];
                            }
                        }
                    }
                }
            }
        }

        $pExcel = new \PHPExcel();

        $pExcel->setActiveSheetIndex(0);
        $aSheet = $pExcel->getActiveSheet();
        $aSheet->setTitle('Расширенная статистика');


        $headers = array();
        $headers[] = 'Дилер (название и номер)';
        $headers[] = 'Концепция';
        $headers[] = 'Срок действия сертификата';

        $headers = array_merge($headers, $fields_headers);
        $aSheet->fromArray($headers, null, 'A1');

        $leftFont = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '12',
                'bold' => false
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $leftFontWithUnderline = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '12',
                'bold' => false,
                'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $header_font = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '8',
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

        $boldFont = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '12',
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $center = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '10',
                'bold' => false
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $right = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '10',
                'bold' => false
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $right_bold = array(
            'font' => array(
                'name' => 'Calibri',
                'size' => '9',
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $bold_small_font = array(
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

        $bold_normal_font = array(
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
            $aSheet->getColumnDimension($cell->getColumn())->setWidth(25);
        }

        $aSheet->getColumnDimension('A')->setWidth(35);
        $aSheet->freezePane('B3');

        $row = 3;

        foreach ($fields as $quarter => $quarter_data) {

            $aSheet->setCellValueByColumnAndRow(0, $row, sprintf('Квартал: %s', $quarter));
            $aSheet->getStyle('A'.$row.':' . $last_letter . $row)->applyFromArray($header_quarter_font);

            $row++;
            foreach ($quarter_data as $dealer_id => $dealer_data) {
                foreach ($dealer_data as $concept_id => $concept_data) {
                    $column = 0;

                    $aSheet->setCellValueByColumnAndRow($column++, $row, sprintf('%s [%s]', $dealers[ $dealer_id ]->name, $dealers[ $dealer_id ]->number));
                    $aSheet->setCellValueByColumnAndRow($column++, $row, $concept_id);
                    $aSheet->setCellValueByColumnAndRow($column++, $row, $concept_id != 0 ? date("d-m-Y", strtotime($concept_dates[ $concept_id ])) : "");

                    foreach ($concept_data as $field_id => $field_data) {
                        $aSheet->setCellValueByColumnAndRow($column++, $row, $field_data[ 'value' ]);
                    }

                    $row++;
                }
            }
            $row++;
        }

        $file_name = 'sc_general_steps.xlsx';

        $objWriter = PHPExcel_IOFactory::createWriter($pExcel, "Excel2007");
        $objWriter->save(\Yii::$app->getBasePath() . '/web/downloads/' . $file_name);

        return [ 'success' => count($fields) > 0, 'url' => 'downloads/' . $file_name ];
    }
}