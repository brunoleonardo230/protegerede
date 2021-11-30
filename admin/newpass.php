<?php
ob_start();
session_start();
require '../_app/Config.inc.php';

$CodePass = filter_input(INPUT_GET, 'key', FILTER_DEFAULT);
if(!$CodePass):
    $_SESSION['trigger_login'] = Erro("<b>OPSSS:</b> Você Tentou Recuperar Sua Senha Sem Um Código de Acesso!", E_USER_ERROR);
    header('Location: ./');
    exit;
else:
    $_SESSION['RecoverPass'] = $CodePass;
endif;

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Bem-vindo(a) ao <?= ADMIN_NAME; ?> - Nova Senha!</title>
        <meta name="description" content="<?= ADMIN_DESC; ?>"/>
        <meta name="viewport" content="width=device-width,initial-scale=1"/>

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
                        <input type="hidden" name="callback" value="Login">
                        <input type="hidden" name="callback_action" value="admin_newpass">
                        
                        <label>
                            <span class="field icon-key">Nova Senha:</span>
                            <input type="password" name="user_password" placeholder="SUA NOVA SENHA" required/>
                        </label>
    
                        <label>
                            <span class="field icon-key">Repita a Nova Senha:</span>
                            <input class="darkaquablue" type="password" name="user_password_re" placeholder="REPITA SUA NOVA SENHA:" required/>
                        </label>
                        
                        <a class="dash_login_box_content_pass icon-lock" href="./" title="Esqueci Minha Senha">Logar-se!</a>

                        <button title="FAZER LOGIN" class="dash_btn darkaquablue radius">CRIAR NOVA SENHA <img class="form_load none" style="margin-left: 6px; margin-bottom: 7px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                        <div class="clear"></div>
                    </form>
                </div>
                <p class="dash_login_box_footer">Desenvolvido Com  <span class="icon-heart icon-notext"></span>  Por <a href="https://www.gbtechweb.com.br" title="GbTechWeb">GbTechWeb</a></p>
            </div>
        </div>

        <script src="../_cdn/jquery.js"></script>
        <script src="../_cdn/jquery.form.js"></script>
        <script src="_js/workcontrol.js"></script>
    </body>
</html>
<?php
ob_end_flush();
