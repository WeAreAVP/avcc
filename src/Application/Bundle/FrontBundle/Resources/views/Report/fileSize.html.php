<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<div class="grid">
    <div class="row">
        <div class="table-responsive">
            <table class="table hovered bordered">
                <thead>
                    <tr>
                        <th>Media Type</th>
                        <th>Format</th>
                        <th>Count</th>
                        <th>Total Duration</th>
                        <th>Average Duration</th>
                        <th>96/24 Uncompressed WAV Stereo</th>
                        <th>48/24 Uncompressed WAV Stereo</th>
                        <th>48/16 Uncompressed WAV Stereo</th>
                        <th>44.1/16 Uncompressed WAV Stereo</th>
                        <th>96/24 Uncompressed WAV Mono</th>
                        <th>48/24 Uncompressed WAV Mono</th>
                        <th>48/16 Uncompressed WAV Mono</th>
                        <th>44.1/16 Uncompressed WAV Mono</th>
                        <th>256Kbps MP3</th>                        
                    </tr>
                </thead>
                <tbody>                    
                    <?php $i = 1;
                    foreach ($audioResult as $audio) {
                        ?>
                        <tr>
                            <?php if ($i == 1) { ?>
                                <td rowspan="<?php echo count($audioResult); ?>"> Audio </td>
    <?php } ?>
                            <td><?php echo $audio['format'] ?></td>
                            <td><?php echo $audio['total'] ?></td>
                            <td><?php echo $audio['sum_content_duration'] ?></td>
                            <td><?php echo number_format($audio['sum_content_duration'] / $audio['total'], 2) ?></td>
                            <td><?php echo number_format(($audio['sum_content_duration'] * 34.56) / 1024 / 1024, 2) ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>                            
                        </tr>                    
                        <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<?php
$view['slots']->stop();
