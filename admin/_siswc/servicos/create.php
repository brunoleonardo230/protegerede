<?php
$AdminLevel = LEVEL_WC_SERVICES;
if (!APP_SERVICES || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Create)):
    $Create = new Create;
endif;

$ServiceId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($ServiceId):
    $Read->ExeRead(DB_SERVICES, "WHERE service_id = :id", "id={$ServiceId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = "<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Serviço Que Não Existe ou Que Foi Removida Recentemente!";
        header('Location: dashboard.php?wc=servicos/home');
    endif;
else:
    $ServiceCreate = ['service_datecreate' => date('Y-m-d H:i:s')];
    $Create->ExeCreate(DB_SERVICES, $ServiceCreate);
    header('Location: dashboard.php?wc=servicos/create&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header" xmlns="http://www.w3.org/1999/html">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-briefcase">Serviços</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=servicos/home">Serviços</a>
        </p>
    </div>

    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;">
        <a class="btn_header btn_darkaquablue icon-undo2" title="Voltar" href="dashboard.php?wc=servicos/home">Voltar</a>
    </div>
</header>

<div class="dashboard_content">
    <div class="box box70">
        <article class="wc_tab_target wc_active" id="services">
            <form class="auto_save" name="service_info" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Services"/>
                <input type="hidden" name="callback_action" value="manager"/>
                <input type="hidden" name="service_id" value="<?= $ServiceId; ?>"/>

                <div class="panel_header darkaquablue">
                    <h2 class="icon-briefcase">Dados Sobre o Serviço</h2>
                </div>
                <div class="panel">
                    <label class="label">
                        <span class="legend">Imagem do Serviço:</span>
                        <input type="file" class="wc_loadimage" name="service_image"/>
                    </label>

                    <label class="label">
                        <span class="legend">Título do Serviço:</span>
                        <input name="service_title" style="font-size: 1.3em;" value="<?= $service_title; ?>"
                               placeholder="Título do Serviço" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Tipo do Serviço:</span>
                        <select name="service_type" required>
                            <option value="">Selecione o Tipo:</option>
                            <?php
                            foreach (getServicesType() as $TypeId => $TypeValue):
                                echo "<option " . ($service_type == $TypeId ? "selected='selected'" : null) . " value='{$TypeId}'>{$TypeValue}</option>";
                            endforeach;
                            ?>
                        </select>
                    </label>

                    <div class="box box30">
                        <label class="label">
                            <span class="legend">Tipo do Ícone:</span>
                            <select name="service_icon_type" class="j_icon" required>
                                <option selected disabled value="">Selecione o Tipo:</option>
                                <option value="1" <?= ($service_icon_type == 1 ? 'selected="selected"' : ''); ?>>
                                    Imagem
                                </option>
                                <option value="2" <?= ($service_icon_type == 2 ? 'selected="selected"' : ''); ?>>Texto
                                </option>
                            </select>
                        </label>

                        <div class="j_icon_image">
                            <?php
                            $Image = (file_exists("../uploads/{$service_icon}") && !is_dir("../uploads/{$service_icon}") ? "uploads/{$service_icon}" : 'admin/_img/no_avatar.jpg');
                            ?>
                            <label class="label" style="margin-bottom: 10px;">
                                <span class="legend">Ícone do Serviço:</span>
                            </label>

                            <img class="service_icon" style="width: 100%;"
                                 src="../tim.php?src=<?= $Image; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" alt=""
                                 title=""/>

                            <label class="label" style="margin-top: 10px;">
                                <input type="file" class="wc_loadimage" name="service_icon"/>
                            </label>
                        </div>

                        <div class="j_icon_text">
                            <label class='label'>
                                <span class='legend'>Ícone do Serviço:</span>
                                <input value='<?= $service_icon_text; ?>' type='text' name='service_icon_text'
                                       placeholder='Informe o Ícone'/>
                            </label>
                        </div>
                    </div>

                    <div class="box box70">
                        <label class="label">
                            <span class="legend">Descrição do Serviço:</span>
                            <textarea class="work_mce" rows="50"
                                      name="service_content"><?= $service_content; ?></textarea>
                        </label>
                    </div>

                    <div class="wc_actions" style="text-align: center">
                        <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_darkaquablue icon-share">ATUALIZAR
                            <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;"
                                 alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>

                        <div class="switch__container">
                            <input value='1' id="switch-shadow" class="switch switch--shadow" type="checkbox"
                                   name='service_status' <?= ($service_status == 1 ? 'checked' : ''); ?>>
                            <label for="switch-shadow"></label>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </article>

        <article class="wc_tab_target ds_none" id="types">
            <div class="panel_header darkaquablue">
                <span>
                    <a title="Novo Tipo" href="dashboard.php?wc=servicos/types&servicos=<?= $service_id; ?>" class="btn_header btn_aquablue icon-plus icon-notext"></a>
                </span>
                <h2 class="icon-briefcase">Tipos de Serviço</h2>
            </div>
            <div class="panel">
                <?php
                $Read->ExeRead(DB_SERVICES_TYPES, "WHERE service_id = :types ORDER BY service_type_datecreate DESC, service_type_title ASC", "types={$service_id}");
                if (!$Read->getResult()):
                    echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Tipos do Serviço Cadastrados. Comece Agora Mesmo Cadastrando o Primeiro Tipo!</span>", E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() as $Types):
                        extract($Types);
                        $TypesImage = (file_exists("../uploads/{$service_type_image}") && !is_dir("../uploads/{$service_type_image}") ? "uploads/{$service_type_image}" : 'admin/_img/no_image.jpg');
                        echo "<div class='single_user_addr js-rel-to' id='{$service_type_id}'>
                            <h1 class='icon-list2'>{$service_type_title}</h1>
                            <p>" . Check::Words($service_type_content, 20) . "</p>
                            <div class='single_user_addr_actions'>
                                <a title='Editar Tipo' href='dashboard.php?wc=servicos/types&id={$service_type_id}' class='post_single_center icon-notext icon-pencil btn_header btn_darkaquablue'></a>
                                <span title='Excluir Tipo' rel='single_user_addr' class='j_delete_action icon-notext icon-bin btn_header btn_red' callback='Services' callback_action='delete_types' id='{$service_type_id}'></span>
                            </div>
                        </div>";
                    endforeach;
                endif;
                ?>
            </div>
            <div class="clear"></div>
        </article>
    </div>

    <div class="box box30">
        <div class="panel_header aquablue">
            <h2 class="icon-image">Imagem do Serviço</h2>
        </div>
        <?php
        $Image = (file_exists("../uploads/{$service_image}") && !is_dir("../uploads/{$service_image}") ? "uploads/{$service_image}" : 'admin/_img/no_avatar.jpg');
        ?>
        <img class="service_image" style="width: 100%;"
             src="../tim.php?src=<?= $Image; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" alt="" title=""/>

        <div class="box_conf_menu no_icon" style="margin-top: 0;">
            <div class="panel">
                <a class='conf_menu wc_tab wc_active' href='#services'><span class="icon-briefcase">Serviço</span></a>
                <a class='conf_menu wc_tab' href='#types'><span class="icon-list2">Tipos</span></a>
            </div>
        </div>
    </div>
</div>