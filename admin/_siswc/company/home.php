<?php
$AdminLevel = LEVEL_WC_COMPANY;
if (!APP_COMPANY || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-office">A Empresa</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="Todas as Empresas" href="dashboard.php?wc=company/home">A Empresa</a>
        </p>
    </div>
    
    <div class="dashboard_header_search">
        <a title="Nova Filial" href="dashboard.php?wc=company/create" class="btn_header btn_darkaquablue icon-plus">Nova Filial</a>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Paginator = new Pager("dashboard.php?wc=company/home&pg=", '<<', '>>', 5);
    $Paginator->ExePager($Page, 12);

    $Read->FullRead("SELECT * FROM " . DB_COMPANY . " WHERE 1=1 "
            . "ORDER BY company_status ASC, company_datecreated DESC "
            . "LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}");
            
    if (!$Read->getResult()):
        $Paginator->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existe Empresa Cadastrada. Comece Agora Mesmo Cadastrando Sua Primeira Empresa!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $COMPANY):
            extract($COMPANY);

            $CompanyImage = (file_exists("../uploads/{$company_image}") && !is_dir("../uploads/{$company_image}") ? "uploads/{$company_image}" : 'admin/_img/no_image.jpg');
            $company_title = (!empty($company_title) ? $company_title : 'Edite Esse Rascunho Para Exibir as Informações da Empresa!');

            echo "<article class='box box25 post_single js-rel-to' id='{$company_id}'>           
                <div class='post_single_cover'>
                    <img alt='{$company_title}' title='{$company_title}' src='../tim.php?src={$CompanyImage}&w=" . IMAGE_W / 2 . "&h=" . IMAGE_H / 2 . "'/>
                </div>
                <div class='post_single_content wc_normalize_height'>
                    <h1 class='title icon-office'>{$company_title}</h1>
                    <p class='post_single_cat icon-location'>{$company_street} - {$company_district} - {$company_city} / {$company_state}</p>
                </div>
                <div class='post_single_actions'>
                    <a title='Editar Empresa' href='dashboard.php?wc=company/create&id={$company_id}' class='post_single_center icon-pencil icon-notext btn_header btn_darkaquablue'></a>
                    <span title='Excluir Empresa' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Company' callback_action='delete' id='{$company_id}'></span>
                </div>
            </article>";
        endforeach;

        $Paginator->ExePaginator(DB_COMPANY); 
        echo $Paginator->getPaginator();
    endif;
    ?>
</div>