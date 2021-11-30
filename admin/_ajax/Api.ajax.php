<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_CONFIG_API;

if (empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Api';
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

    //SELECIONA AÇÃO
    switch ($Case):
        //STATS
        case 'create':
            if (!empty($PostData['api_key']) && mb_strlen($PostData['api_key']) > 8):
                $CreateAPP = [
                    'api_key' => $PostData['api_key'],
                    'api_token' => base64_encode(time() . "wc" . $PostData['api_key']),
                    'api_date' => date('Y-m-d H:i:s'),
                    'api_status' => 1,
                    'api_loads' => 0,
                    'api_lastload' => date('Y-m-d H:i:s')
                ];
                $Create->ExeCreate(DB_WC_API, $CreateAPP);
                if ($Create->getResult()):
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "</b> O APP <b>{$PostData['api_key']}</b> Foi Criado Com Sucesso e Já Pode Consumir Dados No " . ADMIN_NAME . "! <b>Aguarde...</b>"];
                    $jSON['redirect'] = 'dashboard.php?wc=config/wcapi';
                endif;
            else:
                $jSON['alert'] = ["red", "wondering2", "ERRO AO CRIAR APP", "</b> Desculpe, Mas Não é Seguro Criar Uma Key Com Menos de 8 Caracteres!"];
            endif;
            break;

        //ACTIVE ACESS
        case 'active':
            $Api = $PostData['id'];
            $UpdateApi = ['api_status' => '1'];
            $Update->ExeUpdate(DB_WC_API, $UpdateApi, "WHERE api_id = :id", "id={$Api}");
            $jSON['active'] = 1;
            break;

        //REMVOE ACESS
        case 'inactive':
            $Api = $PostData['id'];
            $UpdateApi = ['api_status' => '0'];
            $Update->ExeUpdate(DB_WC_API, $UpdateApi, "WHERE api_id = :id", "id={$Api}");
            $jSON['active'] = 0;
            break;

        //REMOVE APP
        case 'delete':
            $Api = $PostData['del_id'];
            $Delete->ExeDelete(DB_WC_API, "WHERE api_id = :id", "id={$Api}");
            $jSON['success'] = true;
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A APP Foi Excluída Com Sucesso!"];
            $jSON['redirect'] = "dashboard.php?wc=config/wcapi";
            break;

        case 'license':
            /*
             * ATENÇÃO: PARA SUA SEGURANÇA NÃO ALTERE ESSE GATILHO
             * E SEMPRE LICENCIE SEUS DOMÍNIOS AO COLOCALOS ONLINE!
             */

            if (in_array('', $PostData)):
                $jSON['alert'] = ["yellow", "warning", "LICENSE KEY", "Para Licenciar Um Domínio é Preciso Informar Seus Dados e a Chave Da Licença!"];
            else:
                set_error_handler(create_function('$severity, $message, $file, $line', 'throw new ErrorException($message, $severity, $severity, $file, $line);'));
                try {
                    $PostLicence = file_get_contents("https://download.workcontrol.com.br?k={$PostData['licene_key']}&u={$PostData['user_email']}&p=" . hash('sha512', $PostData['user_password']) . "&v=" . ADMIN_VERSION . "&d=" . urlencode(BASE));
                    $resultLicence = json_decode($PostLicence);

                    if (!empty($resultLicence->trigger)):
                        $jSON['alert'] = ["red", "wondering2", "ERRO", "{$resultLicence->trigger}"];
                    else:
                        //CREATE LICENCE JSON
                        $LicenceFile = fopen("../dashboard.json", "w");
                        fwrite($LicenceFile, $PostLicence);
                        fclose($LicenceFile);

                        chmod("../dashboard.json", 0755);
                        copy("../dashboard.json", "../_js/workcontrol.json");
                        copy("../dashboard.json", "../_js/tinymce/tinymce.json");
                        $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "Licença Gerada Com Sucesso!"];
                        $jSON['redirect'] = "dashboard.php?wc=config/license";
                    endif;
                } catch (Exception $e) {
                    $jSON['alert'] = ["red", "wondering2", "ERRO", "Desculpe Mas Não Foi Possível Comunicar Com download.workcontro.com.br. Por Favor, Tente Mais Tarde!"];
                }
                restore_error_handler();
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
