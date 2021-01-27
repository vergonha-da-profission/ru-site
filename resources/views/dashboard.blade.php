<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Válida acesso - RU</title>
    <link rel="icon" href={{ URL::asset('img/logo.ico') }} type="image/x-icon"/>
    <link href={{ URL::asset('css/styles.css') }} rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous">
    </script>

    <style>
        #reader {
            width: 300px;
        }


        @media only screen and (min-width: 600px) {
            #reader {
                width: 600px;
            }
        }

    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.html">Vergonha da Profission</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <div class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
        </div>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0 md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">Settings</a>
                    <a class="dropdown-item" href="#">Activity Log</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="href={{ route('logout') }}"
                            onclick="event.preventDefault();this.closest('form').submit();">
                        {{ __('Logout') }}</a>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Validação</div>
                        <a class="nav-link" href="">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Leitura
                        </a>

                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    {{ $user->name }}
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Validação de usuário</h1>

                    <div id="reader"></div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; <a href="https://github.com/vergonha-da-profission/">Vergonha da Profission</a> 2021</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    {{-- <script src="js/scripts.js"></script> --}}



    {{-- <script src="path/to/qr-scanner.umd.min.js"></script>
    --}}

    <script src={{ URL::asset('js/qrcode.min.js') }}></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <script src="{{ mix('/js/app.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let reading = false;
            const axios = window.axios;

            async function onScanSuccess(qrCodeMessage) {
                if (reading) return;
                reading = true;

                Swal.fire({
                    title: 'Validando entrada',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                });

                await new Promise(r => setTimeout(r, 2000));

                const userId = qrCodeMessage;

                try {
                    const res = await axios.post('/api/validate-user', {
                        userId
                    });

                    const data = res.data;

                    if (data.message === 'Insuficient balance') {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Usuário não possui saldo na carteira!',
                        });

                        reading = false;

                        return;
                    }

                    if (data.message === 'Bad Request') {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'QrCode inválido',
                        });

                        reading = false;

                        return;
                    }

                    await Swal.fire({
                        title: "Liberado!",
                        imageUrl: `${data.user.avatar}`,
                        imageHeight: 100,
                        imageWidth: 100,
                        imageAlt: 'Foto de perfil',
                        timer: 3000,
                        timerProgressBar: true,
                        html: `${data.user.name} um total de R$: 2,50 foram debitados de sua conta`,
                    })

                    reading = false;
                } catch (error) {
                    await Swal.fire({
                        title: 'Um erro ocorreu!',
                        text: 'Um erro ocorreu, verifique sua conexão com a internet e tente novamente',
                        icon: 'error',
                        confirmButtonText: 'Continuar'
                    });

                    reading = false;
                }
            }

            var html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: 250,
                }, false);
            html5QrcodeScanner.render(onScanSuccess);

        });

    </script>
</body>

</html>
