<?php
include("partials/header_session.php");
?>
<main>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
            <span class="fs-4">TestSocial</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link active" aria-current="page">
                    <span class="bi bi-house-door menu-icons">
                        <span class="menu-text">Pealeht</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <span class="bi bi-person menu-icons">
                        <span class="menu-text">Sõbrad</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <span class="bi bi-image menu-icons">
                        <span class="menu-text">Galerii</span>
                    </span>
                </a>
            </li>
        </ul>
        <form>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Otsi...">
                <button class="btn btn-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="img/profile_placeholder.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong><?=$_SESSION['user']['fullname']?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">Profiil</a></li>
                <li><a class="dropdown-item" href="#">Sätted</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" id="logout" href="">Logi välja</a></li>
            </ul>
        </div>
    </div>
    <div class="b-divider"></div>
    <div class="container content">
        <div class="alert-div alert-float" id="alert-div" style="display:none;">

        </div>
        <div class="row">
            <div class="col-8" id="content-wrapper">
                <div class="row content-box bg-dark" style="border-bottom-right-radius: 0;">
                    <form class="form-floating">
                        <div class="input-group">
                            <input type="text" class="form-control" name="post_text" id="post_text" placeholder="Uus postitus...">
                            <span><button class="btn btn-primary" id="post" disabled>Postita!</button></span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-4 content-box bg-dark" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                Test!
            </div>
        </div>

    </div>

<?php
include("partials/footer_session.php");
?>
