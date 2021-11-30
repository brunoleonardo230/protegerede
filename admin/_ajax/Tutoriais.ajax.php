<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_TUTORIAIS;

if (!APP_TUTORIAIS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//ADMIN
$Admin = $_SESSION['userLogin'];

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Tutoriais';
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
        //CADASTRA TUTORIAL
        case 'manager':
            if (empty($PostData['tutorial_title'])):
                $jSON['alert'] = ["red", "wondering2", "OPSSS {$_SESSION['userLogin']['user_name']}", "Para Cadastrar Um Tutorial, é Preciso Informar Pelo Menos o Título. Por Favor, Tente Novamente!"];
            else:
                if (empty($PostData['tutorial_id'])):
                    //Realiza Cadastro
                    $PostData['tutorial_name'] = Check::Name($PostData['tutorial_title']);
                    $PostData['tutorial_date'] = date("Y-m-d H:i:s");
                    $PostData['tutorial_status'] = '1';
                    $Create->ExeCreate(DB_TUTORIAIS, $PostData);

                    //Realtime
                    $jSON['add_content'] = null;

                    $Read->ExeRead(DB_TUTORIAIS, "WHERE tutorial_id = :id", "id={$Create->getResult()}");
                    if ($Read->getResult()):
                        extract($Read->getResult()[0]);

                        foreach (getTutorialCat() as $TipoId => $TipoValue):
                            if ($tutorial_type == $TipoId):
                                $TypeName = $TipoValue;
                            endif;
                        endforeach;

                        $jSON['add_content'] = ['#tutorial' => "<article class='box box25 post_single js-rel-to' id='{$tutorial_id}'> <div class='post_single_cover'> <div class='embed-container'> <iframe id='mediaview' width='640' height='360' src='https://www.youtube.com/embed/{$tutorial_video}?rel=0&amp;showinfo=0&autoplay=0&origin=" . BASE . "' frameborder='0' allowfullscreen></iframe> </div> </div> <div class='post_single_content wc_normalize_height'> <h1 class='title'>{$tutorial_title}</h1> <p class='post_single_cat'>{$TypeName}</p></div> <div class='post_single_actions'> <span title='Assistir Tutorial' class='post_single_center icon-play icon-notext btn_header btn_aquablue j_video_modal' callback='Tutoriais' id='{$tutorial_id}'></span> <span title='Editar Tutorial' class='btn_header btn_darkaquablue icon-notext icon-pencil j_edit_modal' callback='Tutoriais' callback_action='edit' id='{$tutorial_id}'></span> <span title='Excluir Tutorial' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Tutoriais' callback_action='delete' id='{$tutorial_id}'></span> </div> </article>"];
                    endif;

                    $divremove = [".js-trigger", ".js-tutorial"];
                    $jSON['divremove'] = $divremove;
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Seu Tutorial Foi Cadastrado Com Sucesso. Agora Você Já Pode Assistir Sempre Que Tiver Dúvidas!"];
                else:
                    $TutorialId = $PostData['tutorial_id'];
                    unset($PostData['tutorial_id']);
                    $Update->ExeUpdate(DB_TUTORIAIS, $PostData, "WHERE tutorial_id = :id", "id={$TutorialId}");

                    //RealTime
                    $jSON['divcontent']['#tutorial'] = null;

                    $Read->ExeRead(DB_TUTORIAIS, "ORDER BY tutorial_date DESC");
                    if ($Read->getResult()):
                        foreach ($Read->getResult() as $Tuto):
                            extract($Tuto);

                            foreach (getTutorialCat() as $TipoId => $TipoValue):
                                if ($tutorial_type == $TipoId):
                                    $TypeName = $TipoValue;
                                endif;
                            endforeach;

                            $jSON['divcontent']['#tutorial'] .= "<article class='box box25 post_single js-rel-to' id='{$tutorial_id}'> <div class='post_single_cover'> <div class='embed-container'> <iframe id='mediaview' width='640' height='360' src='https://www.youtube.com/embed/{$tutorial_video}?rel=0&amp;showinfo=0&autoplay=0&origin=" . BASE . "' frameborder='0' allowfullscreen></iframe> </div> </div> <div class='post_single_content wc_normalize_height'> <h1 class='title'>{$tutorial_title}</h1> <p class='post_single_cat'>{$TypeName}</p> </div> <div class='post_single_actions'> <span title='Assistir Tutorial' class='post_single_center icon-play icon-notext btn_header btn_aquablue j_video_modal' callback='Tutoriais' id='{$tutorial_id}'></span> <span title='Editar Tutorial' class='btn_header btn_darkaquablue icon-notext icon-pencil j_edit_modal' callback='Tutoriais' callback_action='edit' id='{$tutorial_id}'></span> <span title='Excluir Tutorial' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Tutoriais' callback_action='delete' id='{$tutorial_id}'></span> </div> </article>";

                        endforeach;
                    endif;
                    $jSON['divremove'] = ".js-tutorial";
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Seu Tutorial Foi Atualizado Com Sucesso!"];
                endif;
            endif;
            break;

        case 'edit':
            $TutorialId = $PostData['edit_id'];
            $Read->ExeRead(DB_TUTORIAIS, "WHERE tutorial_id = :id", "id={$TutorialId}");
            if ($Read->getResult()):
                extract($Read->getResult()[0]);

                $jSON['data'] = [
                    'tutorial_id' => $tutorial_id,
                    'tutorial_video' => $tutorial_video,
                    'tutorial_title' => $tutorial_title,
                    'tutorial_content' => $tutorial_content
                ];
            else:
                $jSON['alert'] = ["red", "wondering2", "ERRO", "Você Tentou Editar Um Tutorial Que Não Existe Ou Foi Removido!"];
            endif;
            break;

        case 'video':
            $TutorialId = $PostData['id'];
            $Read->ExeRead(DB_TUTORIAIS, "WHERE tutorial_id = :id", "id={$TutorialId}");
            if (!$Read->getResult()):
                $jSON['alert'] = ["red", "wondering2", "ERRO", "Você Tentou Assistir Um Tutorial Que Não Existe Ou Foi Removido!"];
            else:
                $Video = $Read->getResult()[0]['tutorial_video'];
                $jSON['VideoModal'] = "<iframe width='640' height='360' src='https://www.youtube.com/embed/{$Video}?rel=0&amp;showinfo=0&amp;autoplay=0' frameborder='0' allowfullscreen></iframe>";
            endif;
            break;

        case 'delete':
            $TutorialDel = $PostData['del_id'];
            $Delete->ExeDelete(DB_TUTORIAIS, "WHERE tutorial_id = :id", "id={$TutorialDel}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Tutorial Foi Excluído Com Sucesso!"];
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
