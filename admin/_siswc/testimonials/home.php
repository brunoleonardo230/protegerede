<?php
$AdminLevel = LEVEL_WC_TESTIMONIALS;
if (!APP_TESTIMONIALS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;
?>
<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-bubbles3">Depoimentos</h1>
        <p class="dashboard_header_breadcrumbs">
            <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="Depoimentos" href="dashboard.php?wc=testimonials/home">Depoimentos</a>
            <span class="crumb">/</span>
            &raquo; Gerencie os Depoimentos
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Novo Depoimento" class="btn_header btn_darkaquablue icon-plus add"> Novo Depoimento</a>
    </div>
</header>
<div class="dashboard_content">
    <!--CADASTRO-->
    <div class="refs box box100" id="cadastro">  
        <div class="panel_header darkaquablue">
            <h2 class="icon-bubbles3">Dados Sobre o Depoimento</h2>
        </div>
        <div class="panel">
            <span class="icon-cross icon-notext add close"></span>
            <div class="box box25">
                <div class="thumb_controll">
                    <?php
                    $TestimonialCover = (!empty($testimonial_image) && file_exists("../uploads/{$testimonial_image}") && !is_dir("../uploads/{$testimonial_image}") ? "../tim.php?src=uploads/" . $testimonial_image . "&w=" . AVATAR_W . "&h=" . AVATAR_H . "']" : (!empty($fb_review_id) ? 'https://graph.facebook.com/' . $fb_review_id . '/picture?type=large' : "admin/_img/no_image.jpg"));
                    ?>
                    <img class="testimonial_image" alt="Capa" title="Capa" src="<?= $TestimonialCover; ?>" default="../tim.php?src=admin/_img/no_image.jpg&w=<?= AVATAR_W; ?>&h=<?= AVATAR_H; ?>"/>
                </div>
            </div><div class="box box70">
                <form name="testimonial_add" class="j_testimonials" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="callback" value="Testimonials"/>
                    <input type="hidden" name="callback_action" value="manage"/>
                    <input type="hidden" name="testimonial_id" value=""/>
                    <label class="label">
                        <span class="legend">Foto:</span>
                        <input type="file" class="wc_loadimage" name="testimonial_image"/>
                    </label>
                    <div class="label_30">
                        <label class="label">
                            <span class="legend">Nome:</span>
                            <input type="text" name="testimonial_name" value="" placeholder="Informe o Título" required/>
                        </label>
                        <label class="label">
                            <span class="legend">Cargo:</span>
                            <input type="text" name="testimonial_cargo" value="" placeholder="Informe o Cargo" required/>
                        </label>
                        <label class="label">
                            <span class="legend">Headline:</span>
                            <input type="text" name="testimonial_headline" placeholder="Informe a Headline" value="" />
                        </label>
                        <label class="label">
                            <span class="legend">Depoimento:</span>
                            <textarea rows="10" name="testimonial_depoiment" value="" required></textarea>
                        </label>
                    </div>

                    <div class="wc_actions" style="text-align: right;">
                        <button title="ATUALIZAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ATUALIZAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                        
                    </div>
                </form>
            </div>
        </div>
        <div class="panel_footer_external"></div>
    </div>

    <!--LIST-->
    <div class="refs box box100" id="base">
        <?php
        $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
        $Page = ($getPage ? $getPage : 1);
        $Paginator = new Pager('dashboard.php?wc=testimonials/home&pg=', '<<', '>>', 5);
        $Paginator->ExePager($Page, 12);

        $Read->ExeRead(DB_TESTIMONIALS, "ORDER BY testimonial_date DESC LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}");
        if (!$Read->getResult()):
            $Paginator->ReturnPage();
            echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Depoimentos Cadastrados . Comece Agora Mesmo Cadastrando Seu Primeiro Depoimento!</span>", E_USER_NOTICE);
        else:
            foreach ($Read->getResult() as $Testi):
                $TestimonialCover = ($Testi['testimonial_image'] ? "../tim.php?src=uploads/" . $Testi['testimonial_image'] . "&w=300&h=300']" : ($Testi['fb_review_id'] ? 'https://graph.facebook.com/' . $Testi['fb_review_id'] . '/picture?type=large' : "admin/_img/no_image.jpg"));
                $Type = ($Testi['testimonial_type'] == 1 ? '<span>MANUAL</span>' : ($Testi['testimonial_type'] == 2 ? '<span style="color:#3b5998">FACEBOOK</span>' : null));
                echo"<article class='box box25 post_single js-rel-to' id='{$Testi['testimonial_id']}'>
                        <header class='wc_normalize_height'>
                        <img alt='[{$Testi['testimonial_name']}]' title='{$Testi['testimonial_name']}' style='width: 100%' src='{$TestimonialCover}'/>
                             <div class='info'>
                                <p class='icon-bubbles3'><b>Cliente:</b> {$Testi['testimonial_name']}</p>   
                                <p class='icon-cog'><b>Origem:</b> {$Type}</p>                        
                            </div>
                        </header>
                        <footer class='al_center'>
                            <span title='Editar Depoimento' class='btn_header btn_darkaquablue icon-pencil icon-notext jbs_action' cc='Testimonials' ca='edit' rel='{$Testi['testimonial_id']}'></span>
                            <span title='Excluir Depoimento' rel='post_single' callback='Testimonials' callback_action='delete' class='j_delete_action icon-bin icon-notext btn_header btn_red' id='{$Testi['testimonial_id']}'></span>
                        </footer>              
                    </article>";

            endforeach;
            $Paginator->ExePaginator(DB_TESTIMONIALS);
            echo $Paginator->getPaginator();
        endif;
        ?>
    </div>
</div>