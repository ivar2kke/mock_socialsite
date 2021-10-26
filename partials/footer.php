    <!--Skriptid-->
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>

    <script
            src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"
            integrity="sha256-hlKLmzaRlE8SCJC1Kw8zoUbU8BxA+8kR3gseuKfMjxA="
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <?php
    session_start();
    if(isset($_SESSION['error'])){
        echo "<script>alertDiv('".$_SESSION['error']['type']."', '".$_SESSION['error']['text']."');</script>";
        unset($_SESSION['error']);
    }
    ?>

    </body>
</html>