<!DOCTYPE html>
<html>
<head>
    <title>Redirection en JavaScript</title>
    <script type="text/javascript">
        // Fonction de redirection
        function redirigerVersAutrePage() {
            window.location.href = "DR/index.php"; // Remplacez "nouvelle_page.html" par l'URL de la page vers laquelle vous souhaitez rediriger.
        }
        // Appeler la fonction de redirection après un certain délai (par exemple, 3 secondes)
        setTimeout(redirigerVersAutrePage,500); // 3000 millisecondes (3 secondes)
		
    </script>
</head>
<body>

</html>