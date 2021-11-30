<?php
$AdminLevel = LEVEL_WC_PAGES;
if (!APP_PAGES || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

//AUTO DELETE POST TRASH
if (DB_AUTO_TRASH):
    $Delete = new Delete;
    $Delete->ExeDelete(DB_PAGES, "WHERE page_title IS NULL AND page_content IS NULL AND page_status = :st", "st=0");

    //AUTO TRASH IMAGES
    $Read->FullRead("SELECT image FROM " . DB_PAGES_IMAGE . " WHERE page_id NOT IN(SELECT page_id FROM " . DB_PAGES . ")");
    if ($Read->getResult()):
        $Delete->ExeDelete(DB_PAGES_IMAGE, "WHERE id >= :id AND page_id NOT IN(SELECT page_id FROM " . DB_PAGES . ")", "id=1");
        foreach ($Read->getResult() as $ImageRemove):
            if (file_exists("../uploads/{$ImageRemove['image']}") && !is_dir("../uploads/{$ImageRemove['image']}")):
                unlink("../uploads/{$ImageRemove['image']}");
            endif;
        endforeach;
    endif;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-pagebreak">Páginas</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Páginas
        </p>
    </div>

    <div class="dashboard_header_search">
        <span title="Ordenar" class="btn_header btn_aquablue icon-spinner9 wc_drag_active" title="Organizar Cursos">Ordenar</span>
        <a title="Nova Página" href="dashboard.php?wc=pages/create" class="btn_header btn_darkaquablue icon-plus">Nova Página</a>
    </div>

</header>
<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Paginator = new Pager('dashboard.php?wc=pages/home&pg=', '<<', '>>', 5);
    $Paginator->ExePager($Page, 12);

    $Read->ExeRead(DB_PAGES, "ORDER BY page_order ASC, page_title ASC, page_date DESC LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}");
    if (!$Read->getResult()):
        $Paginator->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Páginas Cadastradas. Comece Agora Mesmo Criando Sua Primeira Página!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $PAGE):
            extract($PAGE);
            $page_status = ($page_status == 1 ? '<span class="icon-checkmark font_aquablue">Publicada</span>' : '<span class="icon-warning font_yellow">Rascunho</span>');
            $page_cover = (!empty($page_cover) ? BASE . "/tim.php?src=uploads/{$page_cover}&w=" . IMAGE_W / 4 . "&h=" . IMAGE_H / 4 . "" : "");

            echo "<article class='box box25 page_single wc_draganddrop js-rel-to' callback='Pages' callback_action='pages_order' id='{$page_id}'>
                <a title='Ver Página No Site' target='_blank' href='" . BASE . "/{$page_name}'><img alt='' title='' src='{$page_cover}'/></a>
                <div class='box_content wc_normalize_height'>
                    <h1 class='title'><a title='Ver Página No Site' target='_blank' href='" . BASE . "/{$page_name}'>/{$page_title}</a></h1>
                    <p>{$page_status}</p>
                </div>
                <div class='page_single_action'>
                    <a title='Editar Página' href='dashboard.php?wc=pages/create&id={$page_id}' class='post_single_center icon-pencil icon-notext btn_header btn_darkaquablue'></a>
                    <span title='Excluir Página' rel='page_single' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Pages' callback_action='delete' id='{$page_id}'></span>
                </div>
            </article>";
        endforeach;

        $Paginator->ExePaginator(DB_PAGES);
        echo $Paginator->getPaginator();
    endif;
    ?>
</div>