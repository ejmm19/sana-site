<?php

$employeesModel = new \model\Employees();
$employees = $employeesModel->getEmployees();
$employeeId = !empty($_POST['employeeId']) ? $_POST['employeeId'] : '';
?>
<h1><?= __('Employees') ?></h1>
<p><?= __('Contenido de la segunda vista.') ?></p>

<div id="app-calendar" class="container-fuid">
    <div class="row w-100">
        <div class="col-4">
            <h2>&nbsp;</h2>
            <div id="employees-row" class="row">
                <?php if ($employees->have_posts()) : ?>
                    <?php while ($employees->have_posts()) : ?>
                        <?php $employees->the_post() ?>
                        <div class="card mb-3 col-6" style="max-width: 540px;" @click="submitForm($event)" data-name="<?= get_the_title() ?>">
                            <form class="form-employee" method="post" action="<?= acf_get_current_url() ?>">
                                <input type="hidden" name="employeeId" value="<?= get_the_ID() ?>">
                            </form>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="<?= get_the_post_thumbnail_url(); ?>" class="img-fluid rounded-start" width="100%" alt="<?= get_the_title() ?>">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= get_the_title() ?></h5>
                                        <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php wp_reset_postdata() ?>
                    <?php endwhile; ?>
                <?php endif ?>
            </div>
        </div>
        <div class="col-4">
            <h2 ><?= __('Calendar from') ?> <span id="employee-name">
                    <?= wp_trim_words($employeesModel->getEmployee($employeeId)->post_title, 2, '') ?></span>
            </h2>
            <div>
                <v-date-picker :attributes="attrs" mode="date" v-model="dateRange" timezone="America/Bogota" color="purple" is-expanded trim-weeks :min-date='new Date()'/>

                <!--to user-->
                <!--<v-date-picker
                        :attributes="attrs"
                        mode="dateTime"
                        v-model="dateRange"
                        :min-date='new Date()'
                        :disabled-dates='{ weekdays: [1] }'
                        :timezone="timezone"
                        :is-range="range"
                        :valid-hours="validHours"
                        is24hr
                        :minute-increment="30"
                        :from-page="fromPage"
                        is-required
                        @dayclick="onDayClick"
                />
                <div class="flex mt-2">
                    <span class="font-semibold text-gray-600 w-12">Start:</span>
                    <span class="ml-2">{{ dateRange  }}</span>
                </div>
                <div class="flex mt-2">
                    <span class="font-semibold text-gray-600 w-12">End:</span>
                    <span class="ml-2">{{ dateRange }}</span>
                </div>-->

            </div>
        </div>
        <div class="col-4">....</div>
    </div>
</div>
