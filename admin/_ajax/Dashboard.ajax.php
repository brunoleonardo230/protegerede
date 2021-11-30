<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = 6;

if (empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Dashboard';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack):
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    // AUTO INSTANCE OBJECT READ
    if (empty($Read)):
        $Read = new Read;
    endif;

    // AUTO INSTANCE OBJECT CREATE
    if (empty($Create)):
        $Create = new Create;
    endif;

    // AUTO INSTANCE OBJECT UPDATE
    if (empty($Update)):
        $Update = new Update;
    endif;

    // AUTO INSTANCE OBJECT DELETE
    if (empty($Delete)):
        $Delete = new Delete;
    endif;

    //LICENCE CHECK
    if (file_exists("../dashboard.json")):
        $LicenseFile = file_get_contents("../dashboard.json");
        $LicenseDomain = json_decode($LicenseFile);

        if (empty($LicenseDomain->license_auth_date) || empty($LicenseDomain->license_hash)):
            unlink("../dashboard.json");
            exit;
        endif;

        if (!empty($LicenseDomain->license_auth_date)):
            $DateNow = new DateTime();
            $DatePing = new DateTime($LicenseDomain->license_auth_date);
            $DateDiff = $DateNow->diff($DatePing)->days;

            if ($DateDiff >= 5):
                set_error_handler(create_function('$severity, $message, $file, $line', 'throw new ErrorException($message, $severity, $severity, $file, $line);'));
                try {
                    $PostLicence = file_get_contents("https://download.workcontrol.com.br?h={$LicenseDomain->license_hash}&d=" . urlencode(BASE));
                    $resultLicence = json_decode($PostLicence);

                    if (!empty($resultLicence->trigger)):
                        $_SESSION['trigger_controll'] = $resultLicence->trigger;
                        unlink("../dashboard.json");
                    else:
                        //UPDATE LICENSE
                        $LicenseUpdate = str_replace('"license_auth_date":"' . $LicenseDomain->license_auth_date . '"', '"license_auth_date":"' . date("Y-m-d H:i:s") . '"', $LicenseFile);
                        chmod("../dashboard.json", 0755);
                        $LicenseFile = fopen("../dashboard.json", "w+");
                        fwrite($LicenseFile, $LicenseUpdate);
                        fclose($LicenseFile);
                        chmod("../dashboard.json", 0644);
                    endif;
                } catch (Exception $e) {
                    //ERROR HANDLER
                }
                restore_error_handler();
            endif;
        endif;
    endif;

    //SELECIONA AÇÃO
    switch ($Case):
        //WC LOGIN FIX
        case 'wc_login_fix':
            if (!empty($_SESSION['userLogin']) && $_SESSION['userLogin']['user_level'] >= 6):
                $Read->ExeRead(DB_USERS, "WHERE user_id = :user", "user={$_SESSION['userLogin']['user_id']}");
                if ($Read->getResult() && $Read->getResult()[0]['user_level'] >= 6):
                    $_SESSION['userLogin'] = $Read->getResult()[0];
                    $jSON['login'] = true;
                else:
                    unset($_SESSION['userLogin']);
                    $_SESSION['trigger_login'] = AjaxErro("<div class='al_center icon-warning'>Sua Sessão Expirou Ou Você Não Tem Permissão Para Acessar o Painel!</div>", E_USER_ERROR);
                    $jSON['redirect'] = BASE . "/admin";
                endif;
            else:
                unset($_SESSION['userLogin']);
                $_SESSION['trigger_login'] = AjaxErro("<div class='al_center icon-warning'>Sua Sessão Expirou Ou Você Não Tem Permissão Para Acessar o Painel!</div>", E_USER_ERROR);
                $jSON['redirect'] = BASE . "/admin";
            endif;
            break;

        //STATS
        case 'siteviews':
            $Read->FullRead("SELECT count(online_id) AS total from " . DB_VIEWS_ONLINE . " WHERE online_endview >= NOW()");
            $jSON['useron'] = str_pad($Read->getResult()[0]['total'], 4, 0, STR_PAD_LEFT);

            $Read->ExeRead(DB_VIEWS_VIEWS, "WHERE views_date = date(NOW())");
            if (!$Read->getResult()):
                $jSON['users'] = '0000';
                $jSON['views'] = '0000';
                $jSON['pages'] = '0000';
                $jSON['stats'] = '0.00';
            else:
                $Views = $Read->getResult()[0];
                $Stats = number_format($Views['views_pages'] / $Views['views_views'], 2, '.', '');
                $jSON['users'] = str_pad($Views['views_users'], 4, 0, STR_PAD_LEFT);
                $jSON['views'] = str_pad($Views['views_views'], 4, 0, STR_PAD_LEFT);
                $jSON['pages'] = str_pad($Views['views_pages'], 4, 0, STR_PAD_LEFT);
                $jSON['stats'] = $Stats;
            endif;

            $Read->FullRead("SELECT COUNT(online_id) AS TotalOnline FROM " . DB_VIEWS_ONLINE . " WHERE online_endview >= NOW() AND online_user IN(SELECT user_id FROM " . DB_EAD_ENROLLMENTS . ")");
            $jSON['students'] = str_pad($Read->getResult()[0]['TotalOnline'], 4, 0, 0);
            break;

        case 'onlinenow':
        $Where = "";
        $ParseString = "";

        if (!empty($PostData['user'])):
            $Where = "AND online_user = :user";
            $ParseString = "user={$PostData['user']}";
        endif;

        if (!empty($PostData['url'])):
            $Where = "AND online_url = :url";
            $ParseString = "url={$PostData['url']}";
        endif;

        if (!empty($PostData['cidade'])):
            $Where = "AND online_city = :ct";
            $ParseString = "ct={$PostData['cidade']}";
        endif;
        if (!empty($PostData['estado'])):
            $Where = "AND online_state = :st";
            $ParseString = "st={$PostData['estado']}";
        endif;
        if (!empty($PostData['pais'])):
            $Where = "AND online_country = :count";
            $ParseString = "count={$PostData['pais']}";
        endif;
        if (!empty($PostData['dispositivo'])):
            $Where = "AND online_device = :dev";
            $ParseString = "dev={$PostData['dispositivo']}";
        endif;

        $Read->ExeRead(DB_VIEWS_ONLINE, "WHERE online_endview >= NOW() {$Where} ORDER BY online_endview DESC", "{$ParseString}");
        if (!$Read->getResult()):
            $jSON['data'] = '<div class="trigger trigger_info"><span class="icon-earth al_center">Não Existem Usuários Online Neste Momento!</span></div>';
            $jSON['now'] = '0000';
        else:
            $i = 0;
            $jSON['data'] = null;
            $jSON['now'] = str_pad($Read->getRowCount(), 4, 0, 0);
            foreach ($Read->getResult() as $Online):
                if (!is_null($Online['online_user'])):
                    $Read->FullRead("SELECT CONCAT(user_name, ' ', user_lastname) AS Name FROM " . DB_USERS . " WHERE user_id = {$Online['online_user']}");
                    $Name = $Read->getResult()[0]['Name'];
                else:
                    $Name = "Visitante";
                endif;
                $filter_url = "<a style='margin-right:6px!important' href='" . BASE . "/admin/dashboard.php?wc=onlinenow&url={$Online['online_url']}' title='Filtrar Por URL' class='btn_header btn_darkaquablue btn_small icon-notext icon-filter'></a>";
                $filter_device = "<a style='margin-right:6px!important' href='" . BASE . "/admin/dashboard.php?wc=onlinenow&dispositivo={$Online['online_device']}' title='Filtrar Por Dispositivo' class='btn_header btn_darkaquablue btn_small icon-notext icon-filter'></a>";
                $filter_city = "<a style='margin-right:6px!important' href='" . BASE . "/admin/dashboard.php?wc=onlinenow&cidade={$Online['online_city']}' title='Filtrar Por Cidade' class='btn_header btn_darkaquablue btn_small icon-notext icon-filter'></a>";
                $filter_state = "<a style='margin-right:6px!important' href='" . BASE . "/admin/dashboard.php?wc=onlinenow&estado={$Online['online_state']}' title='Filtrar Por Estado' class='btn_header btn_darkaquablue btn_small icon-notext icon-filter'></a>";
                $filter_country = "<a style='margin-right:6px!important' href='" . BASE . "/admin/dashboard.php?wc=onlinenow&pais={$Online['online_country']}' title='Filtrar Por País' class='btn_header btn_darkaquablue btn_small icon-notext icon-filter'></a>";
                $jSON['data'] .= "
                        <tr>
                            <td>{$Name}</td>
                            <td>" . date('d/m/Y H:i', strtotime($Online['online_startview'])) . "</td>
                            <td>{$filter_url} <a class='table-link' target='_blank' href='" . BASE . "/{$Online['online_url']}' title='Ver Destino'>" . ($Online['online_url'] ? $Online['online_url'] : 'home') . "</a></td>
                            <td>{$filter_device} {$Online['online_device']}</td>
                            <td>{$filter_city} {$Online['online_city']}</td>
                            <td>{$filter_state} {$Online['online_state']}</td>
                            <td>{$filter_country} {$Online['online_country']}</td>
                            <td>{$Online['online_ip']}</td>
                        </tr>
                        ";
            endforeach;
        endif;
        break;
    endswitch;

    //RETORNA O CALLBACK
    if ($jSON):
        echo json_encode($jSON);
    else:
        $jSON['alert'] = ["red", "wondering2", "Desculpe {$_SESSION['userLogin']['user_name']}", "Uma Ação Do Sistema Não Respondeu Corretamente. Ao Persistir, Contate o Desenvolvedor!"];
        echo json_encode($jSON);
    endif;
else:
    //ACESSO DIRETO
    die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;
