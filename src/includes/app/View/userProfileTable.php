<table border="1" cellpadding="8" cellspacing="0" style>
    <thead>
        <tr>
            <th colspan="2"><b>Thông tin facebook query</b></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $key => $value) { ?>

            <tr>
                <td><?= "<b>$key</b>" ?></td>
                <td><?= $value === "Không tìm thấy" ? "<span style='color: red'>$value</span>" : $value ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>