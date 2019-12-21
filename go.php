<?php

// C:\Sphinx\bin\indexer --all --config C:\Sphinx\sphinx.conf.in //запускаем
// C:\Sphinx\bin\indexer --all --config C:\Sphinx\sphinx.conf.in --rotate //строим индекс
// d:/OS/OSPanel/modules/database/MySQL-5.7/bin/mysql -h 127.0.0.1 -P 9306 --default-character-set=utf8 //входим в командную строку
// SELECT * FROM posts LIMIT 5; // проверка

    class go extends cmsFrontend {
        
            public function actionIndex() {
                $template = cmsTemplate::getInstance();

                $this->db = cmsCore::getInstance()->db;
                
                $conn = mysqli_connect("127.0.0.1:9306", "", "", "");
                if (mysqli_connect_errno())
                die("failed to connect to Sphinx: " . mysqli_connect_error());

                $q = "SELECT * FROM posts, comments WHERE MATCH('пришельцы')";
                $result = mysqli_query($conn, $q);
              
                $tables_array = array();
                while($row = mysqli_fetch_assoc($result)){
                   $tables_array[] = $row['id'];
                   $go = implode(', ',$tables_array);
                }

         
                $sql = "SELECT * FROM ins_con_post WHERE id IN (".$go.")";
               
                $sql_result = $this->db->query($sql);
               
                //проверяем есть ли что для нас по запросу
                if (!$this->db->numRows($sql_result)){
                    return false;
                }

                //пробегаемся по массиву полученному от запроса
                while ($item = $this->db->fetchAssoc($sql_result)){
                    $search[] = $item;
                }      

               $template->render('go', array(
                        'search' => $search
                ));
        }    
}    
?>
