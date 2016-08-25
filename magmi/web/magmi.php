<?php
header('Pragma: public'); // required
header('Expires: -1'); // no cache
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: private', false);


$msg = '';

if (!empty($_POST['username'])
    && !empty($_POST['password'])) {

    if ($_POST['username'] == 'lybe' &&
        $_POST['password'] == 'pI*%v@9iX!Oi') {
        $_SESSION['valid'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['username'] = 'lybe';


    }else {
        $msg = 'Wrong username or password';
    }
}




if (isset( $_SESSION['valid'])){

    require_once("header.php");
    require_once("magmi_config.php");
    require_once("magmi_statemanager.php");

    require_once("fshelper.php");
    require_once("magmi_web_utils.php");
    $badrights = array();
// checking post install procedure

    $postinst = "../inc/magmi_postinstall.php";
    if (file_exists($postinst)) {
        require_once("$postinst");
        if (function_exists("magmi_post_install")) {
            $result = magmi_post_install();

            if ($result["OK"] != "") {
                ?>
                <div class="container_12">
                    <div class="grid_12 subtitle">
                        <span>Post install procedure</span>
                    </div>
                    <div class="grid_12 col">
                        <h3>Post install output</h3>
                        <div class="mgupload_info" style="margin-top: 5px">
                            <?php echo $result["OK"]?>
                        </div>
                    </div>
                </div>
            <?php

            }
            rename($postinst, $postinst . "." . strval(time()));
        }
    }
    foreach (array("../state", "../conf", "../plugins") as $dirname) {
        if (!FSHelper::isDirWritable($dirname)) {
            $badrights[] = $dirname;
        }
    }
    if (count($badrights) == 0) {
        $state = Magmi_StateManager::getState();

        if ($state == "running" || (isset($_REQUEST["run"]) && $_REQUEST["run"] == "import")) {
            require_once("magmi_import_run.php");
        } else {
            Magmi_StateManager::setState("idle", true);
            require_once("magmi_config_setup.php");
            require_once("magmi_profile_config.php");
        }
    } else {
        ?>

        <div class="container_12">
            <div class="grid_12">
                <div class="magmi_error" style="margin-top: 5px">
                    Directory permissions not compatible with Mass Importer operations
                    <ul>
                        <?php

                        foreach ($badrights as $dirname) {
                            $trname = str_replace("..", "magmi", $dirname);
                            ?>
                            <li><?php echo $trname?> not writable!</li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php

    }
    ?>
    <?php require_once("footer.php");?>
    <div id="overlay" style="display: none">
        <div id="overlaycontent"></div>
    </div>
<?php }else{

    ?>

    <html lang = "en">

    <head>
        <link rel="stylesheet" href="
https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" />
        <style>
            body {
                padding-top: 40px;
                padding-bottom: 40px;
                background-color: #eee;
            }

            .form-signin {
                max-width: 330px;
                padding: 15px;
                margin: 0 auto;
            }
            .form-signin .form-signin-heading,
            .form-signin .checkbox {
                margin-bottom: 10px;
            }
            .form-signin .checkbox {
                font-weight: normal;
            }
            .form-signin .form-control {
                position: relative;
                height: auto;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                padding: 10px;
                font-size: 16px;
            }
            .form-signin .form-control:focus {
                z-index: 2;
            }
            .form-signin input[type="email"] {
                margin-bottom: -1px;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }
            .form-signin input[type="password"] {
                margin-bottom: 10px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }
        </style>
    </head>

    <body>


    <div class = "container form-signin span-6">


    </div> <!-- /container -->

    <div class = "container">

        <form class="form-signin" method="post" >
            <h2 class="form-signin-heading">Please sign in</h2>
            <?php if (isset($msg)) echo $msg; ?>
            <input name="username" type="text" id="inputEmail" class="form-control" placeholder="Username" required="" autofocus="">
            <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required="">
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>
    </div>

    </body>
    </html>
<?php } ?>
