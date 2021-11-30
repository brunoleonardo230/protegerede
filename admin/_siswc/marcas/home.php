<?php
$AdminLevel = LEVEL_WC_BRANDS;
if (!APP_BRANDS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você Não Está Logado<br>Ou Não Tem Permissão Para Acessar Essa Página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;
?>

<header class="dashboard_header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="dashboard_header_title">
        <h1 class="icon-heart">Marcas Parceiras</h1>
        <p class="dashboard_header_breadcrumbs">
            <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Gerencie as Marcas Parceiras
        </p>
    </div>

    <div class="dashboard_header_search">
        <a class="btn_header btn_darkaquablue icon-plus add"> Nova Marca Parceira</a>
    </div>
</header>
<div class="dashboard_content">
    <!--CADASTRO-->
    <div class="refs box box100" id="cadastro">  
        <div class="panel">
            <span class="icon-cross icon-notext add close"></span>
            <div class="box box25">
                <div class="thumb_controll">
                    <?php
                    $BrandImage = (!empty($brand_image) && file_exists("../uploads/{$brand_image}") && !is_dir("../uploads/{$brand_image}") ? "../tim.php?src=uploads/" . $brand_image . "&w=300&h=100']" : "admin/_img/no_image.jpg");
                    ?>
                    <img class="brand_image" alt="<?= $brand_image; ?>" title="<?= $brand_image; ?>" src="<?= $BrandImage; ?>" default="../tim.php?src=admin/_img/no_image.jpg&w=300&h=100"/>
                </div>
            </div>
            
            <div class="box box70">
                <form name="brand_add" class="j_brands" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="callback" value="Brands"/>
                    <input type="hidden" name="callback_action" value="manager"/>
                    <input type="hidden" name="brand_id" value=""/>
                    <label class="label">
                        <span class="legend">Logotipo:</span>
                        <input type="file" class="wc_loadimage" name="brand_image"/>
                    </label>
                    <div class="label_30">
                        <label class="label">
                            <span class="legend">Nome da Marca:</span>
                            <input type="text" name="brand_name" value="" placeholder="Informe o Nome da Marca" required/>
                        </label>
                        
                        <label class="label">
                            <span class="legend">Site da Marca:</span>
                            <input type="text" name="brand_site" value="" placeholder="Informe o Site da Marca"/>
                        </label>
                    </div>

                    <div class="wc_actions" style="text-align: right">
                        <button title="ENVIAR" name="public" value="1" class="btn_big btn_aquablue icon-share">ENVIAR <img class="form_load none" style="margin-left: 6px; margin-bottom: 9px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.svg"/></button>
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
        $Paginator = new Pager('dashboard.php?wc=brands/home&pg=', '<<', '>>', 5);
        $Paginator->ExePager($Page, 12);

        $Read->ExeRead(DB_BRANDS, "ORDER BY brand_datecreate DESC LIMIT :limit OFFSET :offset", "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}");
        if (!$Read->getResult()):
            $Paginator->ReturnPage();
            echo Erro("<span class='al_center icon-notification'>Olá {$Admin['user_name']}, Ainda Não Existem Marcas Parceiras Cadastradas. Comece Agora Mesmo Cadastrando a Primeira Marca Parceira!</span>", E_USER_NOTICE);
        else:
            foreach ($Read->getResult() as $Brands):
                extract($Brands);
                
                $BrandImage = ($brand_image ? "../tim.php?src=uploads/" . $brand_image . "&w=300&h=100']" : "admin/_img/no_image.jpg");
                
                echo"<article class='box box25 post_single js-rel-to' id='{$brand_id}'> <header class='wc_normalize_height'> <img alt='{$brand_name}' title='{$brand_name}' style='width: 100%' src='{$BrandImage}'/> <div class='info'> <p class='icon-heart'><b>Marca Parceira: </b> {$brand_name}</p> </div> </header> <footer class='al_center'> <span title='Editar Marca Parceira' class='btn_header btn_darkaquablue icon-pencil icon-notext jbs_action' cc='Brands' ca='edit' rel='{$brand_id}'></span> <span title='Excluir Marca Parceira' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Brands' callback_action='delete' id='{$brand_id}'></span> </footer> </article>";
            endforeach;
            $Paginator->ExePaginator(DB_BRANDS);
            echo $Paginator->getPaginator();
        endif;
        ?>
    </div>
</div>