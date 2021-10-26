<?php
include("partials/header.php");
?>
    <main class="form-signin">
        <form>
            <img class="mb-4" src="img/logo.png" alt="">
            <h1 class="h3 mb-3 fw-normal">Registreeru</h1>
            <div id="alert-div">
                <!-- Kuvab js-ga alerte -->
            </div>
            <div class="input-group name">
                <div class="row g-2">
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="firstname" placeholder="Eesnimi">
                            <label for="firstname">Eesnimi</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="lastname" placeholder="Perekonnanimi">
                            <label for="lastname">Perekonnanimi</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-floating">
                <input type="email" class="form-control" id="email" placeholder="name@example.com">
                <label for="email">E-posti aadress</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control pw" id="pw" placeholder="Parool">
                <label for="pw">Parool</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control pwrepeat" id="pwrepeat" placeholder="Korda parooli">
                <label for="pwrepeat">Korda parooli</label>
            </div>
            <div class="input-group gender-radio mx-auto" id="radio_group">
                <input type="radio" class="btn-check" name="gender" id="male" value="male" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="male">Mees</label>

                <input type="radio" class="btn-check" name="gender" id="female" value="female" autocomplete="off">
                <label class="btn btn-outline-info" for="female">Naine</label>
            </div>
            <div class="buttons">
                <button class="w-100 btn btn-lg btn-primary" type="submit" id="register">Registreeru</button>
                <a href="index.php" class="w-100 btn btn-lg btn-secondary" type="submit">Tagasi</a>
            </div>
        </form>
    </main>

<?php
include("partials/footer.php");
?>