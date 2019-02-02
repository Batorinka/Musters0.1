<?php $this->layout('layout', ['title' => $title, 'col_md_n' => '12']) ?>

<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col" class="col-md-2">Название предприятия</th>
      <th scope="col">Название прибора</th>
      <th scope="col">Заводской номер</th>
      <th scope="col">Дата последней поверки</th>
      <th scope="col">Межповерочный интервал</th>
      <th scope="col">Дата следующей поверки</th>
    </tr>
  </thead>
  <tbody>
  <?foreach ($objects as $object):?>
    <?if ($object['count_musters'] != 0):?>
      <tr>
          <td rowspan="<?= $object['count_musters']; ?>">

              <a href="/object/<?= $object['id'] ?>">
                  <?= "{$object['company_name']} ({$object['name']})"; ?>
              </a>
          </td>
          <?foreach ($musters as $muster):?>
            <?if ($muster['object_id'] == $object['id']): ?>
              <td>
                  <a href="/device/<?= $muster['device_id'] ?>">
                      <?= $muster['device_name']; ?>
                  </a>
              </td>
              <td>
                  <?= $muster['number'] ?>
              </td>
              <td>
                  <?= $muster['last_date'] ?>
              </td>
              <td>
                  <?= $muster['interval_of_muster'] ?>
              </td>
              <td class="<?= $muster['is_overlooked'] ?>
    <?= $muster['is_overlooked_in_month'] ?>">
                  <?= $muster['next_date'] ?>
              </td>
              </tr>
            <?endif;?>
          <?endforeach;?>
      </tr>
    <?endif;?>
  <?endforeach;?>
  </tbody>
</table>
<p>Колличество поверок: <?=count($musters)?></p>