<?php
$config = new \model\Config();
$values = $config->getConfig(['agencyEmails', 'agencyPhones', 'baseValuePerHour', 'whatsAppNumber'], true);
?>

<h1><?= __('Settings') ?></h1>

<div id="app-settings" class="container">
    <div class="row">
        <div class="col-4">
            <fieldset class="mb-5">
                <div class="mb-3">
                    <label for="agencyEmails" class="form-label">
                        <b><?= __('Emails') ?></b>
                    </label>
                    <input type="text" data-item-value="<?= $values['agencyEmails'] ?>" v-model="form.agencyEmails" @blur="setAgencyEmails" id="agencyEmails" class="form-control" placeholder="jhondoe@correo.com">
                    <small><?= __('Add emails separated by comma ,') ?></small>
                </div>
                <div class="mb-3">
                    <label for="agencyPhones" class="form-label">
                        <b><?= __('Phones') ?></b>
                    </label>
                    <input type="text" data-item-value="<?= $values['agencyPhones'] ?>" v-model="form.agencyPhones" @blur="validatePhone" id="agencyPhones" class="form-control" placeholder="3200000000">
                    <small><?= __('Add phones separated by comma ,') ?></small>
                </div>
                <div class="mb-3">
                    <label for="baseValuePerHour" class="form-label">
                        <b><?= __('Base value per hour') ?></b>
                    </label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="number" data-item-value="<?= $values['baseValuePerHour'] ?>" v-model="form.baseValuePerHour" @keyup="formatNumber" id="baseValuePerHour" class="form-control">
                        <div class="input-group-append">
                            <span class="input-group-text">COP</span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="whatsAppNumber" class="form-label">
                        <b><?= __('WhatsApp number') ?></b>
                    </label>
                    <input type="text" data-item-value="<?= $values['whatsAppNumber'] ?>" v-model="form.whatsAppNumber" @blur="validateWhatsApp" id="whatsAppNumber" class="form-control" placeholder="3200000000">
                </div>
            </fieldset>

            <button :disabled="validForm" class="btn btn-outline-primary w-100" @click="saveConfig($event)"><?= __('Save') ?></button>
        </div>
        <div class="col-4"></div>
        <div class="col-4"></div>
    </div>
</div>