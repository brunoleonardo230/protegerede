<?php
$AdminLevel = LEVEL_WC_FAQ;
if (!APP_FAQ || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;
?>
<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-info">Perguntas Frequentes</h1>
        <p class="dashboard_header_breadcrumbs">
            <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="Perguntas Frequentes" href="dashboard.php?wc=faq/home">FAQ</a>
            <span class="crumb">/</span>
            Perguntas Frequentes
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Nova Pergunta" class="btn_header btn_darkaquablue icon-plus icon-notext add"> Nova Pergunta</a>
    </div>
</header>
<div class="dashboard_content">
    <!--CADASTRO-->
    <div class="refs box box100" id="cadastro">
        <div class="panel_header darkaquablue">
            <h2 class="icon-info">Dados Sobre a Pergunta</h2>
        </div>  
        <div class="panel">
            <span class="icon-cross icon-notext add close"></span>
            <div class="box box100">
                <form name="faq_add" class="j_faq" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="callback" value="FAQ"/>
                    <input type="hidden" name="callback_action" value="manage"/>
                    <input type="hidden" name="faq_id" value=""/>
                    <div class="label_30">
                        <label class="label">
                            <span class="legend">Pergunta:</span>
                            <input type="text" name="faq_title" value="" placeholder="Informe a Pergunta" required/>
                        </label>
                        <label class="label">
                            <span class="legend">Resposta:</span>
                            <textarea name="faq_desc" value="" placeholder="Informe a Resposta da Pergunta" required></textarea>
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
        $Paginator = new Pager('dashboard.php?wc=faq/home&pg=', '<<', '>>', 5);
        $Paginator->ExePager($Page, 12);

        $Read->ExeRead(DB_FAQ, "ORDER BY faq_date DESC LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}");
        if (!$Read->getResult()):
            $Paginator->ReturnPage();
            echo Erro("<span class='al_center icon-notification'>Ainda Não Existem Perguntas Cadastradas {$Admin['user_name']}. Comece Agora Mesmo Cadastrando Sua Primeira Pergunta!</span>", E_USER_NOTICE);
        else:
            foreach ($Read->getResult() as $FAQ):
                extract($FAQ);
                echo"<article class='box box25 post_single js-rel-to' id='{$faq_id}'>
                        <header class='wc_normalize_height'>
                             <div class='info'>
                                <p class='icon-info'><b>Pergunta:</b> {$faq_title}</p>                     
                            </div>
                        </header>
                        <footer class='al_center'>
                            <span title='Editar Pergunta' class='btn_header btn_darkaquablue icon-pencil icon-notext jbs_action' cc='FAQ' ca='edit' rel='{$faq_id}'></span>
                            <span title='Excluir Pergunta' rel='post_single' callback='FAQ' callback_action='delete' class='j_delete_action icon-bin icon-notext btn_header btn_red' id='{$faq_id}'></span>
                        </footer>              
                    </article>";

            endforeach;
            $Paginator->ExePaginator(DB_FAQ);
            echo $Paginator->getPaginator();
        endif;
        ?>
    </div>
</div>