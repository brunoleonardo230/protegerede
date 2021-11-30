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

$TypesId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$ServiceId = filter_input(INPUT_GET, 'servicos', FILTER_VALIDATE_INT);
if ($TypesId):
    $Read->ExeRead(DB_SERVICES_TYPES, "WHERE service_type_id = :id", "id={$TypesId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);

        $Read->ExeRead(DB_SERVICES, "WHERE service_id = :service", "service={$service_id}");
        if ($Read->getResult()):
            extract($Read->getResult()[0]);
        else:
            $_SESSION['trigger_controll'] = Erro("<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Tipo Que Não Existe ou Que Foi Removido Recentemente!", E_USER_NOTICE);
            header('Location: dashboard.php?wc=servicos/home');
            exit;
        endif;
    endif;
elseif ($ServiceId):
    $NewTypes = ['service_id' => $ServiceId];
    $Create->ExeCreate(DB_SERVICES_TYPES, $NewTypes);
    header('Location: dashboard.php?wc=servicos/types&id=' . $Create->getResult());
    exit;
else:
    $_SESSION['trigger_controll'] = Erro("<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Benefício Que Não Existe ou Que Foi Removido Recentemente!", E_USER_NOTICE);
    header('Location: dashboard.php?wc=servicos/home');
    exit;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-list2"><?= $service_type_title ? $service_type_title : "Novo Benefício"; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=servicos/home">Serviços</a>
            <span class="crumb">/</span>
            Benefícios
        </p>
    </div>
    
    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;">
        <a class="btn_header btn_aquablue icon-undo2" title="Voltar" href="dashboard.php?wc=servicos/create&id=<?= $service_id; ?>">Voltar</a>
        <a title="Novo Benefício" href="dashboard.php?wc=servicos/types&servicos=<?= $service_id; ?>" class="btn_header btn_darkaquablue icon-plus">Novo Tipo</a>
    </div>
</header>

<div class="dashboard_content">
    <form class="auto_save" name="create_service_types" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Services"/>
        <input type="hidden" name="callback_action" value="create_types"/>
        <input type="hidden" name="service_type_id" value="<?= $TypesId; ?>"/>
    
        <div class="box box70">
            <div class="panel_header darkaquablue">
                <h2 class="icon-list2">Dados Sobre o Tipo</h2>
            </div>
            <div class="panel">
                <label class="label">
                    <span class="legend">Título do Tipo:</span>
                    <input name="service_type_title" style="font-size: 1.2em;" value="<?= $service_type_title; ?>" placeholder="Informe o Título do Tipo" required/>
                </label>
				
				<label class="label">
                    <span class="legend">Preço do Tipo:</span>
                    <input name="service_type_price" class="mask-money" value="<?= (!empty($service_type_price) ? number_format($service_type_price, 2, ',', '.') : null); ?>" placeholder="Informe o Preço do Tipo" required/>
                </label>
                
                <div class="box box30">
                    <label class="label">
                        <span class="legend">Tipo do Ícone:</span>
                        <select name="service_type_icon_type" class="j_benefits_icon" required>
                            <option selected disabled value="">Selecione o Tipo:</option>
                            <option value="1" <?= ($service_type_icon_type == 1 ? 'selected="selected"' : ''); ?>>Imagem</option>
                            <option value="2" <?= ($service_type_icon_type == 2 ? 'selected="selected"' : ''); ?>>Texto</option>
                        </select>
                    </label>
                    
                    <div class="j_benefits_icon_image">    
                        <?php
                        $Image = (file_exists("../uploads/{$service_type_icon}") && !is_dir("../uploads/{$service_type_icon}") ? "uploads/{$service_type_icon}" : 'admin/_img/no_avatar.jpg');
                        ?>
                        <label class="label" style="margin-bottom: 10px;">
                            <span class="legend">Ícone do Tipo:</span>
                        </label>
                        
                        <img class="service_type_icon" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" alt="" title=""/>
                        
                        <label class="label" style="margin-top: 10px;">
                            <input type="file" class="wc_loadimage" name="service_type_icon"/>
                        </label>     
                    </div>    
                    
                    <div class="j_benefits_icon_text">        
                        <label class='label'>
                            <span class='legend'>Ícone do Tipo:</span>
                            <input value='<?= $service_type_icon_text; ?>' type='text' name='service_type_icon_text' placeholder='Informe o Ícone do Tipo' />
                        </label>
                    </div>
                </div>
                
                <div class="box box70">
                    <label class="label">
                        <span class="legend">Descrição do Tipo:</span>
                        <textarea class="work_mce" rows="50" name="service_type_content"><?= $service_type_content; ?></textarea>
                    </label>    
                </div>
                
                <div class="wc_actions" style="text-align: center">
                    <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_darkaquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                </div>
                <div class="clear"></div>
            </div>
        </div>    
        
        <div class="box box30">
            <div class="panel_header aquablue">
                    <h2 class="icon-image">Imagem do Tipo</h2>
                </div>
                <div class="post_create_cover">
                    <div class="upload_progress none">0%</div>
                    <?php
                    $BenefitsImage = (!empty($service_type_image) && file_exists("../uploads/{$service_type_image}") && !is_dir("../uploads/{$service_type_image}") ? "uploads/{$service_type_image}" : 'admin/_img/no_image.jpg');
                    ?>
                    <img class="post_thumb service_type_image" alt="Capa" title="Capa" src="../tim.php?src=<?= $BenefitsImage; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=<?= $BenefitsImage; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
                </div>
        
                <div class="panel">
                    <label class="label">
                        <span class="legend">Imagem do Tipo</span>
                        <input type="file" class="wc_loadimage" name="service_type_image"/>
                    </label>
                </div>
            </div>
        </div>    
    </form>    
</div>