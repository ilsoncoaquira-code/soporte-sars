<?php
session_start();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/');
?>
<script>
alert("✅ Sesión cerrada");
window.location.href = "./login";
</script>