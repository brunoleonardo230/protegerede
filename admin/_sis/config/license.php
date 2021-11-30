<?php
/*
 * ATENÇÃO: PARA SUA SEGURANÇA NÃO ALTERE ESSE ARQUIVO
 * E SEMPRE LICENCIE SEUS DOMÍNIOS AO COLOCALOS ONLINE!
 */

$AdminLevel = LEVEL_WC_CONFIG_API;
if (empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-key">Work Control® License</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=config/home">Configurações</a>
            <span class="crumb">/</span>
            Licenciamento de Domínio
        </p>
    </div>
</header>
<div class="dashboard_content">
    <?php
    if (file_exists("dashboard.json")):
        $getLicense = file_get_contents("dashboard.json");
        $License = json_decode($getLicense);

        echo "<div class='licence_box'>"
        . "<span class='icon-checkbox-checked icon-notext font_green auth'></span>"
        . "<p class='title'>Work Control® Licenciado por <a title='Conferir Profissional' href='https://pro.workcontrol.com.br/?p={$License->user_id}' target='_blank'>{$License->user_name} {$License->user_lastname}</a></p>"
        . "<p class='icon-warning'>Licença Exclusiva Para o Domínio <b>{$License->license_domain}</b>.</p>"
        . "<p class='icon-lock key'><b>IP do SERVIDOR:</b>&nbsp;{$License->license_request_ip}</p>"
        . "<p class='icon-key key'><b>CHAVE:</b>&nbsp;{$License->license_hash}</p>"
        . "<p class='icon-calendar'>Licença: " . date("d/m/Y", strtotime($License->license_date)) . " | Autenticação: " . date("d/m/Y H\hi", strtotime($License->license_auth_date)) . " | Versão: {$License->license_version}</p>"
        . "</div>";
    endif;
    ?>

    <div class="wc_api_new">
        <form action="" method="post" enctype="multipart/form-data">
            <div style="text-align: center"></div>
            <input type="hidden" name="callback" value="Api"/>
            <input type="hidden" name="callback_action" value="license"/>

            <div class="label_50">
                <label class="label">
                    <input style="width: 100%; border: 1px solid #ccc;" type="text" name="user_email" value="" placeholder="Seu E-mail UpInside:" required="required"/>
                </label><label class="label">
                    <input style="width: 100%; border: 1px solid #ccc;" type="password" name="user_password" value="" placeholder="Sua Senha UpInside:" required="required"/>
                </label>
            </div>
            <input type="text" name="licene_key" value="" placeholder="License Key: bdc807-383f8d-83daafb7-ae87d8" required="required"/><button title="APLICAR CHAVE" class="btn btn_darkaquablue">APLICAR CHAVE <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
        </form>
        <p class="wc_api_new_info">( ! ) Para Gerar Uma Chave de Licenciamento Acesse <a href="https://download.workcontrol.com.br" target="_blank">download.workcontrol.com.br</a>, Utilize Seu E-mail e Senha da UpInside Para Logar-se e Gerar a Licença!</p>
    </div>
</div>