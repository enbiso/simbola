<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Simbola Framework Installer</title>

        <!-- Bootstrap -->
        <link href="include/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="include/bootstrap/css/bootstrap-theme.css" rel="stylesheet">

        <!--[if lt IE 9]>
            <script src="include/html5shiv.js"></script>
            <script src="include/respond.min.js"></script>
        <![endif]-->
        <style>
            body {
                padding-top: 20px;
                padding-bottom: 20px;
            }

            .navbar {
                margin-bottom: 20px;
            }

        </style>
    </head>
    <body>       
        <div class="container">
            <div class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">                            
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Simbola Framework Installer</a>
                    </div>
                    
                </div><!--/.container-fluid -->
            </div>
            <?php echo $content ?>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="include/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="include/bootstrap/js/bootstrap.js"></script>
    </body>
</html>