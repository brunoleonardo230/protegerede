<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_SERVICES;

if (!APP_SERVICES || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Services';
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
        //GERENCIA SERVIÇO
        case 'manager':
            $ServiceId = $PostData['service_id'];
            unset($PostData['service_id']);

            $Read->ExeRead(DB_SERVICES, "WHERE service_id = :id", "id={$ServiceId}");
            $ThisServices = $Read->getResult()[0];

            $ServiceName = (!empty($PostData['service_name']) ? $PostData['service_name'] : $PostData['service_title']);
            $PostData['service_name'] = Check::Name($ServiceName);
            $Read->FullRead("SELECT service_name FROM " . DB_SERVICES . " WHERE service_name = :nm AND service_id != :id", "nm={$PostData['service_name']}&id={$ServiceId}");
            if ($Read->getResult()):
                $PostData['service_name'] = "{$PostData['service_name']}-{$ServiceId}";
            endif;
            $jSON['name'] = $PostData['service_name'];

            if (!empty($_FILES['service_image'])):
                $File = $_FILES['service_image'];

                if ($ThisServices['service_image'] && file_exists("../../uploads/{$ThisServices['service_image']}") && !is_dir("../../uploads/{$ThisServices['service_image']}")):
                    unlink("../../uploads/{$ThisServices['service_image']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, $PostData['service_name'] . '-image', IMAGE_W, 'servicos');
                if ($Upload->getResult()):
                    $PostData['service_image'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG Ou PNG Para Enviar Como Capa!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['service_image']);
            endif;

            if (!empty($_FILES['service_icon'])):
                $File = $_FILES['service_icon'];

                if ($ThisServices['service_icon'] && file_exists("../../uploads/{$ThisServices['service_icon']}") && !is_dir("../../uploads/{$ThisServices['service_icon']}")):
                    unlink("../../uploads/{$ThisServices['service_icon']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, $PostData['service_name'] . '-icon', IMAGE_W, 'servicos');
                if ($Upload->getResult()):
                    $PostData['service_icon'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "warning", "ERRO AO ENVIAR O ÍCONE", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Ícone!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['service_icon']);
            endif;

            $PostData['service_status'] = (!empty($PostData['service_status']) ? '1' : '0');
            $PostData['service_datecreate'] = (!empty($PostData['service_datecreate']) ? Check::Data($PostData['service_datecreate']) : date('Y-m-d H:i:s'));

            $Update->ExeUpdate(DB_SERVICES, $PostData, "WHERE service_id = :id", "id={$ServiceId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Serviço <b>{$PostData['service_title']}</b> Foi Atualizado Com Sucesso!"];
            break;

        //DELETA SERVIÇO
        case 'delete':
            $PostData['service_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT service_image FROM " . DB_SERVICES . " WHERE service_id = :ps", "ps={$PostData['service_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['service_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['service_image']}")):
                unlink("../../uploads/{$Read->getResult()[0]['service_image']}");
            endif;

            $Read->FullRead("SELECT service_icon FROM " . DB_SERVICES . " WHERE service_id = :ps", "ps={$PostData['service_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['service_icon']}") && !is_dir("../../uploads/{$Read->getResult()[0]['service_icon']}")):
                unlink("../../uploads/{$Read->getResult()[0]['service_icon']}");
            endif;

            $Delete->ExeDelete(DB_SERVICES, "WHERE service_id = :id", "id={$PostData['service_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Serviço Foi Excluído Com Sucesso!"];
            break;

        //CADASTRA TIPOS
        case 'create_types':
            $Types = $PostData['service_type_id'];
            unset($PostData['service_type_id']);

            $Read->ExeRead(DB_SERVICES_TYPES, "WHERE service_type_id = :id", "id={$Types}");
            $ThisTypes = $Read->getResult()[0];

            if (!empty($_FILES['service_type_image'])):
                $File = $_FILES['service_type_image'];

                if ($ThisTypes['service_type_image'] && file_exists("../../uploads/{$ThisTypes['service_type_image']}") && !is_dir("../../uploads/{$ThisTypes['service_type_image']}")):
                    unlink("../../uploads/{$ThisTypes['service_type_image']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, $PostData['service_type_title'] . '-image', IMAGE_W, 'types');
                if ($Upload->getResult()):
                    $PostData['service_type_image'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR A IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Imagem!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['service_type_image']);
            endif;

            if (!empty($_FILES['service_type_icon'])):
                $File = $_FILES['service_type_icon'];

                if ($ThisTypes['service_type_icon'] && file_exists("../../uploads/{$ThisTypes['service_type_icon']}") && !is_dir("../../uploads/{$ThisTypes['service_type_icon']}")):
                    unlink("../../uploads/{$ThisTypes['service_type_icon']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, $PostData['service_type_title'] . '-icon', IMAGE_W, 'services-types');
                if ($Upload->getResult()):
                    $PostData['service_type_icon'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "warning", "ERRO AO ENVIAR O ÍCONE", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Ícone!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['service_type_icon']);
            endif;

            $PostData['service_type_datecreate'] = (!empty($PostData['service_type_datecreate']) ? Check::Data($PostData['service_type_datecreate']) : date('Y-m-d H:i:s'));

            $Update->ExeUpdate(DB_SERVICES_TYPES, $PostData, "WHERE service_type_id = :id", "id={$Types}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Tipo <b>{$PostData['service_type_title']}</b> Foi Atualizado Com Sucesso!"];
            break;

        //DELETA TIPOS
        case 'delete_types':
            $PostData['service_type_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT service_type_image FROM " . DB_SERVICES_TYPES . " WHERE service_type_id = :ps", "ps={$PostData['service_type_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['service_type_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['service_type_image']}")):
                unlink("../../uploads/{$Read->getResult()[0]['service_type_image']}");
            endif;

            $Read->FullRead("SELECT service_type_icon FROM " . DB_SERVICES_TYPES . " WHERE service_type_id = :ps", "ps={$PostData['service_type_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['service_type_icon']}") && !is_dir("../../uploads/{$Read->getResult()[0]['service_type_icon']}")):
                unlink("../../uploads/{$Read->getResult()[0]['service_type_icon']}");
            endif;

            $Delete->ExeDelete(DB_SERVICES_TYPES, "WHERE service_type_id = :id", "id={$PostData['service_type_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Tipo Foi Excluído Com Sucesso!"];
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
