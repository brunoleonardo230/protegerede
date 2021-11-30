<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_TESTIMONIALS;

if (!APP_TESTIMONIALS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Testimonials';
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
            $TesId = (!empty($PostData['testimonial_id']) ? $PostData['testimonial_id'] : null);

            if (empty($PostData['testimonial_name'])):
                $jSON['alert'] = ["yellow", "warning", "Desculpe {$_SESSION['userLogin']['user_name']}", "Aparentemente Existem Campos Em Branco. Por Favor Verifique e Tente Novamente!"];
            else:
                if (empty($TesId)):
                    //Realiza Cadastro
                    if (!empty($_FILES['testimonial_image'])):
                        //Realiza o Upload da Imagem
                        $File = $_FILES['testimonial_image'];
                        $Upload = new Upload('../../uploads/');
                        $Upload->Image($File, $PostData['testimonial_name'] . '-' . time(), THUMB_W, 'clientes');
                        if ($Upload->getResult()):
                            $PostData['testimonial_image'] = $Upload->getResult();
                        else:
                            $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Imagem!"];
                            echo json_encode($jSON);
                            return;
                        endif;
                    endif;

                    $PostData['testimonial_type'] = 1;
                    $PostData['testimonial_date'] = date('Y-m-d H:i:s');

                    $Create->ExeCreate(DB_TESTIMONIALS, $PostData);
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Depoimento Foi Cadastrado Com Sucesso!"];
                else:
                    unset($PostData['testimonial_image']);
                    //Atualiza Cadastro
                    $Read->ExeRead(DB_TESTIMONIALS, "WHERE testimonial_id = :id", "id={$TesId}");
                    if (!$Read->getResult()):
                        $jSON['alert'] = ["yellow", "warning", "OPSSS", "Desculpe {$_SESSION['userLogin']['user_name']}", "Não Encontramos o Depoimento Que Deseja Atualizar!"];
                        echo json_encode($jSON);
                        return;
                    endif;

                    if (!empty($_FILES['testimonial_image'])):
                        //Verifica se esta sendo enviado uma nova imagem
                        $File = $_FILES['testimonial_image'];
                        if ($Read->getResult()[0]['testimonial_image'] && file_exists("../../uploads/{$Read->getResult()[0]['testimonial_image']}") && !is_dir("../../uploads/{$Read->getResult()[0]['testimonial_image']}")):
                            //apaga imagem anterior
                            unlink("../../uploads/{$Read->getResult()[0]['testimonial_image']}");
                        endif;
                        //Envia nova Imagem
                        $Upload = new Upload('../../uploads/');
                        $Upload->Image($File, $PostData['testimonial_name'] . '-' . time(), THUMB_W, 'clientes');
                        $PostData['testimonial_image'] = $Upload->getResult();
                    endif;
                    $Update->ExeUpdate(DB_TESTIMONIALS, $PostData, "WHERE testimonial_id = :id", "id={$TesId}");
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Depoimento Foi Atualizado Com Sucesso!"];
                endif;

                //RealTime
                $jSON['divcontent']['#base'] = null;

                $Read->ExeRead(DB_TESTIMONIALS, "ORDER BY testimonial_date DESC");
                if ($Read->getResult()):
                    foreach ($Read->getResult() as $Testi):
                        $TestimonialCover = ($Testi['testimonial_image'] ? "../tim.php?src=uploads/" . $Testi['testimonial_image'] . "&w=" . AVATAR_W . "&h=" . AVATAR_H . "']" : ($Testi['fb_review_id'] ? 'https://graph.facebook.com/' . $Testi['fb_review_id'] . '/picture?type=large' : "admin/_img/no_image.jpg"));
                        $Type = ($Testi['testimonial_type'] == 1 ? '<span>MANUAL</span>' : ($Testi['testimonial_type'] == 2 ? '<span style="color:#3b5998">FACEBOOK</span>' : null));

                        $jSON['divcontent']['#base'] .= "<article class='box box25 post_single js-rel-to' id='{$Testi['testimonial_id']}'>
                        <header class='wc_normalize_height'><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
                        <img alt='[{$Testi['testimonial_name']}]' title='{$Testi['testimonial_name']}' style='width:100%' src='{$TestimonialCover}'/>
                             <div class='info'>
                                <p class='icon-bubbles3'><b>Cliente:</b> {$Testi['testimonial_name']}</p>   
                                <p class='icon-cog'><b>Origem:</b> {$Type}</p>    
                            </div>
                        </header>
                        <footer class='al_center'>
                            <span title='Editar Depoimento' class='btn_header btn_darkaquablue icon-pencil icon-notext jbs_action' cc='Testimonials' ca='edit' rel='{$Testi['testimonial_id']}'></span>
                            <span title='Excluir Depoimento' rel='post_single' callback='Testimonials' callback_action='delete' class='j_delete_action icon-bin icon-notext btn_header btn_red' id='{$Testi['testimonial_id']}'></span>
                        </footer>              
                    </article>";

                    endforeach;
                endif;

                //Actions
                $jSON['divremove'] = "#cadastro";
            endif;
            break;

        //Obtem Cadastros
        case "edit":
            $PhId = $PostData['action_id'];
            $Read->ExeRead(DB_TESTIMONIALS, "WHERE testimonial_id = :id", "id={$PhId}");
            if ($Read->getResult()):
                $Data = $Read->getResult()[0];

                $IMG = $Data['testimonial_image'];
                unset($Data['testimonial_image']);
                $TestimonialCover = ($IMG ? "../tim.php?src=uploads/" . $IMG . "&w=" . AVATAR_W . "&h=" . AVATAR_H . "']" : ($Data['fb_review_id'] ? 'https://graph.facebook.com/' . $Data['fb_review_id'] . '/picture?type=large' : "admin/_img/no_image.jpg")); 

                $jSON['divcontent']['.thumb_controll'] = "<img class='testimonial_image' alt='Capa' title='Capa' src='{$TestimonialCover}' default='../tim.php?src=admin/_img/no_image.jpg&w=" . AVATAR_W . "&h=" . AVATAR_H . "'/>";
                $jSON['form'] = ".j_testimonials";
                $jSON['result'] = $Data;
                $jSON['fadein'] = "#cadastro";
            else:
                $jSON['alert'] = ["yellow", "warning", "OPSSS", "Desculpe {$_SESSION['userLogin']['user_name']}", "Não Encontramos '<b>{$PostData['testimonial_name']}</b>'!"];
            endif;
            break;

        //DELETE
        case 'delete':
            $Read->FullRead("SELECT testimonial_image FROM " . DB_TESTIMONIALS . " WHERE testimonial_id = :ps", "ps={$PostData['del_id']}");
            if ($Read->getResult()):
                $ImageRemove = "../../uploads/{$Read->getResult()[0]['testimonial_image']}";
                if (file_exists($ImageRemove) && !is_dir($ImageRemove)):
                    unlink($ImageRemove);
                endif;
            endif;

            $Delete->ExeDelete(DB_TESTIMONIALS, "WHERE testimonial_id = :id", "id={$PostData['del_id']}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Depoimento Foi Excluído Com Sucesso!"];
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
