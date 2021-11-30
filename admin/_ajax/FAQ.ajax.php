<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_FAQ;

if (!APP_FAQ || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'FAQ';
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
        case 'manage':
            $FaqId = (!empty($PostData['faq_id']) ? $PostData['faq_id'] : null);

            if (empty($PostData['faq_title']) || empty($PostData['faq_desc'])):
                $jSON['alert'] = ["yellow", "warning", "OPSSS", "Não Foi Possível Cadastrar!</b> Preencha Todos os Campos e Tente Novamente!"];
            else:
                if (empty($FaqId)):
                    //Realiza Cadastro
                    $PostData['faq_date'] = date('Y-m-d H:i:s');

                    $Create->ExeCreate(DB_FAQ, $PostData);
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Pergunta Foi Cadastrada Com Sucesso!"];
                else:
                    unset($PostData['faq_image']);
                    //Atualiza Cadastro
                    $Read->ExeRead(DB_FAQ, "WHERE faq_id = :id", "id={$FaqId}");
                    if (!$Read->getResult()):
                        $jSON['alert'] = ["yellow", "warning", "OPSSS", "Não Foi Possível Atualizar! Desculpe, Mas Não Encontramos a Pergunta Que Deseja Atualizar!"];
                        echo json_encode($jSON);
                        return;
                    endif;
                    $Update->ExeUpdate(DB_FAQ, $PostData, "WHERE faq_id = :id", "id={$FaqId}");
                endif;

                //RealTime
                $jSON['divcontent']['#base'] = null;

                $Read->ExeRead(DB_FAQ, "ORDER BY faq_date DESC");
                if ($Read->getResult()):
                    foreach ($Read->getResult() as $FAQ):
                        extract($FAQ);
                        $jSON['divcontent']['#base'] .= "<article class='box box25 post_single js-rel-to' id='{$faq_id}'>
                        <header class='wc_normalize_height'><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                             <div class='info'>
                                <p><b>Pergunta: </b> {$faq_title}</p>
                            </div>
                        </header>
                        <footer class='al_center'>
                            <span title='Editar Pergunta' class='btn_header btn_darkaquablue icon-pencil icon-notext jbs_action' cc='FAQ' ca='edit' rel='{$faq_id}'></span>
                            <span title='Excluir Pergunta' rel='post_single' callback='FAQ' callback_action='delete' class='j_delete_action icon-bin icon-notext btn_header btn_red' id='{$faq_id}'></span>
                        </footer>              
                    </article>";

                    endforeach;
                endif;

                //Actions
                $jSON['divremove'] = "#cadastro";
            endif;
            break;

        //Obtém Cadastros
        case "edit":
            $PhId = $PostData['action_id'];
            $Read->ExeRead(DB_FAQ, "WHERE faq_id = :id", "id={$PhId}");
            if ($Read->getResult()):
                $Data = $Read->getResult()[0];

                $jSON['divcontent']['.thumb_controll'] = "";
                $jSON['form'] = ".j_faq";
                $jSON['result'] = $Data;
                $jSON['fadein'] = "#cadastro";
            else:
                $jSON['alert'] = ["yellow", "warning", "OPSSS, {$_SESSION['userLogin']['user_name']}", "Não Encontramos: <b>{$faq_title}</b>!"];
            endif;
            break;

        //DELETE
        case 'delete':
            $Delete->ExeDelete(DB_FAQ, "WHERE faq_id = :id", "id={$PostData['del_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Pergunta Foi Excluída Com Sucesso!"];
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
