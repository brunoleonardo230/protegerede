<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_BRANDS;

if (!APP_BRANDS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = AjaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Brands';
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
        //GERENCIA MARCAS PARCEIRAS
        case 'manager':
            $BrandId = (!empty($PostData['brand_id']) ? $PostData['brand_id'] : null);
            
            if (empty($PostData['brand_name'])):
                $jSON['alert'] = ["red", "wondering2", "OPSSS {$_SESSION['userLogin']['user_name']}", "Para Cadastrar Uma Marca Parceira, é Preciso Informar Pelo Menos o Título. Por Favor, Tente Novamente!"];
            else:
                if (empty($BrandId)):
                    //Realiza Cadastro
                    if (!empty($_FILES['brand_image'])):
                        //Realiza o Upload da Imagem
                        $File = $_FILES['brand_image'];
                        $Upload = new Upload('../../uploads/');
                        $Upload->Image($File, Check::Name($PostData['brand_name']), '', 'marcas');
                        if ($Upload->getResult()):
                            $PostData['brand_image'] = $Upload->getResult();
                        else:
                            $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG Ou PNG Para Enviar Como Imagem!"];
                            echo json_encode($jSON);
                            return;
                        endif;
                    endif;

                    $PostData['brand_datecreate'] = date('Y-m-d H:i:s');

                    $Create->ExeCreate(DB_BRANDS, $PostData);
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "A Marca Parceira Foi Cadastrada Com Sucesso!"];
                else:
                    unset($PostData['brand_image']);
                    //Atualiza Cadastro
                    $Read->ExeRead(DB_BRANDS, "WHERE brand_id = :id", "id={$BrandId}");
                    if (!$Read->getResult()):
                        $jSON['alert'] = ["yellow", "image", "OPSSS, Não Foi Possível Atualizar!", "Desculpe {$_SESSION['userLogin']['user_name']}, Não Encontramos a Marca Parceira Que Deseja Atualizar!"];
                        echo json_encode($jSON);
                        return;
                    endif;

                    if (!empty($_FILES['brand_image'])):
                        //Verifica Se Está Sendo Enviada Uma Nova Imagem
                        $File = $_FILES['brand_image'];
                        if ($Read->getResult()[0]['brand_image'] && file_exists("../../uploads/{$Read->getResult()[0]['brand_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['brand_image']}")):
                            //Apaga Imagem Anterior
                            unlink("../../uploads/{$Read->getResult()[0]['brand_image']}");
                        endif;
                        //Envia Nova Imagem
                        $Upload = new Upload('../../uploads/');
                        $Upload->Image($File, Check::Name($PostData['brand_name']), '', 'marcas');
                        $PostData['brand_image'] = $Upload->getResult();
                    endif;
                    $Update->ExeUpdate(DB_BRANDS, $PostData, "WHERE brand_id = :id", "id={$BrandId}");
                endif;

                //RealTime
                $jSON['divcontent']['#base'] = null;

                $Read->ExeRead(DB_BRANDS, "ORDER BY brand_datecreate DESC");
                if ($Read->getResult()):
                    foreach ($Read->getResult() as $Brands):
                        extract($Brands);
                
                        $BrandImage = ($brand_image ? "../tim.php?src=uploads/" . $brand_image . "&w=300&h=100']" : "admin/_img/no_image.jpg");
                        
                        $jSON['divcontent']['#base'] .= "<article class='box box25 post_single js-rel-to' id='{$brand_id}'> <header class='wc_normalize_height'><meta http-equiv='Content-Type' content='text/html; charset=utf-8'> <img alt='{$brand_name}' title='{$brand_name}' style='width: 100%' src='{$BrandImage}'/> <div class='info'> <p class='icon-heart'><b>Marca Parceira: </b> {$brand_name}</p> </div> </header> <footer class='al_center'> <span title='Editar Marca Parceira' class='btn_header btn_darkaquablue icon-pencil icon-notext jbs_action' cc='Brands' ca='edit' rel='{$brand_id}'></span> <span title='Excluir Marca Parceira' class='j_delete_action icon-bin icon-notext btn_header btn_red' callback='Brands' callback_action='delete' id='{$brand_id}'></span> </footer> </article>";
                    endforeach;
                endif;

                //Actions
                $jSON['divremove'] = "#cadastro";
            endif;
            break;

        //EDITA MARCAS PARCEIRAS
        case "edit":
            $BrandId = $PostData['action_id'];
            $Read->ExeRead(DB_BRANDS, "WHERE brand_id = :id", "id={$BrandId}");
            if ($Read->getResult()):
                $Data = $Read->getResult()[0];

                $IMG = $Data['brand_image'];
                unset($Data['brand_image']);
                $BrandImage = ($IMG ? "../tim.php?src=uploads/" . $IMG . "&w=300&h=100']" : "admin/_img/no_image.jpg"); 

                $jSON['divcontent']['.thumb_controll'] = "<img class='brand_image' alt='Logotipo' title='Logotipo' src='{$BrandImage}' default='../tim.php?src=admin/_img/no_image.jpg&w=300&h=100'/>";
                $jSON['form'] = ".j_brands";
                $jSON['result'] = $Data;
                $jSON['fadein'] = "#cadastro";
            else:
                $jSON['alert'] = ["yellow", "image", "OPSSS, Não Foi Possível Atualizar!", "Desculpe {$_SESSION['userLogin']['user_name']}, Não Encontramos a Marca Parceira Que Deseja Atualizar!"];
            endif;
            break;

        //DELETE MARCAS PARCEIRAS
        case 'delete':
            $Read->FullRead("SELECT brand_image FROM " . DB_BRANDS . " WHERE brand_id = :ps", "ps={$PostData['del_id']}");
            if ($Read->getResult()):
                $ImageRemove = "../../uploads/{$Read->getResult()[0]['brand_image']}";
                if (file_exists($ImageRemove) && !is_dir($ImageRemove)):
                    unlink($ImageRemove);
                endif;
            endif;

            $Delete->ExeDelete(DB_BRANDS, "WHERE brand_id = :id", "id={$PostData['del_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Marca Parceira Foi Excluída Com Sucesso!"];
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
