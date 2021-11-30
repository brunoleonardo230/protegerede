<?php
$AdminLevel = LEVEL_WC_SLIDES;
if (!APP_SLIDE || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
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

$SlideId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($SlideId):
    $Read->ExeRead(DB_SLIDES, "WHERE slide_id = :id", "id={$SlideId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = "<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Um Slide Que Não Existe Ou Que Foi Removido Recentemente!";
        header('Location: dashboard.php?wc=slide/home');
        exit;
    endif;
else:
    $SlideCreate = ['slide_date' => date('Y-m-d H:i:s'), 'slide_start' => date('Y-m-d H:i:s')];
    $Create->ExeCreate(DB_SLIDES, $SlideCreate);
    header('Location: dashboard.php?wc=slide/create&id=' . $Create->getResult());
    exit;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-camera"><?= $slide_title ? $slide_title : 'Novo Slide'; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=slide/home">Slides</a>
            <span class="crumb">/</span>
            Gerenciar Slide
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Ver Slides!" href="dashboard.php?wc=slide/home" class="btn_header btn_aquablue icon-eye">Ver Slides</a>
        <a title="Novo Slide" href="dashboard.php?wc=slide/create" class="btn_header btn_darkaquablue icon-plus">Novo Slide</a>
    </div>
</header>

<div class="dashboard_content">
    <form name="post_create" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Slides"/>
        <input type="hidden" name="callback_action" value="manager"/>
        <input type="hidden" name="slide_id" value="<?= $SlideId; ?>"/>

        <div class="sample_tab_nav">
            <a class='conf_menu wc_tab wc_active' href='#mobile' title="Mobile"><span class="icon-mobile">Mobile</span></a>
            <a class='conf_menu wc_tab' href='#tablet' title="Tablet"><span class="icon-tablet">Tablet</span></a>
            <a class='conf_menu wc_tab' href='#desktop' title="Desktop"><span class="icon-display">Desktop</span></a>
        </div>

        <article class="box box100">
            <div class="panel">
                <div class="wc_tab_target wc_active" id="mobile">
                    <div class="slide_create_cover al_center">
                        <div class="upload_progress none">0%</div>
                        <?php
                        $imageMobile = (!empty($slide_image_mobile) && file_exists("../uploads/{$slide_image_mobile}") && !is_dir("../uploads/{$slide_image_mobile}") ? "uploads/{$slide_image_mobile}" : 'admin/_img/no_image.jpg');
                        ?>
                        <img class="slide_image_mobile post_cover" alt="Capa" title="Capa" src="../tim.php?src=<?= $imageMobile; ?>&w=450&h=300" default="../tim.php?src=<?= $imageMobile; ?>&w=450&h=300"/>
                    </div>

                    <label class="label m_top">
                        <span class="legend">Capa: (JPG 450x300px)</span>
                        <input type="file" class="wc_loadimage" name="slide_image_mobile"/>
                    </label>
                </div>

                <div class="wc_tab_target ds_none" id="tablet">
                    <div class="slide_create_cover al_center">
                        <div class="upload_progress none">0%</div>
                        <?php
                        $imageTablet = (!empty($slide_image_tablet) && file_exists("../uploads/{$slide_image_tablet}") && !is_dir("../uploads/{$slide_image_tablet}") ? "uploads/{$slide_image_tablet}" : 'admin/_img/no_image.jpg');
                        ?>
                        <img class="slide_image_tablet post_cover" alt="Capa" title="Capa" src="../tim.php?src=<?= $imageTablet; ?>&w=1500&h=460" default="../tim.php?src=<?= $imageTablet; ?>&w=1500&h=460"/>
                    </div>

                    <label class="label m_top">
                        <span class="legend">Capa: (JPG 1500x460px)</span>
                        <input type="file" class="wc_loadimage" name="slide_image_tablet"/>
                    </label>
                </div>

                <div class="wc_tab_target ds_none" id="desktop">
                    <div class="slide_create_cover al_center">
                        <div class="upload_progress none">0%</div>
                        <?php
                        $imageDesktop = (!empty($slide_image_desktop) && file_exists("../uploads/{$slide_image_desktop}") && !is_dir("../uploads/{$slide_image_desktop}") ? "uploads/{$slide_image_desktop}" : 'admin/_img/no_image.jpg');
                        ?>
                        <img class="slide_image_desktop post_cover" alt="Capa" title="Capa" src="../tim.php?src=<?= $imageDesktop; ?>&w=<?= SLIDE_W; ?>&h=<?= SLIDE_H; ?>" default="../tim.php?src=<?= $imageDesktop; ?>&w=<?= SLIDE_W; ?>&h=<?= SLIDE_H; ?>"/>
                    </div>

                    <label class="label m_top">
                        <span class="legend">Capa: (JPG <?= SLIDE_W; ?>x<?= SLIDE_H; ?>px)</span>
                        <input type="file" class="wc_loadimage" name="slide_image_desktop"/>
                    </label>
                </div>

                <label class="label">
                    <span class="legend">Título:</span>
                    <input style="font-size: 1.2em;" type="text" name="slide_title" value="<?= $slide_title; ?>" placeholder="Informe o Título do Slide" required/>
                </label>

                <label class="label">
                    <span class="legend">Descrição:</span>
                    <textarea name="slide_desc" rows="3" required><?= $slide_desc; ?></textarea>
                </label>

                <label class="label">
                    <span class="legend">Link: (<?= BASE; ?>/<b>Destino</b>)</span>
                    <input type="text" name="slide_link" value="<?= $slide_link; ?>" placeholder="Informe o Link do Slide" required/>
                </label>

                <div class="label_50">
                    <label class="label">
                        <span class="legend">À Partir De:</span>
                        <input style="font-size: 1.2em;" type="text" class="jwc_datepicker" data-timepicker="true" readonly="readonly" name="slide_start" value="<?= (!empty($slide_start) ? date('d/m/Y H:i:s', strtotime($slide_start)) : date('d/m/Y H:i:s')); ?>" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Até Dia: (Opcional)</span>
                        <input style="font-size: 1.2em;" type="text" class="jwc_datepicker" data-timepicker="true" readonly="readonly" name="slide_end" value="<?= (!empty($slide_end) ? date('d/m/Y H:i:s', strtotime($slide_end)) : date('d/m/Y H:i:s', strtotime("+1month"))); ?>"/>
                    </label>
                </div>

                <div class="wc_actions" style="text-align: center">
                    <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share m_top">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                    
                    <div class="switch__container" style="margin-bottom: 10px;">
                      <input value='1' id="switch-shadow" class="switch switch--shadow" type="checkbox" name='slide_status' <?= ($slide_status == 1 ? 'checked' : ''); ?>>
                      <label for="switch-shadow"></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </article>
    </form>
</div>