<?php
/** @var \hipanel\widgets\AdvancedSearch $search */
?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('type')->dropDownList($search->model->getTypeOptions(), ['prompt' => '--']) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('periodicity')->dropDownList($search->model->getPeriodicityOptions(), ['prompt' => '--']) ?>
</div>
