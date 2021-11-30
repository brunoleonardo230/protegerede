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

$BlockId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$CompanyId = filter_input(INPUT_GET, 'company', FILTER_VALIDATE_INT);
if ($BlockId):
    $Read->ExeRead(DB_COMPANY_BLOCKS, "WHERE block_id = :id", "id={$BlockId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);

        $Read->ExeRead(DB_COMPANY, "WHERE company_id = :company", "company={$company_id}");
        if ($Read->getResult()):
            extract($Read->getResult()[0]);
        else:
            $_SESSION['trigger_controll'] = Erro("<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Bloco Que Não Existe ou Que Foi Removido Recentemente!", E_USER_NOTICE);
            header('Location: dashboard.php?wc=company/home');
            exit;
        endif;
    endif;
elseif ($CompanyId):
    $NewBlocks = ['company_id' => $CompanyId];
    $Create->ExeCreate(DB_COMPANY_BLOCKS, $NewBlocks);
    header('Location: dashboard.php?wc=company/blocks&id=' . $Create->getResult());
    exit;
else:
    $_SESSION['trigger_controll'] = Erro("<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Bloco Que Não Existe ou Que Foi Removido Recentemente!", E_USER_NOTICE);
    header('Location: dashboard.php?wc=company/home');
    exit;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-office"><?= $block_title ? $block_title : "Novo Bloco"; ?></h1>
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
        <a title="Novo Bloco" href="dashboard.php?wc=company/blocks&company=<?= $company_id; ?>" class="btn_header btn_darkaquablue icon-plus">Novo Bloco</a>    
    </div>
</header>

<div class="dashboard_content">
    <form class="auto_save" name="create_blocks_company" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Company"/>
        <input type="hidden" name="callback_action" value="create_block"/>
        <input type="hidden" name="block_id" value="<?= $BlockId; ?>"/>
    
        <div class="box box70">
            <div class="panel_header darkaquablue">
                <h2 class="icon-office">Dados Sobre o Bloco</h2>
            </div>
            <div class="panel">
                <label class="label">
                    <span class="legend">Título do Bloco:</span>
                    <input name="block_title" style="font-size: 1.2em;" value="<?= $block_title; ?>" placeholder="Informe o Título do Bloco" required/>
                </label>
                
                <label class='label'>
                    <span class='legend'>Ícone do Bloco:</span>
                    <input value='<?= $block_icon; ?>' type='text' name='block_icon' placeholder='Informe o Ícone do Bloco' />
                </label>
                
                <label class="label">
                    <span class="legend">Descrição do Bloco:</span>
                    <textarea class="work_mce_basic" rows="50" name="block_content"><?= $block_content; ?></textarea>
                </label>
                
                <div class="m_top">&nbsp;</div>
                <div class="wc_actions" style="text-align: center">
                    <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                    
                    <div class="switch__container" style="margin-bottom: 10px;">
                      <input value='1' id="switch-shadow" class="switch switch--shadow" type="checkbox" name='block_status' <?= ($block_status == 1 ? 'checked' : ''); ?>>
                      <label for="switch-shadow"></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>    
        
        
        <div class="box box30">
            <div class="panel_header aquablue">
                    <h2 class="icon-image">Imagem do Bloco</h2>
                </div>
                <div class="post_create_cover">
                    <div class="upload_progress none">0%</div>
                    <?php
                    $BlockCover = (!empty($block_image) && file_exists("../uploads/{$block_image}") && !is_dir("../uploads/{$block_image}") ? "uploads/{$block_image}" : 'admin/_img/no_image.jpg');
                    ?>
                    <img class="post_thumb block_image" alt="Capa" title="Capa" src="../tim.php?src=<?= $BlockCover; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=<?= $BlockCover; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
                </div>
        
                <div class="panel">
                    <label class="label">
                        <span class="legend">Imagem: (JPG <?= IMAGE_W; ?>x<?= IMAGE_H; ?>px)</span>
                        <input type="file" class="wc_loadimage" name="block_image"/>
                    </label>
                </div>
            </div>
        </div>    
    </form>    
</div>