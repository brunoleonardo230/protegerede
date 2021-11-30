<?php
$AdminLevel = LEVEL_WC_GALLERY;
if (empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

//AUTO DELETE POST TRASH
if (DB_AUTO_TRASH):
    $Delete = new Delete;
    $Delete->ExeDelete(DB_GALLERY, "WHERE gallery_title = '' AND gallery_cover = '' AND gallery_status = :st", "st=0");

    //AUTO TRASH IMAGES
    $Read->FullRead("SELECT gallery_file FROM " . DB_GALLERY_IMAGES . " WHERE gallery_id NOT IN(SELECT gallery_id FROM " . DB_GALLERY . ")");
    if ($Read->getResult()):
        $Delete->ExeDelete(DB_GALLERY_IMAGES, "WHERE gallery_image_id >= :id AND galley_id NOT IN(SELECT gallery_id FROM " . DB_GALLERY . ")", "id=1");
        foreach ($Read->getResult() as $ImageRemove):
            if (file_exists("../uploads/{$ImageRemove['gallery_file']}") && !is_dir("../uploads/{$ImageRemove['gallery_file']}")):
                unlink("../uploads/{$ImageRemove['gallery_file']}");
            endif;
        endforeach;
    endif;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-images">Galerias de Fotos</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Galeria de Fotos
        </p>
    </div>

    <div class="dashboard_header_search">
        <span class="btn_header btn_aquablue icon-spinner9 wc_drag_active" title="Ordenar">Ordenar</span>
        <a title="Nova Galeria" href="dashboard.php?wc=gallery/create" class="btn_header btn_darkaquablue icon-plus">Nova Galeria</a>
    </div>

</header>
<div class="dashboard_content">
    <?php
    $getGallery = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
    $Gallery = ($getGallery ? $getGallery : 1);
    $Paginator = new Pager('dashboard.php?wc=gallery/home&pg=', '<<', '>>', 5);
    $Paginator->ExePager($Gallery, 12);

    $Read->ExeRead(DB_GALLERY, "ORDER BY gallery_order ASC, gallery_title ASC, gallery_date DESC LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}");
    if (!$Read->getResult()):
        $Paginator->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existe Nenhuma Galeria. Comece Agora Mesmo Criando Sua Primeira Galeria de Fotos!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $GALLERY):
            extract($GALLERY);
            $gallery_status = ($gallery_status == 1 ? '<span class="icon-checkmark font_aquablue">Publicada</span>' : '<span class="icon-warning font_yellow">Rascunho</span>');
            $gallery_cover = (!empty($gallery_cover) ? BASE . "/tim.php?src=uploads/{$gallery_cover}&w=" . IMAGE_W / 4 . "&h=" . IMAGE_H / 4 . "" : "");

            echo "<article class='box box25 page_single wc_draganddrop js-rel-to' callback='Gallery' callback_action='gallery_order' id='{$gallery_id}'>
        <img alt='{$gallery_title}' title='{$gallery_title}' src='{$gallery_cover}'/>
        <div class='box_content wc_normalize_height'>
            <h1 class='title'>{$gallery_title}</h1>
            <p>{$gallery_status}</p>
        </div>
        <div class='page_single_action'>
            <a title='Editar Galeria' href='dashboard.php?wc=gallery/create&id={$gallery_id}' class='post_single_center icon-pencil icon-notext btn_header btn_darkaquablue'></a>
            <span title='Excluir Galeria' rel='page_single' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Gallery' callback_action='delete' id='{$gallery_id}'></span>
        </div>
    </article>";
        endforeach;

        $Paginator->ExePaginator(DB_PAGES);
        echo $Paginator->getPaginator();
    endif;
    ?>
</div>