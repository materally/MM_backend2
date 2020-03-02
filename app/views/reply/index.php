<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="<?= SITE_URL_PUBLIC ?>assets/img/favicon.png" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
    .container { max-width: 960px; }
    .lh-condensed { line-height: 1.25; }
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }
    </style>
    <title><?= $data['title'] ?></title>
  </head>
  <body class="bg-light">
  
    <div class="container">
        <div class="py-3 text-center">
            <img class="d-block mx-auto mb-4" src="<?= SITE_URL_PUBLIC ?>assets/img/menuheader.png" alt="">
            <h2>Árajánlatkérés válasz</h2>
            <p class="lead">Ide valami leírás?</p>
        </div>

    <?php
        $tartalom = str_replace("{url}", " ", $data['arajanlat']['tartalom']);
        if($data['success']){
            echo '<div class="row">
                    <div class="alert alert-success" role="alert" style="width:100%">
                        Köszönjük a válaszod!
                    </div></div>';
        }

    ?>

    <div class="row">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col" colspan="2" style="text-align:center">Árajánlat</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Tárgy</th>
                    <td><?= $data['arajanlat']['targy'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Dátum</th>
                    <td><?= $data['arajanlat']['datum'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Gyártási határidő</th>
                    <td><?= $data['arajanlat']['arajanlat']['gyartasi_hatarido'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Bekérte</th>
                    <td><?= $data['arajanlat']['admin']['vezeteknev'] ?> <?= $data['arajanlat']['admin']['keresztnev'] ?> (<?= $data['arajanlat']['admin']['telefonszam'] ?>) - <a href="mailto:<?= $data['arajanlat']['admin']['email'] ?>" target="_blank"><?= $data['arajanlat']['admin']['email'] ?></a></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center; font-weight:bold">Tartalom</td>
                </tr>
                <tr>
                    <td colspan="2"><?= nl2br($tartalom) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-12">
            <?php
                if(empty(trim($data['arajanlat']['valasz'], " "))){
                    echo '
                    <form action="'.SITE_URL_PUBLIC.'reply/valasz/'.substr($_GET['url'], 6).'" method="POST">
                        <div class="form-group">
                            <label for="valasz"><b>Válasz</b></label>
                            <textarea class="form-control" id="valasz" name="valasz" rows="5" required></textarea>
                        </div>
                        <div style="text-align:center">
                            <button class="btn btn-success" type="submit" name="submit">Válasz küldése</button>
                        </div>
                    </form>
                    ';
                }else{
                    echo '
                    <div class="alert alert-success" role="alert">
                        <h6 class="alert-heading">Válaszod:</h6>
                        <p style="margin-top:10px;">'.nl2br($data['arajanlat']['valasz']).'</p>
                    </div>
                    ';
                }
            ?>
        </div>
    </div>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; <?= date("Y") ?> MagentaMedia</p>
    </footer>

</div> <!-- ./container -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="<?= SITE_URL_PUBLIC ?>assets/js/autosize.js"></script>
    <script>
        autosize($('textarea'));
    </script>
  </body>
</html>