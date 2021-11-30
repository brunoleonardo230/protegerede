<?php
$AdminLevel = LEVEL_WC_HELLO;
if (!APP_PAGES || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

// AUTO INSTANCE OBJECT CREATE
if (empty($Create)):
    $Create = new Create;
endif;

$HelloId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($HelloId):
    $Read->ExeRead(DB_HELLO, "WHERE hello_id = :id", "id={$HelloId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Uma Hellobar Que Não Existe Ou Que Foi Removida Recentemente!", E_USER_NOTICE);
        header('Location: dashboard.php?wc=hello/home');
    endif;
else:
    $HelloCreate = ['hello_date' => date('Y-m-d H:i:s'), 'hello_status' => 0, "user_id" => $Admin['user_id']];
    $Create->ExeCreate(DB_HELLO, $HelloCreate);
    header('Location: dashboard.php?wc=hello/create&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-bullhorn"><?= $hello_title ? $hello_title : 'Nova Hellobar'; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=hello/home">Hellobar</a>
            <span class="crumb">/</span>
            Gerenciar Hellobar
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Voltar" href="dashboard.php?wc=hello/home" class="btn_header btn_darkaquablue icon-undo2">Voltar</a>
    </div>
</header>

<div class="dashboard_content">

    <form name="hello_add" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Hellobar"/>
        <input type="hidden" name="callback_action" value="hellobar_update"/>
        <input type="hidden" name="hello_id" value="<?= $HelloId; ?>"/>

        <div class="box box70">
            <div class="panel_header darkaquablue">
                <h2 class="icon-bullhorn">Dados Sobre a Hellobar</h2>
            </div>
            <div class="panel">
                <label class="label">
                    <span class="legend">Headline:</span>
                    <input style="font-size: 1.2em;" type="text" name="hello_title" value="<?= $hello_title; ?>" placeholder="Informe o Título da Hellobar" required/>
                </label>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">CTA (Texto do Botão):</span>
                        <input  type="text" name="hello_cta" value="<?= $hello_cta; ?>" placeholder="Informe o  Texto do Botão" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Link:</span>
                        <input  type="text" name="hello_link" value="<?= $hello_link; ?>" placeholder="Informe o Link de Ação" required/>
                    </label>
                </div>

                <label class="label">
                    <span class="legend">Cor do Botão?</span>
                    <select name="hello_color" required="required">
                        <option value="">Selecione a Cor</option>
                        <option <?= ($hello_color == 'aquablue' ? 'selected="selected"' : ''); ?> value="aquablue">Azul</option>
                        <option <?= ($hello_color == 'green' ? 'selected="selected"' : ''); ?> value="green">Verde</option>
                        <option <?= ($hello_color == 'yellow' ? 'selected="selected"' : ''); ?> value="yellow">Amarelo</option>
                        <option <?= ($hello_color == 'red' ? 'selected="selected"' : ''); ?> value="yellow">Vermelho</option>
                    </select>
                </label>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Onde Você Quer Exibir?</span>
                        <select name="hello_position" required="required">
                            <option value="">Selecione a Posição</option>
                            <option <?= ($hello_position == 'center' ? 'selected="selected"' : ''); ?> value="center">Ao Centro da Página!</option>
                            <option <?= ($hello_position == 'right_top' ? 'selected="selected"' : ''); ?> value="right_top">Direita Acima!</option>
                            <option <?= ($hello_position == 'right_bottom' ? 'selected="selected"' : ''); ?> value="right_bottom">Direita Abaixo!</option>
                        </select>
                    </label>

                    <label class="label">
                        <span class="legend">Regra de Exibição: <span class="icon-info icon-notext wc_tooltip"><span class="wc_tooltip_balloon">Defina Uma Palavra Chave Para Disparar Sua Hellobar!</span></span></span>
                        <input  type="text" name="hello_rule" value="<?= $hello_rule; ?>" placeholder="Informe a Regra de Exibição"/>
                    </label>
                </div>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">Exibir a Partir De:</span>
                        <input class="jwc_datepicker" data-timepicker="true" readonly="readonly" type="text" name="hello_start" value="<?= (!empty($hello_start) ? date("d/m/Y H:i", strtotime($hello_start)) : date("d/m/Y H:i")); ?>" placeholder="Informe o Início da Programação" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Interromper Dia:</span>
                        <input class="jwc_datepicker" data-timepicker="true" readonly="readonly" type="text" name="hello_end" value="<?= (!empty($hello_end) ? date("d/m/Y H:i", strtotime($hello_end)) : date("d/m/Y H:i", strtotime("+10days"))); ?>" placeholder="Informe o  Encerramento da Programação" required/>
                    </label>
                </div>

                <div class="m_top">&nbsp;</div>
                <div class="wc_actions" style="text-align: center;">
                    <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                    
                    <div class="switch__container" style="margin-bottom: 10px;">
                      <input value='1' id="switch-shadow" class="switch switch--shadow" type="checkbox" name='hello_status' <?= ($hello_status == 1 ? 'checked' : ''); ?>>
                      <label for="switch-shadow"></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <div class="box box30">
            <div class="panel_header aquablue">
                <h2 class="icon-image">Imagem da Hellobar</h2>
            </div>
            <div class="post_create_cover">
                <?php
                $HelloImage = (!empty($hello_image) && file_exists("../uploads/{$hello_image}") && !is_dir("../uploads/{$hello_image}") ? "uploads/{$hello_image}" : 'admin/_img/no_image.jpg');
                ?>
                <img style="width: 100%;" class="hello_cover" alt="Imagem da Hellobar" title="Imagem da Hellobar" src="../tim.php?src=<?= $HelloImage; ?>&w=<?= IMAGE_W / 3; ?>" default="../tim.php?src=<?= $HelloImage; ?>&w=<?= IMAGE_W / 3; ?>"/>
            </div>
            
            <div class="panel">
                <label class="label">
                    <span class="legend">Imagem (Largura de <?= IMAGE_W; ?>px):</span>
                    <input type="file" class="wc_loadimage" name="hello_cover"/>
                </label>
            </div>
        </div>
    </form>
</div>