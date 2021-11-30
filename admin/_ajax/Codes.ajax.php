<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_CONFIG_CODES;

if (empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Codes';
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
        case 'workcodes':
            if (empty($PostData['code_name']) || empty($PostData['code_script'])):
                $jSON['alert'] = ["red", "wondering2", "OPSSS {$_SESSION['userLogin']['user_name']}", "Para Cadastrar Um WC Code é Preciso Informar Pelo Menos o Título e o Script. Por Favor, Tente Novamente!"];
            else:
                if (empty($PostData['code_id'])):
                    $PostData['code_created'] = date("Y-m-d H:i:s");
                    $Create->ExeCreate(DB_WC_CODE, $PostData);
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Seu WC Code Foi Cadastrado Com Sucesso e Você Já Pode Ver a Alteração Em Seu Site!"];
                    $jSON['redirect'] = "dashboard.php?wc=config/codes";
                else:
                    $CodeId = $PostData['code_id'];
                    unset($PostData['code_id']);
                    $Update->ExeUpdate(DB_WC_CODE, $PostData, "WHERE code_id = :id", "id={$CodeId}");
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Seu WC Code Foi Atualizado Com Sucesso e Você Já Pode Ver a Alteração Em Seu Site!"];
                    $jSON['redirect'] = "dashboard.php?wc=config/codes";
                endif;
            endif;
            break;

        case 'edit':
            $CodeId = $PostData['edit_id'];
            $Read->ExeRead(DB_WC_CODE, "WHERE code_id = :id", "id={$CodeId}");
            if (!$Read->getResult()):
                $jSON['alert'] = ["red", "wondering2", "ERRO AO OBTER WORK CONTROL CODE", "Você Tentou Editar Um Código Que Não Existe Ou Foi Removido!"];
            else:
                $jSON['data'] = $Read->getResult()[0];
            endif;
            break;

        case 'delete':
            $CodeDel = $PostData['del_id'];
            $Delete->ExeDelete(DB_WC_CODE, "WHERE code_id = :id", "id={$CodeDel}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Código Foi Excluído Com Sucesso!"];
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
