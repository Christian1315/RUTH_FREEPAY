<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>FREEPAY</title>
    <link rel="shortcut icon" href="logo_freepay_favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="style.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />


    <style>
        body {
            /* background-color: white !important; */
            /* background-color: #000 !important; */
            font-size: 12px;
        }

        #content {
            padding: 150px;
        }

        .form-control,
        .form-select {
            border-color: lightblue;
            border-radius: 0px;
        }

        select:invalid {
            color: gray;
        }

        ::placeholder {

            font-size: 10px;
        }

        .bouton1 {
            background-color: white;
            border: 2px solid #0F74A9;
            color: #0F74A9;
        }

        .bouton2 {
            color: white;
            border: #0F74A9;
            background: linear-gradient(90deg, #0F74A9 0%, #13B9B1 100%);
            margin-left: 10px;
        }

        .logo-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-title a {
            text-decoration: none;
            font-size: 16px !important;
        }

        .logo-title img {
            width: 100px;
        }

        .buttons {
            display: flex;
            text-align: right;
            justify-content: space-between;
        }

        .buttons button {
            font-family: Mulish;
            font-size: 10px;
            font-weight: 600;
            line-height: 10px;
            letter-spacing: 0em;
            padding: 8px;
        }

        @media screen and (max-width:690px) {
            .logo-title {
                display: list-item;
                list-style-type: none;
                text-align: center;
            }

            .logo-title img {
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="body" id="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <fieldset style="border: solid lightblue;" class="p-3 shadow-lg">
                        <form method="post" action="#" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="logo-title">
                                        <a href="">Lien de paiement unique</a>
                                        <img src="logo_freepay.png" class="img img-fluid" alt="" srcset="">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class=" col-md-4">
                                    <div class="form-group">
                                        <label>Motif lien paiement:</label>
                                    </div>
                                </div>
                                <div class=" col-md-8">
                                    <div class="form-group">
                                        <select class="form-select form-control" aria-label="Default select example">
                                            <option value="" disabled selected hidden>Vente d'article</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                    </div><br>
                                </div>
                            </div>
                            @error('motif')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Titre de la page de paiement:</label>
                                        <input type="text" class="form-control" name="nom" placeholder="La page de paiement ">
                                    </div>
                                    @error('titre')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Montant à recevoir:</label>
                                        <input type="text" class="form-control" name="nom" placeholder="Precisez le montant à recevoir">
                                    </div>
                                    @error('montant')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            Moyen du paiement:
                                        </label>
                                        <select class="form-select">
                                            <option value="" disabled selected hidden>Moyen de paiement</option>
                                            <option value="MOMO">MTN MOMO</option>
                                            <option value="LOOZ">MOOV</option>
                                            <option value="CARTE">Carte bancaire</option>
                                        </select>
                                    </div><br>
                                    @error('moyen')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>

                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            Description:
                                        </label>
                                        <input type="text" class="form-control" name="nom" placeholder="Description paiement ">
                                    </div><br>
                                    @error('description')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div><br>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            Logo ou image du prodruit:
                                        </label>
                                        <input type="file" class="form-control" value="Ajouter" name="nom" placeholder="Selectioner l'image du produit">
                                    </div><br>
                                    @error('logo')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                    <div class="buttons">
                                        <button type="reset" name="annuler" class="bouton1">Retour</button>
                                        <button type="submit" name="envoyer" class="bouton2"> Générer un lien</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

</html>