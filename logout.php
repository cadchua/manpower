<?php
 session_start();
 session_destroy();
 echo "<script>top.location='index.php'</script>"
?>