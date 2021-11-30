<?php
$AdminLevel = LEVEL_WC_SERVICES;
if (!APP_SERVICES || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-briefcase">Serviços</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="Todos os Serviços" href="dashboard.php?wc=servicos/home">Serviços</a>
        </p>
    </div>
    
    <div class="dashboard_header_search">
        <a title="Novo Serviço" href="dashboard.php?wc=servicos/create" class="btn_header btn_darkaquablue icon-plus">Novo Serviço</a>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Paginator = new Pager("dashboard.php?wc=servicos/home&pg=", '<<', '>>', 5);
    $Paginator->ExePager($Page, 12);

    $Read->FullRead("SELECT * FROM " . DB_SERVICES . " WHERE 1=1 "
            . "ORDER BY service_status ASC, service_datecreate DESC "
            . "LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}");
            
    if (!$Read->getResult()):
        $Paginator->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existe Serviços Cadastrados. Comece Agora Mesmo Cadastrando Seu Primeiro Serviço!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $SERVICES):
            extract($SERVICES);

            $ServicesImage = (file_exists("../uploads/{$service_image}") && !is_dir("../uploads/{$service_image}") ? "uploads/{$service_image}" : 'admin/_img/no_image.jpg');
            $service_title = (!empty($service_title) ? $service_title : 'Edite Esse Rascunho Para Exibir as Informações do Serviço!');

            echo "<article class='box box25 post_single js-rel-to' id='{$service_id}'>           
                <div class='post_single_cover'>
                    <img alt='{$service_title}' title='{$service_title}' src='../tim.php?src={$ServicesImage}&w=" . IMAGE_W / 2 . "&h=" . IMAGE_H / 2 . "'/>
                </div>
                <div class='post_single_content wc_normalize_height'>
                    <h1 class='title icon-briefcase'>{$service_title}</h1>
                    <p class='post_single_cat icon-pencil'>" . Check::Words($service_content, 15) . "</p>
                </div>
                <div class='post_single_actions'>
                    <a title='Editar Serviço' href='dashboard.php?wc=servicos/create&id={$service_id}' class='post_single_center icon-pencil icon-notext btn_header btn_darkaquablue'></a>
                    <span title='Excluir Serviço' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Services' callback_action='delete' id='{$service_id}'></span>
                </div>
            </article>";
        endforeach;

        $Paginator->ExePaginator(DB_SERVICES); 
        echo $Paginator->getPaginator();
    endif;
    ?>
</div>