<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_GALLERY;

if (empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Gallery';
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
        //DELETAR GALERIA
        case 'delete':
            $Read->FullRead("SELECT gallery_file FROM " . DB_GALLERY_IMAGES . " WHERE gallery_id = :ps", "ps={$PostData['del_id']}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $GalleryImage):
                    $ImageRemove = "../../uploads/{$GalleryImage['gallery_file']}";
                    if (file_exists($ImageRemove) && !is_dir($ImageRemove)):
                        unlink($ImageRemove);                        
                    endif;
                endforeach;
            endif;
            
            $Read->FullRead("SELECT gallery_cover FROM " . DB_GALLERY . " WHERE gallery_id = :ps", "ps={$PostData['del_id']}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $GalleryImage):
                    //EXCLUI A IMAGEM DE CAPA, EXCETO SE FOR A PADRÃO DO APP
                    if ($GalleryImage['gallery_cover'] != '../admin/_siswc/gallery/no-image.png'):
                        $ImageRemove = "../../uploads/{$GalleryImage['gallery_cover']}";
                        if (file_exists($ImageRemove) && !is_dir($ImageRemove)):
                            unlink($ImageRemove);
                        endif;
                    endif;    
                endforeach;
            endif;
            
            $Delete->ExeDelete(DB_GALLERY_IMAGES, "WHERE gallery_id = :id", "id={$PostData['del_id']}");
            $Delete->ExeDelete(DB_GALLERY, "WHERE gallery_id = :id", "id={$PostData['del_id']}");

            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Galeria Foi Excluída Com Sucesso!"];
            break;

        //EDITAR GALERIA
        case 'manage':
            $GalleryId = $PostData['gallery_id'];
            unset($PostData['gallery_id']);

            $PostData['gallery_status'] = (!empty($PostData['gallery_status']) ? '1' : '0');
            $PostData['gallery_order'] = (!empty($PostData['gallery_order']) ? $PostData['page_order'] : null);
            
            $Read->ExeRead(DB_GALLERY, "WHERE gallery_id= :id", "id={$GalleryId}");
            $ThisGallery = $Read->getResult()[0];
			
			$PostData['gallery_name'] = (!empty($PostData['gallery_name']) ? Check::Name($PostData['gallery_name']) : Check::Name($PostData['gallery_title']));
            $Read->ExeRead(DB_GALLERY, "WHERE gallery_id != :id AND gallery_name = :name", "id={$GalleryId}&name={$PostData['gallery_name']}");
            if ($Read->getResult()):
                $PostData['gallery_name'] = "{$PostData['gallery_name']}-{$GalleryId}";
            endif;
            $jSON['name'] = $PostData['gallery_name'];
            
            if (!empty($_FILES['gallery_cover'])):
                $File = $_FILES['gallery_cover'];

                if ($ThisGallery['gallery_cover'] && file_exists("../../uploads/{$ThisGallery['gallery_cover']}") && !is_dir("../../uploads/{$ThisGallery['gallery_cover']}")):
                    unlink("../../uploads/{$ThisGallery['gallery_cover']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, Check::Name($PostData['gallery_title']) . '-' . time(), IMAGE_W, 'gallery');
                if ($Upload->getResult()):
                    $PostData['gallery_cover'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Desculpe {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG Ou PNG Para Inserir Na Galeria!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['gallery_cover']);
            endif;

            
            $Read->FullRead("SELECT gallery_title FROM " . DB_GALLERY . " WHERE gallery_title = :nm AND gallery_id != :id", "nm={$PostData['gallery_title']}&id={$GalleryId}");
            if ($Read->getResult()):
                $PostData['gallery_title'] = "{$PostData['gallery_title']}-{$GalleryId}";
            endif;

            $Update->ExeUpdate(DB_GALLERY, $PostData, "WHERE gallery_id = :id", "id={$GalleryId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "Galeria Atualizada Com Sucesso!"];
            break;
                    
        case 'gallery_order':
            if (is_array($PostData['Data'])):
                foreach ($PostData['Data'] as $RE):
                    $UpdateCourse = ['gallery_order' => $RE[1]];
                    $Update->ExeUpdate(DB_GALLERY, $UpdateCourse, "WHERE gallery_id = :gallery", "gallery={$RE[0]}");
                endforeach;

                $jSON['sucess'] = true;
                $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "Galeria Ordenada Com Sucesso!"];
            endif;
            break;
            
        //IMAGENS DA GALERIA 
        case 'gallery_image':
            $GalleryId = $PostData['gallery_id']; //ID DA GALERIA QUE SERÁ ATUALIZADA
            unset($PostData['gallery_id']);
            $GalleryImages = $_FILES['gallery_images'];//ARRAY COM AS IMAGENS

            //VERIFICA SE A GALERIA EXISTE
            $Read->FullRead("SELECT gallery_title FROM " . DB_GALLERY . " WHERE gallery_id = :id", "id={$GalleryId}");
            if (!$Read->getResult()):
                $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Desculpe {$_SESSION['userLogin']['user_name']}, Mas Não Foi Possível Identificar a Galeria Vinculada!"];
            else:
                $GalleryTitle = $Read->getResult()[0]['gallery_title'];
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
                    $Upload->Image($gbUpload, Check::Name($GalleryTitle) . '-' . $i . time() , IMAGE_W, 'gallery');
                    
                    if ($Upload->getResult()):
                        $PostData['gallery_id'] = $GalleryId;
                        $PostData['gallery_file'] = $Upload->getResult();
                        $PostData['gallery_image_legend'] = $GalleryTitle;
                        $Create->ExeCreate(DB_GALLERY_IMAGES, $PostData);
                        $u++;
                    endif;
                endforeach;
                
                if ($u >= 1):
                    $jSON['divremove'] = ".js-trigger";
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "Fotos Enviadas Com Sucesso!"];
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Desculpe {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG Ou PNG Para Inserir Na Galeria!"];
                endif;
            endif;
            
            //RECARREGA A GALERIA
            $Read->ExeRead(DB_GALLERY_IMAGES, "WHERE gallery_id = :id ORDER BY gallery_image_order ASC", "id={$GalleryId}");
            if (!$Read->getResult()):
                Erro('Ainda Não Existe Nenhuma Foto Nessa Galeria!', E_USER_NOTICE);
            else:
                $GalleryHtml = '';
                foreach ($Read->getResult() as $gallery):
                    extract($gallery);
                    $GalleryHtml = $GalleryHtml . "<div class='panel_gallery_image wc_draganddrop js-rel-to' callback='Gallery' callback_action='gallery_image_order' id='{$gallery_image_id}' data-id='{$gallery_image_id}' >" .
                        "<img src='../tim.php?src=uploads/{$gallery_file}&w=200&h=200'>" .
                        "<div class='panel_gallery_action'><ul class='buttons'>" .
                        "<li><span title='Editar Galeria'class='j_edit_action icon-pencil icon-notext btn_header btn_darkaquablue'></span></li>" .
                        "<li><span rel='panel_gallery_image' class='j_delete_action icon-cancel-circle icon-notext btn_header btn_red' callback='Gallery' callback_action='gallery_image_delete' id='{$gallery_image_id}'></span></li>" .
                        "</ul></div>" .
                        "<span class='panel_gallery_image_legend al_center'>" . Check::Words($gallery_image_legend, 100) . "</span>" .
                        "</div>" .
                        "<script src=" . BASE ."/admin/_siswc/gallery/gallery.js></script>";
                endforeach;
            endif;  
            $jSON['gallery'] = $GalleryHtml;

            break;
            
        case 'gallery_image_order':
            if (is_array($PostData['Data'])):
                foreach ($PostData['Data'] as $RE):
                    $UpdateCourse = ['gallery_image_order' => $RE[1]];
                    $Update->ExeUpdate(DB_GALLERY_IMAGES, $UpdateCourse, "WHERE gallery_image_id = :gallery", "gallery={$RE[0]}");
                endforeach;

                $jSON['sucess'] = true;
            endif;
            break;    
            
        case 'gallery_image_delete':
            $Read->FullRead("SELECT gallery_file FROM " . DB_GALLERY_IMAGES . " WHERE gallery_image_id = :ps", "ps={$PostData['del_id']}");
            if ($Read->getResult()):
                $ImageRemove = "../../uploads/{$Read->getResult()[0]['gallery_file']}";
                if (file_exists($ImageRemove) && !is_dir($ImageRemove)):
                    unlink($ImageRemove);                        
                endif;
            endif;
            
            $Delete->ExeDelete(DB_GALLERY_IMAGES, "WHERE gallery_image_id = :id", "id={$PostData['del_id']}");

            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Imagem Foi Excluída Com Sucesso!"];
            break;  
            
        case 'gallery_legend':
            $Read->FullRead("SELECT gallery_image_legend FROM " . DB_GALLERY_IMAGES . " WHERE gallery_image_id = :ps", "ps={$PostData['gallery_image_id']}");
            if ($Read->getResult()): 
                $Legend = ['gallery_image_legend' => $PostData['gallery_image_legend']];
                $Update->ExeUpdate(DB_GALLERY_IMAGES, $Legend , "WHERE gallery_image_id = :gallery", "gallery={$PostData['gallery_image_id']}");

                $jSON['gallery'] = Check::Words($PostData['gallery_image_legend'], 80);
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
