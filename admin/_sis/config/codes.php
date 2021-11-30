<?php
$AdminLevel = LEVEL_WC_CONFIG_CODES;
if (empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-cog">Work Control Codes</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=config/home">Configurações</a>
            <span class="crumb">/</span>
            Codes
        </p>
    </div>

    <div class="dashboard_header_search">
        <a href="#" title="Cadastrar WC CODE" class="btn btn_aquablue radius icon-plus j_open_modal" data-modal=".js-code">Cadastrar WC CODE</a>
    </div>
</header>

<div class="bs_ajax_modal js-code" style="display: none;">
        <div class="bs_ajax_modal_box">
            <p class="bs_ajax_modal_title aquablue"><span class="icon-tab">Novo Code Work Control</span></p>
            <span class="bs_ajax_modal_close icon-cross icon-notext j_close_modal" data-modal=".js-code"></span>
            <div class="bs_ajax_modal_content scrollbar">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="callback" value="Codes"/>
                    <input type="hidden" name="callback_action" value="workcodes"/>
                    <input type="hidden" name="code_id" value=""/>
        
                    <div class="label_100">
                        <label class="label">
                            <span class="legend">Título de Identificação: (Ex: Google Analitycs)</span>
                            <input type="text" name="code_name" placeholder="Título:" required/>
                        </label>
                        <label class="label">
                            <span class="legend">Carregar Somente Em: (Opcional)</span>
                            <input type="text" name="code_condition" placeholder="Caminho ou Caminho / Argumento:"/>
                        </label>
                        <label class="label">
                            <span class="legend">Script, Código do Pixel:</span>
                            <textarea required name="code_script" class="scrollbar" rows="8" placeholder="<script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            
                ga('create', 'UA-00000000000-1', 'auto');
                ga('send', 'pageview');
            </script>"></textarea>
                        </label>
                    </div>
        
                    <div class="wc_actions" style="text-align: right">
                        <button name="public" value="1" class="btn_big btn_aquablue icon-share">ENVIAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
                    </div>
                </form>
            </div>  
            
            <div class="bs_ajax_modal_footer">
                <p>Cadastre Seu WC Code</p>
            </div>    
            <div class="clear"></div>
        </div>
    </div>
</div>

<div class="dashboard_content">
    
    <?php
    $Read->ExeRead(DB_WC_CODE, "ORDER BY code_created DESC");
    if (!$Read->getResult()):
        echo Erro("<span class='al_center icon-notification'>Ainda Não Existem Codes Cadastrados {$Admin['user_name']}. Comece a Rastrear Seus Resultados Agora Cadastrando Seus Codes!</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $CODE):
            extract($CODE);
            ?>
            <article class="box box25" id="<?= $code_id; ?>">
                <div class="panel_header darkaquablue">
                    <h2 class="icon-cog"><?= $code_name; ?></h2>
                </div>
                <div class="box_content code_single al_center" id="<?= $code_id; ?>">
                    <div class="codes_loaded"><?= date('d/m/Y H:i:s', strtotime($code_created)); ?><span><?= str_pad($code_views, 5, 0, 0); ?></span><?= (!empty($code_condition) ? $code_condition : '√ Pixel Carregado Sempre e Em Todas as Páginas do Site!'); ?></div>
                </div>
                
                <div class='code_single_actions'>
                    <span title="Editar" class="btn btn_darkaquablue icon-pencil j_edit_modal" callback="Codes" id="<?= $code_id; ?>">Editar</span>
                    <span title="Excluir" rel="code_single" class="j_delete_action icon-notext icon-cancel-circle btn btn_red" callback="Codes" callback_action="delete" id="<?= $code_id; ?>"> Excluir</span>
                </div>
            </article>
            <?php
        endforeach;
    endif;
    ?>
</div>