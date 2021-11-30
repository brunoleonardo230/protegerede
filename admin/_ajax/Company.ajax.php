<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_COMPANY;

if (!APP_COMPANY || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Company';
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
        //GERENCIA EMPRESA
        case 'manager':
            $CompanyId = $PostData['company_id'];
            unset($PostData['company_id']);

            $Read->ExeRead(DB_COMPANY, "WHERE company_id = :id", "id={$CompanyId}");
            $ThisCompany = $Read->getResult()[0];
            
            $CompanyName = (!empty($PostData['company_name']) ? $PostData['company_name'] : $PostData['company_title']);
            $PostData['company_name'] = Check::Name($CompanyName);
            $Read->FullRead("SELECT company_name FROM " . DB_COMPANY . " WHERE company_name = :nm AND company_id != :id", "nm={$PostData['company_name']}&id={$CompanyId}");
            if ($Read->getResult()):
                $PostData['company_name'] = "{$PostData['company_name']}-{$CompanyId}";
            endif;
            $jSON['name'] = $PostData['company_name'];

            if (!empty($_FILES['company_image'])):
                $File = $_FILES['company_image'];

                if ($ThisCompany['company_image'] && file_exists("../../uploads/{$ThisCompany['company_image']}") && !is_dir("../../uploads/{$ThisCompany['company_image']}")):
                    unlink("../../uploads/{$ThisCompany['company_image']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, $PostData['company_name'] . '-' . time(), IMAGE_W, 'company');
                if ($Upload->getResult()):
                    $PostData['company_image'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG Ou PNG Para Enviar Como Capa!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['company_image']);
            endif;

            $PostData['company_datecreated'] = (!empty($PostData['company_datecreated']) ? Check::Data($PostData['company_datecreated']) : date('Y-m-d H:i:s'));

            $Update->ExeUpdate(DB_COMPANY, $PostData, "WHERE company_id = :id", "id={$CompanyId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Empresa <b>{$PostData['company_title']}</b> Foi Atualizada Com Sucesso!"];
            break;
            
        //DELETE
        case 'delete':
            $PostData['company_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT company_image FROM " . DB_COMPANY . " WHERE company_id = :ps", "ps={$PostData['company_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['company_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['company_image']}")):
                unlink("../../uploads/{$Read->getResult()[0]['company_image']}");
            endif;

            $Delete->ExeDelete(DB_COMPANY, "WHERE company_id = :id", "id={$PostData['company_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Empresa Foi Excluída Com Sucesso!"];
            break;        
            
        //CADASTRA MISSÃO, VISÃO E VALORES    
        case 'create_tripod':
            $CompanyId = $PostData['company_id'];
            unset($PostData['company_id']);

            $Update->ExeUpdate(DB_COMPANY, $PostData, "WHERE company_id = :id", "id={$CompanyId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Missão, Visão e Valores Foram Atualizadas Com Sucesso!"];
            break;    
        
        //CADASTRA BLOCO
        case 'create_block':
            $BlockId = $PostData['block_id'];
            unset($PostData['block_id']);

            $Read->ExeRead(DB_COMPANY_BLOCKS, "WHERE block_id = :id", "id={$BlockId}");
            $ThisBlock = $Read->getResult()[0];

            $PostData['block_name'] = (!empty($PostData['block_name']) ? Check::Name($PostData['block_name']) : Check::Name($PostData['block_title']));
            $Read->ExeRead(DB_COMPANY_BLOCKS, "WHERE block_id != :id AND block_name = :name", "id={$BlockId}&name={$PostData['block_name']}");
            if ($Read->getResult()):
                $PostData['block_name'] = "{$PostData['block_name']}-{$BlockId}";
            endif;
            $jSON['name'] = $PostData['block_name'];

            if (!empty($_FILES['block_image'])):
                $File = $_FILES['block_image'];

                if ($ThisBlock['block_image'] && file_exists("../../uploads/{$ThisBlock['block_image']}") && !is_dir("../../uploads/{$ThisBlock['block_image']}")):
                    unlink("../../uploads/{$ThisBlock['block_image']}");
                endif;

                $Upload = new Upload('../../uploads/');
                 $Upload->Image($File, $PostData['block_name'], IMAGE_W, 'company');
                if ($Upload->getResult()):
                    $PostData['block_image'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Capa!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['block_image']);
            endif;

            $PostData['block_status'] = (!empty($PostData['block_status']) ? '1' : '0');
            $PostData['block_datecreate'] = (!empty($PostData['block_datecreate']) ? Check::Data($PostData['block_datecreate']) : date('Y-m-d H:i:s'));

            $Update->ExeUpdate(DB_COMPANY_BLOCKS, $PostData, "WHERE block_id = :id", "id={$BlockId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Bloco <b>{$PostData['block_title']}</b> Foi Atualizado Com Sucesso!"];
            break;
            
        //DELETE BLOCO
        case 'delete_block':
            $PostData['block_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT block_image FROM " . DB_COMPANY_BLOCKS . " WHERE block_id = :ps", "ps={$PostData['block_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['block_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['block_image']}")):
                unlink("../../uploads/{$Read->getResult()[0]['block_image']}");
            endif;

            $Delete->ExeDelete(DB_COMPANY_BLOCKS, "WHERE block_id = :id", "id={$PostData['block_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Bloco Foi Excluído Com Sucesso!"];
            break;    
            
        //IMAGENS DA GALERIA 
        case 'gallery_image':
            $CompanyId = $PostData['company_id'];//ID DA EMPRESA QUE SERÁ ATUALIZADA
            unset($PostData['company_id']);
            $GalleryImages = $_FILES['gallery_images'];//ARRAY COM AS IMAGENS

            //VERIFICA SE A EMPRESA EXISTE
            $Read->FullRead("SELECT company_title FROM " . DB_COMPANY . " WHERE company_id = :id", "id={$CompanyId}");
            if (!$Read->getResult()):
                $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Desculpe {$_SESSION['userLogin']['user_name']}, Mas Não Foi Possível Identificar a Galeria Vinculada!"];
            else:
                $CompanyTitle = $Read->getResult()[0]['company_title'];
                //SE EXISTIR, ADICIONA AS FOTOS
                //PREPARA ARRAY COM TODOS OS ARQUIVOS
                $gbFiles = array();
                $gbCount = count($GalleryImages['tmp_name']);
                $gbKeys = array_keys($GalleryImages);

                for ($gb = 0; $gb < $gbCount; $gb++):
                    foreach ($gbKeys as $Keys):
                        $gbFiles[$gb][$Keys] = $GalleryImages[$Keys][$gb];
                    endforeach;
                endfor;
                
                //UPLOAD DE TODOS OS ARQUIVOS            
                $Upload = new Upload('../../uploads/');
                $i = 0; //LAÇO DE REPETIÇÃO UPLOAD
                $u = 0; //LAÇO DE REPETIÇÃO BANCO

                foreach ($gbFiles as $gbUpload):
                    $i++;
                    $Upload->Image($gbUpload, Check::Name($CompanyTitle) . '-' . $i . time() , IMAGE_W, 'company-gallery');
                    
                    if ($Upload->getResult()):
                        $PostData['company_id'] = $CompanyId;
                        $PostData['gallery_file'] = $Upload->getResult();
                        $PostData['gallery_image_legend'] = $CompanyTitle;
                        $Create->ExeCreate(DB_COMPANY_GALLERY, $PostData);
                        $u++;
                    endif;
                endforeach;
                
                if ($u >= 1):
                    $jSON['divremove'] = ".js-trigger";
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "Fotos Enviadas Com Sucesso!"];
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Desculpe {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG Ou PNG Para Inserir Na Galeria!"];
                endif;
            endif;
            
            //RECARREGA A GALERIA
            $Read->ExeRead(DB_COMPANY_GALLERY, "WHERE company_id = :id ORDER BY gallery_image_order ASC", "id={$CompanyId}");
            if (!$Read->getResult()):
                Erro('Ainda Não Existe Nenhuma Foto Nessa Galeria!', E_USER_NOTICE);
            else:
                $GalleryHtml = '';
                foreach ($Read->getResult() as $gallery):
                    extract($gallery);
                    $GalleryHtml = $GalleryHtml . "<div class='panel_gallery_image wc_draganddrop' callback='Company' callback_action='gallery_image_order' id='{$gallery_image_id}' data-id='{$gallery_image_id}' >" .
                        "<img src='../tim.php?src=uploads/{$gallery_file}&w=200&h=200'>" .
                        "<div class='panel_gallery_action'><ul class='buttons'>" .
                        "<li><span title='Editar Galeria' class='j_edit_action icon-pencil icon-notext btn_header btn_darkaquablue'></span></li>" .
                        "<li><span rel='panel_gallery_image' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Company' callback_action='gallery_image_delete' id='{$gallery_image_id}'></span></li>" .
                        "</ul></div>" .
                        "<span class='panel_gallery_image_legend al_center'>" . Check::Words($gallery_image_legend, 100) . "</span>" .
                        "</div>" .
                        "<script src=" . BASE ."/admin/_siswc/company/company.js></script>";
                endforeach;
            endif;  
            $jSON['gallery'] = $GalleryHtml;
            break;
            
        //ORDENA IMAGEM DA GALERIA    
        case 'gallery_image_order':
            if (is_array($PostData['Data'])):
                foreach ($PostData['Data'] as $RE):
                    $UpdateCourse = ['gallery_image_order' => $RE[1]];
                    $Update->ExeUpdate(DB_COMPANY_GALLERY, $UpdateCourse, "WHERE gallery_image_id = :gallery", "gallery={$RE[0]}");
                endforeach;

                $jSON['sucess'] = true;
            endif;
            break;    
            
        //DELETA IMAGEM DA GALERIA    
        case 'gallery_image_delete':
            $Read->FullRead("SELECT gallery_file FROM " . DB_COMPANY_GALLERY . " WHERE gallery_image_id = :ps", "ps={$PostData['del_id']}");
            if ($Read->getResult()):
                $ImageRemove = "../../uploads/{$Read->getResult()[0]['gallery_file']}";
                if (file_exists($ImageRemove) && !is_dir($ImageRemove)):
                    unlink($ImageRemove);                        
                endif;
            endif;
            
            $Delete->ExeDelete(DB_COMPANY_GALLERY, "WHERE gallery_image_id = :id", "id={$PostData['del_id']}");

            $jSON['success'] = true;
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Imagem Foi Excluída Com Sucesso!"];
            break;  
            
        case 'gallery_legend':
            $Read->FullRead("SELECT gallery_image_legend FROM " . DB_COMPANY_GALLERY . " WHERE gallery_image_id = :ps", "ps={$PostData['gallery_image_id']}");
            if ($Read->getResult()): 
                $Legend = ['gallery_image_legend' => $PostData['gallery_image_legend']];
                $Update->ExeUpdate(DB_COMPANY_GALLERY, $Legend , "WHERE gallery_image_id = :gallery", "gallery={$PostData['gallery_image_id']}");

                $jSON['gallery'] = Check::Words($PostData['gallery_image_legend'], 80);
            endif;
            break;
            
        //CADASTRA DIFERENCIAL    
        case 'create_differential':
        	$DifferentialId = $PostData['differential_id'];
        	unset($PostData['differential_id']);
        
        	$Read->ExeRead(DB_COMPANY_DIFFERENTIALS, "WHERE differential_id = :id", "id={$DifferentialId}");
        	$ThisDifferential = $Read->getResult()[0];
        
        	if (!empty($_FILES['differential_image'])):
        		$File = $_FILES['differential_image'];
        
        		if ($ThisDifferential['differential_image'] && file_exists("../../uploads/{$ThisDifferential['differential_image']}") && !is_dir("../../uploads/{$ThisDifferential['differential_image']}")):
        			unlink("../../uploads/{$ThisDifferential['differential_image']}");
        		endif;
        
        		$Upload = new Upload('../../uploads/');
        		 $Upload->Image($File, $PostData['differential_title'], IMAGE_W, 'company');
        		if ($Upload->getResult()):
        			$PostData['differential_image'] = $Upload->getResult();
        		else:
        			$jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Capa!"];
        			echo json_encode($jSON);
        			return;
        		endif;
        	else:
        		unset($PostData['differential_image']);
        	endif;
        	
        	if (!empty($_FILES['differential_icon'])):
        		$File = $_FILES['differential_icon'];
        
        		if ($ThisDifferential['differential_icon'] && file_exists("../../uploads/{$ThisDifferential['differential_icon']}") && !is_dir("../../uploads/{$ThisDifferential['differential_icon']}")):
        			unlink("../../uploads/{$ThisDifferential['differential_icon']}");
        		endif;
        
        		$Upload = new Upload('../../uploads/');
        		 $Upload->Image($File, $PostData['differential_title'], IMAGE_W, 'company');
        		if ($Upload->getResult()):
        			$PostData['differential_icon'] = $Upload->getResult();
        		else:
        			$jSON['alert'] = ["yellow", "warning", "ERRO AO ENVIAR ÍCONE", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Ícone!"];
        			echo json_encode($jSON);
        			return;
        		endif;
        	else:
        		unset($PostData['differential_icon']);
        	endif;
        
        	$PostData['differential_datecreate'] = (!empty($PostData['differential_datecreate']) ? Check::Data($PostData['differential_datecreate']) : date('Y-m-d H:i:s'));
        
        	$Update->ExeUpdate(DB_COMPANY_DIFFERENTIALS, $PostData, "WHERE differential_id = :id", "id={$DifferentialId}");
        	$jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Diferencial <b>{$PostData['differential_title']}</b> Foi Atualizado Com Sucesso!"];
        	break;
        	
        //DELETA DIFERENCIAL
        case 'delete_differential':
        	$PostData['differential_id'] = $PostData['del_id'];
        	$Read->FullRead("SELECT differential_image FROM " . DB_COMPANY_DIFFERENTIALS . " WHERE differential_id = :ps", "ps={$PostData['differential_id']}");
        	if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['differential_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['differential_image']}")):
        		unlink("../../uploads/{$Read->getResult()[0]['differential_image']}");
        	endif;
        
        	$Delete->ExeDelete(DB_COMPANY_DIFFERENTIALS, "WHERE differential_id = :id", "id={$PostData['differential_id']}");
        	$jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Diferencial Foi Excluído Com Sucesso!"];
        	break; 
        	
        //CADASTRA FAQ
        case 'create_faq':
            $CompanyId = $PostData['company_id'];
            unset($PostData['company_id']);
            
            if (empty($PostData['faq_title']) || empty($PostData['faq_content'])):
                $jSON['alert'] = ["yellow", "warning", "OPSSS {$_SESSION['userLogin']['user_name']}", "Para Cadastrar Uma Pergunta, é Preciso Informar a Pergunta e a Resposta. Por Favor, Tente Novamente!"];
            else:
                if (empty($PostData['faq_id'])):
                    //Realiza Cadastro
                    $PostData['company_id'] = $CompanyId;
                    $PostData['faq_datecreate'] = date("Y-m-d H:i:s");
                    $Create->ExeCreate(DB_COMPANY_FAQ, $PostData);

                    //Realtime
                    $jSON['add_content'] = null;

                    $Read->ExeRead(DB_COMPANY_FAQ, "WHERE faq_id = :id", "id={$Create->getResult()}");
                    if ($Read->getResult()):
                        extract($Read->getResult()[0]);
                        
                        $jSON['add_content'] = ['#company-faq' => "<div class='single_user_addr js-rel-to' id='{$faq_id}'> <h1 class='icon-info'>{$faq_title}</h1> <p>" . Check::Words($faq_content, 20) . "</p> <div class='single_user_addr_actions'> <span title='Editar FAQ' class='btn_header btn_darkaquablue icon-notext icon-pencil j_edit_faq_modal' callback='Company' callback_action='edit' id='{$faq_id}'></span> <span title='Excluir FAQ' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Company' callback_action='delete_faq' id='{$faq_id}'></span></div></div>"];
                    endif;

                    $divremove = [".js-trigger", ".js-faq"];
                    $jSON['divremove'] = $divremove;
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Sua Pergunta Foi Cadastrada Com Sucesso!"];
                else:
                    $FaqId = $PostData['faq_id'];
                    unset($PostData['faq_id']);
                    $Update->ExeUpdate(DB_COMPANY_FAQ, $PostData, "WHERE faq_id = :id", "id={$FaqId}");

                    //RealTime
                    $jSON['divcontent']['#company-faq'] = null;
    
                    $Read->ExeRead(DB_COMPANY_FAQ, "ORDER BY faq_datecreate DESC");
                    if ($Read->getResult()):
                        foreach ($Read->getResult() as $FAQ):
                            extract($FAQ);
                            
                            $jSON['divcontent']['#company-faq'] .= "<div class='single_user_addr js-rel-to' id='{$faq_id}'> <h1 class='icon-info'>{$faq_title}</h1> <p>" . Check::Words($faq_content, 20) . "</p> <div class='single_user_addr_actions'> <span title='Editar FAQ' class='btn_header btn_darkaquablue icon-notext icon-pencil j_edit_faq_modal' callback='Company' callback_action='edit' id='{$faq_id}'></span> <span title='Excluir FAQ' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Company' callback_action='delete_faq' id='{$faq_id}'></span></div></div>";
    
                        endforeach;
                    endif;
                    $jSON['divremove'] = ".js-faq";
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Sua Pergunta Foi Atualizada Com Sucesso!"];
                endif;
            endif;
            break;
            
         case 'edit_faq':
            $FaqId = $PostData['edit_id'];
            $Read->ExeRead(DB_COMPANY_FAQ, "WHERE faq_id = :id", "id={$FaqId}");
            if ($Read->getResult()):
                $jSON['data'] = $Read->getResult()[0];
            else:
                $jSON['alert'] = ["red", "wondering2", "ERRO", "Você Tentou Editar Uma Pergunta Que Não Existe Ou Foi Removida!"];
            endif;
            break;

        case 'delete_faq':
            $FaqDel = $PostData['del_id'];
            $Delete->ExeDelete(DB_COMPANY_FAQ, "WHERE faq_id = :id", "id={$FaqDel}");
            $jSON['success'] = true;
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
