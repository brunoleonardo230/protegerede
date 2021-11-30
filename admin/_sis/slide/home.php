<?php
$AdminLevel = LEVEL_WC_SLIDES;
if (!APP_SLIDE || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

//AUTO DELETE POST TRASH
if (DB_AUTO_TRASH):
    $Delete = new Delete;
    $Delete->ExeDelete(DB_SLIDES, "WHERE slide_image_desktop IS NULL AND slide_title IS NULL AND slide_id >= :st", "st=1");
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-images">Slides Ativos</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=slide/home">Slides</a>
            <span class="crumb">/</span>
            Slides Ativos
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Novo Slide" href="dashboard.php?wc=slide/create" class="btn_header btn_darkaquablue icon-plus">Novo Slide</a>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 0);
    $Pager = new Pager('dashboard.php?wc=slide/home&page=', "<<", ">>", 3);
    $Pager->ExePager($Page, 5);
    $Read->ExeRead(DB_SLIDES, "WHERE slide_status = 1 AND slide_start <= NOW() AND (slide_end >= NOW() OR slide_end IS NULL) ORDER BY slide_date DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
    if (!$Read->getResult()):
        $Pager->ReturnPage();
        Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Slides Cadastrados Em Seu Site. Comece Cadastrando o Primeiro Slide!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $Slide):
            extract($Slide);
            echo "<article class='box box100 slide_single js-rel-to' id='{$slide_id}'>
                    <div class='panel_header darkaquablue'>
                        <h2 class='icon-images'>{$slide_title}</h2>
                    </div>  
                    <div class='box_content'>
                    <img src='" . BASE . "/tim.php?src=uploads/{$slide_image_desktop}&w=" . SLIDE_W . "&h=" . SLIDE_H . "' title='{$slide_title}' alt='{$slide_title}'>
                    <p style='font-size: 1.2em; margin: 10px 0 20px 0; color: #555;'><b>de " . date('d/m/Y H\hi', strtotime($slide_start)) . " - " . ($slide_end ? date('d/m/Y H\hi', strtotime($slide_end)) : 'Sempre') . ":</b> {$slide_desc}</p>
                    <a title='Editar Slide' href='dashboard.php?wc=slide/create&id={$slide_id}' class='icon-pencil icon-notext btn_header btn_darkaquablue'></a>
                    <span title='Excluir Slide' rel='slide_single' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Slides' callback_action='delete' id='{$slide_id}'></span>
                    </div>
                </article>";
        endforeach;

        $Pager->ExePaginator(DB_SLIDES, "WHERE slide_start <= NOW() AND (slide_end >= NOW() OR slide_end IS NULL)");
        echo $Pager->getPaginator();

    endif;
    ?>
</div>