<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_USERS;

if ((!APP_USERS && !APP_EAD) || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Users';
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
    $Upload = new Upload('../../uploads/');

    //SELECIONA AÇÃO
    switch ($Case):
        case 'manager':
            $UserId = $PostData['user_id'];
            unset($PostData['user_id'], $PostData['user_thumb']);

            $Read->FullRead("SELECT user_id FROM " . DB_USERS . " WHERE user_email = :email AND user_id != :id", "email={$PostData['user_email']}&id={$UserId}");
            if ($Read->getResult()):
                $jSON['alert'] = ["yellow", "warning", "OPSSS", "Olá {$_SESSION['userLogin']['user_name']}. O E-mail <b>{$PostData['user_email']}</b> Já Está Cadastrado Na Conta de Outro Usuário!"];
            else:
                $Read->FullRead("SELECT user_id FROM " . DB_USERS . " WHERE user_document = :dc AND user_id != :id", "dc={$PostData['user_document']}&id={$UserId}");
                if ($Read->getResult()):
                    $jSON['alert'] = ["yellow", "warning", "OPSSS", "Olá {$_SESSION['userLogin']['user_name']}. O CPF <b>{$PostData['user_document']}</b> Já Está Cadastrado Na Conta de Outro Usuário!"];
                else:
                    if (Check::CPF($PostData['user_document']) != true):
                        $jSON['alert'] = ["yellow", "warning", "OPSSS", "Olá {$_SESSION['userLogin']['user_name']}. O CPF <b>{$PostData['user_document']}</b> Informado Não é Válido!"];
                        echo json_encode($jSON);
                        return;
                    endif;

                    if (!empty($_FILES['user_thumb'])):
                        $UserThumb = $_FILES['user_thumb'];
                        $Read->FullRead("SELECT user_thumb FROM " . DB_USERS . " WHERE user_id = :id", "id={$UserId}");
                        if ($Read->getResult()):
                            if (file_exists("../../uploads/{$Read->getResult()[0]['user_thumb']}") && !is_dir("../../uploads/{$Read->getResult()[0]['user_thumb']}")):
                                unlink("../../uploads/{$Read->getResult()[0]['user_thumb']}");
                            endif;
                        endif;

                        $Upload->Image($UserThumb, $UserId . "-" . Check::Name($PostData['user_name'] . $PostData['user_lastname']) . '-' . time(), 600);
                        if ($Upload->getResult()):
                            $PostData['user_thumb'] = $Upload->getResult();
                        else:
                            $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR FOTO", "Olá {$_SESSION['userLogin']['user_name']}. Selecione Uma Imagem JPG ou PNG Para Enviar Como Foto!"];
                            echo json_encode($jSON);
                            return;
                        endif;
                    endif;

                    if (!empty($PostData['user_password'])):
                        if (strlen($PostData['user_password']) >= 5):
                            $PostData['user_password'] = hash('sha512', $PostData['user_password']);
                        else:
                            $jSON['alert'] = ["yellow", "warning", "ERRO DE SENHA", "Olá {$_SESSION['userLogin']['user_name']}. A Senha Deve Ter No Mínimo 5 Caracteres Para Ser Redefinida!"];
                            echo json_encode($jSON);
                            return;
                        endif;
                    else:
                        unset($PostData['user_password']);
                    endif;

                    if ($UserId == $_SESSION['userLogin']['user_id']):
                        if ($PostData['user_level'] != $_SESSION['userLogin']['user_level']):
                            $jSON['alert'] = ["green", "checkmark", "PERFIL ATUALIZADO COM SUCESSO", "Olá {$_SESSION['userLogin']['user_name']}. Seus Dados Foram Atualizados Com Sucesso! <br><br>Seu Nível de Usuário Não Foi Alterado Pois Não é Permitido Atualizar o Próprio Nível De Acesso!"];
                        else:
                            $jSON['alert'] = ["green", "checkmark", "PERFIL ATUALIZADO COM SUCESSO", "Olá {$_SESSION['userLogin']['user_name']}. Seus Dados Foram Atualizados Com Sucesso!"];
                        endif;
                        $SesseionRenew = true;
                        unset($PostData['user_level']);
                    elseif ($PostData['user_level'] > $_SESSION['userLogin']['user_level']):
                        $PostData['user_level'] = $_SESSION['userLogin']['user_level'];
                        $jSON['alert'] = ["green", "checkmark", "TUDO CERTO ", "Olá {$_SESSION['userLogin']['user_name']}. O Usuário {$PostData['user_name']} {$PostData['user_lastname']} Foi Atualizado Com Sucesso! <br><br>Você Não Pode Criar Usuários Com Nível de Acesso Maior Que o Seu. Então O Nível Gravado Foi " . getWcLevel($PostData['user_level']) . "!"];
                    else:
                        $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "Olá {$_SESSION['userLogin']['user_name']}. O Usuário {$PostData['user_name']} {$PostData['user_lastname']} Foi Atualizado Com Sucesso!"];
                    endif;

                    $PostData['user_datebirth'] = (!empty($PostData['user_datebirth']) ? Check::Nascimento($PostData['user_datebirth']) : null);

                    //ATUALIZA USUÁRIO
                    $Update->ExeUpdate(DB_USERS, $PostData, "WHERE user_id = :id", "id={$UserId}");
                    if (!empty($SesseionRenew)):
                        $Read->ExeRead(DB_USERS, "WHERE user_id = :id", "id={$UserId}");
                        if ($Read->getResult()):
                            $_SESSION['userLogin'] = $Read->getResult()[0];
                        endif;
                    endif;
                endif;
            endif;
            break;

        case 'delete':
            $UserId = $PostData['del_id'];
            $Read->ExeRead(DB_USERS, "WHERE user_id = :user", "user={$UserId}");
            if (!$Read->getResult()):
                $jSON['alert'] = ["yellow", "warning", "USUÁRIO NÃO EXISTE", "Olá {$_SESSION['userLogin']['user_name']}. Você Tentou Deletar Um Usuário Que Não Existe ou Já Foi Removido!"];
            else:
                extract($Read->getResult()[0]);
                if ($user_id == $_SESSION['userLogin']['user_id']):
                    $jSON['alert'] = ["yellow", "warning", "OPSSS", "Olá {$_SESSION['userLogin']['user_name']}. Por Questões de Segurança, o Sistema Não Permite Que Você Remova Sua Própria Conta!"];
                elseif ($user_level > $_SESSION['userLogin']['user_level']):
                    $jSON['alert'] = ["red", "wondering2", "PERMISSÃO NEGADA", "Desculpe {$_SESSION['userLogin']['user_name']}. Mas {$user_name} Tem Acesso Superior Ao Seu. Você Não Pode Removê-lo!"];
                else:
                    $Delete->ExeDelete(DB_ORDERS_ITEMS, "WHERE order_id IN(SELECT order_id FROM " . DB_ORDERS . " WHERE user_id = :user)", "user={$user_id}");
                    $Delete->ExeDelete(DB_ORDERS, "WHERE user_id = :user", "user={$user_id}");
                    $Delete->ExeDelete(DB_USERS_ADDR, "WHERE user_id = :user", "user={$user_id}");

                    //COMMENT CONTROL
                    $Read->FullRead("SELECT id FROM " . DB_COMMENTS . " WHERE user_id = :user", "user={$user_id}");
                    if ($Read->getResult()):
                        //RESPONSES REMOVE
                        foreach ($Read->getResult() as $DelId):
                            $Delete->ExeDelete(DB_COMMENTS, "WHERE alias_id = :in", "in={$DelId['id']}");
                        endforeach;
                        //COMMENT REMOVE
                        $Delete->ExeDelete(DB_COMMENTS, "WHERE user_id = :user", "user={$user_id}");
                        $Delete->ExeDelete(DB_COMMENTS_LIKES, "WHERE user_id = :user", "user={$user_id}");
                    endif;

                    if (file_exists("../../uploads/{$user_thumb}") && !is_dir("../../uploads/{$user_thumb}")):
                        unlink("../../uploads/{$user_thumb}");
                    endif;

                    $Delete->ExeDelete(DB_USERS, "WHERE user_id = :user", "user={$user_id}");
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Usuário Foi Removido Com Sucesso!"];
                    $jSON['redirect'] = "dashboard.php?wc=users/home";
                endif;
            endif;
            break;

        case 'addr_add':
            $AddrId = $PostData['addr_id'];
            unset($PostData['addr_id']);

            $Update->ExeUpdate(DB_USERS_ADDR, $PostData, "WHERE addr_id = :addr", "addr={$AddrId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "O Endereço Foi Atualizado Com Sucesso!"];
            break;

        case 'addr_delete':
            $Read->ExeRead(DB_ORDERS, "WHERE order_addr = :addr", "addr={$PostData['del_id']}");
            if ($Read->getResult()):
                $jSON['alert'] = ["red", "wondering2", "ERRO AO DELETAR", "Olá {$_SESSION['userLogin']['user_name']}, Deletar Um Endereço Vinculado a Pedidos Não é Permitido Pelo Sistema!"];
            else:
                $Delete->ExeDelete(DB_USERS_ADDR, "WHERE addr_id = :addr", "addr={$PostData['del_id']}");
                $jSON['sucess'] = true;
                $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "O Endereço do Usuário Foi Removido Com Sucesso!"];
            endif;
            break;

        case 'block_user':

            //ADD NOTE
            $Read->ExeRead(DB_USERS, "WHERE user_id = :user", "user={$PostData['admin_id']}");
            $AdminName = $Read->getResult()[0]['user_name'] . ' ' . $Read->getResult()[0]['user_lastname'];
            $NoteBlock = [
                'user_id' => $PostData['user_id'],
                'admin_id' => $PostData['admin_id'],
                'note_text' => "<b class='font_red'>Usuário Bloqueado</b> Motivo: {$PostData['user_blocking_reason']}",
                'note_datetime' => date('Y-m-d H:i:s')
            ];

            $Create->ExeCreate(DB_USERS_NOTES, $NoteBlock);

            //BLOCK USER
            $Block = [
                'user_blocking_reason' => $PostData['user_blocking_reason']
            ];
            $Update->ExeUpdate(DB_USERS, $Block, "WHERE user_id = :user", "user={$PostData['user_id']}");

            //SEND NOTIFICATION
            $Read->LinkResult(DB_USERS, "user_id", $PostData['user_id']);
            $Student = $Read->getResult()[0];

            require '../../_ead/wc_ead.email.php';
            $MailBody = "
                    <p style='font-size: 1.4em;'>Olá {$Student['user_name']},</p>
                    <p>Este e-mail é para informar que sua conta foi <b>bloqueada</b> na nossa Escola Online.</p>
                    <p>Analise o motivo do bloqueio abaixo:</p>
                    <p>{$Block['user_blocking_reason']}</p>
                    <p>Se acredita que sua conta foi bloqueada de forma equivocada, não deixe de responder este e-mail!</p>
                ";

            $MailContent = str_replace("#mail_body#", $MailBody, $MailContent);
            $Email = new Email;
            $Email->EnviarMontando("Sua Conta Foi Suspensa da Escola Online!", $MailContent, MAIL_SENDER, MAIL_USER, "{$Student['user_name']} {$Student['user_lastname']}", $Student['user_email']);


            $jSON['redirect'] = 'dashboard.php?wc=teach/students_gerent&id=' . $PostData['user_id'];
            $jSON['success'] = true;
            $jSON['clear'] = true;
            break;

        case 'unblock_user':

            //ADD NOTE
            $Read->ExeRead(DB_USERS, "WHERE user_id = :user", "user={$PostData['admin_id']}");
            $AdminName = $Read->getResult()[0]['user_name'] . ' ' . $Read->getResult()[0]['user_lastname'];
            $NoteBlock = [
                'user_id' => $PostData['user_id'],
                'admin_id' => $PostData['admin_id'],
                'note_text' => "<b class='font_green'>Usuário Desbloqueado!</b> Motivo: {$PostData['user_blocking_reason']}",
                'note_datetime' => date('Y-m-d H:i:s')
            ];

            $Create->ExeCreate(DB_USERS_NOTES, $NoteBlock);

            //BLOCK USER
            $Block = [
                'user_blocking_reason' => null
            ];
            $Update->ExeUpdate(DB_USERS, $Block, "WHERE user_id = :user", "user={$PostData['user_id']}");

            //SEND NOTIFICATION
            $Read->LinkResult(DB_USERS, "user_id", $PostData['user_id']);
            $Student = $Read->getResult()[0];

            require '../../_ead/wc_ead.email.php';
            $MailBody = "
                    <p style='font-size: 1.4em;'>Olá {$Student['user_name']},</p>
                    <p>Este e-mail é para informar que sua conta foi <b>desbloqueada</b> na nossa Escola Online.</p>
                    <p>Seja bem vindo de volta!</p>
                    <p>Se tiver qualquer problema, não deixe de responder este e-mail!</p>
                ";

            $MailContent = str_replace("#mail_body#", $MailBody, $MailContent);
            $Email = new Email;
            $Email->EnviarMontando("Sua Conta Foi Desbloqueada Na Escola Online!", $MailContent, MAIL_SENDER, MAIL_USER, "{$Student['user_name']} {$Student['user_lastname']}", $Student['user_email']);

            $jSON['redirect'] = 'dashboard.php?wc=teach/students_gerent&id=' . $PostData['user_id'];
            $jSON['success'] = true;
            $jSON['clear'] = true;
            break;

        case 'note_draft':
            $Draft = ['note_status' => 1];
            $Update->ExeUpdate(DB_USERS_NOTES, $Draft, "WHERE note_id = :id", "id={$PostData['del_id']}");
            $jSON['success'] = true;
            break;

        case 'note_add':
            $Note = [
                'user_id' => $PostData['user_id'],
                'admin_id' => $PostData['admin_id'],
                'note_text' => $PostData['note_text'],
                'note_datetime' => date('Y-m-d H:i:s')
            ];

            $Create->ExeCreate(DB_USERS_NOTES, $Note);

            //GET NOTES USER
            $Read->ExeRead(DB_USERS_NOTES, "WHERE user_id = :user AND note_status IS NULL ORDER BY note_datetime DESC", "user={$PostData['user_id']}");
            if ($Read->getResult()):
                $ContentDiv = "";

                foreach ($Read->getResult() as $Note):
                    $Read->LinkResult(DB_USERS, "user_id", "{$Note['admin_id']}", 'user_id, user_name, user_lastname');
                    $UserName = $Read->getResult()[0]['user_name'] . ' ' . $Read->getResult()[0]['user_lastname'];
                    $DateNote = date('d/m/Y H:i', strtotime($Note['note_datetime']));
                    $ContentDiv .= "<article class='student_gerent_home_anotation' id='" . $Note['note_id'] . "'>
                        <span class='icon-cross icon-notext student_gerent_home_anotation_remove j_delete_action_confirm' callback='Users' callback_action='note_draft' id='" . $Note['note_id'] . "' rel='student_gerent_home_anotation'></span>
                        <div class='student_gerent_home_anotation_content icon-pushpin'>
                            " . nl2br($Note['note_text']) . "
                            <p class='icon-calendar'>" . $DateNote . " por " . $UserName . "</p>
                        </div>
                    </article>";
                endforeach;
            endif;

            $jSON['content'] = ['.j_content_note' => $ContentDiv];
            $jSON['success'] = true;
            $jSON['clear'] = true;
            break;

        case 'list_notes_all':

            //GET NOTES USER
            $Read->ExeRead(DB_USERS_NOTES, "WHERE user_id = :user ORDER BY note_datetime DESC", "user={$PostData['user_id']}");
            if ($Read->getResult()):
                $ContentDiv = "";

                foreach ($Read->getResult() as $Note):
                    $Read->LinkResult(DB_USERS, "user_id", "{$Note['admin_id']}", 'user_id, user_name, user_lastname');
                    $UserName = $Read->getResult()[0]['user_name'] . ' ' . $Read->getResult()[0]['user_lastname'];
                    $DateNote = date('d/m/Y H:i', strtotime($Note['note_datetime']));
                    $ContentDiv .= "<article class='student_gerent_home_anotation' id='" . $Note['note_id'] . "'>
                        <span class='icon-cross icon-notext student_gerent_home_anotation_remove j_delete_action_confirm' callback='Users' callback_action='note_draft' id='" . $Note['note_id'] . "' rel='student_gerent_home_anotation'></span>
                        <div class='student_gerent_home_anotation_content icon-pushpin'>
                            " . nl2br($Note['note_text']) . "
                            <p class='icon-calendar'>" . $DateNote . " por " . $UserName . "</p>
                        </div>
                    </article>";
                endforeach;
            endif;

            $jSON['content'] = ['.j_content_note' => $ContentDiv];
            $jSON['success'] = true;
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
