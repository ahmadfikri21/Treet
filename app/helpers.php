<?php
    // fungsi recursive untuk menampilkan tree dengan parameter, $parentNode = setiap node yang memiliki parent_node == null, $status = menandakan apakah dia node yg memiliki child atau bukan, $indent = untuk menentukan indentasi
    function showTree($parentNode,$status,$indent){
        foreach($parentNode as $node){
            // untuk root node yang tidak memiliki child (tidak memiliki child namun bukan child)
            if(($status == "noChild" && $node->children->isEmpty())){
                // membangun node menggunakan jquery + php
                ?>
                    <script>
                        $('.navigationTree').append("<div class='node noChild' style='margin-left: <?= $indent ?>px;' id='<?= $node->id_node ?>'><?= $node->nama_node ?></div>")
                    </script>
                <?php
            }

            // untuk setiap root node yang memiliki children
            if(($status == "noChild" && $node->children->isNotEmpty())){
                // membangun node menggunakan jquery + php
                // jika memiliki child, maka childnya akan memiliki name parentId
                ?>
                    <script>
                        $('.navigationTree').append("<div class='node hasChild' style='margin-left: <?= $indent ?>px;' id='<?= $node->id_node ?>'> <img src='<?= asset('img/dropdownArrow.svg') ?>'></img><span><?= $node->nama_node ?></span></div>");
                        
                        $('.navigationTree').append("<div class='colllapsible' id='collapsibleid:<?= $node->id_node ?>'></div>");
                    </script>
                <?php
            }

            // untuk setiap node yang bukan root(memiliki parent) namun memiliki child
            if($status == "hasChild" && $node->children->isNotEmpty()){
                ?>
                    <script>
                        $("<div class='node hasChild' style='margin-left: <?= $indent ?>px;' id='<?= $node->id_node ?>'> <img src='<?= asset('img/dropdownArrow.svg') ?>'></img><span><?= $node->nama_node ?></span></div>").appendTo('#collapsibleid:<?= $node->parent_node ?>');
                    </script>
                <?php
            }

            // untuk setiap leaf node
            if(($status == "hasChild" && $node->children->isEmpty())){
                // membangun node menggunakan jquery + php
                ?>
                    <script>
                        $('.navigationTree').append("<div class='node noChild' style='margin-left: <?= $indent ?>px;' id='<?= $node->id_node ?>'><?= $node->nama_node ?></div>")
                    </script>
                <?php
            }
            
            if($node->children->isNotEmpty()){
                showTree($node->children,"hasChild",$indent+20);
            }
        }
    }

    function findPopularPath($detailHasil, $noTask){
        $popularPath = false;
        $countPopularPath = 0;            
        // array untuk menghitung jumlah jawaban setiap path
        $countPath = [];
        // counter array 2d
        $j = 0;

        // mengambil array detail hasil berdasarkan nomor tasknya
        $detailByTask = $detailHasil->where('nomorTask', $noTask);
        foreach($detailByTask as $data){
            // counter untuk menghitung jumlah setiap path
            $counter = 0;
            // foreach kedua untuk melakukan perhitungan
            foreach($detailByTask as $row){
                if($row->direct_path == $data->direct_path){
                    $counter++;
                }
                // memasukkan hasil perhitungan ke array populariy, lalu menjadikan path sebagai key dari array
                array_push($countPath, $counter);
                $countPath[$data->direct_path] = $countPath[$j];
                unset($countPath[$j]);
                $j++;
            }
        }
        
        // mengambil key path paling populer berdasarkan jumlah counter terbanyak
        $popularPath = array_keys($countPath, max($countPath))[0];
        $countPopularPath = max($countPath);
        
        $hasil['path'] = [];
        $hasil['jumlah'] = [];

        array_push($hasil['path'], $popularPath);
        array_push($hasil['jumlah'], $countPopularPath);

        return $hasil; 
    }

    // mengambil datetimem hari ini
    function getTodayTimestamp(){
        return date("Y-m-d G:i:s", time());
    }
?>