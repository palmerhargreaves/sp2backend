<?php

namespace common\models\activity\sections;

/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 03.09.2018
 * Time: 11:51
 */

interface ActivitySectionInterface {

    /**
     * Получить данные по блоку
     * @param $activity
     * @param $section_template_id
     * @return mixed
     */
    public function getSection($activity, $section_template_id);

    /**
     * Render field list of section
     * @param $view
     * @return mixed
     */
    public function render($view);

    /** Render fields
     * @param $view
     * @return mixed
     */
    public function renderFields($view);

    /**
     * @return mixed
     */
    public function getModel();

    /**
     * Add new field
     * @param $view
     * @return mixed
     */
    public function addBlockField($view);
}
