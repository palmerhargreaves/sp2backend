<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 09.01.2019
 * Time: 10:14
 */

?>

<div class="col s12 m12 l12">
    <div class="card-panel">
        <h4 class="header">Проверки по заявкам за выбранный период</h4>
        <ul class="tabs tabs-months z-depth-1">
            <?php foreach ($result['data'] as $quarter => $data): ?>
                <li class="tab col s3">
                    <a class="active" href="#quarter-<?php echo $quarter; ?>">Квартал: <?php echo $quarter; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="col s12" style="margin-top: 20px;">
            <?php foreach ($result['data'] as $quarter => $quarter_items): ?>
                <div id="quarter-<?php echo $quarter; ?>" class="col s12">
                    <div class="col s12 m8 l12">
                        <table class="responsive-table bordered striped">
                            <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Проверено менеджером</th>
                                <th>Проверено менеджером / Дизайнером</th>
                                <th>Всего заявок</th>
                            </tr>
                            </thead>

                            <?php
                            $sum_manager_count = 0;
                            $sum_manager_designer_count = 0;
                            $sum_total = 0;
                            ?>

                            <tbody>
                            <?php foreach ($quarter_items as $items): ?>
                                <?php foreach ($items as $date_key => $date_item): ?>
                                    <?php
                                    $manager_check_count = isset($date_item['manager_check_count']) ? $date_item['manager_check_count'] : 0;
                                    $manager_designer_check_count = isset($date_item['manager_designer_check_count']) ? $date_item['manager_designer_check_count'] : 0;

                                    $manager_designer_both = $manager_check_count + $manager_designer_check_count;
                                    ?>
                                    <tr>
                                        <td><?php echo $date_key; ?></td>
                                        <td><?php echo $manager_check_count; ?></td>
                                        <td><?php echo $manager_designer_check_count; ?></td>
                                        <td><?php echo $manager_designer_both; ?></td>
                                    </tr>

                                    <?php
                                    $sum_manager_count += $manager_check_count;
                                    $sum_manager_designer_count += $manager_designer_check_count;
                                    $sum_total += $manager_designer_both;
                                    ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                            </tbody>

                            <tfoot style="font-weight: 700;">
                            <tr>
                                <td>Всего: </td>
                                <td><?php echo $sum_manager_count; ?></td>
                                <td><?php echo $sum_manager_designer_count; ?></td>
                                <td><?php echo $sum_total; ?></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
