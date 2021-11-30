<?php
//SETA TIMEZONE
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$AdminLevel = LEVEL_WC_FBREVIEWS;
if (!APP_FBREVIEW || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;
?>
<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-facebook2">FB Reviews</h1>
        <p class="dashboard_header_breadcrumbs">
            <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="FB Reviews" href="dashboard.php?wc=fbreview/home">FB Reviews</a>
            <span class="crumb">/</span>
            &raquo; Gerenciar FB Reviews
        </p>
    </div>
</header>
<div class="dashboard_content">
    <div class="wc_api_new">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="FBReview"/>
            <input type="hidden" name="callback_action" value="reviews"/>
            <input required type="text" name="token" value="" placeholder="PAGE ACCESS TOKEN"/><button class="btn btn_darkaquablue icon-redo">Importar Reviews! <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
        </form>
        <p class="wc_api_new_info">( ! ) Informe o Page Access Token Gerado No <a href="https://developers.facebook.com/tools/explorer/" target="_blank">Explorador da Graph API</a></p>
    </div>
    <div id="error"></div>
    <?php
    $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Paginator = new Pager("dashboard.php?wc=fbreview/home&pg=", '<<', '>>', 5);
    $Paginator->ExePager($Page, 12);

    $Read->ExeRead(DB_TESTIMONIALS, "WHERE testimonial_type = 2 ORDER BY testimonial_date DESC LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}");
    if (!$Read->getResult()):
        Erro("<div style='text-align: center;' class='icon-notification'>Olá {$Admin['user_name']}, Você Ainda Não Importou Nenhum Review!</div>", E_USER_NOTICE);
    else:
        echo '<div class="wc_api_app_actions">
        <span callback="FBReview" callback_action="delete" class="j_delete_action_confirm icon-cancel-circle btn btn_red">Remover Todos os Reviews</span>
    </div>';
        foreach ($Read->getResult() as $Review):
            extract($Review);
            $Rank = str_repeat("&starf;", intval($testimonial_rating)) . str_repeat("&star;", 5 - intval($testimonial_rating));
            ?>
            <article class="box box25 post_single" id="<?= $fb_review_id; ?>">
                <div class='post_single_cover'>
                    <img src="https://graph.facebook.com/<?= $fb_review_id; ?>/picture?type=large" alt="<?= $review_name; ?>" width="100%"/>
                    <div class='post_single_status'>
                        <span class='btn wc_tooltip'><?= $Rank; ?><span class='wc_tooltip_baloon'><?= $testimonial_rating; ?> DE 5</span></span>
                    </div>
                </div>
                <header>
                    <h1 class="icon-user"><?= $testimonial_name; ?></h1>
                    <p class='post_single_cat'>Review Feito Em: <?= utf8_encode(strftime('%d de %B de %Y', strtotime($testimonial_date))); ?></p>
                </header>
            </article>
            <?php
        endforeach;
        
        $Paginator->ExePaginator(DB_TESTIMONIALS);
        echo $Paginator->getPaginator();
    endif;
    ?>
</div>