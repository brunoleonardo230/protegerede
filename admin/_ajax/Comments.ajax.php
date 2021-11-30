<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_COMMENTS;

if (!APP_COMMENTS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Comments';
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

    //CLEAR LIKES
    $Delete->ExeDelete(DB_COMMENTS_LIKES, "WHERE user_id NOT IN(SELECT user_id FROM " . DB_USERS . ") AND id >= :id", "id=1");
    $Delete->ExeDelete(DB_COMMENTS_LIKES, "WHERE comm_id NOT IN(SELECT id FROM " . DB_COMMENTS . ") AND id >= :id", "id=1");

    //SELECIONA AÇÃO
    switch ($Case):
        //CURTIR
        case 'like':
            $Read->FullRead("SELECT id FROM " . DB_COMMENTS_LIKES . " WHERE user_id = :user AND comm_id = :comm", "user={$_SESSION['userLogin']['user_id']}&comm={$PostData['id']}");
            if (!$Read->getResult()):
                $LikeThis = ['user_id' => $_SESSION['userLogin']['user_id'], 'comm_id' => $PostData['id']];
                $Create->ExeCreate(DB_COMMENTS_LIKES, $LikeThis);
            endif;
            $UpdateData = ['status' => 1];
            $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :id", "id={$PostData['id']}");
            $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE alias_id = :id", "id={$PostData['id']}");
            $jSON['aprove'] = "<b class='icon-checkmark icon-notext'></b>";

            $jSON['like'] = true;
            $jSON['admin'] = "<a target='_blank' title='Ver Usuário' href='dashboard.php?wc=users/create&id={$_SESSION['userLogin']['user_id']}'>{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}</a>";
            break;

        //APROVAR
        case 'aprove':
            $UpdateData = ['status' => 1];
            $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :id OR alias_id = :id", "id={$PostData['id']}");

            //VERIFICA SE AINDA EXISTEM RESPOSTAS PENDENTES. SE NÃO, APROVA O COMENTÁRIO
            $Read->FullRead("SELECT alias_id FROM " . DB_COMMENTS . " WHERE id={$PostData['id']}");
            if ($Read->getResult()):
                $Alias = $Read->getResult()[0]['alias_id'];
                $Read->FullRead("SELECT id FROM " . DB_COMMENTS . " WHERE status > 1 AND alias_id = :id", "id={$Alias}");
                if (!$Read->getResult()):
                    $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :id", "id={$Alias}");
                    $jSON['alias'] = $Alias;
                endif;
            endif;
            $jSON['aprove'] = "<b class='icon-checkmark icon-notext'></b>";
            break;

        //RESPONDER
        case 'response':
            $Read->ExeRead(DB_COMMENTS, "WHERE id = :alias", "alias={$PostData['alias_id']}");
            if ($Read->getResult()):
                $Comm = $Read->getResult()[0];
                $ResponseTo = $PostData['user_id'];
                unset($PostData['user_id']);

                if (empty($PostData['comment'])):
                    //MSG DE ERRO
                    $jSON['alert'] = ["yellow", "warning", "OPSSS", "Você Esqueceu de Escrever a Resposta {$_SESSION['userLogin']['user_name']}, Para Responder Informe a Resposta!"];
                    echo json_encode($jSON);
                    return;
                endif;

                //CADASTRA COMENTÁRIO
                $PostData['user_id'] = $_SESSION['userLogin']['user_id'];
                $PostData['rank'] = 5;
                $PostData['created'] = date('Y-m-d H:i:s');
                $PostData['interact'] = date('Y-m-d H:i:s');
                $PostData['status'] = 1;
                $Create->ExeCreate(DB_COMMENTS, $PostData);

                //ATUALIZA RESPOSTAS
                $UpdateData = ['status' => 1];
                $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :id", "id={$PostData['alias_id']}");
                $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE alias_id = :id", "id={$PostData['alias_id']}");

                //OBTÉM LINK DO COMENTÁRIO
                if ($Comm['post_id']):
                    $Read->FullRead("SELECT post_name, post_title FROM " . DB_POSTS . " WHERE post_id = :id", "id={$Comm['post_id']}");
                    $Link = BASE . "/artigo/{$Read->getResult()[0]['post_name']}";
                    $Title = $Read->getResult()[0]['post_title'];
                elseif ($Comm['pdt_id']):
                    $Read->FullRead("SELECT pdt_name, pdt_title FROM " . DB_PDT . " WHERE pdt_id = :id", "id={$Comm['pdt_id']}");
                    $Link = BASE . "/produto/{$Read->getResult()[0]['pdt_name']}";
                    $Title = $Read->getResult()[0]['pdt_title'];
                elseif ($Comm['page_id']):
                    $Read->FullRead("SELECT page_name, page_title FROM " . DB_PAGES . " WHERE page_id = :id", "id={$Comm['page_id']}");
                    $Link = BASE . "/pagina/{$Read->getResult()[0]['page_name']}";
                    $Title = $Read->getResult()[0]['page_title'];
                endif;
                $Stars = str_repeat("<span class='icon-star-full icon-notext'></span>", $Comm['rank']);

                //AVISA AUTHOR SOBRE RESPOSTA
                $Email = new Email;
                require '../_tpl/Client.email.php';

                $Read->FullRead("SELECT user_name, user_lastname, user_email FROM " . DB_USERS . " WHERE user_id = :id", "id={$Comm['user_id']}");
                //EMAIL DE RESPOSTA A COMENTÁRIO
                $BodyMail = "
                    <p>Olá, você está recebendo esse e-mail pois recentemente deixou um comentário em <a title='{$Title}' href='{$Link}' target='_blank'>{$Title}</a>. Obrigado por seu comentário {$Read->getResult()[0]['user_name']}!</p>
                    <p>Meu nome é {$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}. Sou membro da equipe oficial " . SITE_NAME . ", e acabo de responder seu comentário :)</p>
                    <p>Acesse agora mesmo o link abaixo para ver minha resposta, e caso queira você pode continuar comentando...<p>
                    <p><a title='Ver Comentário' href='{$Link}#comment{$Create->getResult()}' target='_blank'>VER/RESPONDER COMENTÁRIO!</a></p>
                    <p>Qualquer dúvida que venha a ter, não deixe de responder este e-mail. E vamos atende-lo o mais breve possível.</p>
                    <p>Muito obrigado por sua interação conosco {$Read->getResult()[0]['user_name']}. Espero ter atendido suas expectativas em minha resposta!</p>
                    <p><i>{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']} - " . SITE_NAME . "</i></p>
                    ";
                $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
                $Email->EnviarMontando("{$Read->getResult()[0]['user_name']}, seu comentário foi respondido!", $Mensagem, SITE_NAME, MAIL_USER, "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}", $Read->getResult()[0]['user_email']);

                //AVISA SOBRE RESPOSTA NA RESPOSTA :P
                if ($ResponseTo != $Comm['user_id']):
                    $Read->FullRead("SELECT user_name, user_lastname, user_email FROM " . DB_USERS . " WHERE user_id = :id", "id={$ResponseTo}");
                    //EMAIL DE RESPOSTA A RESPOSTA
                    $BodyMail = "
                    <p>Olá, você está recebendo esse e-mail pois recentemente deixou um comentário em <a title='{$Title}' href='{$Link}' target='_blank'>{$Title}</a>. Obrigado por seu comentário {$Read->getResult()[0]['user_name']}!</p>
                    <p>Meu nome é {$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}. Sou membro da equipe oficial " . SITE_NAME . ", e deixei uma resposta para você :)</p>
                    <p>Acesse agora mesmo o link abaixo para ver minha resposta, e caso queira você pode continuar comentando...<p>
                    <p><a title='Ver Comentário' href='{$Link}#comment{$Create->getResult()}' target='_blank'>VER/RESPONDER COMENTÁRIO!</a></p>
                    <p>Qualquer dúvida que venha a ter, não deixe de responder este e-mail. E vamos atende-lo o mais breve possível.</p>
                    <p>Muito obrigado por sua interação conosco {$Read->getResult()[0]['user_name']}. Espero ter atendido suas expectativas em minha resposta...</p>
                    <p><i>{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']} - " . SITE_NAME . "</i></p>
                    ";
                    $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
                    $Email->EnviarMontando("{$Read->getResult()[0]['user_name']}, existe uma nova resposta em seu comentário!", $Mensagem, SITE_NAME, MAIL_USER, "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}", $Read->getResult()[0]['user_email']);
                endif;

                //COUNT REPLIES
                $Read->ExeRead(DB_COMMENTS, "WHERE alias_id = :id ORDER BY created ASC", "id={$PostData['alias_id']}");
                $Reply = $Read->getResult();

                if ($Reply):
                    $jSON['content'] = '';
                    foreach ($Reply as $ResponseReply):

                        //VAR USER
                        $Read->LinkResult(DB_USERS, "user_id", "{$ResponseReply['user_id']}", 'user_id, user_name, user_lastname, user_email, user_thumb');
                        $user_reply = $Read->getResult()[0];
                        $UserThumb = "../../uploads/{$user_reply['user_thumb']}";
                        $user_reply['user_thumb'] = (file_exists($UserThumb) && !is_dir($UserThumb) ? "uploads/{$user_reply['user_thumb']}" : 'admin/_img/no_avatar.jpg');

                        $jSON['content'] .= "<article class='ead_support_response ead_support_response_reply reply' id='{$ResponseReply['id']}'>
                                <div class='ead_support_response_avatar'>
                                    <img class='rounded' src='" . BASE . "/tim.php?src={$user_reply['user_thumb']}&w=" . round(AVATAR_W / 2) . "&h=" . round(AVATAR_H / 2) . "' alt='{$user_reply['user_name']}' title='{$user_reply['user_lastname']}'/>
                                </div><div class='ead_support_response_content'>
                                    <header class='ead_support_response_content_header'><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                                        <h1>Resposta de <a target='_blank' href='dashboard.php?wc=teach/students_gerent&id={$user_reply['user_id']}' title='{$user_reply['user_name']} {$user_reply['user_lastname']}'>{$user_reply['user_name']} {$user_reply['user_lastname']}</a> dia " . date('d/m/Y H\hi', strtotime($ResponseReply['created'])) . "</h1>
                                    </header>
                                    <div class='htmlchars reply_chars'>{$ResponseReply['comment']}</div>
                                    <div class='ead_support_response_actions'>
                                        <span rel='ead_support_response_reply' class='j_delete_action icon-cross btn btn_red' callback='Comments' callback_action='remove' id='{$ResponseReply['id']}'>Apagar</span>
                                        <span class='btn btn_darkaquablue icon-pencil2 center j_ead_support_action' data-action='ead_support_reply_edit' id='{$ResponseReply['id']}'>Editar</span>
                                    </div>
                                </div>       
                                <div class='ead_support_response_edit_modal reply'>
                                    <form name='class_adda' action='' method='post' enctype='multipart/form-data'>
                                        <p class='title icon-pencil2'>Atualizar Resposta de {$user_reply['user_name']} {$user_reply['user_lastname']}</p>
                                        <span class='btn btn_red icon-cross icon-notext ead_support_response_edit_modal_close j_comment_action_close'></span>
                                        <input type='hidden' name='callback' value='Comments'/>
                                        <input type='hidden' name='callback_action' value='edit_response'/>
                                        <input type='hidden' name='id' value='{$ResponseReply['id']}'/>
                                        <input type='hidden' name='user_id' value='{$ResponseReply['user_id']}'/>
                                        <input type='hidden' name='type' value='reply'/>
                                        <label class='label'>
                                            <textarea class='work_mce_basic' style='font-size: 1em;' name='comment' rows='3'>{$ResponseReply['comment']}</textarea>
                                        </label>
                                        <div class='wc_actions' style='margin-top: 15px;'>
                                            <button class='btn btn_darkaquablue icon-pencil2'>ATUALIZAR RESPOSTA</button>
                                            <img class='form_load none' style='margin-left: 10px;' alt='Enviando Requisição!' title='Enviando Requisição!' src='_img/load.gif'/>
                                        </div>
                                    </form>
                                </div>
                        </article>";
                    endforeach;
                endif;

                $jSON['divremove'] = '.ead_support_finish';
                $jSON['divcontent'] = [".j_comment_status", "<span class='status bar_aquablue radius'>Respondido</span>"];

                //RESPONSE NULL
                $jSON['success'] = true;
                $jSON['clear'] = true;

                //AVISO DE RESPOSTA EFETUADA
                $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "Sua Resposta Foi Enviada Com Sucesso!"];
            else:
                $jSON['alert'] = ["red", "wondering2", "ERRO", "Desculpe {$_SESSION['userLogin']['user_name']}, Mas Não Foi Possível Recuperar o Comentário Que Deseja Responder!"];
            endif;
            break;

        //DELETAR
        case 'remove':
            $Read->FullRead("SELECT alias_id FROM " . DB_COMMENTS . " WHERE id = :id", "id={$PostData['del_id']}");
            if ($Read->getResult()):
                $Comment = $Read->getResult()[0]['alias_id'];
                $Read->FullRead("SELECT id FROM " . DB_COMMENTS . " WHERE status > 1 AND alias_id = :id AND id != :this", "id={$Comment}&this={$PostData['del_id']}");
                if (!$Read->getResult()):
                    $UpdateData = ['status' => 1];
                    $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :id", "id={$Comment}");
                    $jSON['alias'] = $Comment;
                    $jSON['aprove'] = "<b class='icon-checkmark icon-notext'></b>";
                endif;
            endif;

            $Delete->ExeDelete(DB_COMMENTS_LIKES, "WHERE comm_id = :id OR comm_id IN(SELECT id FROM " . DB_COMMENTS . " WHERE alias_id = :id)", "id={$PostData['del_id']}");
            $Delete->ExeDelete(DB_COMMENTS, "WHERE alias_id = :id", "id={$PostData['del_id']}");
            $Delete->ExeDelete(DB_COMMENTS, "WHERE id = :id", "id={$PostData['del_id']}");
            $jSON['remove'] = true;
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "O Comentário Foi Excluído Com Sucesso!"];
            $jSON['redirect'] = "dashboard.php?wc=users/home";
            break;
            
        //DELETAR
        case 'remove_comment':
            $Read->FullRead("SELECT alias_id FROM " . DB_COMMENTS . " WHERE id = :id", "id={$PostData['del_id']}");
            if ($Read->getResult()):
                $Comment = $Read->getResult()[0]['alias_id'];
                $Read->FullRead("SELECT id FROM " . DB_COMMENTS . " WHERE status > 1 AND alias_id = :id AND id != :this", "id={$Comment}&this={$PostData['del_id']}");
                if (!$Read->getResult()):
                    $UpdateData = ['status' => 1];
                    $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :id", "id={$Comment}");
                    $jSON['alias'] = $Comment;
                    $jSON['aprove'] = "<b class='icon-checkmark icon-notext'></b>";
                endif;
            endif;

            $Delete->ExeDelete(DB_COMMENTS_LIKES, "WHERE comm_id = :id OR comm_id IN(SELECT id FROM " . DB_COMMENTS . " WHERE alias_id = :id)", "id={$PostData['del_id']}");
            $Delete->ExeDelete(DB_COMMENTS, "WHERE alias_id = :id", "id={$PostData['del_id']}");
            $Delete->ExeDelete(DB_COMMENTS, "WHERE id = :id", "id={$PostData['del_id']}");
            $jSON['remove'] = true;
            $jSON['redirect'] = "dashboard.php?wc=comments/comment_response";
            break;

        //EDITAR RESPOSTA
        case 'edit_response':
            $comment = ['comment' => $PostData['comment']];
            $Update->ExeUpdate(DB_COMMENTS, $comment, "WHERE id = :id", "id={$PostData['id']}");

            if ($PostData['type'] == 'comment'):
                $jSON['divcontent'] = ["#{$PostData['id']} .response_chars" => $PostData['comment']];
            else:
                $jSON['divcontent'] = ["#{$PostData['id']} .reply_chars" => $PostData['comment']];
            endif;
            $jSON['forceclick'] = "#{$PostData['id']} .j_comment_action_close";

            if ($Update->getResult()):
                if ($PostData['type'] == 'comment'):
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "O Comentário Foi Alterado Com Sucesso!"];
                else:
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "A Resposta Foi Alterada Com Sucesso!"];
                endif;
            else:
                $jSON['alert'] = ["red", "wondering2", "ERRO", "Desculpe {$_SESSION['userLogin']['user_name']}, Mas Não Foi Possível Editar o Conteúdo!"];
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
