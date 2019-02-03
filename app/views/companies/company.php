<?php $this->layout('layout', ['title' => $company['name_sub'], 'col_md_n' => '5']) ?>

<table id="dt" class="table">
    <thead>
    <tr>
        <th scope="col">Название объекта</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
		<?php foreach ($objects as $object): ?>
        <tr>
            <td>
                <a href="/object/<?= $object['id'] ?>">
                    <?= $object['name']; ?>
                </a>
            </td>
            <td class="dropdown">
                <a href="#" class="dropdown-toggle btn btn-info" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-cog"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="/updateobjectform/<?=$object['id'];?>" target="_blank">Редактировать</a></li>
                    <li><a href="/deleteobject/<?=$object['id'];?>"
                           onclick="return confirm('Вы уверенны?')">Удалить</a></li>
                </ul>
            </td>
        </tr>
		<?php endforeach; ?>
    </tbody>
</table>

<h4>Информация об предприятие</h4>

<table class="table">

    <tbody>
    <tr>
        <td>
            Полное название предприятия:
        </td>
        <td>
            <?= $company['name_full'] ?>
        </td>
    </tr>
    <tr>
        <td>
            <?=$contract_types[$company['contract_type'] - 1]['name']?>:
        </td>
        <td>
            № <?= $company['contract_number'].' от '.$company['contract_date'] ?>
        </td>
    </tr>
    <tr>
        <td>
            email:
        </td>
        <td>
            <?= $company['email'] ?>
        </td>
    </tr>
    </tbody>
</table>