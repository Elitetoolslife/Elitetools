<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel 11 Custom User Registration & Login Tutorial - AllPHPTricks.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container">
          <a class="navbar-brand" href="{{ URL('/') }}"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('login')) ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('register')) ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
                    </li>
                @else    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"
                            >Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                        </li>
                        </ul>
                    </li>
                @endguest
            </ul>
          </div>
        </div>
    </nav>    <div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-6">
            <div class="alert alert-light shadow-sm p-4">
                <h4 class="fw-bold">Welcome, 
                    <a href="#" class="badge bg-primary text-decoration-none" onclick="pageDiv(12,'Setting - OluxShop','setting.html',0); return false;">ethinker</a>
                </h4>
                <p>
                    Do you have any <strong>questions</strong>, <strong>concerns</strong>, <strong>suggestions</strong>, or <strong>requests</strong>? 
                    <a href="#" class="badge bg-secondary text-decoration-none" onclick="pageDiv(9,'Tickets - OluxShop','tickets.html#open',0); return false;">
                        <i class="fas fa-pen"></i> Submit a Ticket
                    </a>.
                </p>
                <p>
                    To report an issue with an order, visit 
                    <abbr title="Account -> My Orders" class="text-primary text-decoration-underline" onclick="pageDiv(13,'Orders - OluxShop','orders.html',0); return false;">
                        My Orders <i class="fas fa-shopping-cart"></i>
                    </abbr> and click the <span class="badge bg-primary">Report #ID</span> button.
                </p>
                <p>
                    Save our official domains for quick access: <strong>Olux.io</strong>, <strong>Olux.to</strong>, <strong>Olux.is</strong>.
                </p>
            </div>

            <div class="list-group shadow-sm">
                <h4 class="fw-bold mb-3"><i class="fas fa-info-circle"></i> Latest Updates</h4>
                <a href="#" class="list-group-item list-group-item-action">
                    <h5 class="mb-1">SMTP Checker is now operational. We apologize for the inconvenience caused.</h5>
                    <small class="text-muted">Monday, July 29, 2019, 5:35 PM</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <h5 class="mb-1">HTTPS filtering has been added to the Shell/Control Panel sections.</h5>
                    <small class="text-muted">Friday, July 26, 2019, 11:02 AM</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <h5 class="mb-1">Bitcoin payments have been restored. We apologize for the inconvenience.</h5>
                    <small class="text-muted">Monday, July 8, 2019, 4:41 PM</small>
                </a>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <!-- Slider (Carousel) -->
            <div id="announcementCarousel" class="carousel slide shadow-sm mb-4" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="files/img/announcement1.jpg" class="d-block w-100 rounded" alt="Announcement 1">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Stay Updated!</h5>
                            <p>Check out our latest features and services.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="files/img/announcement2.jpg" class="d-block w-100 rounded" alt="Announcement 2">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>New Payment Methods</h5>
                            <p>We’ve added new payment options for your convenience.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="files/img/announcement3.jpg" class="d-block w-100 rounded" alt="Announcement 3">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Special Offers</h5>
                            <p>Don’t miss our ongoing promotions and discounts.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#announcementCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#announcementCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div><div class="alert alert-light shadow-sm p-4">
    <h4 class="fw-bold text-center text-md-start">Need Help? Our Support Team is Ready!</h4>
    <div class="text-center text-md-start">
        <button class="btn btn-secondary my-2" onclick="pageDiv(9,'Tickets - OluxShop','tickets.html#open',0); return false;">
            <i class="fas fa-pen"></i> Submit a Ticket
        </button>
    </div>
    <h5 class="mt-4 fw-bold text-center text-md-start"><strong>Payment Options</strong></h5>
    <div class="row g-3 mt-3 text-center">
        <!-- Payment Option 1 -->
        <div class="col-4 col-md-2">
            <img src="files/img/pmlogo2.png" class="img-fluid rounded shadow-sm" alt="PerfectMoney" title="PerfectMoney" style="cursor: pointer;" onclick="pageDiv(11,'Add Balance - OluxShop','addBalance.html#perfectmoney',0);">
        </div>
        <!-- Payment Option 2 -->
        <div class="col-4 col-md-2">
            <img src="files/img/btclogo.png" class="img-fluid rounded shadow-sm" alt="Bitcoin" title="Bitcoin" style="cursor: pointer;" onclick="pageDiv(11,'Add Balance - OluxShop','addBalance.html#bitcoin',0);">
        </div>
        <!-- Payment Option 3 -->
        <div class="col-4 col-md-2">
            <img src="files/img/ethlogo.png" class="img-fluid rounded shadow-sm" alt="Ethereum" title="Ethereum" style="cursor: pointer;" onclick="pageDiv(11,'Add Balance - OluxShop','addBalance.html#ethereum',0);">
        </div>
        <!-- Payment Option 4 -->
        <div class="col-4 col-md-2">
            <img src="files/img/ltclogo2.png" class="img-fluid rounded shadow-sm" alt="Litecoin" title="Litecoin" style="cursor: pointer;" onclick="pageDiv(11,'Add Balance - OluxShop','addBalance.html#litecoin',0);">
        </div>
        <!-- Payment Option 5 -->
        <div class="col-4 col-md-2">
            <img src="files/img/bchlogo.png" class="img-fluid rounded shadow-sm" alt="BitcoinCash" title="BitcoinCash" style="cursor: pointer;" onclick="pageDiv(11,'Add Balance - OluxShop','addBalance.html#bcash',0);">
        </div>
        <!-- Payment Option 6 -->
        <div class="col-4 col-md-2">
            <img src="files/img/payeerlogo.png" class="img-fluid rounded shadow-sm" alt="Payeer" title="Payeer" style="cursor: pointer;" onclick="pageDiv(11,'Add Balance - OluxShop','addBalance.html#payeer',0);">
        </div>
        <!-- Payment Option 7 -->
        <div class="col-4 col-md-2">
            <img src="files/img/advlogo.png" class="img-fluid rounded shadow-sm" alt="AdvCash" title="AdvCash" style="cursor: pointer;" onclick="pageDiv(11,'Add Balance - OluxShop','addBalance.html#advcash',0);">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body>
</html>
   l