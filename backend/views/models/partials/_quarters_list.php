<?php foreach ($quarters_list as $quarter): ?>
    <div class="input-field col s2">
        <input type="checkbox" class="quarters" id="quarter_<?php echo $quarter; ?>" data-quarter="<?php echo $quarter; ?>"/>
        <label for="quarter_<?php echo $quarter; ?>"><?php echo sprintf('Квартал: %s', $quarter); ?></label>
    </div>
<?php endforeach; ?>