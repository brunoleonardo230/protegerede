<?php
$AdminLevel = LEVEL_WC_GALLERY;
if (empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
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

$GalleryId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($GalleryId):
    $Read->ExeRead(DB_GALLERY, "WHERE gallery_id = :id", "id={$GalleryId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPSSS {$Admin['user_name']}</b>, Você Tentou Editar Uma Página Que Não Existe Ou Que Foi Removida Recentemente!", E_USER_NOTICE);
        header('Location: dashboard.php?wc=gallery/home');
        exit;
    endif;
else:
    $GalleryCreate = ['gallery_date' => date('Y-m-d H:i:s'), 'gallery_status' => 0, 'gallery_cover' => '../admin/_siswc/gallery/no-image.png'];
    $Create->ExeCreate(DB_GALLERY, $GalleryCreate);
    header('Location: dashboard.php?wc=gallery/create&id=' . $Create->getResult());
    exit;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-images"><?= $gallery_title ? $gallery_title : 'Nova Galeria'; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=gallery/home">Galerias de Fotos</a>
            <span class="crumb">/</span>
            Gerenciar Galeria
        </p>
    </div>
</header>

<div class="dashboard_content">

    <form class="auto_save" name="page_add" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Gallery"/>
        <input type="hidden" name="callback_action" value="manage"/>
        <input type="hidden" name="gallery_id" value="<?= $GalleryId; ?>"/>

        <div class="box box100">

            <div class="panel_header darkaquablue">
                <h2 class="icon-images">Dados Sobre a Galeria</h2>
            </div>

            <div class="panel">
                <div class="box box70">
                    <label class="label">
                        <span class="legend">Título:</span>
                        <input style="font-size: 1.2em;" type="text" name="gallery_title" value="<?= $gallery_title; ?>" placeholder="Título da Galeria" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Descrição:</span>
			<textarea class="work_mce" rows="50" name="gallery_description" placeholder="Sobre a Galeria:" required><?= $gallery_description; ?></textarea>
                    </label>
					
			<select name="gallery_category">
                        <option value="" disabled="disabled" selected="selected">Selecione Uma Categoria:</option>
                            <?php
                            foreach (getGalleryCat() as $CatId => $CatValue):
                                echo "<option " . ($gallery_category == $CatId ? "selected='selected'" : null) . " value='{$CatId}'>{$CatValue}</option>";
                            endforeach;
                            ?>
                    </select>
                </div>    
                <div class="box box30">
                    <div class="upload_progress none">0%</div>
                    <?php
                    $GalleryCover = (!empty($gallery_cover) && file_exists("../uploads/{$gallery_cover}") && !is_dir("../uploads/{$gallery_cover}") ? "uploads/{$gallery_cover}" : 'admin/_img/no_image.jpg');
                    ?>
                    <img class="post_thumb gallery_cover" alt="Capa" title="Capa" src="../tim.php?src=<?= $GalleryCover; ?>&w=<?= IMAGE_W / 3; ?>&h=<?= IMAGE_H / 3; ?>" default="../tim.php?src=<?= $GalleryCover; ?>&w=<?= IMAGE_W / 3; ?>&h=<?= IMAGE_H / 3; ?>"/>
                    
                    <label class="label m_top">
                        <input type="file" class="wc_loadimage" name="gallery_cover"/>
                    </label>

                    <div class="wc_actions" style="text-align: center; margin-top: 20px;">
                        <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                        
                        <div class="switch__container" style="margin-bottom: 10px;">
                          <input value='1' id="switch-shadow" class="switch switch--shadow" type="checkbox" name='gallery_status' <?= ($gallery_status == 1 ? 'checked' : ''); ?>>
                          <label for="switch-shadow"></label>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
 
    <div class="box box100">
        <div class="panel_header darkaquablue">
            <h2 class="icon-images" style="display:inline-block">Fotos da Galeria</h2>
            <span class="btn_header btn_aquablue icon-spinner9 wc_drag_active" title="Ordenar" style="display:inline-block; margin-bottom: 10px;">Ordenar</span>
        </div>
        
        <div class="panel">
            <form class="j_formsubmit" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Gallery"/>
                <input type="hidden" name="callback_action" value="gallery_image"/>
                <input type="hidden" name="gallery_id" value="<?= $GalleryId; ?>"/>
                
                <div class="upload_progress none" style="padding: 5px; background: #218FE5; color: #fff; width: 0%; text-align: center; max-width: 100%;">0%</div>
                                 
                <input type="file" name="gallery_images[]" multiple required/>                
                <div class="wc_actions" style="text-align: center; margin-top: 15px;">
                    <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                </div>
                <div class="clear"></div>
                
                <div class='gallery panel_gallery'>
                    <?php
                        $Read->ExeRead(DB_GALLERY_IMAGES, "WHERE gallery_id = :id ORDER BY gallery_image_order ASC", "id={$GalleryId}");
                        if (!$Read->getResult()):
                            Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Fotos Cadastradas Em Nossa Galeria!</span>", E_USER_NOTICE);
                        else:
                            foreach ($Read->getResult() as $image):
                                extract($image);
                                ?>                            
                                <div class='panel_gallery_image wc_draganddrop js-rel-to' callback='Gallery' callback_action='gallery_image_order' id='<?= $gallery_image_id; ?>' data-id="<?= $gallery_image_id; ?>" >
                                    <img src='../tim.php?src=uploads/<?= $gallery_file; ?>&w=200&h=200'>
                                    <div class='panel_gallery_action'>
                                        <ul class="buttons"> 
                                        <li><span title="Editar Imagem" class='j_edit_action icon-pencil icon-notext btn_header btn_darkaquablue'></span></li>
                                        <li><span title="Excluir Imagem" rel='panel_gallery_image' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Gallery' callback_action='gallery_image_delete' id="<?= $gallery_image_id; ?>"></span></li>
                                        </ul>
                                    </div>
                                    <span class="panel_gallery_image_legend al_center"><?= Check::Words($gallery_image_legend, 80) ?></span>
                                </div>

                                <?php
                            endforeach;
                        endif;    
                    ?>    
                </div>
                <div class="clear"></div>
            </form>
            
            <div class="modal_legend">
                <div class="modal_legend_content">
                    <form class="j_form_legend" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="gallery_image_id" value=""/>
                        <input type="hidden" name="callback" value="Gallery"/>
                        <input type="hidden" name="callback_action" value="gallery_legend"/>

                        <span class="legend">Alterar Legenda da Foto:</span>
                        <input type="text" name="gallery_image_legend" placeholder="Legenda:" required/>
                        <span title="Fechar" class="modal_cancel icon-cancel-circle btn btn_red" id="post_control" style="margin-right: 8px;">Fechar</span>
                        <button title="ATUALIZAR" class="btn btn_aquablue icon-share">ATUALIZAR</button>
                        <div class="clear"></div>
                    </form>  
                </div>    
            </div>
        </div>    
    </div>
</div>

<script src="<?= BASE; ?>/admin/_siswc/gallery/gallery.js"></script>
