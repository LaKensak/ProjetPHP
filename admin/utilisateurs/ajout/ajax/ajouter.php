<?php


$lesPilotes = <<< EOD
    SELCET id,name
    FROM Cubes
    ORDER BY id,name;
EOD;



$head = <<<EOD
<script>
    let lesPilotes = $lesPilotes;
</script>
EOD;


