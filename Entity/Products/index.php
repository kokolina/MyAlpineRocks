<?php
session_destroy();
session_unset();
header("Location: ../../");
exit;
?>