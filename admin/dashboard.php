<?php
ob_start();
session_start();
require '../_app/Config.inc.php';
require '../_cdn/cronjob.php';

if (isset($_SESSION['userLogin']) && isset($_SESSION['userLogin']['user_level']) && $_SESSION['userLogin']['user_level'] >= 6):
    $Read = new Read;
    $Read->FullRead("SELECT user_level FROM " . DB_USERS . " WHERE user_id = :user", "user={$_SESSION['userLogin']['user_id']}");
    if (!$Read->getResult() || $Read->getResult()[0]['user_level'] < 6):
        unset($_SESSION['userLogin']);
        header('Location: ./index.php');
        exit;
    else:
        $Admin = $_SESSION['userLogin'];
        $Admin['user_thumb'] = (!empty($Admin['user_thumb']) && file_exists("../uploads/{$Admin['user_thumb']}") && !is_dir("../uploads/{$Admin['user_thumb']}") ? $Admin['user_thumb'] : '../admin/_img/no_avatar.jpg');
        $DashboardLogin = true;
    endif;
else:
    unset($_SESSION['userLogin']);
    header('Location: ./index.php');
    exit;
endif;

$AdminLogOff = filter_input(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);
if ($AdminLogOff):
    $_SESSION['trigger_login'] = Erro("<b>LOGOFF:</b> Olá {$Admin['user_name']}, Você Desconectou Com Sucesso do " . ADMIN_NAME . ", Volte Logo!");
    unset($_SESSION['userLogin']);
    header('Location: ./index.php');
    exit;
endif;

$getViewInput = filter_input(INPUT_GET, 'wc', FILTER_DEFAULT);
$getView = ($getViewInput == 'home' ? 'home' . ADMIN_MODE : $getViewInput);

//PARA SUA SEGURANÇA, NÃO REMOVA ESSA VALIDAÇÃO!
if (!file_exists("dashboard.json")):
    echo "<span class='wc_domain_license icon-key icon-notext wc_tooltip radius'></span>";
endif;


//SITEMAP GENERATE (1X DAY)
$SiteMapCheck = fopen('sitemap.txt', "a+");
$SiteMapCheckDate = fgets($SiteMapCheck);
if ($SiteMapCheckDate != date('Y-m-d')):
    $SiteMapCheck = fopen('sitemap.txt', "w");
    fwrite($SiteMapCheck, date('Y-m-d'));
    fclose($SiteMapCheck);

    $SiteMap = new Sitemap;
    $SiteMap->exeSitemap(DB_AUTO_PING);
endif;
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <title><?= ADMIN_NAME; ?> - <?= SITE_NAME; ?></title>
        <meta name="description" content="<?= ADMIN_DESC; ?>"/>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0">
        <meta name="robots" content="noindex, nofollow"/>

        <link href="https://fonts.googleapis.com/css?family=Mali:200,300,400,500,600,700&display=swap" rel="stylesheet">
        <link rel="base" href="<?= BASE; ?>/admin/">
        <link rel="shortcut icon" href="_img/favicon.png" />

        <link rel="stylesheet" href="../_cdn/datepicker/datepicker.min.css"/>
        <link rel="stylesheet" href="../_cdn/pickr.min.css"/>
        <link rel="stylesheet" href="_css/reset.css"/>        
        <link rel="stylesheet" href="_css/workcontrol.css"/>
        <link rel="stylesheet" href="_css/workcontrol-860.css" media="screen and (max-width: 860px)"/>
        <link rel="stylesheet" href="_css/workcontrol-480.css" media="screen and (max-width: 480px)"/>
        <link rel="stylesheet" href="../_cdn/bootcss/fonticon.css"/>
        <link rel="stylesheet" href="../_cdn/bootcss/font-awesome.css"/>
        <link rel="stylesheet" href="../_cdn/lightgallery/css/lightgallery.min.css" /> 

        <script src="../_cdn/jquery.js"></script>
        <script src="../_cdn/jquery.form.js"></script>
        <script src="../_cdn/pickr.es5.min.js"></script>
        <script src="_js/workcontrol.js"></script>

        <script src="_js/tinymce/tinymce.min.js"></script>
        <script src="_js/maskinput.js"></script>
        <script src="_js/workplugins.js"></script>

        <script src="../_cdn/highcharts.js"></script>
        <script src="../_cdn/datepicker/datepicker.min.js"></script>
        <script src="../_cdn/datepicker/datepicker.pt-BR.js"></script>
        <script src="../_cdn/lightgallery/js/lightgallery.min.js"></script>
        
    </head>
    <body class="dashboard_main">
        <div class="workcontrol_upload workcontrol_loadmodal">
            <div class="workcontrol_upload_bar">
                <img class="m_botton" width="50" src="_img/load_w.gif" alt="Processando Requisição!" title="Processando Requisição!"/>
                <p><span class="workcontrol_upload_progrees">0%</span> - Processando Requisição!</p>
            </div>
        </div>

        <div class="dashboard_fix">
            <?php
            if (isset($_SESSION['trigger_controll'])):
                echo "<div class='trigger_modal' style='display: block'>";
                Erro("<span class='icon-warning'>{$_SESSION['trigger_controll']}</span>", E_USER_ERROR);
                echo "</div>";
                unset($_SESSION['trigger_controll']);
            endif;
            ?>

            <nav class="dashboard_nav">
                <div class="dashboard_nav_admin">
                    <img class="dashboard_nav_admin_thumb rounded" alt="<?= SITE_NAME; ?>" title="<?= SITE_NAME; ?>" src="_img/company.png"/>
                    <p><a href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><?= SITE_NAME; ?></a></p>
                    
                    <!-- <img class="dashboard_nav_admin_thumb rounded" alt="" title="" src="../tim.php?src=uploads/<?= $Admin['user_thumb']; ?>&w=76&h=76"/>
                    <p><a href="dashboard.php?wc=<?= (APP_EAD ? 'teach/students_gerent' : 'users/create'); ?>&id=<?= $Admin['user_id']; ?>" title="Meu Perfil"><?= $Admin['user_name']; ?> <?= $Admin['user_lastname']; ?></a></p> -->
                </div>
                <ul class="dashboard_nav_menu">
                    <li class="dashboard_nav_menu_li <?= $getViewInput == 'home' ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-home" title="Dashboard" href="dashboard.php?wc=home">Dashboard</a></li>

                    <!-- SISWC Verifica Personalizações! -->
                    <?php if (ADMIN_WC_CUSTOM && file_exists(__DIR__ . "/_siswc/wc_menu.php")):
                        require __DIR__ . "/_siswc/wc_menu.php";
                    endif; ?>
                    
                </ul>
                <div class="dashboard_nav_normalize"></div>        
            </nav>

            <div class="dashboard">
                <?php
                if (file_exists('../DATABASE.sql')):
                    echo "<div>";
                    echo Erro("<span class='al_center'><b class='icon-warning'>IMPORTANTE:</b> Para Sua Segurança Delete o Arquivo DATABASE.sql da Pasta do Projeto! <a class='btn_header btn_yellow' href='dashboard.php?wc=home&database=true' title=''>Deletar Agora!</a></span>", E_USER_ERROR);
                    echo "</div>";

                    $DeleteDatabase = filter_input(INPUT_GET, 'database', FILTER_VALIDATE_BOOLEAN);
                    if ($DeleteDatabase):
                        unlink('../DATABASE.sql');
                        header('Location: dashboard.php?wc=home');
                        exit;
                    endif;
                endif;

                if (!file_exists("../license.txt")):
                    echo "<div>";
                    echo Erro("<span class='al_center'><b class='icon-warning'>ATENÇÃO:</b> O license.txt Não Está Presente Na Raiz do Projeto. Utilizar o Work Control® Sem Esse Arquivo Caracteriza Cópia Não Licenciada.", E_USER_ERROR);
                    echo "</div>";
                endif;

                if (ADMIN_MAINTENANCE):
                    echo "<div>";
                    echo Erro("<span class='al_center'><b class='icon-warning'>IMPORTANTE:</b> O Modo de Manutenção Está Ativo. Somente Usuários Administradores Podem Ver o Site Assim!</span>", E_USER_ERROR);
                    echo "</div>";
                endif;

                //DB TEST
                $Read->FullRead("SELECT VERSION() as mysql_version");
                if ($Read->getResult()):
                    $MysqlVersion = $Read->getResult()[0]['mysql_version'];
                    if (!stripos($MysqlVersion, "MariaDB")):
                        echo "<div>";
                        echo Erro('<span class="al_center"><b class="icon-warning">ATENÇÃO:</b> O Work Control® Foi Projetado Com <b>Banco de Dados MariaDB Superior a 10.1</b>, Você Está Usando ' . $MysqlVersion . '!</span>', E_USER_ERROR);
                        echo "</div>";
                    endif;
                endif;

                //PHP TEST
                $PHPVersion = phpversion();
                if ($PHPVersion < '5.6'):
                    echo "<div>";
                    echo Erro('<span class="al_center"><b class="icon-warning">ATENÇÃO:</b> O Work Control® Foi Projetado Com <b>PHP 5.6 ou Superior</b>, a Versão do Seu PHP é ' . $PHPVersion . '!</span>', E_USER_ERROR);
                    echo "</div>";
                endif;
                ?>
                <div class="dashboard_sidebar">
                    <span class="mobile_menu btn_header btn_light icon-menu icon-notext" style="margin-left: 5px !important; margin-top: 0.55em !important; padding: 5px 10px;"></span>

                    <div class="bs_nav_right">
                        <div class="bs_nav_base bs_nav_user_data fl_right" id="bsbox_nav_profile">
							<a class="icon-notext fl_right bs_get_open" rel="bsbox_nav_profile" href="#">
								<?php  if(!empty($Admin['user_thumb'])): ?>
									<img class="rounded" alt="[<?= "{$Admin['user_name']} {$Admin['user_lastname']}"; ?>]" title="<?= "{$Admin['user_name']} {$Admin['user_lastname']}"; ?>" src="../tim.php?src=uploads/<?= $Admin['user_thumb']; ?>&w=30&h=30"/>
								<?php else:
									if($Admin['user_genre'] == 1): ?>
										<img class="rounded" alt="Usuário Sem Foto" title="Usuário Sem Foto" src="_img/avatarm.png" />
									<?php else: ?>
										<img class="rounded" alt="Usuário Sem Foto" title="Usuário Sem Foto" src="_img/avatarf.png" />
									<?php endif; ?>
								<?php endif; ?>     
							</a>
							<article class="bs_box_dropdown bs_profile_nav">
								<header>
									<div class="user_img">
										<?php  if(!empty($Admin['user_thumb'])): ?>
											<img class="rounded" alt="[<?= "{$Admin['user_name']} {$Admin['user_lastname']}"; ?>]" title="<?= "{$Admin['user_name']} {$Admin['user_lastname']}"; ?>" src="../tim.php?src=uploads/<?= $Admin['user_thumb']; ?>&w=250&h=250"/>
										<?php else:
											if($Admin['user_genre'] == 1): ?>
												<img class="rounded" alt="Usuário Sem Foto" title="Usuário Sem Foto" src="_img/avatarm.png" />
											<?php else: ?>
												<img class="rounded" alt="Usuário Sem Foto" title="Usuário Sem Foto" src="_img/avatarf.png" />
											<?php endif; ?>
										<?php endif; ?>     
									</div>
									<div class="user_info">
										<span><?= "{$Admin['user_name']} {$Admin['user_lastname']}"; ?> <i><?= $Admin['user_email']; ?></i></span>
										<a class="icon-user" href=""dashboard.php?wc=<?= (APP_EAD ? 'teach/students_gerent' : 'users/create'); ?>&id=<?= $Admin['user_id']; ?>" title="Editar Perfil">Editar Perfil</a>
									</div>
									<div class="clear"></div>
								</header>
								<footer>
									<a class="icon-switch btn_header btn_red al_center" style="width: 100%;" href="dashboard.php?wc=home&logoff=true" title="Home ">Desconectar</a>
								</footer>
							</article>
						</div>
                    </div>
                </div>

                <?php
                //QUERY STRING
                if (!empty($getView)):
                    $ShowModule = explode("/", $getView);
                    $ValidaCssModule = __DIR__ . '/_siswc/' . $ShowModule[0] . '/' . $ShowModule[0] . '.css';
                    $ValidaJsModule = __DIR__ . '/_siswc/' . $ShowModule[0] . '/' . $ShowModule[0] . '.js';
                    $includepatch = __DIR__ . '/_sis/' . strip_tags(trim($getView)) . '.php';
                else:
                    $includepatch = __DIR__ . '/_sis/' . 'dashboard.php';
                endif;
                if (file_exists(__DIR__ . "/_siswc/" . strip_tags(trim($getView)) . '.php')):
                    if (file_exists($ValidaCssModule)):
                        echo "<link rel='stylesheet' href='" . '_siswc/' . $ShowModule[0] . '/' . $ShowModule[0] . '.css' . "'/>";
                    endif;
                    if (file_exists($ValidaJsModule)):
                        echo "<script src='" . '_siswc/' . $ShowModule[0] . '/' . $ShowModule[0] . '.js' . "'></script>";
                    endif;
                    require_once __DIR__ . "/_siswc/" . strip_tags(trim($getView)) . '.php';
                elseif (file_exists($includepatch)):
                    require_once($includepatch);
                else:
                    $_SESSION['trigger_controll'] = "<b>OPPSSS:</b> <span class='fontred'>_sis/{$getView}.php</span> Ainda Está Em Construção!";
                    header('Location: dashboard.php?wc=home');
                    exit;
                endif;
                ?>
            </div>
        </div>
    </body>
</html>
<?php
ob_end_flush();
