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
        <p></p>

        <div id="container-filter-models-list" class="col s12 m8 l12">
            <table class="responsive-table bordered striped">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Проверено менеджером</th>
                    <th>Проверено менеджером / Дизайнером</th>
                    <th>Всего заявок</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td><?php echo $result['check_by_date']; ?></td>
                    <td><?php echo $result['check_count']; ?></td>
                    <td><?php echo $result['check_count_by_manager_designer']; ?></td>
                    <td><?php echo $result['check_count'] + $result['check_count_by_manager_designer']; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
