<?php
ob_start();
session_start();
require '../_app/Config.inc.php';

if (isset($_SESSION['userLogin']) && isset($_SESSION['userLogin']['user_level']) && $_SESSION['userLogin']['user_level'] >= 6):
    header('Location: dashboard.php?wc=home');
    exit;
endif;

$Cookie = filter_input(INPUT_COOKIE, 'workcontrol', FILTER_VALIDATE_EMAIL);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <meta name="mit" content="2018-01-03T21:07:08-02:00+19935">
        <title>Bem-vindo(a) ao <?= ADMIN_NAME; ?> - Entrar!</title>
        <meta name="description" content="<?= ADMIN_DESC; ?>"/>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0"/>
        <meta name="robots" content="noindex, nofollow"/>

        <link rel="shortcut icon" href="_img/favicon.png" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Source+Code+Pro:300,500' rel='stylesheet' type='text/css'>
        <link rel="base" href="<?= BASE; ?>/admin/">

        <link rel="stylesheet" href="_css/reset.css"/>        
        <link rel="stylesheet" href="_css/workcontrol.css"/>
        <link rel="stylesheet" href="../_cdn/bootcss/fonticon.css"/>
    </head>
    <body>
        <div class="dash_login darkaquablue">
            <div class="dash_login_box">
                <img class="dash_login_box_logo" alt="<?= ADMIN_NAME; ?>" title="<?= ADMIN_NAME; ?>" src="_img/work_icon.png"/>
                <div class="dash_login_box_content radius">
                    <form class="dash_login_box_content_form basic_form" name="work_login" action="" method="post" enctype="multipart/form-data">
                        <div class="callback_return m_botton">
                            <?php
                            if (!empty($_SESSION['trigger_login'])):
                                echo $_SESSION['trigger_login'];
                                unset($_SESSION['trigger_login']);
                            endif;
                            ?>
                        </div>
                        <input type="hidden" name="callback" value="Login">
                        <input type="hidden" name="callback_action" value="admin_login">
    
                        <label>
                            <span class="field icon-envelop">E-mail:</span>
                            <input class="darkaquablue" type="email" name="user_email" value="<?= $Cookie ? $Cookie : ''; ?>" placeholder="SEU E-MAIL:" required/>
                        </label>
    
                        <label>
                            <span class="field icon-key">Senha:</span>
                            <input class="darkaquablue" type="password" name="user_password" placeholder="SUA SENHA:" required/>
                        </label>
                        
                        <a class="dash_login_box_content_pass icon-lock" href="recover.php" title="Esqueci Minha Senha">Esqueci Minha Senha</a>
    
                        <button title="FAZER LOGIN" class="dash_btn darkaquablue radius">FAZER LOGIN <img class="form_load none" style="margin-left: 6px; margin-bottom: 7px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                        <div class="clear"></div>
                    </form>
                </div>
                <p class="dash_login_box_footer"><span class="icon-earth back_span"></span><a class="back" href="<?= BASE; ?>" title="Voltar Para <?= SITE_NAME; ?>"><?= SITE_NAME; ?></a></p>
                
                <p class="dash_login_box_footer">Desenvolvido Com  <span class="icon-heart icon-notext"></span>  Por <a href="https://www.gbtechweb.com.br" target="_blank" title="GbTechWeb">GbTechWeb</a></p>
            </div>
        </div>
        
        <script src="../_cdn/jquery.js"></script>
        <script src="../_cdn/jquery.form.js"></script>
        <script src="_js/workcontrol.js"></script>
    </body>
</html>
<?php
ob_end_flush();
