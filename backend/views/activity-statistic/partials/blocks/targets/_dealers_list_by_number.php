<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 27.10.2018
 * Time: 15:12
 */
?>

<div class="card-panel">
    <h4 class="header2">Результат поиска</h4>
    <div class="row">
        <div class="col s12 m8 l12">
            <?php if (count($dealers_list)): ?>
            <table id="mainTable" class="table-responsive sortable-fields-table">
                <thead>
                <tr>
                    <th style="width: 100px;">#</th>
                    <th>Дилер</th>
                    <th>Группа</th>
                </tr>
                </thead>
                <tbody class="">
                <?php foreach ($dealers_list as $dealer): ?>
                    <tr class="sortable-list-items"
                        data-id="<?php echo $dealer->id; ?>">
                        <td >
                            <input id="dealer_number_<?php echo $dealer->id; ?>" type="radio" name="ActivityTargetBlock[dealer_id]" value="<?php echo $dealer->id; ?>">
                            <label for="dealer_number_<?php echo $dealer->id; ?>"><?php echo $dealer->getShortNumber(); ?></label>
                            <p class="help-block help-block-error"></p>
                        </td>

                        <td>
                            <?php echo $dealer->name; ?>
                        </td>
                        <td>
                            <?php echo $dealer->getDealerTypeLabel(); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div id="card-alert" class="card red lighten-5">
                    <div class="card-content red-text">
                        <p>Дилер не найден!</p>
                    </div>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

