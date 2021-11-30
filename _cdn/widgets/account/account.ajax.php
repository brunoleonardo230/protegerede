<?php

session_start();

$getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($getPost) || empty($getPost['action'])):
    die('Acesso Negado!');
endif;

$strPost = array_map('strip_tags', $getPost);
$POST = array_map('trim', $strPost);

$Action = $POST['action'];
$jSON = null;
unset($POST['action']);
unset($POST['user_level']);

usleep(2000);

require '../../../_app/Config.inc.php';
$Read = new Read;
$Create = new Create;
$Update = new Update;

switch ($Action):
    //LOGIN
    case 'wc_login':
        if (in_array('', $POST)):
            $jSON['trigger'] = AjaxErro("Favor Informe Seu E-mail e Senha Para Logar!", E_USER_WARNING);
        elseif (!Check::Email($POST['user_email']) || !filter_var($POST['user_email'], FILTER_VALIDATE_EMAIL)):
            $jSON['trigger'] = AjaxErro("O E-mail Informado Não Tem Um Formato Válido!", E_USER_WARNING);
        elseif (strlen($POST['user_password']) < 5):
            $jSON['trigger'] = AjaxErro("Sua Senha Deve Conter No Mínimo 5 Caracteres!", E_USER_WARNING);
        else:
            $Password = hash("sha512", $POST['user_password']);
            $Read->ExeRead(DB_USERS, "WHERE user_email = :email AND user_password = :pass", "email={$POST['user_email']}&pass={$Password}");
            if (!$Read->getResult()):
                $jSON['trigger'] = AjaxErro("Os Dados Informados Não Conferem. Informe Seu E-mail e Senha!", E_USER_WARNING);
            else:
                $_SESSION['userLogin'] = $Read->getResult()[0];
                $jSON['clear'] = true;
                $jSON['redirect'] = BASE . "/conta/home#acc";

                $LoginUpdate = ['user_login' => time(), "user_lastaccess" => date("Y-m-d H:i:s")];
                $Update->ExeUpdate(DB_USERS, $LoginUpdate, "WHERE user_id = :id", "id={$Read->getResult()[0]['user_id']}");
            endif;
        endif;
        break;

    //CREATE
    case 'wc_create':
        if (in_array('', $POST)):
            $jSON['trigger'] = AjaxErro("Por Favor, Preencha Todos os Campos Para Criar Sua Nova Conta!", E_USER_WARNING);
        elseif (!Check::Email($POST['user_email']) || !filter_var($POST['user_email'], FILTER_VALIDATE_EMAIL)):
            $jSON['trigger'] = AjaxErro("Opsss! O E-mail Informado Não Parece Ter Um Formato Válido!", E_USER_WARNING);
        elseif (strlen($POST['user_password']) < 5):
            $jSON['trigger'] = AjaxErro("Opsss! Sua Senha Deve Ter No Mínimo 5 Caracteres!", E_USER_WARNING);
        else:
            $Read->FullRead("SELECT user_email FROM " . DB_USERS . " WHERE user_email = :email", "email={$POST['user_email']}");
            if ($Read->getResult()):
                $jSON['trigger'] = AjaxErro("Desculpe, Mas o E-mail <b>{$POST['user_email']}</b> Já Está Cadastrado!", E_USER_ERROR);
            else:
                $POST['user_password'] = hash("sha512", $POST['user_password']);
                $POST['user_registration'] = date("Y-m-d H:i:s");
                $POST['user_lastupdate'] = date("Y-m-d H:i:s");
                $POST['user_lastaccess'] = date("Y-m-d H:i:s");
                $POST['user_channel'] = "Cadastro";
                $POST['user_level'] = 1;

                $Create->ExeCreate(DB_USERS, $POST);
                $POST['user_id'] = $Create->getResult();
                $_SESSION['userLogin'] = $POST;

                $jSON['trigger'] = AjaxErro("Seja Muito Bem-vindo ao " . SITE_NAME . " {$POST['user_name']}!");
                $jSON['redirect'] = BASE . "/conta/home#acc";
            endif;
        endif;
        break;

    //RECOVER
    case 'wc_recover':
        if (in_array('', $POST)):
            $jSON['trigger'] = AjaxErro("Por Favor, Informe Seu E-mail Para Continuar!", E_USER_WARNING);
        elseif (!Check::Email($POST['user_email']) || !filter_var($POST['user_email'], FILTER_VALIDATE_EMAIL)):
            $jSON['trigger'] = AjaxErro("O E-mail Informado Não Tem Um Formato Válido!", E_USER_WARNING);
        else:
            $Read->ExeRead(DB_USERS, "WHERE user_email = :email", "email={$POST['user_email']}");
            if (!$Read->getResult()):
                $jSON['trigger'] = AjaxErro("O E-mail Informado Não Está Cadastrado Em Nosso Site!", E_USER_WARNING);
            else:
                $AccountUser = $Read->getResult()[0];
                $HashRecover = base64_encode($Read->getResult()[0]['user_email']) . "pass" . $Read->getResult()[0]['user_password'];
                $LinkRecover = BASE . "/conta/nova-senha/{$HashRecover}";

                setcookie("wc_recover_passtowd", base64_encode($Read->getResult()[0]['user_email']), time() + 3600, '/');

                //SEND MAIL RECOVER
                //SEND CODE TO LOGIN
                require_once './account.email.php';
                $BodyMail = "
                    <p>Olá {$AccountUser['user_name']}, você está recebendo esse e-mail pois solicitou uma nova senha em nosso site.</p>
                    <p>Caso não tenha solicitado essa senha. Por favor nos desculpe pelo incomodo. E apenas ignore este e-mail :)</p>
                    <p>Caso Contrário:</p>
                    <p><a title='Recuperar Minha Senha' href='{$LinkRecover}#acc'>RECUPERAR MINHA SENHA AGORA!</a></p>
                    <p>Ao clicar no link acima você será redirecionado para criar uma nova senha, e assim recuperar seu acesso!</p>
                    <p><i>Atenciosamente, " . SITE_NAME . "!</i></p>
                    ";
                $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
                $SendEmail = new Email;
                $SendEmail->EnviarMontando("Recupere Sua Senha {$AccountUser['user_name']}!", $Mensagem, SITE_NAME, MAIL_USER, "{$AccountUser['user_name']} {$AccountUser['user_lastname']}", $AccountUser['user_email']);
                $jSON['trigger'] = AjaxErro("Olá {$AccountUser['user_name']}. Enviamos os Dados de Acesso Para Seu E-mail :)");
                $jSON['clear'] = true;
            endif;
        endif;
        break;

    //RESET
    case 'wc_newpass':
        if (empty($_SESSION['userRecoverId'])):
            $jSON['redirect'] = BASE . "/conta/recuperar#acc";
        elseif (in_array('', $POST)):
            $jSON['trigger'] = AjaxErro("Por Favor, Informe e Repita Sua Nova Senha!", E_USER_WARNING);
        elseif (strlen($POST['user_password']) < 5):
            $jSON['trigger'] = AjaxErro("Sua Nova Senha Deve Ter No Mínimo 5 Caracteres!", E_USER_WARNING);
        elseif ($POST['user_password'] != $POST['user_password_r']):
            $jSON['trigger'] = AjaxErro("Você Informou Duas Senhas Diferentes!", E_USER_WARNING);
        else:
            $UpdatePassword = ['user_password' => hash("sha512", $POST['user_password']), 'user_lastupdate' => date('Y-m-d H:i:s')];
            $Update->ExeUpdate(DB_USERS, $UpdatePassword, "WHERE user_id = :id", "id={$_SESSION['userRecoverId']}");
            $Read->ExeRead(DB_USERS, "WHERE user_id = :id", "id={$_SESSION['userRecoverId']}");
            if ($Read->getResult()):
                $_SESSION['userLogin'] = $Read->getResult()[0];
                if (!empty($_SESSION['userRecoverId'])):
                    unset($_SESSION['userRecoverId']);
                endif;
                $jSON['trigger'] = AjaxErro("Olá {$_SESSION['userLogin']['user_name']}, Sua Senha Foi Alterada! <a href='" . BASE . "/conta/home' title='Acessar Minha Conta!'>Acessar Minha Conta!</a>");
                $jSON['clear'] = true;

                setcookie("wc_recover_passtowd", base64_encode($Read->getResult()[0]['user_email']), time(), '/');
            else:
                $jSON['trigger'] = AjaxErro("Você Informou Duas Senhas Diferentes!", E_USER_WARNING);
            endif;
        endif;
        break;

    //USER
    case 'wc_user':
        if (empty($_SESSION['userLogin']['user_id']) || empty($_SESSION['userLogin']['user_email'])):
            unset($_SESSION['userLogin']);
            $jSON['redirect'] = BASE . "/conta/sair#acc";
        elseif (empty($POST['user_name']) || empty($POST['user_lastname']) || empty($POST['user_genre'])):
            $jSON['trigger'] = AjaxErro("Opssss {$_SESSION['userLogin']['user_name']}, Você Deve Preencher os Campos Obrigatórios (*)!", E_USER_WARNING);
        else:
            $UserId = $_SESSION['userLogin']['user_id'];
            $UserEmail = $_SESSION['userLogin']['user_email'];
            unset($POST['user_thumb']);

            if (!empty($_FILES['user_thumb'])):
                $UserThumb = $_FILES['user_thumb'];
                $Read->FullRead("SELECT user_thumb FROM " . DB_USERS . " WHERE user_id = :id", "id={$UserId}");
                if ($Read->getResult()):
                    if (file_exists("../../../uploads/{$Read->getResult()[0]['user_thumb']}") && !is_dir("../../../uploads/{$Read->getResult()[0]['user_thumb']}")):
                        unlink("../../../uploads/{$Read->getResult()[0]['user_thumb']}");
                    endif;
                endif;

                $Upload = new Upload('../../../uploads/');
                $Upload->Image($UserThumb, $UserId . "-" . Check::Name($POST['user_name'] . $POST['user_lastname']), AVATAR_W);
                if ($Upload->getResult()):
                    $POST['user_thumb'] = $Upload->getResult();
                else:
                    $jSON['trigger'] = AjaxErro("Opssss {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem Jpg ou Png Para Enviar Sua Foto!", E_USER_WARNING);
                    echo json_encode($jSON);
                    return;
                endif;
            endif;

            if (!empty($POST['user_password'])):
                if (strlen($POST['user_password']) >= 5):
                    $POST['user_password'] = hash('sha512', $POST['user_password']);
                else:
                    $jSON['trigger'] = AjaxErro("Opsss {$_SESSION['userLogin']['user_name']}, Sua Senha Deve Ter No Mínimo 5 Caracteres Para Ser Redefinida!", E_USER_WARNING);
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($POST['user_password']);
            endif;

            if (!empty($POST['user_document'])):
                if (!Check::CPF($POST['user_document'])):
                    $jSON['trigger'] = AjaxErro("<b>Opsss:</b> {$_SESSION['userLogin']['user_name']}, O CPF Informado Não é Válido. Favor Confira Seu CPF Para Atualizar!", E_USER_WARNING);
                    echo json_encode($jSON);
                    return;
                else:
                    $Read->FullRead("SELECT user_document FROM " . DB_USERS . " WHERE user_document = :document AND user_id != :user", "document={$POST['user_document']}&user={$UserId}");
                    if ($Read->getResult()):
                        $jSON['trigger'] = AjaxErro("<b>Opsss:</b> {$_SESSION['userLogin']['user_name']}, o CPF Informado Já Está Cadastrado Em Outra Conta. Se Isso For Um Erro, Favor Entre Em Contato Conosco Via " . SITE_ADDR_EMAIL . "!", E_USER_WARNING);
                        echo json_encode($jSON);
                        return;
                    endif;
                endif;
            endif;

            //ATUALIZA USUÁRIO
            $POST['user_lastupdate'] = date('Y-m-d H:i:s');
            $Update->ExeUpdate(DB_USERS, $POST, "WHERE user_id = :id", "id={$UserId}");
            $Read->ExeRead(DB_USERS, "WHERE user_id = :id", "id={$UserId}");
            if ($Read->getResult()):
                $_SESSION['userLogin'] = $Read->getResult()[0];
            endif;

            $jSON['trigger'] = AjaxErro("<b>TUDO CERTO:</b> Olá {$_SESSION['userLogin']['user_name']}, Seus Dados Foram Atualizados Com Sucesso!");
        endif;
        break;
endswitch;

echo json_encode($jSON);
