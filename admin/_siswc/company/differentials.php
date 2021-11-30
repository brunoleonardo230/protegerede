<?php
$AdminLevel = LEVEL_WC_COMPANY;
if (!APP_COMPANY || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
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

$DifferentialId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$CompanyId = filter_input(INPUT_GET, 'company', FILTER_VALIDATE_INT);
if ($DifferentialId):
    $Read->ExeRead(DB_COMPANY_DIFFERENTIALS, "WHERE differential_id = :id", "id={$DifferentialId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);

        $Read->ExeRead(DB_COMPANY, "WHERE company_id = :company", "company={$company_id}");
        if ($Read->getResult()):
            extract($Read->getResult()[0]);
        else:
            $_SESSION['trigger_controll'] = Erro("<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Diferencial Que Não Existe ou Que Foi Removido Recentemente!", E_USER_NOTICE);
            header('Location: dashboard.php?wc=company/home');
            exit;
        endif;
    endif;
elseif ($CompanyId):
    $NewDifferential = ['company_id' => $CompanyId];
    $Create->ExeCreate(DB_COMPANY_DIFFERENTIALS, $NewDifferential);
    header('Location: dashboard.php?wc=company/differentials&id=' . $Create->getResult());
    exit;
else:
    $_SESSION['trigger_controll'] = Erro("<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Diferencial Que Não Existe ou Que Foi Removido Recentemente!", E_USER_NOTICE);
    header('Location: dashboard.php?wc=company/home');
    exit;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-list2"><?= $differential_title ? $differential_title : "Novo Diferencial"; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=company/home">A Empresa</a>
            <span class="crumb">/</span>
            Gerenciar Bloco
        </p>
    </div>
    
    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;">
        <a class="btn_header btn_aquablue icon-undo2" title="Voltar" href="dashboard.php?wc=company/create&id=<?= $company_id; ?>">Voltar</a>
        <a title="Novo Bloco" href="dashboard.php?wc=company/differentials&company=<?= $company_id; ?>" class="btn_header btn_darkaquablue icon-plus">Novo Diferencial</a>
    </div>
</header>

<div class="dashboard_content">
    <form class="auto_save" name="create_differential_company" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Company"/>
        <input type="hidden" name="callback_action" value="create_differential"/>
        <input type="hidden" name="differential_id" value="<?= $DifferentialId; ?>"/>
    
        <div class="box box70">
            <div class="panel_header darkaquablue">
                <h2 class="icon-list2">Dados Sobre o Diferencial</h2>
            </div>
            <div class="panel">
                <label class="label">
                    <span class="legend">Título do Diferencal:</span>
                    <input name="differential_title" style="font-size: 1.3em;" value="<?= $differential_title; ?>" placeholder="Informe o Título do Diferencial:" required/>
                </label>
                
                <div class="box box30">
                    <label class="label">
                        <span class="legend">Tipo do Ícone:</span>
                        <select name="differential_icon_type" class="j_differential_icon" required>
                            <option selected disabled value="">Selecione o Tipo:</option>
                            <option value="1" <?= ($differential_icon_type == 1 ? 'selected="selected"' : ''); ?>>Imagem</option>
                            <option value="2" <?= ($differential_icon_type == 2 ? 'selected="selected"' : ''); ?>>Texto</option>
                        </select>
                    </label>
                    
                    <div class="j_differential_icon_image">    
                        <?php
                        $DifferentialIcon = (!empty($differential_icon) && file_exists("../uploads/{$differential_icon}") && !is_dir("../uploads/{$differential_icon}") ? "uploads/{$differential_icon}" : 'admin/_img/no_image.jpg');
                        ?>
                        <label class="label" style="margin-bottom: 10px;">
                            <span class="legend">Ícone do Diferencial:</span>
                        </label>
                            
                        <img class="post_thumb differential_icon" alt="Capa" title="Capa" src="../tim.php?src=<?= $DifferentialIcon; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=<?= $DifferentialIcon; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
                        <label class="label">
                            <input type="file" class="wc_loadimage" name="differential_icon"/>
                        </label>
                    </div>    
                    
                    <div class="j_differential_icon_text">        
                        <label class='label'>
                            <span class='legend'>Ícone do Diferencial:</span>
                            <input value='<?= $differential_icon_text; ?>' type='text' name='differential_icon_text' placeholder='Informe o Ícone' />
                        </label>
                    </div>
                </div>
                
                <div class="box box70">
                    <label class="label">
                        <span class="legend">Descrição do Diferencial:</span>
                        <textarea class="work_mce_basic" rows="50" name="differential_content"><?= $differential_content; ?></textarea>
                    </label>  
                </div>
                
                <div class="wc_actions" style="text-align: center">
                    <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                </div>
                <div class="clear"></div>
            </div>
        </div>    
        
        <div class="box box30">
            <div class="panel_header aquablue">
                    <h2 class="icon-image">Imagem do Diferencial</h2>
                </div>
                <div class="post_create_cover">
                    <div class="upload_progress none">0%</div>
                    <?php
                    $DifferentialImage = (!empty($differential_image) && file_exists("../uploads/{$differential_image}") && !is_dir("../uploads/{$differential_image}") ? "uploads/{$differential_image}" : 'admin/_img/no_image.jpg');
                    ?>
                    <img class="post_thumb differential_image" alt="Capa" title="Capa" src="../tim.php?src=<?= $DifferentialImage; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=<?= $DifferentialImage; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
                </div>
        
                <div class="panel">
                    <label class="label">
                        <span class="legend">Imagem</span>
                        <input type="file" class="wc_loadimage" name="differential_image"/>
                    </label>
                </div>
            </div>
        </div>    
    </form>    
</div>