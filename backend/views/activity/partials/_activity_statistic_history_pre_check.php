<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 19.02.2018
 * Time: 3:33
 */
?>

<h4 class="header">История по согласованию статистики</h4>

<div class="card-panel" style="padding-top: 3px;">
    <div class="row">
        <div class="col s12 m8 l12">
            <table id="mainTable" class="table-responsive sortable-sections-table sortable-sections-list">
                <thead>
                <tr>
                    <th>Действие</th>
                    <th>Пользователь</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody class="">

                <?php foreach ($items as $item): ?>
                    <tr class="sortable-list-items">
                        <td><?php echo $item->description; ?></td>
                        <td><?php echo sprintf('%s %s', $item->user->surname, $item->user->name); ?></td>
                        <td><?php echo $item->created_at; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

