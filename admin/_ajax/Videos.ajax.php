<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_VIDEOS;

if (!APP_VIDEOS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Videos';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] = $CallBack):
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
        //DELETE
        case 'delete':
            $PostData['videos_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT videos_cover FROM " . DB_GALLERY_VIDEOS . " WHERE videos_id = :ps", "ps={$PostData['videos_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['videos_cover']}") && !is_dir("../../uploads/{$Read->getResult()[0]['videos_cover']}")):
                unlink("../../uploads/{$Read->getResult()[0]['videos_cover']}");
            endif;

            $Delete->ExeDelete(DB_GALLERY_VIDEOS, "WHERE videos_id = :id", "id={$PostData['videos_id']}");
    
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Vídeo Foi Excluído Com Sucesso!"];
            break;

        case 'manager':
            $PostId = $PostData['videos_id'];
            unset($PostData['videos_id']);

            $Read->ExeRead(DB_GALLERY_VIDEOS, "WHERE videos_id = :id", "id={$PostId}");
            $ThisPost = $Read->getResult()[0];

            $PostData['videos_name'] = (!empty($PostData['videos_name']) ? Check::Name($PostData['videos_name']) : Check::Name($PostData['videos_title']));
            $Read->ExeRead(DB_GALLERY_VIDEOS, "WHERE videos_id != :id AND videos_name = :name", "id={$PostId}&name={$PostData['videos_name']}");
            if ($Read->getResult()):
                $PostData['videos_name'] = "{$PostData['videos_name']}-{$PostId}";
            endif;
            $jSON['name'] = $PostData['videos_name'];

            if (!empty($_FILES['videos_cover'])):
                $File = $_FILES['videos_cover'];

                if ($ThisPost['videos_cover'] && file_exists("../../uploads/{$ThisPost['videos_cover']}") && !is_dir("../../uploads/{$ThisPost['videos_cover']}")):
                    unlink("../../uploads/{$ThisPost['videos_cover']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, $PostData['videos_name'] . '-' . time(), IMAGE_W, 'videos');
                if ($Upload->getResult()):
                    $PostData['videos_cover'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG Ou PNG Para Enviar Como Imagem!"];
                    echo json_encode($jSON);
                    return;
                endif;
            endif;

            $PostData = array_filter($PostData);
            $PostData['videos_status'] = (!empty($PostData['videos_status']) ? '1' : '0');
            $PostData['videos_date'] = (!empty($PostData['videos_date']) ? Check::Data($PostData['videos_date']) : date('Y-m-d H:i:s'));

            $Update->ExeUpdate(DB_GALLERY_VIDEOS, $PostData, "WHERE videos_id = :id", "id={$PostId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Vídeo <b>{$PostData['videos_title']}</b> Foi Atualizado Com Sucesso!"];
            $jSON['view'] = BASE . "/artigo/{$PostData['videos_name']}";
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
