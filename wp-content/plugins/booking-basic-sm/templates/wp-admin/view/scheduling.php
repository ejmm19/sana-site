<?php
$order = new \model\Order();
$employees = new \model\Employees();
$orders = $order->getOrders('DESC');
?>
<h1><?= __('Scheduling') ?></h1>
<table id="app-order-data" class="table table-hover">
    <caption><?= __('Schedule list') ?></caption>
    <thead>
    <tr>
        <th scope="col">Id</th>
        <th scope="col">
            <?= __('Customer Email') ?>
        </th>
        <th scope="col">
            <?= __('Name') ?>
        </th>
        <th scope="col">
            <?= __('Phone') ?>
        </th>
        <th scope="col">
            <?= __('Employee') ?>
        </th>
        <th scope="col">
            <?= __('Status') ?>
        </th>
        <th scope="col">
            <?= __('Init date') ?>
        </th>
        <th scope="col">
            <?= __('End date') ?>
        </th>
        <th scope="col">
            <?= __('Created at') ?>
        </th>
        <th scope="col">
            <?= __('Action') ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $key => $order) : ?>
        <tr class="bg-success">
            <th scope="row"><?= $key ?></th>
            <td><?= $order['email'] ?></td>
            <td><?= $order['name'] ?></td>
            <td><?= $order['phone'] ?></td>
            <td><?= $employees->getEmployee($order['employeeId'])->post_title ?></td>
            <td><?= $order['status'] ?></td>
            <td><?= $order['date_init'] ?></td>
            <td><?= $order['date_finish'] ?></td>
            <td><?= $order['created_at'] ?></td>
            <td>
                <?php $statusClass = ($order['status'] === 'pending') ? 'btn-outline-secondary' : 'btn-success'?>
                <button class="btn <?= $statusClass ?>" data-item-id="<?= $order['id'] ?>" @click="approveItem('<?= $order['id'] ?>')">
                    <i class="fas fa-thumbs-up"></i>
                </button>
                <button class="btn btn-primary disabled" data-item-id="<?= $order['id'] ?>" @click="editItem('<?= $order['id'] ?>')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger" data-item-id="<?= $order['id'] ?>" @click="deleteItem('<?= $order['id'] ?>')">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>