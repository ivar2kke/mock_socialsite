<?php
    include("partials/header.php");
?>
<main class="form-signin">
    <form>
        <img class="mb-4" src="img/logo.png" alt="">
        <h1 class="h3 mb-3 fw-normal">Logi sisse</h1>
        <div id="alert-div">
            <!-- Kuvab js-ga alerte -->
        </div>

        <div class="form-floating">
            <input type="email" class="form-control" id="email" placeholder="nimi@näide.com">
            <label for="email">E-posti aadress</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="pw" placeholder="Parool">
            <label for="pw">Parool</label>
        </div>

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" name="remember" value="remember-me"> Pea mind meeles
            </label>
        </div>
        <div class="buttons">
            <button class="w-100 btn btn-lg btn-primary" type="submit" id="login">Logi sisse</button>
            <a href="register.php" class="w-100 btn btn-lg btn-secondary" type="submit">Registreeru</a>
        </div>
    </form>
</main>

<?php
    include("partials/footer.php");
?>