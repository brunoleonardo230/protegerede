<?php
$AdminLevel = LEVEL_WC_TUTORIAIS;
if (!APP_TUTORIAIS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;


// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

$S = filter_input(INPUT_GET, "s", FILTER_DEFAULT);
$C = filter_input(INPUT_GET, "cat", FILTER_DEFAULT);
$T = filter_input(INPUT_GET, "tag", FILTER_DEFAULT);

$Search = filter_input_array(INPUT_POST);
if ($Search && (isset($Search['s']) || isset($Search['status']))):
    $S = (isset($Search['s']) ? urlencode($Search['s']) : $S);
    $SearchCat = (!empty($Search['searchcat']) ? $Search['searchcat'] : null);
    header("Location: dashboard.php?wc=tutoriais/home&s={$S}&cat={$SearchCat}&tag={$T}");
    exit;
endif;
 
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-film">Tutoriais do <?= SITE_NAME; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="Tutoriais" href="dashboard.php?wc=tutoriais/home">Tutoriais</a>
            <?= ($S ? "<span class='crumb'>/</span> <span class='icon-search'>{$S}</span>" : ''); ?>
        </p>
    </div>

    <div class="dashboard_header_search">

        <form style="width: 100%; display: inline-block;" name="searchCategoriesPost" action="" method="post" enctype="multipart/form-data" class="ajax_off">
            <input type="search" value="<?= $S; ?>" name="s" placeholder="Pesquisar:" style="width: 38%; margin-right: 3px;">
            <select name="searchcat" style="width: 45%; margin-right: 3px; padding: 5px 10px">
                <option value="">Todos</option>
                <?php
                  echo "<option " . ($C == 1 ? "selected='selected'" : null) . " value='1'> &raquo; Tutoriais do Site</option>";
                  echo "<option " . ($C == 2 ? "selected='selected'" : null) . " value='2'> &raquo; Tutoriais do Sistema</option>";   
                  echo "<option " . ($C == 3 ? "selected='selected'" : null) . " value='3'> &raquo; Tutoriais de Configuração</option>";
                  echo "<option " . ($C == 4 ? "selected='selected'" : null) . " value='4'> &raquo; Outros Tutoriais</option>";
                ?>
            </select>
            <button title='Pesquisar' class="btn_header btn_darkaquablue icon-search icon-notext"></button>
            <a href="#" title='Novo Tutorial' class="btn_header btn_aquablue icon-plus m_top j_create_modal" data-modal=".js-tutorial">Novo Tutorial </a>
        </form> 
    </div>
</header>

<div class="dashboard_content" id="tutorial">
    <?php
    $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Paginator = new Pager("dashboard.php?wc=tutoriais/home&s={$S}&cat={$C}&tag={$T}&pg=", '<<', '>>', 5);
    $Paginator->ExePager($Page, 12);

    if (!empty($C)):
       
        $WhereCat[0] = "AND tutorial_type = :cat";
        $WhereCat[1] = "&cat={$C}";
    else:
        $WhereCat[0] = "";
        $WhereCat[1] = "";
       
    endif;
 
    if (!empty($S)):
        $WhereString[0] = "AND (tutorial_title LIKE '%' :s '%' OR tutorial_content LIKE '%' :s '%')";
        $WhereString[1] = "&s={$S}";
    else:
        $WhereString[0] = "";
        $WhereString[1] = "";
    endif;

    $Read->FullRead("SELECT * FROM " . DB_TUTORIAIS . " WHERE 1=1 "
            . "{$WhereCat[0]} "           
            . "{$WhereString[0]} "
            . "ORDER BY tutorial_status ASC, tutorial_date DESC "
            . "LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}{$WhereCat[1]}{$WhereString[1]}"
    );
            
    if (!$Read->getResult()):
        $Paginator->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Tutoriais Cadastrados. Comece Agora Mesmo Criando Seu Primeiro Tutorial!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $TUTORIAL):
            extract($TUTORIAL);

            $title = (!empty($tutorial_title) ? $tutorial_title : 'Edite Esse Rascunho Para Ativar o Tutorial');

            $TutorialTags = null;   
            $Category = null;
            
            foreach (getTutorialCat() as $TipoId => $TipoValue):
                if($tutorial_type == $TipoId):
                    $TypeName = $TipoValue;
                endif;    
            endforeach;

            echo "<article class='box box25 post_single js-rel-to' id='{$tutorial_id}'>   
                <div class='post_single_cover'>
                    <div class='embed-container'>
                    <iframe id='mediaview' width='640' height='360' src='https://www.youtube.com/embed/{$tutorial_video}?rel=0&amp;showinfo=0&autoplay=0&origin=" . BASE . "' frameborder='0' allowfullscreen></iframe>
                    </div>
                </div>
                <div class='post_single_content wc_normalize_height'>
                    <h1 class='title'>{$tutorial_title}</h1>
                    <p class='post_single_cat'>{$TypeName}</p>
                </div>
                <div class='post_single_actions'>
                    <span title='Assistir Tutorial' class='post_single_center icon-play icon-notext btn_header btn_aquablue j_video_modal' callback='Tutoriais' id='{$tutorial_id}'></span>
                    <span title='Editar Tutorial' class='btn_header btn_darkaquablue icon-notext icon-pencil j_edit_modal' callback='Tutoriais' callback_action='edit' id='{$tutorial_id}'></span>
                    <span title='Excluir Tutorial' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Tutoriais' callback_action='delete' id='{$tutorial_id}'></span>
                </div>
            </article>";
        endforeach;
        $Paginator->ExeFullPaginator("SELECT * FROM " . DB_TUTORIAIS . " WHERE 1=1 {$WhereCat[0]} {$WhereString[0]} ORDER BY tutorial_status ASC, tutorial_date DESC ", "{$WhereCat[1]}{$WhereString[1]}");
       
        echo $Paginator->getPaginator();
    endif;
    ?>
</div>

<!-- MODAL CADASTRO DO TUTORIAL -->
<div class="bs_ajax_modal js-tutorial" style="display: none;">
    <div class="bs_ajax_modal_box">
        <p class="bs_ajax_modal_title aquablue"><span class="icon-film">Cadastrar Tutorial</span></p>
        <span title="Fechar" class="bs_ajax_modal_close icon-cross icon-notext j_close_modal" data-modal=".js-tutorial"></span>
        <div class="bs_ajax_modal_content scrollbar">
            <form name="tutorial_create_modal" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Tutoriais"/>
                <input type="hidden" name="callback_action" value="manager"/>
                <input type="hidden" name="tutorial_id" value=""/>
                
                <div class="label_100">
                    <label class="label">
                        <span class="legend">Título:</span>
                        <input type="text" name="tutorial_title" placeholder="Informe o Título do Tutorial" value="" required/>
                    </label>
                    
                    <label class="label">
                        <span class="legend">Vídeo:</span>
                        <input type="text" name="tutorial_video" value="" placeholder="Informe o Slug do Vídeo" required/>
                    </label>
                    
                    <label class="label">
                        <span class="legend">Categoria:</span>
                        <select name="tutorial_type" required="">
                            <option value="">Selecione Uma Categoria:</option>
                            <?php
                            foreach (getTutorialCat() as $TutorialId => $TutorialName):
                                echo "<option " . ($tutorial_type == $TutorialId ? "selected='selected'" : null) . " value='{$TutorialId}'>{$TutorialName}</option>";
                            endforeach;
                            ?>
                        </select>
                    </label>
                    
                    <label class="label">
                        <span class="legend">Descrição:</span>
                        <textarea class="work_mce_basic" rows="10" name="tutorial_content"></textarea>
                    </label>
                </div>    
                    
                <div class="wc_actions" style="text-align: right">
                    <button title="ENVIAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ENVIAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                </div>
            </form>
        </div>
                
        <div class="bs_ajax_modal_footer">
            <p>Cadastre Seu Tutorial e Assista Sempre Que Tiver Dúvidas!</p>
        </div>    
        <div class="clear"></div>
    </div>
</div>

<!-- MODAL VÍDEO -->
<div class="bs_ajax_modal js-video" style="display: none;">
    <div class="bs_ajax_modal_box_video">
        <p class="bs_ajax_modal_title aquablue"><span class="icon-film">Assistir Tutorial</span></p>
        <span title="Fechar" class="bs_ajax_modal_close icon-cross icon-notext j_close_modal_video"></span>
            <div class="bs_ajax_modal_content_video scrollbar" style="padding: 0;">
                <div class="embed-container"></div>
            </div>    
        <div class="bs_ajax_modal_footer">
            <p>Assista Agora Seu Tutorial e Tire Suas Dúvidas!</p>
        </div>    
        <div class="clear"></div>
    </div>
</div>