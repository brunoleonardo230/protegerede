<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_PERSONALIZE;

if (!APP_PERSONALIZE || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Personalize';
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
        //GERENCIA CLÍNICA
        case 'create_clinic':
            $ClinicId = $PostData['clinic_id'];
            unset($PostData['clinic_id']);

            $Read->ExeRead(DB_CLINICS, "WHERE clinic_id = :id", "id={$ClinicId}");
            $ThisClinic = $Read->getResult()[0];

            if (!empty($_FILES['clinic_image'])):
                $File = $_FILES['clinic_image'];

                if ($ThisClinic['clinic_image'] && file_exists("../../uploads/{$ThisClinic['clinic_image']}") && !is_dir("../../uploads/{$ThisClinic['clinic_image']}")):
                    unlink("../../uploads/{$ThisClinic['clinic_image']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, Check::Name($PostData['clinic_title']) . '-' . time(), IMAGE_W, 'clinicas');
                if ($Upload->getResult()):
                    $PostData['clinic_image'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG Ou PNG Para Enviar Como Imagem!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['clinic_image']);
            endif;

            $PostData['clinic_status'] = (!empty($PostData['clinic_status']) ? '1' : '0');
            $PostData['clinic_datecreate'] = (!empty($PostData['clinic_datecreate']) ? Check::Data($PostData['clinic_datecreate']) : date('Y-m-d H:i:s'));

            $Update->ExeUpdate(DB_CLINICS, $PostData, "WHERE clinic_id = :id", "id={$ClinicId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "A Clínica <b>{$PostData['clinic_title']}</b> Foi Atualizada Com Sucesso!"];
            break;
            
        //DELETA CLÍNICA
        case 'delete_clinic':
            $PostData['clinic_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT clinic_image FROM " . DB_CLINICS . " WHERE clinic_id = :ps", "ps={$PostData['clinic_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['clinic_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['clinic_image']}")):
                unlink("../../uploads/{$Read->getResult()[0]['clinic_image']}");
            endif;
	
            $Delete->ExeDelete(DB_CLINICS, "WHERE clinic_id = :id", "id={$PostData['clinic_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Clínica Foi Excluída Com Sucesso!"];
            break; 			    
            
        //CADASTRA CADEIRA
        case 'create_chair':
            if (empty($PostData['chair_title'])):
                $jSON['alert'] = ["red", "wondering2", "OPSSS {$_SESSION['userLogin']['user_name']}", "Para Cadastrar Uma Cadeira, é Preciso Informar o Nome. Por Favor, Tente Novamente!"];
            else:
                if (empty($PostData['chair_id'])):
                    //Realiza Cadastro
                    $PostData['chair_datecreate'] = date("Y-m-d H:i:s");
                    $Create->ExeCreate(DB_CHAIRS, $PostData);

                    //Realtime
                    $jSON['add_content'] = null;

                    $Read->ExeRead(DB_CHAIRS, "WHERE chair_id = :id", "id={$Create->getResult()}");
                    if ($Read->getResult()):
                        extract($Read->getResult()[0]);
                        
                        $jSON['add_content'] = ['#personalize-chair' => "<div class='single_user_addr js-rel-to' id='{$chair_id}'>
                            <h1 class='icon-list2'>{$chair_title}</h1>
                            <div class='single_user_addr_actions'>
                                <span title='Editar Cadeira' class='btn_header btn_darkblue icon-notext icon-pencil j_edit_chair_modal' callback='Personalize' callback_action='edit_chair' id='{$chair_id}'></span>
                                <span title='Excluir Cadeira' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Personalize' callback_action='delete_chair' id='{$chair_id}'></span>
                                </div>
                            </div>"];
                    endif;

                    $divremove = [".js-trigger", ".js-chair"];
                    $jSON['divremove'] = $divremove;
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Cadeira Foi Cadastrada Com Sucesso!"];
                else:
                    $ChairId = $PostData['chair_id'];
                    unset($PostData['chair_id']);
                    $Update->ExeUpdate(DB_CHAIRS, $PostData, "WHERE chair_id = :id", "id={$ChairId}");

                    //RealTime
                    $jSON['divcontent']['#personalize-chair'] = null;
    
                    $Read->ExeRead(DB_CHAIRS, "ORDER BY chair_datecreate ASC, chair_title ASC");
                    if ($Read->getResult()):
                        foreach ($Read->getResult() as $CHAIRS):
                            extract($CHAIRS);
                            
                            $jSON['divcontent']['#personalize-chair'] .= "<div class='single_user_addr js-rel-to' id='{$chair_id}'>
                                <h1 class='icon-list2'>{$chair_title}</h1>
                                <div class='single_user_addr_actions'>
                                    <span title='Editar Cadeira' class='btn_header btn_darkblue icon-notext icon-pencil j_edit_chair_modal' callback='Personalize' callback_action='edit_chair' id='{$chair_id}'></span>
                                    <span title='Excluir Cadeira' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Personalize' callback_action='delete_chair' id='{$chair_id}'></span>
                                    </div>
                                </div>";
    
                        endforeach;
                    endif;
                    $jSON['divremove'] = ".js-chair";
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Cadeira Foi Atualizada Com Sucesso!"];
                endif;
            endif;
            break;
            
        //EDITA CADEIRA    
        case 'edit_chair':
            $ChairId = $PostData['edit_id'];
            $Read->ExeRead(DB_CHAIRS, "WHERE chair_id = :id", "id={$ChairId}");
            if ($Read->getResult()):
                $jSON['data'] = $Read->getResult()[0];
            else:
                $jSON['alert'] = ["red", "wondering2", "ERRO", "Você Tentou Editar Uma Cadeira Que Não Existe Ou Foi Removida!"];
            endif;
            break;

        //DELETA CADEIRA
        case 'delete_chair':
            $ChairDel = $PostData['del_id'];
            $Delete->ExeDelete(DB_CHAIRS, "WHERE chair_id = :id", "id={$ChairDel}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Cadeira Foi Excluída Com Sucesso!"];
            break;
            
        //CADASTRA PROCEDIMENTO
        case 'create_procedure':
            if (empty($PostData['procedure_title']) || (empty($PostData['procedure_price']))):
                $jSON['alert'] = ["red", "wondering2", "OPSSS {$_SESSION['userLogin']['user_name']}", "Para Cadastrar Um Procedimento, é Preciso Informar o Nome e o Valor. Por Favor, Tente Novamente!"];
            else:
                if (empty($PostData['procedure_id'])):
                    //Realiza Cadastro
                    $PostData['procedure_price'] = str_replace(',', '.', str_replace('.', '', $PostData['procedure_price']));
                    $PostData['procedure_datecreate'] = date("Y-m-d H:i:s");
                    $Create->ExeCreate(DB_PROCEDURES, $PostData);

                    //Realtime
                    $jSON['add_content'] = null;

                    $Read->ExeRead(DB_PROCEDURES, "WHERE procedure_id = :id", "id={$Create->getResult()}");
                    if ($Read->getResult()):
                        extract($Read->getResult()[0]);
                        
                        $jSON['add_content'] = ['#personalize-procedure' => "<article class='marketing__table js-marketing-table js-rel-to' id='{$procedure_id}'> <div class='marketing__data'> <p>{$procedure_title}</p> <p>" . getProceduresCategory($procedure_category) . "</p> <p>{$procedure_code}</p> <p>R$" . number_format($procedure_price, 2, ',', '.') . "</p> <p> <span title='Editar Procedimento' class='btn_header btn_darkblue icon-notext icon-pencil j_edit_procedure_modal' callback='Personalize' callback_action='edit_procedure' id='{$procedure_id}'></span> <span title='Excluir Procedimento' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Personalize' callback_action='delete_procedure' id='{$procedure_id}'></span> </p> </div> </article>"];
                    endif;

                    $divremove = [".js-trigger", ".js-procedure"];
                    $jSON['divremove'] = $divremove;
                    $jSON['alert'] = ["green", "checkmark", "TUDE CERTO {$_SESSION['userLogin']['user_name']}", "O Procedimento Foi Cadastrado Com Sucesso!"];
                else:
                    $ProcedureId = $PostData['procedure_id'];
                    unset($PostData['procedure_id']);
                    
                    $PostData['procedure_price'] = str_replace(',', '.', str_replace('.', '', $PostData['procedure_price']));
                    $Update->ExeUpdate(DB_PROCEDURES, $PostData, "WHERE procedure_id = :id", "id={$ProcedureId}");

                    //RealTime
                    $jSON['divcontent']['#personalize-procedure'] = null;
    
                    $Read->ExeRead(DB_PROCEDURES, "ORDER BY procedure_datecreate DESC, procedure_title ASC LIMIT :limit OFFSET :offset", "limit=7&offset=0");
                    
                    if ($Read->getResult()):
                        foreach ($Read->getResult() as $PROCEDURES):
                            extract($PROCEDURES);
                            
                            $jSON['divcontent']['#personalize-procedure'] .= "<article class='marketing__table js-marketing-table js-rel-to' id='{$procedure_id}'> <div class='marketing__data'> <p>{$procedure_title}</p> <p>" . getProceduresCategory($procedure_category) . "</p> <p>{$procedure_code}</p> <p>R$" . number_format($procedure_price, 2, ',', '.') . "</p> <p> <span title='Editar Procedimento' class='btn_header btn_darkblue icon-notext icon-pencil j_edit_procedure_modal' callback='Personalize' callback_action='edit_procedure' id='{$procedure_id}'></span> <span title='Excluir Procedimento' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Personalize' callback_action='delete_procedure' id='{$procedure_id}'></span> </p> </div> </article>";
    
                        endforeach;
                    endif;
                    $jSON['divremove'] = ".js-procedure";
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Procedimento Foi Atualizado Com Sucesso!"];
                endif;
            endif;
            break;
            
        //EDITA PROCEDIMENTO    
        case 'edit_procedure':
            $ProcedureId = $PostData['edit_id'];
            $Read->ExeRead(DB_PROCEDURES, "WHERE procedure_id = :id", "id={$ProcedureId}");
            if ($Read->getResult()):
                $jSON['data'] = $Read->getResult()[0];
                $jSON['data']['procedure_price'] = number_format($jSON['data']['procedure_price'], 2, ',', '.');
            else:
                $jSON['alert'] = ["red", "wondering2", "ERRO", "Você Tentou Editar Um Procedimento Que Não Existe Ou Foi Removido!"];
            endif;
            break;

        //DELETA PROCEDIMENTO
        case 'delete_procedure':
            $ProcedureDel = $PostData['del_id'];
            $Delete->ExeDelete(DB_PROCEDURES, "WHERE procedure_id = :id", "id={$ProcedureDel}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Procedimento Foi Excluído Com Sucesso!"];
            break;
        
        //PAGINAÇÃO VIA AJAX PROCEDIMENTOS  
        case 'content_procedures':
            $jSON['content'] = null;

            if (isset($PostData['search'])):
                $search = $PostData['search'];
                $Read->ExeRead(DB_PROCEDURES, "WHERE procedure_title LIKE '%' :search '%' LIMIT :limit", "search={$search}&limit=7");
            endif;

            if (isset($PostData['offset'])):
                $offset = $PostData['offset'];
                $Read->ExeRead(DB_PROCEDURES, "LIMIT :limit OFFSET :offset", "limit=7&offset={$offset}");
            endif;

            if ($Read->getResult()):
                foreach ($Read->getResult() as $PROCEDURES):
                    extract($PROCEDURES);
                    
                    $jSON['content'] .= "<article class='marketing__table js-marketing-table js-rel-to' id='{$procedure_id}'> <div class='marketing__data'> <p>{$procedure_title}</p> <p>" . getProceduresCategory($procedure_category) . "</p> <p>{$procedure_code}</p> <p>R$" . number_format($procedure_price, 2, ',', '.') . "</p> <p> <span title='Editar Procedimento' class='btn_header btn_darkblue icon-notext icon-pencil j_edit_procedure_modal' callback='Personalize' callback_action='edit_procedure' id='{$procedure_id}'></span> <span title='Excluir Procedimento' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Personalize' callback_action='delete_procedure' id='{$procedure_id}'></span> </p> </div> </article>";
                endforeach;
            endif;
            break;
        
        //BUSCA DINÂMICA PROCEDIMENTOS  
        case 'search_procedures':
            $search = $PostData['search'];
            $jSON['content'] = null;

            $Read->ExeRead(DB_PROCEDURES, "WHERE procedure_title LIKE '%' :search '%'", "search={$search}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $PROCEDURES):
                    extract($PROCEDURES);

                    $jSON['content'] .= "<article class='marketing__table js-marketing-table js-rel-to' id='{$procedure_id}'> <div class='marketing__data'> <p>{$procedure_title}</p> <p>" . getProceduresCategory($procedure_category) . "</p> <p>{$procedure_code}</p> <p>R$" . number_format($procedure_price, 2, ',', '.') . "</p> <p> <span title='Editar Procedimento' class='btn_header btn_darkblue icon-notext icon-pencil j_edit_procedure_modal' callback='Personalize' callback_action='edit_procedure' id='{$procedure_id}'></span> <span title='Excluir Procedimento' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Personalize' callback_action='delete_procedure' id='{$procedure_id}'></span> </p> </div> </article>";
                endforeach;
            endif;
            break;
            
        //GERENCIA DENTE
        case 'create_tooth':
            $ToothId = $PostData['tooth_id'];
            unset($PostData['tooth_id']);

            $Read->ExeRead(DB_TEETH, "WHERE tooth_id = :id", "id={$ToothId}");
            $ThisTooth = $Read->getResult()[0];

            if (!empty($_FILES['tooth_image'])):
                $File = $_FILES['tooth_image'];

                if ($ThisTooth['tooth_image'] && file_exists("../../uploads/{$ThisTooth['tooth_image']}") && !is_dir("../../uploads/{$ThisTooth['tooth_image']}")):
                    unlink("../../uploads/{$ThisTooth['tooth_image']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, Check::Name($PostData['tooth_title']), IMAGE_W, 'dentes');
                if ($Upload->getResult()):
                    $PostData['tooth_image'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Imagem!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['tooth_image']);
            endif;

            $PostData['tooth_datecreate'] = (!empty($PostData['tooth_datecreate']) ? Check::Data($PostData['tooth_datecreate']) : date('Y-m-d H:i:s'));

            $Update->ExeUpdate(DB_TEETH, $PostData, "WHERE tooth_id = :id", "id={$ToothId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Dente <b>{$PostData['tooth_title']}</b> Foi Atualizado Com Sucesso!"];
            break;
            
        //DELETA DENTE
        case 'delete_tooth':
            $PostData['tooth_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT tooth_image FROM " . DB_TEETH . " WHERE tooth_id = :ps", "ps={$PostData['tooth_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['tooth_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['tooth_image']}")):
                unlink("../../uploads/{$Read->getResult()[0]['tooth_image']}");
            endif;
	
            $Delete->ExeDelete(DB_TEETH, "WHERE tooth_id = :id", "id={$PostData['tooth_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Dente Foi Excluído Com Sucesso!"];
            break;
            
        //PAGINAÇÃO VIA AJAX DENTES  
        case 'content_teeth':
            $jSON['content'] = null;

            if (isset($PostData['search'])):
                $search = $PostData['search'];
                $Read->ExeRead(DB_TEETH, "WHERE (tooth_title LIKE '%' :search '%' OR tooth_number LIKE '%' :search '%') ORDER BY tooth_number ASC LIMIT :limit", "search={$search}&limit=7");
            endif;

            if (isset($PostData['offset'])):
                $offset = $PostData['offset'];
                $Read->ExeRead(DB_TEETH, "ORDER BY tooth_number ASC LIMIT :limit OFFSET :offset", "limit=7&offset={$offset}");
            endif;

            if ($Read->getResult()):
                foreach ($Read->getResult() as $TEETH):
                    extract($TEETH);
                    
                    $ToothImage = BASE . "/tim.php?src=uploads/{$tooth_image}&w=40&h=40";
                    
                    $jSON['content'] .= "<article class='marketing__table js-marketing-table js-rel-to' id='{$tooth_id}'> <div class='marketing__data_tooth'> <p class='payment'> <span class='img'> <img src='{$ToothImage}'/> </span> </p> <p>{$tooth_title}</p> <p>" . getTypeTooth($tooth_type) . "</p> <p>" . getUpDownTooth($tooth_updown) . "</p> <p>" . getLeftRightTooth($tooth_left_right) . "</p> <p>{$tooth_number}</p> <p> <a title='Editar Dente' href='dashboard.php?wc=personalizar/dentes&id={$tooth_id}' class='post_single_center icon-notext icon-pencil btn_header btn_darkblue'></a> <span title='Excluir Dente' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Personalize' callback_action='delete_tooth' id='{$tooth_id}'></span> </p> </div> </article>";
                endforeach;
            endif;
            break;
        
        //BUSCA DINÂMICA DENTE  
        case 'search_tooth':
            $search = $PostData['search'];
            $jSON['content'] = null;

            $Read->ExeRead(DB_TEETH, "WHERE (tooth_title LIKE '%' :search '%' OR tooth_number LIKE '%' :search '%')", "search={$search}");

            if ($Read->getResult()):
                foreach ($Read->getResult() as $TEETH):
                    extract($TEETH);
                    
                    $ToothImage = BASE . "/tim.php?src=uploads/{$tooth_image}&w=40&h=40";

                    $jSON['content'] .= "<article class='marketing__table js-marketing-table js-rel-to' id='{$tooth_id}'> <div class='marketing__data_tooth'> <p class='payment'> <span class='img'> <img src='{$ToothImage}'/> </span> </p> <p>{$tooth_title}</p> <p>" . getTypeTooth($tooth_type) . "</p> <p>" . getUpDownTooth($tooth_updown) . "</p> <p>" . getLeftRightTooth($tooth_left_right) . "</p> <p>{$tooth_number}</p> <p> <a title='Editar Dente' href='dashboard.php?wc=personalizar/dentes&id={$tooth_id}' class='post_single_center icon-notext icon-pencil btn_header btn_darkblue'></a> <span title='Excluir Dente' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Personalize' callback_action='delete_tooth' id='{$tooth_id}'></span> </p> </div> </article>";
                endforeach;
            endif;
            break;
            
        //GERENCIA ANAMNESE
        case 'create_anamnese':
            $AnamneseId = $PostData['anamnese_id'];
            unset($PostData['anamnese_id']);

            $Read->ExeRead(DB_ANAMNESIS, "WHERE anamnese_id = :id", "id={$AnamneseId}");
            $ThisAnamnese = $Read->getResult()[0];

            $PostData['anamnese_status'] = (!empty($PostData['anamnese_status']) ? '1' : '0');
            $PostData['anamnese_datecreate'] = (!empty($PostData['anamnese_datecreate']) ? Check::Data($PostData['anamnese_datecreate']) : date('Y-m-d H:i:s'));

            $Update->ExeUpdate(DB_ANAMNESIS, $PostData, "WHERE anamnese_id = :id", "id={$AnamneseId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Anamnese <b>{$PostData['anamnese_title']}</b> Foi Atualizada Com Sucesso!"];
            break;
            
        //DELETA ANAMNESE
        case 'delete_anamnese':
            $PostData['anamnese_id'] = $PostData['del_id'];
	
            $Delete->ExeDelete(DB_ANAMNESIS, "WHERE anamnese_id = :id", "id={$PostData['anamnese_id']}");

            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Anamnese Foi Excluída Com Sucesso!"];
            break;
            
        //CADASTRA PERGUNTA DA ANAMNESE
        case 'create_anamnese_question':
            $AnamneseId = $PostData['anamnese_id'];
            unset($PostData['anamnese_id']);
            
            if (empty($PostData['anamnese_question_ask'])):
                $jSON['alert'] = ["red", "wondering2", "OPPPS {$_SESSION['userLogin']['user_name']}", "Para Cadastrar Uma Pergunta Para Anamnese, é Preciso Informar o Título da Pergunta. Por Favor, Tente Novamente!"];
            else:
                if (empty($PostData['anamnese_question_id'])):
                    //Realiza Cadastro
                    $PostData['anamnese_id'] = $AnamneseId;
                    $PostData['anamnese_question_datecreate'] = date("Y-m-d H:i:s");
                    $Create->ExeCreate(DB_ANAMNESIS_QUESTIONS, $PostData);

                    //Realtime
                    $jSON['add_content'] = null;

                    $Read->ExeRead(DB_ANAMNESIS_QUESTIONS, "WHERE anamnese_question_id = :id", "id={$Create->getResult()}");
                    if ($Read->getResult()):
                        extract($Read->getResult()[0]);
                        
                        $jSON['add_content'] = ['#personalize-anamnese' => "<div class='single_user_addr js-rel-to' id='{$anamnese_question_id}'> <h1 class='icon-list2'>{$anamnese_question_ask}</h1> <p class='icon-price-tags'>" . getAnamnesisRegister($anamnese_question_type) . "</p> <div class='single_user_addr_actions'> <span title='Editar Pergunta' class='btn_header btn_darkblue icon-notext icon-pencil j_edit_anamnese_modal' callback='Personalize' callback_action='edit_anamnese_question' id='{$anamnese_question_id}'></span> <span title='Excluir Pergunta' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Personalize' callback_action='delete_anamnese_question' id='{$anamnese_question_id}'></span> </div> </div>"];
                    endif;

                    $divremove = [".js-trigger", ".js-anamnese"];
                    $jSON['divremove'] = $divremove;
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Pergunta da Anamnese Foi Cadastrada Com Sucesso!"];
                else:
                    $AnamenseQuestionId = $PostData['anamnese_question_id'];
                    unset($PostData['anamnese_question_id']);
                    $Update->ExeUpdate(DB_ANAMNESIS_QUESTIONS, $PostData, "WHERE anamnese_question_id = :id", "id={$AnamenseQuestionId}");

                    //RealTime
                    $jSON['divcontent']['#personalize-anamnese'] = null;
    
                    $Read->ExeRead(DB_ANAMNESIS_QUESTIONS, "ORDER BY anamnese_question_datecreate DESC, anamnese_question_ask ASC");
                    if ($Read->getResult()):
                        foreach ($Read->getResult() as $QUESTIONS):
                            extract($QUESTIONS);
                            
                            $jSON['divcontent']['#personalize-anamnese'] .= "<div class='single_user_addr js-rel-to' id='{$anamnese_question_id}'> <h1 class='icon-list2'>{$anamnese_question_ask}</h1> <p class='icon-price-tags'>" . getAnamnesisRegister($anamnese_question_type) . "</p> <div class='single_user_addr_actions'> <span title='Editar Pergunta' class='btn_header btn_darkblue icon-notext icon-pencil j_edit_anamnese_modal' callback='Personalize' callback_action='edit_anamnese_question' id='{$anamnese_question_id}'></span> <span title='Excluir Pergunta' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Personalize' callback_action='delete_anamnese_question' id='{$anamnese_question_id}'></span> </div> </div>";
    
                        endforeach;
                    endif;
                    $jSON['divremove'] = ".js-anamnese";
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Pergunta da Anamnese Foi Atualizada Com Sucesso!"];
                endif;
            endif;
            break;
            
        //EDITA PERGUNTA DA ANAMNESE    
        case 'edit_anamnese_question':
            $AnamenseQuestionId = $PostData['edit_id'];
            $Read->ExeRead(DB_ANAMNESIS_QUESTIONS, "WHERE anamnese_question_id = :id", "id={$AnamenseQuestionId}");
            if ($Read->getResult()):
                $jSON['data'] = $Read->getResult()[0];
            else:
                $jSON['alert'] = ["red", "wondering2", "ERRO", "Você Tentou Editar Uma Pergunta Que Não Existe Ou Foi Removido!"];
            endif;
            break;

        //DELETA PERGUNTA DA ANAMNESE
        case 'delete_anamnese_question':
            $AnamenseQuestionDel = $PostData['del_id'];
            $Delete->ExeDelete(DB_ANAMNESIS_QUESTIONS, "WHERE anamnese_question_id = :id", "id={$AnamenseQuestionDel}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Pergunta da Anamnese Foi Excluída Com Sucesso!"];
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
