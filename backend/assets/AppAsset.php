<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';

    public $sourcePath = '@backend/assets/public';

    public $css = [
        "assets/materialize/css/materialize.min.css",
        "assets/materialize/css/style.min.css",
        "assets/materialize/css/custom/custom.min.css",

        "assets/materialize/js/plugins/perfect-scrollbar/perfect-scrollbar.css",
        "assets/materialize/js/plugins/jvectormap/jquery-jvectormap.css",
        "assets/materialize/js/plugins/chartist-js/chartist.min.css",
        "assets/materialize/js/plugins/morris-chart/morris.css",
        "assets/materialize/js/plugins/jquery.nestable/nestable.css",

        "assets/materialize/js/plugins/sweetalert/sweetalert.css",
        "assets/materialize/css/spectrum.css",
        "assets/css/timeline.css",
        "assets/materialize/js/plugins/animate-css/animate.css",

        "assets/css/app.css",
    ];

    public $js = [
        //"assets/materialize/js/plugins/jquery-1.11.2.min.js",
        "assets/materialize/js/materialize.min.js",
        "assets/materialize/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js",

        "assets/materialize/js/plugins/chartist-js/chartist.min.js",
        "assets/materialize/js/plugins/chartjs/chart.min.js",

        "assets/materialize/js/plugins/raphael/raphael-min.js",
        "assets/materialize/js/plugins/morris-chart/morris.min.js",

        "assets/materialize/js/plugins/sparkline/jquery.sparkline.min.js",
        "assets/materialize/js/plugins/sparkline/sparkline-script.js",

        "assets/materialize/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js",
        "assets/materialize/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js",
        "assets/materialize/js/plugins/jvectormap/vectormap-script.js",

        "assets/materialize/js/plugins/jquery.nestable/jquery.nestable.js",

        "assets/materialize/js/plugins/sweetalert/sweetalert.min.js",

        'assets/materialize/js/plugins/spectrum/spectrum.js',

        "assets/materialize/js/plugins.min.js",
        "assets/materialize/js/custom-script.js",

        "assets/js/table-dragger.min.js",

        "assets/materialize/js/materialize-plugins/date_picker/ru_RU.js",

        "assets/materialize/js/plugins/floatThead/jquery.floatThead.min.js",
        "assets/materialize/js/plugins/floatThead/jquery.floatThead-slim.min.js",

        "assets/materialize/js/plugins/jquery-validation/jquery.validate.min.js",
        "assets/materialize/js/plugins/jquery-validation/additional-methods.min.js",

        "assets/js/plugins/draggable/draggable.js",

        "assets/js/app/app.js",

        "assets/js/app/activity/statistic/base_form.js",
        "assets/js/app/activity/statistic/activity_statistic.js",
        "assets/js/app/activity/statistic/form.js",

        "assets/js/app/activity/company/image.js",

    ];

    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
