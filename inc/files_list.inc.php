<table class="table-striped table-hover table-condensed col-xs-12" id="items">
    <?php $id = 1; ?>
    <?php foreach ($user->get_list() as $f): ?>

        <tr id="row_<?= $f['file_name'] != '..' ? $id : 0 ?>" style="border-bottom: 1px solid rgba(0,0,0, .0)">
            <?php if ($f['file_name'] != '..') : ?>
                <td style="width: 45px; padding: 5px;">
                    <input type="hidden" id="file_n_<?= $id; ?>" value="<?= $f['file_name']; ?>"/>
                    <input type="checkbox" name="file_n" value="<?= $id ?>" class="form-control">
                </td>
            <?php endif; ?>
            <td style="word-break: break-all" <?= $f['file_name'] == '..' ? 'colspan="5"' : '' ?>>
                <a href="<?= $f['route'] ?>"><?= $f['file_name'] ?></a>
            </td>
            <?php if ($f['file_name'] != '..') : ?>
                <td style="width: 35px; padding: 5px;">
                    <a style="margin-right: 10px" class="btn btn-xs btn-success" href="<?= $f['file_link'] ?>">Link</a>
                </td>
                <td style="width: 35px; padding: 5px;">
                    <a style="margin-right: 10px" class="btn btn-xs btn-primary" onclick="boxShow('rename', <?= $id ?>)">Rename</a>
                </td>
                <td style="width: 35px; padding: 5px;">
                    <a class="btn btn-xs btn-default" onclick="copyLink(<?= $id++ ?>)">Copy</a>
                </td>
            <?php endif; ?>
        </tr>

    <?php endforeach; ?>
</table>