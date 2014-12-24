<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<div class="grid">
    <div class="row">
        <h1><a href="<?php echo $view['router']->generate('report') ?>"><i class="icon-arrow-left-3 fg-darker smaller"></i> </a>
            File Size Calculator for Digitized Assets
        </h1> 
        <br />
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
                    <?php
                    $i = 1;
                    $totalUncompress1 = 0.00;
                    $totalUncompress2 = 0.00;
                    $totalUncompress3 = 0.00;
                    $totalUncompress4 = 0.00;
                    $totalUncompress5 = 0.00;
                    $totalUncompress6 = 0.00;
                    $totalUncompress7 = 0.00;
                    $totalUncompress8 = 0.00;
                    $totalKbps = 0.00;
                    foreach ($audioResult as $audio) {
                        ?>
                        <tr>
                            <?php if ($i == 1) { ?>
                                <td rowspan="<?php echo count($audioResult); ?>" class="text"> Audio </td>
                            <?php } ?>
                            <td><?php echo $audio['format'] ?></td>
                            <td><?php echo $audio['total'] ?></td>
                            <td><?php echo $audio['sum_content_duration'] ?></td>
                            <td><?php echo number_format($audio['sum_content_duration'] / $audio['total'], 2) ?></td>
                            <td>
                                <?php
                                echo $uncompress1 = number_format(($audio['sum_content_duration'] * 34.56) / 1024 / 1024, 5);
                                $totalUncompress1 += $uncompress1;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $uncompress2 = number_format(($audio['sum_content_duration'] * 17.28) / 1024 / 1024, 5);
                                $totalUncompress2 += $uncompress2;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $uncompress3 = number_format(($audio['sum_content_duration'] * 11.52) / 1024 / 1024, 5);
                                $totalUncompress3 += $uncompress3;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $uncompress4 = number_format(($audio['sum_content_duration'] * 10.584) / 1024 / 1024, 5);
                                $totalUncompress4 += $uncompress4;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $uncompress5 = number_format(($audio['sum_content_duration'] * 17.28) / 1024 / 1024, 5);
                                $totalUncompress5 += $uncompress5;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $uncompress6 = number_format(($audio['sum_content_duration'] * 8.64) / 1024 / 1024, 5);
                                $totalUncompress6 += $uncompress6;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $uncompress7 = number_format(($audio['sum_content_duration'] * 5.75) / 1024 / 1024, 5);
                                $totalUncompress7 += $uncompress7;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $uncompress8 = number_format(($audio['sum_content_duration'] * 5.292) / 1024 / 1024, 5);
                                $totalUncompress8 += $uncompress8;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $kbps = number_format(($audio['sum_content_duration'] * 1.92) / 1024 / 1024, 5);
                                $totalKbps += $kbps;
                                ?>
                            </td>                            
                        </tr>                    
                        <?php
                        $i++;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="text-bold">
                        <td>Total File Space</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $totalUncompress1; ?></td>
                        <td><?php echo $totalUncompress2; ?></td>
                        <td><?php echo $totalUncompress3; ?></td>
                        <td><?php echo $totalUncompress4; ?></td>
                        <td><?php echo $totalUncompress5; ?></td>
                        <td><?php echo $totalUncompress6; ?></td>
                        <td><?php echo $totalUncompress7; ?></td>
                        <td><?php echo $totalUncompress8; ?></td>
                        <td><?php echo $totalKbps; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br />
        <br />
        <div class="table-responsive">
            <table class="table hovered bordered">
                <thead>
                    <tr>
                        <th>Media Type</th>
                        <th>Format</th>
                        <th>Count</th>
                        <th>Total Duration</th>
                        <th>Average Duration</th>
                        <th>Uncompressed 10-bit .mov HD</th>
                        <th>Uncompressed 10-bit .mov SD</th>
                        <th>Lossless compression 10-bit JP2k</th>
                        <th>FFV1 10-bit</th>
                        <th>MPEG2 8-bit</th>
                        <th>ProRes 422</th>
                        <th>DV25</th>
                        <th>MPEG4 5.0Mbps</th>
                        <th>MPEG4 2.0Mbps</th>                        
                    </tr>
                </thead>
                <tbody>                    
                    <?php
                    $i = 1;
                    $totalVUncompress1 = 0.00;
                    $totalVUncompress2 = 0.00;
                    $totalLossless = 0.00;
                    $totalFFV1 = 0.00;
                    $totalMPEG2 = 0.00;
                    $totalProRes = 0.00;
                    $totalDV25 = 0.00;
                    $totalMPEG45 = 0.00;
                    $totalMPEG42 = 0.00;
                    foreach ($videoResult as $video) {
                        ?>
                        <tr>
                            <?php if ($i == 1) { ?>
                                <td rowspan="<?php echo count($videoResult); ?>" class="text"> Video </td>
                            <?php } ?>
                            <td><?php echo $video['format'] ?></td>
                            <td><?php echo $video['total'] ?></td>
                            <td><?php echo $video['sum_content_duration'] ?></td>
                            <td><?php echo number_format($video['sum_content_duration'] / $video['total'], 2) ?></td>
                            <td>
                                <?php
                                echo $VUncompress1 = number_format(($video['sum_content_duration'] * 10240) / 1024 / 1024, 5);
                                $totalVUncompress1 += $VUncompress1;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $VUncompress2 = number_format(($video['sum_content_duration'] * 1800) / 1024 / 1024, 5);
                                $totalVUncompress2 += $VUncompress2;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $Lossless = number_format(($video['sum_content_duration'] * 900) / 1024 / 1024, 5);
                                $totalLossless += $Lossless;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $FFV1 = number_format(($video['sum_content_duration'] * 600) / 1024 / 1024, 5);
                                $totalFFV1 += $FFV1;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $MPEG2 = number_format(($video['sum_content_duration'] * 427) / 1024 / 1024, 5);
                                $totalMPEG2 += $MPEG2;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $ProRes = number_format(($video['sum_content_duration'] * 306) / 1024 / 1024, 5);
                                $totalProRes += $ProRes;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $DV25 = number_format(($video['sum_content_duration'] * 240) / 1024 / 1024, 5);
                                $totalDV25 += $DV25;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $MPEG45 = number_format(($video['sum_content_duration'] * 36) / 1024 / 1024, 5);
                                $totalMPEG45 += $MPEG45;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $MPEG42 = number_format(($video['sum_content_duration'] * 17.1) / 1024 / 1024, 5);
                                $totalMPEG42 += $MPEG42;
                                ?>
                            </td>                            
                        </tr>                    
                        <?php
                        $i++;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="text-bold">
                        <td>Total File Space</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $totalVUncompress1; ?></td>
                        <td><?php echo $totalVUncompress2; ?></td>
                        <td><?php echo $totalLossless; ?></td>
                        <td><?php echo $totalFFV1; ?></td>
                        <td><?php echo $totalMPEG2; ?></td>
                        <td><?php echo $totalProRes; ?></td>
                        <td><?php echo $totalDV25; ?></td>
                        <td><?php echo $totalMPEG45; ?></td>
                        <td><?php echo $totalMPEG42; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</div>
<?php
$view['slots']->stop();
