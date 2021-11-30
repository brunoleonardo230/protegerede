<?php
$AdminLevel = LEVEL_WC_SPECIALTIES;
if (!APP_SPECIALTIES || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-lab">Especialidades</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="Todas as Especialidades" href="dashboard.php?wc=especialidades/home">Especialidades</a>
        </p>
    </div>
    
    <div class="dashboard_header_search">
        <a title="Nova Especialidade" href="dashboard.php?wc=especialidades/create" class="btn_header btn_darkaquablue icon-plus">Nova Especialidade</a>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Paginator = new Pager("dashboard.php?wc=especialidades/home&pg=", '<<', '>>', 5);
    $Paginator->ExePager($Page, 12);

    $Read->FullRead("SELECT * FROM " . DB_SPECIALTIES . " WHERE 1=1 "
            . "ORDER BY specialtie_status ASC, specialtie_datecreate DESC "
            . "LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}");
            
    if (!$Read->getResult()):
        $Paginator->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existe Especialidades Cadastradas. Comece Agora Mesmo Cadastrando Sua Primeira Especialidade!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $SPECIALTIE):
            extract($SPECIALTIE);

            $SpecialtieImage = (file_exists("../uploads/{$specialtie_image}") && !is_dir("../uploads/{$specialtie_image}") ? "uploads/{$specialtie_image}" : 'admin/_img/no_image.jpg');
            $specialtie_title = (!empty($specialtie_title) ? $specialtie_title : 'Edite Esse Rascunho Para Exibir as Informações da Especialidade!');

            echo "<article class='box box25 post_single js-rel-to' id='{$specialtie_id}'>           
                <div class='post_single_cover'>
                    <img alt='{$specialtie_title}' title='{$specialtie_title}' src='../tim.php?src={$SpecialtieImage}&w=" . IMAGE_W / 2 . "&h=" . IMAGE_H / 2 . "'/>
                </div>
                <div class='post_single_content wc_normalize_height'>
                    <h1 class='title icon-lab'>{$specialtie_title}</h1>
                    <p class='post_single_cat icon-pencil'>" . Check::Words($specialtie_content, 15) . "</p>
                </div>
                <div class='post_single_actions'>
                    <a title='Editar Especialidade' href='dashboard.php?wc=especialidades/create&id={$specialtie_id}' class='post_single_center icon-pencil icon-notext btn_header btn_darkaquablue'></a>
                    <span title='Excluir Especialidade' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Specialties' callback_action='delete' id='{$specialtie_id}'></span>
                </div>
            </article>";
        endforeach;

        $Paginator->ExePaginator(DB_SPECIALTIES); 
        echo $Paginator->getPaginator();
    endif;
    ?>
</div>