<?php

session_start();
require '../../_app/Config.inc.php';

if (empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < 6):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

//ADMIN
$Admin = $_SESSION['userLogin'];

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Hellobar';
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
        //HELLO CAR CREATE / UPDATE
        case 'hellobar_update':
            //NIVEL DE ACESSO
            if ($Admin['user_level'] < LEVEL_WC_HELLO):
                $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
                break;
            endif;

            $HelloId = $PostData['hello_id'];
            $HelloRule = $PostData['hello_rule'];
            unset($PostData['hello_id'], $PostData['hello_rule'], $PostData['hello_cover']);

            $Read->ExeRead(DB_HELLO, "WHERE hello_id = :id", "id={$HelloId}");
            if (!$Read->getResult()):
                $jSON['alert'] = ["red", "wondering2", "OPSSS", "Desculpe {$Admin['user_name']}, Mas Você Tentou Atualizar Uma Hellobar Que Não Existe!"];
                break;
            endif;

            extract($Read->getResult()[0]);

            $HelloUpload = (!empty($_FILES['hello_cover']) ? $_FILES['hello_cover'] : null);
            if (empty($hello_image) && empty($HelloUpload)):
                $jSON['alert'] = ["red", "wondering2", "ERRO AO ATUALIZAR", "Olá {$Admin['user_name']}, Você Precisa Enviar a Imagem da Hellobar!"];
                break;
            endif;

            if (!empty($HelloUpload)):
                if (!empty($hello_image) && file_exists("../../uploads/{$hello_image}") && !is_dir("../../uploads/{$hello_image}")):
                    unlink("../../uploads/{$hello_image}");
                endif;

                $Upload = new Upload("../../uploads/");
                $Upload->Image($HelloUpload, Check::Name($PostData['hello_title']), IMAGE_W);
                if (!$Upload->getResult()):
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR A IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG Ou PNG Para Enviar Como Imagem!"];
                    break;
                else:
                    $PostData['hello_image'] = $Upload->getResult();
                endif;
            endif;

            //CAMPOS EM BRANCO
            if (in_array("", $PostData)):
                $jSON['alert'] = ["yellow", "warning", "ERRO AO CADASTRAR", "Para Criar Uma Hellobar, Preencha Todos os Campos do Formulário!"];
                break;
            endif;

            //RETORNAR O RULE PARA O WC_VIEW QUANDO TIVER
            $PostData['hello_status'] = (!empty($PostData['hello_status']) ? 1 : 0);
            $PostData['hello_rule'] = (!empty($HelloRule) ? $HelloRule : null);
            $PostData['hello_start'] = Check::Data($PostData['hello_start']);
            $PostData['hello_end'] = Check::Data($PostData['hello_end']);
            $PostData['hello_date'] = date(DATE_W3C);

            $Update->ExeUpdate(DB_HELLO, $PostData, "WHERE hello_id = :id", "id={$HelloId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "Sua Hellobar Foi Atualizada e Já Pode Ser Exibida Em Seu Site!"];
            break;

        //HELLO BAR DELETE
        case 'hellobar_delete':
            $HelloId = $PostData['del_id'];

            $Read->FullRead("SELECT hello_image FROM " . DB_HELLO . " WHERE hello_id = :hello", "hello={$HelloId}");
            if ($Read->getResult()):
                $hello_image = $Read->getResult()[0]['hello_image'];
                if (file_exists("../../uploads/{$hello_image}") && !is_dir("../../uploads/{$hello_image}")):
                    unlink("../../uploads/{$hello_image}");
                endif;
            endif;

            $Delete->ExeDelete(DB_HELLO, "WHERE hello_id = :id", "id={$HelloId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Hellobar Foi Excluída Com Sucesso!"];
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
