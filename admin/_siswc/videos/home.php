<?php
$AdminLevel = LEVEL_WC_VIDEOS;
if (!APP_VIDEOS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;


// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

$S = filter_input(INPUT_GET, "s", FILTER_DEFAULT);
$T = filter_input(INPUT_GET, "tag", FILTER_DEFAULT);

$Search = filter_input_array(INPUT_POST);
if ($Search && (isset($Search['s']) || isset($Search['status']))):
    $S = (isset($Search['s']) ? urlencode($Search['s']) : $S);
    $SearchCat = (!empty($Search['searchcat']) ? $Search['searchcat'] : null);
    header("Location: dashboard.php?wc=videos/home&s={$S}&cat={$SearchCat}&tag={$T}");
    exit;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-youtube">Vídeos<?= ($T ? " em {$T}" : ""); ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="Todos os Vídeos" href="dashboard.php?wc=videos/home">Vídeos</a>
            <?= ($S ? "<span class='crumb'>/</span> <span class='icon-search'>{$S}</span>" : ''); ?>
        </p>
    </div>

    <div class="dashboard_header_search">

        <form style="width: 100%; display: inline-block;" name="searchCategoriesPost" action="" method="post" enctype="multipart/form-data" class="ajax_off">
            <input type="search" value="<?= $S; ?>" name="s" placeholder="Pesquisar:" style="width: 38%; margin-right: 3px;">
            <button title="Pesquisar" class="btn_header btn_darkaquablue icon icon-search icon-notext"></button>
        </form>        
    </div>
</header>

<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Paginator = new Pager("dashboard.php?wc=videos/home&s={$S}&tag={$T}&pg=", '<<', '>>', 5);
    $Paginator->ExePager($Page, 12);

    if (!empty($T)):
        $WhereTag[0] = "AND videos_tags LIKE '%' :tag '%'";
        $WhereTag[1] = "&tag={$T}";
    else:
        $WhereTag[0] = "";
        $WhereTag[1] = "";
    endif;
    
    if (!empty($S)):
        $WhereString[0] = "AND (videos_title LIKE '%' :s '%' OR videos_content LIKE '%' :s '%')";
        $WhereString[1] = "&s={$S}";
    else:
        $WhereString[0] = "";
        $WhereString[1] = "";
    endif;

    $Read->FullRead("SELECT * FROM " . DB_GALLERY_VIDEOS . " WHERE 1=1 "
            . "{$WhereTag[0]} "
            . "{$WhereString[0]} "
            . "ORDER BY videos_status ASC, videos_date DESC "
            . "LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}{$WhereTag[1]}{$WhereString[1]}"
    );
            
    if (!$Read->getResult()):
        $Paginator->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Vídeos Cadastrados. Comece Agora Mesmo Cadastrando Seu Primeiro Vídeo!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $VIDEO):
            extract($VIDEO);

            $VideosCover = (file_exists("../uploads/{$videos_cover}") && !is_dir("../uploads/{$videos_cover}") ? "uploads/{$videos_cover}" : 'admin/_img/no_image.jpg');
            $VideoStatus = ($videos_status == 1 && strtotime($videos_date) >= strtotime(date('Y-m-d H:i:s')) ? '<span class="btn_header btn_aquablue icon-clock icon-notext wc_tooltip"><span class="wc_tooltip_baloon">Agendado</span></span>' : ($videos_status == 1 ? '<span class="btn_header btn_green icon-checkmark icon-notext wc_tooltip"><span class="wc_tooltip_baloon">Publicado</span></span>' : '<span class="btn_header btn_yellow icon-warning icon-notext wc_tooltip"><span class="wc_tooltip_baloon">Pendente</span></span>'));
            $videos_title = (!empty($videos_title) ? $videos_title : 'Edite Esse Rascunho Para Poder Exibir Como Vídeo Em Seu Site!');

            $videoTags = null;
            if ($videos_tags):
                foreach (explode(",", $videos_tags) AS $tags):
                    $tag = ltrim(rtrim($tags));
                    $videoTags .= "<a class='icon-price-tag radius' title='Vídeos Marcados Com {$tag}' href='dashboard.php?wc=videos/home&s={$S}&tag=" . urlencode($tag) . "'>{$tag}</a>";
                endforeach;
            endif;

            echo "<article class='box box25 post_single js-rel-to' id='{$videos_id}'>           
                <div class='post_single_cover'>
                    <a title='Ver vídeo no site' target='_blank' href='" . BASE . "/videos/{$videos_name}'><img alt='{$videos_title}' title='{$videos_title}' src='../tim.php?src={$VideosCover}&w=" . IMAGE_W / 2 . "&h=" . IMAGE_H / 2 . "'/></a>
                    <div class='post_single_status'>
                        <span class='btn_header wc_tooltip'>" . str_pad($videos_views, 4, 0, STR_PAD_LEFT) . " <span class='wc_tooltip_baloon'>Visualizações</span></span>
                        {$VideoStatus}
                    </div>
                </div>
                <div class='post_single_content wc_normalize_height'>
                    <h1 class='title'><a title='Ver Vídeo No Site' target='_blank' href='" . BASE . "/videos/{$videos_name}'>{$videos_title}</a></h1>
                </div>
                <div class='post_single_actions'>
                    <a title='Editar Vídeo' href='dashboard.php?wc=videos/create&id={$videos_id}' class='post_single_center icon-pencil icon-notext btn_header btn_darkaquablue'></a>
                    <span title='Excluir Vídeo' rel='post_single' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Videos' callback_action='delete' id='{$videos_id}'></span>
                </div>
            </article>";
        endforeach;

        $Paginator->ExePaginator(DB_GALLERY_VIDEOS, "WHERE "
                . " (FIND_IN_SET(:tag, videos_tags) OR :tag = '') "
                . "AND (videos_title LIKE '%' :s '%' OR videos_content LIKE '%' :s '%')", "tag={$T}&s={$S}"
        );
        echo $Paginator->getPaginator();
    endif;
    ?>
</div>