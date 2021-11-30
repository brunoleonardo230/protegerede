<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_SPECIALTIES;

if (!APP_SPECIALTIES || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Specialties';
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
        //GERENCIA ESPECIALIDADE
        case 'manager':
            $SpecialtieId = $PostData['specialtie_id'];
            unset($PostData['specialtie_id']);

            $Read->ExeRead(DB_SPECIALTIES, "WHERE specialtie_id = :id", "id={$SpecialtieId}");
            $ThisSpecialties = $Read->getResult()[0];
            
            $SpecialtieName = (!empty($PostData['specialtie_name']) ? $PostData['specialtie_name'] : $PostData['specialtie_title']);
            $PostData['specialtie_name'] = Check::Name($SpecialtieName);
            $Read->FullRead("SELECT specialtie_name FROM " . DB_SPECIALTIES . " WHERE specialtie_name = :nm AND specialtie_id != :id", "nm={$PostData['specialtie_name']}&id={$SpecialtieId}");
            if ($Read->getResult()):
                $PostData['specialtie_name'] = "{$PostData['specialtie_name']}-{$SpecialtieId}";
            endif;
            $jSON['name'] = $PostData['specialtie_name'];

            if (!empty($_FILES['specialtie_image'])):
                $File = $_FILES['specialtie_image'];

                if ($ThisSpecialties['specialtie_image'] && file_exists("../../uploads/{$ThisSpecialties['specialtie_image']}") && !is_dir("../../uploads/{$ThisSpecialties['specialtie_image']}")):
                    unlink("../../uploads/{$ThisSpecialties['specialtie_image']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, $PostData['specialtie_name']. '-image', IMAGE_W, 'specialties');
                if ($Upload->getResult()):
                    $PostData['specialtie_image'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG Ou PNG Para Enviar Como Capa!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['specialtie_image']);
            endif;
            
            if (!empty($_FILES['specialtie_icon'])):
                $File = $_FILES['specialtie_icon'];

                if ($ThisSpecialties['specialtie_icon'] && file_exists("../../uploads/{$ThisSpecialties['specialtie_icon']}") && !is_dir("../../uploads/{$ThisSpecialties['specialtie_icon']}")):
                        unlink("../../uploads/{$ThisSpecialties['specialtie_icon']}");
                endif;

                $Upload = new Upload('../../uploads/');
                 $Upload->Image($File, $PostData['specialtie_name']. '-icon', IMAGE_W, 'specialties');
                if ($Upload->getResult()):
                        $PostData['specialtie_icon'] = $Upload->getResult();
                else:
                        $jSON['alert'] = ["yellow", "warning", "ERRO AO ENVIAR O ÍCONE", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Ícone!"];
                        echo json_encode($jSON);
                        return;
                endif;
        	else:
                    unset($PostData['specialtie_icon']);
        	endif;

			$PostData['specialtie_status'] = (!empty($PostData['specialtie_status']) ? '1' : '0');
            $PostData['specialtie_datecreate'] = (!empty($PostData['specialtie_datecreate']) ? Check::Data($PostData['specialtie_datecreate']) : date('Y-m-d H:i:s'));

            $Update->ExeUpdate(DB_SPECIALTIES, $PostData, "WHERE specialtie_id = :id", "id={$SpecialtieId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Especialidade <b>{$PostData['specialtie_title']}</b> Foi Atualizada Com Sucesso!"];
            break;
            
        //CADASTRA IMAGENS DE ANTES E DEPOIS    
        case 'before_after':
            $SpecialtieId = $PostData['specialtie_id'];
            unset($PostData['specialtie_id']);
			
			$Read->ExeRead(DB_SPECIALTIES, "WHERE specialtie_id = :id", "id={$SpecialtieId}");
            $ThisSpecialties = $Read->getResult()[0];
            
            $SpecialtieName = (!empty($PostData['specialtie_name']) ? $PostData['specialtie_name'] : $ThisSpecialties['specialtie_title']);
            $PostData['specialtie_name'] = Check::Name($SpecialtieName);
            $Read->FullRead("SELECT specialtie_name FROM " . DB_SPECIALTIES . " WHERE specialtie_name = :nm AND specialtie_id != :id", "nm={$PostData['specialtie_name']}&id={$SpecialtieId}");
            if ($Read->getResult()):
                $PostData['specialtie_name'] = "{$PostData['specialtie_name']}-{$SpecialtieId}";
            endif;
            $jSON['name'] = $PostData['specialtie_name'];	
                if (!empty($_FILES['specialtie_treatment_before'])):
                $File = $_FILES['specialtie_treatment_before'];

                if ($ThisSpecialties['specialtie_treatment_before'] && file_exists("../../uploads/{$ThisSpecialties['specialtie_treatment_before']}") && !is_dir("../../uploads/{$ThisSpecialties['specialtie_treatment_before']}")):
                        unlink("../../uploads/{$ThisSpecialties['specialtie_treatment_before']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, $PostData['specialtie_name'] . '-before', IMAGE_W, 'specialties');
                if ($Upload->getResult()):
                        $PostData['specialtie_treatment_before'] = $Upload->getResult();
                else:
                        $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR A IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Imagem!"];
                        echo json_encode($jSON);
                        return;
                endif;
            else:
                unset($PostData['specialtie_treatment_before']);
            endif;
                if (!empty($_FILES['specialtie_treatment_after'])):
                $File = $_FILES['specialtie_treatment_after'];

                if ($ThisSpecialties['specialtie_treatment_after'] && file_exists("../../uploads/{$ThisSpecialties['specialtie_treatment_after']}") && !is_dir("../../uploads/{$ThisSpecialties['specialtie_treatment_after']}")):
                    unlink("../../uploads/{$ThisSpecialties['specialtie_treatment_after']}");
                endif;

                $Upload = new Upload('../../uploads/');
                 $Upload->Image($File, $PostData['specialtie_name'] . '-after', IMAGE_W, 'specialties');
                if ($Upload->getResult()):
                    $PostData['specialtie_treatment_after'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "warning", "ERRO AO ENVIAR A IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Imagem!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['specialtie_treatment_after']);
            endif;

            $Update->ExeUpdate(DB_SPECIALTIES, $PostData, "WHERE specialtie_id = :id", "id={$SpecialtieId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Antes e Depois da Especialidade Foram Atualizados Com Sucesso!"];
            break;

	//DELETA ESPECIALIDADE
        case 'delete':
            $PostData['specialtie_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT specialtie_image FROM " . DB_SPECIALTIES . " WHERE specialtie_id = :ps", "ps={$PostData['specialtie_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['specialtie_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['specialtie_image']}")):
                unlink("../../uploads/{$Read->getResult()[0]['specialtie_image']}");
            endif;
			
            $Read->FullRead("SELECT specialtie_icon FROM " . DB_SPECIALTIES . " WHERE specialtie_id = :ps", "ps={$PostData['specialtie_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['specialtie_icon']}") && !is_dir("../../uploads/{$Read->getResult()[0]['specialtie_icon']}")):
                unlink("../../uploads/{$Read->getResult()[0]['specialtie_icon']}");
            endif;
			
            $Read->FullRead("SELECT specialtie_treatment_before FROM " . DB_SPECIALTIES . " WHERE specialtie_id = :ps", "ps={$PostData['specialtie_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['specialtie_treatment_before']}") && !is_dir("../../uploads/{$Read->getResult()[0]['specialtie_treatment_before']}")):
                unlink("../../uploads/{$Read->getResult()[0]['specialtie_treatment_before']}");
            endif;
			
            $Read->FullRead("SELECT specialtie_treatment_after FROM " . DB_SPECIALTIES . " WHERE specialtie_id = :ps", "ps={$PostData['specialtie_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['specialtie_treatment_after']}") && !is_dir("../../uploads/{$Read->getResult()[0]['specialtie_treatment_after']}")):
                unlink("../../uploads/{$Read->getResult()[0]['specialtie_treatment_after']}");
            endif;

            $Delete->ExeDelete(DB_SPECIALTIES, "WHERE specialtie_id = :id", "id={$PostData['specialtie_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Especialidade Foi Excluída Com Sucesso!"];
            break; 			
        
        //CADASTRA BENEFÍCIOS
        case 'create_benefits':
            $BenefitsId = $PostData['specialtie_benefits_id'];
            unset($PostData['specialtie_benefits_id']);

            $Read->ExeRead(DB_SPECIALTIES_BENEFITS, "WHERE specialtie_benefits_id = :id", "id={$BenefitsId}");
            $ThisBenefits = $Read->getResult()[0];

            if (!empty($_FILES['specialtie_benefits_image'])):
                $File = $_FILES['specialtie_benefits_image'];

                if ($ThisBenefits['specialtie_benefits_image'] && file_exists("../../uploads/{$ThisBenefits['specialtie_benefits_image']}") && !is_dir("../../uploads/{$ThisBenefits['specialtie_benefits_image']}")):
                    unlink("../../uploads/{$ThisBenefits['specialtie_benefits_image']}");
                endif;

                $Upload = new Upload('../../uploads/');
                 $Upload->Image($File, $PostData['specialtie_benefits_title'] . '-image', IMAGE_W, 'benefits');
                if ($Upload->getResult()):
                    $PostData['specialtie_benefits_image'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR A IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Imagem!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['specialtie_benefits_image']);
            endif;
			
		if (!empty($_FILES['specialtie_benefits_icon'])):
                $File = $_FILES['specialtie_benefits_icon'];

                if ($ThisBenefits['specialtie_benefits_icon'] && file_exists("../../uploads/{$ThisBenefits['specialtie_benefits_icon']}") && !is_dir("../../uploads/{$ThisBenefits['specialtie_benefits_icon']}")):
                    unlink("../../uploads/{$ThisBenefits['specialtie_benefits_icon']}");
                endif;

                $Upload = new Upload('../../uploads/');
                 $Upload->Image($File, $PostData['specialtie_benefits_title'] . '-icon', IMAGE_W, 'benefits');
                if ($Upload->getResult()):
                    $PostData['specialtie_benefits_icon'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "warning", "ERRO AO ENVIAR O ÍCONE", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Ícone!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['specialtie_benefits_icon']);
            endif;

            $PostData['specialtie_benefits_datecreate'] = (!empty($PostData['specialtie_benefits_datecreate']) ? Check::Data($PostData['specialtie_benefits_datecreate']) : date('Y-m-d H:i:s'));

            $Update->ExeUpdate(DB_SPECIALTIES_BENEFITS, $PostData, "WHERE specialtie_benefits_id = :id", "id={$BenefitsId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Benefício <b>{$PostData['specialtie_benefits_title']}</b> Foi Atualizado Com Sucesso!"];
            break;
            
        //DELETA BENEFÍCIOS
        case 'delete_benefits':
            $PostData['specialtie_benefits_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT specialtie_benefits_image FROM " . DB_SPECIALTIES_BENEFITS . " WHERE specialtie_benefits_id = :ps", "ps={$PostData['specialtie_benefits_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['specialtie_benefits_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['specialtie_benefits_image']}")):
                unlink("../../uploads/{$Read->getResult()[0]['specialtie_benefits_image']}");
            endif;
			
            $Read->FullRead("SELECT specialtie_benefits_icon FROM " . DB_SPECIALTIES_BENEFITS . " WHERE specialtie_benefits_id = :ps", "ps={$PostData['specialtie_benefits_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['specialtie_benefits_icon']}") && !is_dir("../../uploads/{$Read->getResult()[0]['specialtie_benefits_icon']}")):
                unlink("../../uploads/{$Read->getResult()[0]['specialtie_benefits_icon']}");
            endif;

            $Delete->ExeDelete(DB_SPECIALTIES_BENEFITS, "WHERE specialtie_benefits_id = :id", "id={$PostData['specialtie_benefits_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Benefício Foi Excluído Com Sucesso!"];
            break;    
        	
        //CADASTRA PROCEDIMENTO
        case 'create_procedure':
            $SpecialtieId = $PostData['specialtie_id'];
            unset($PostData['specialtie_id']);
            
            if (empty($PostData['specialtie_procedure_title']) || empty($PostData['specialtie_procedure_price'])):
                $jSON['alert'] = ["red", "wondering2", "OPSSS {$_SESSION['userLogin']['user_name']}", "Para Cadastrar Um Procedimento, é Preciso Informar o Nome e o Valor. Por Favor, Tente Novamente!"];
            else:
                if (empty($PostData['specialtie_procedure_id'])):
                    //Realiza Cadastro
                    $PostData['specialtie_id'] = $SpecialtieId;
                    $PostData['specialtie_procedure_price'] = str_replace(',', '.', str_replace('.', '', $PostData['specialtie_procedure_price']));
                    $PostData['specialtie_procedure_datecreate'] = date("Y-m-d H:i:s");
                    $Create->ExeCreate(DB_SPECIALTIES_PROCEDURES, $PostData);

                    //Realtime
                    $jSON['add_content'] = null;

                    $Read->ExeRead(DB_SPECIALTIES_PROCEDURES, "WHERE specialtie_procedure_id = :id", "id={$Create->getResult()}");
                    if ($Read->getResult()):
                        extract($Read->getResult()[0]);
                        
                        $jSON['add_content'] = ['#specialtie-procedure' => "<div class='single_user_addr js-rel-to' id='{$specialtie_procedure_id}'> <h1 class='icon-list2'>{$specialtie_procedure_title}</h1> <p class='icon-coin-dollar'>R$" . number_format($specialtie_procedure_price, 2, ',', '.') . "</p> <div class='single_user_addr_actions'> <span title='Editar Procedimento' class='btn_header btn_darkaquablue icon-notext icon-pencil j_edit_procedure_modal' callback='Specialties' callback_action='edit_procedure' id='{$specialtie_procedure_id}'></span> <span title='Excluir Procedimento' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Specialties' callback_action='delete_procedure' id='{$specialtie_procedure_id}'></span></div></div>"];
                    endif;

                    $divremove = [".js-trigger", ".js-procedure"];
                    $jSON['divremove'] = $divremove;
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Procedimento Foi Cadastrado Com Sucesso!"];
                else:
                    $ProcedureId = $PostData['specialtie_procedure_id'];
                    unset($PostData['specialtie_procedure_id']);
                    
                    $PostData['specialtie_procedure_price'] = str_replace(',', '.', str_replace('.', '', $PostData['specialtie_procedure_price']));
                    $Update->ExeUpdate(DB_SPECIALTIES_PROCEDURES, $PostData, "WHERE specialtie_procedure_id = :id", "id={$ProcedureId}");

                    //RealTime
                    $jSON['divcontent']['#specialtie-procedure'] = null;
    
                    $Read->ExeRead(DB_SPECIALTIES_PROCEDURES, "ORDER BY specialtie_procedure_datecreate DESC");
                    if ($Read->getResult()):
                        foreach ($Read->getResult() as $PROCEDURE):
                            extract($PROCEDURE);
                            
                            $jSON['divcontent']['#specialtie-procedure'] .= "<div class='single_user_addr js-rel-to' id='{$specialtie_procedure_id}'> <h1 class='icon-list2'>{$specialtie_procedure_title}</h1> <p class='icon-coin-dollar'>R$" . number_format($specialtie_procedure_price, 2, ',', '.') . "</p> <div class='single_user_addr_actions'> <span title='Editar Procedimento' class='btn_header btn_darkaquablue icon-notext icon-pencil j_edit_procedure_modal' callback='Specialties' callback_action='edit_procedure' id='{$specialtie_procedure_id}'></span> <span title='Excluir Procedimento' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Specialties' callback_action='delete_procedure' id='{$specialtie_procedure_id}'></span></div></div>";
    
                        endforeach;
                    endif;
                    $jSON['divremove'] = ".js-procedure";
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Procedimento Foi Atualizado Com Sucesso!"];
                endif;
            endif;
            break;
            
         //EDITA MODAL PROCEDIMENTO    
         case 'edit_procedure':
            $ProcedureId = $PostData['edit_id'];
            $Read->ExeRead(DB_SPECIALTIES_PROCEDURES, "WHERE specialtie_procedure_id = :id", "id={$ProcedureId}");
            if ($Read->getResult()):
                $jSON['data'] = $Read->getResult()[0];
                $jSON['data']['specialtie_procedure_price'] = number_format($jSON['data']['specialtie_procedure_price'], 2, ',', '.');
            else:
                $jSON['alert'] = ["red", "wondering2", "ERRO", "Você Tentou Editar Um Procedimento Que Não Existe Ou Foi Removido!"];
            endif;
            break;

        //DELETA PROCEDIMENTO
        case 'delete_procedure':
            $ProcedureDel = $PostData['del_id'];
            $Delete->ExeDelete(DB_SPECIALTIES_PROCEDURES, "WHERE specialtie_procedure_id = :id", "id={$ProcedureDel}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Procedimento Foi Excluído Com Sucesso!"];
            break;

	//CADASTRA MÉDICO
        case 'create_doctor':
            $SpecialtieId = $PostData['specialtie_id'];
            unset($PostData['specialtie_id']);
            
            if (empty($PostData['doctor_id'])):
                $jSON['alert'] = ["yellow", "warning", "OPSSS {$_SESSION['userLogin']['user_name']}", "Para Realizar o Cadastro, é Preciso Escolher Um Médico. Por Favor, Tente Novamente!"];
            else:
                if (empty($PostData['specialtie_doctor_id'])):
                    //Realiza Cadastro
                    $PostData['specialtie_id'] = $SpecialtieId;
                    $PostData['specialtie_doctor_datecreate'] = date("Y-m-d H:i:s");
                    $Create->ExeCreate(DB_SPECIALTIES_DOCTORS, $PostData);

                    //Realtime
                    $jSON['add_content'] = null;

                    $Read->FullRead(
                    "SELECT "
                    . "s.specialtie_id, "
                    . "s.doctor_id, "
                    . "s.specialtie_doctor_id, "
                    . "s.specialtie_doctor_datecreate, "
                    . "d.doctor_name, "
                    . "d.doctor_email, "
                    . "d.doctor_cover, "
                    . "d.doctor_number_advice "
                    . "FROM " . DB_SPECIALTIES_DOCTORS . " s "
                    . "INNER JOIN " . DB_DOCTORS . " d ON d.doctor_id = s.doctor_id "
                    . "WHERE specialtie_doctor_id = :id "
                    . "AND s.specialtie_id = :specialtie "
                    . "ORDER BY specialtie_doctor_datecreate DESC", "specialtie={$SpecialtieId}&id={$Create->getResult()}"
                    );

                    if ($Read->getResult()):
                        extract($Read->getResult()[0]);
                        
                        $DoctorCover = "../uploads/{$doctor_cover}";
                        $doctor_cover = (file_exists($DoctorCover) && !is_dir($DoctorCover) ? "uploads/{$doctor_cover}" : 'admin/_img/no_avatar.jpg');
                        $jSON['add_content'] = ['#specialtie-doctor' => "<article class='single_user box box33 al_center js-rel-to' id='{$specialtie_doctor_id}' > <div class='box_content wc_normalize_height'> <img alt='Este é {$doctor_name}' title='Este é {$doctor_name}' src='../tim.php?src={$doctor_cover}&w=400&h=400'/> <h1>{$doctor_name}</h1> <div class='m_top'></div> <p class='info icon-clipboard'>CRO: " . $doctor_number_advice . "</p> <p class='info icon-envelop'>" . $doctor_email . "</p> </div> <div class='single_user_actions'> <a title='Editar Médico' class='btn_header btn_darkaquablue icon-pencil icon-notext' href='dashboard.php?wc=medicos/create&id={$doctor_id}'></a> <span title='Excluir Médico' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Specialties' callback_action='delete_doctor' id='{$specialtie_doctor_id}'></span> </div> </article>"];
                    endif;

                    $divremove = [".js-trigger", ".js-doctors"];
                    $jSON['divremove'] = $divremove;
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Médico Para Especialidade Foi Cadastrado Com Sucesso!"];
               endif;
            endif;
            break;
		
		//DELETA MÉDICO	
        case 'delete_doctor':
            $DoctorDel = $PostData['del_id'];
            $Delete->ExeDelete(DB_SPECIALTIES_DOCTORS, "WHERE specialtie_doctor_id = :id", "id={$DoctorDel}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Médico Foi Excluído Com Sucesso!"];
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
