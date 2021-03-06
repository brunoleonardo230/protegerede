<?php

session_start();

$getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($getPost) || empty($getPost['action'])):
    die('Acesso Negado!');
endif;

$strPost = array_map('strip_tags', $getPost);
$trimPost = array_map('trim', $strPost);
$POST = array_map('htmlspecialchars', $trimPost);

$Action = $POST['action'];
$jSON = null;
unset($POST['action']);

usleep(2000);

require '../../../_app/Config.inc.php';
$Read = new Read;
$Create = new Create;
$Update = new Update;

switch ($Action):
//COMMENT ID IN SESSION
    case 'setcomment':
        $Read->FullRead("SELECT id, alias_id FROM " . DB_COMMENTS . " WHERE id = :id", "id={$POST['id']}");
        if (!$Read->getResult()):
            $jSON['comment'] = false;
        else:
            $jSON['comment'] = "<input type='hidden' name='to' value='{$POST['to']}'/>";
            if ($Read->getResult() && !empty($Read->getResult()[0]['alias_id'])):
                $jSON['comment'] .= "<input type='hidden' name='alias_id' value='{$Read->getResult()[0]['alias_id']}'/>";
            else:
                $jSON['comment'] .= "<input type='hidden' name='alias_id' value='{$Read->getResult()[0]['id']}'/>";
            endif;
        endif;
        break;

    //COMMENT LIKE
    case 'like':
        if (empty($_SESSION['userLogin'])):
            $jSON['login'] = true;
        else:
            $Read->FullRead("SELECT id FROM " . DB_COMMENTS_LIKES . " WHERE user_id = :user AND comm_id = :comm", "user={$_SESSION['userLogin']['user_id']}&comm={$POST['id']}");
            if ($Read->getResult()):
                $jSON['liked'] = true;
            else:
                $CreateLike = ['user_id' => $_SESSION['userLogin']['user_id'], 'comm_id' => $POST['id']];
                $Create->ExeCreate(DB_COMMENTS_LIKES, $CreateLike);
                $jSON['like'] = "<b>EU</b>";
            endif;
        endif;
        break;

    //COMMENTS RESPONSE
    case 'response':
        if (empty($_SESSION['userLogin'])):
            $jSON['login'] = true;
        else:
            if (!empty($POST['to'])):
                $UserResponder = $POST['to'];
                unset($POST['to']);
            endif;

            $POST['user_id'] = $_SESSION['userLogin']['user_id'];
            $POST['post_id'] = (!empty($_SESSION['comm']['post_id']) && empty($POST['alias_id']) ? $_SESSION['comm']['post_id'] : null);
            $POST['page_id'] = (!empty($_SESSION['comm']['page_id']) && empty($POST['alias_id']) ? $_SESSION['comm']['page_id'] : null);
            $POST['pdt_id'] = (!empty($_SESSION['comm']['pdt_id']) && empty($POST['alias_id']) ? $_SESSION['comm']['pdt_id'] : null);
            $POST['created'] = date('Y-m-d H:i:s');
            $POST['interact'] = date('Y-m-d H:i:s');
            $POST['status'] = (COMMENT_MODERATE ? 2 : 3);
            $POST['likes'] = 0;

            $Create->ExeCreate(DB_COMMENTS, $POST);
            $ThisComment = $Create->getResult();

            //SEND E-MAIL TO COMMENT AUTHOR
            if (COMMENT_SEND_EMAIL && !empty($POST['alias_id'])):
                $Read->ExeRead(DB_COMMENTS, "WHERE id = :key", "key={$POST['alias_id']}");
                if ($Read->getResult()):
                    $COMM = $Read->getResult()[0];

                    //GET CONTENT
                    if (!empty($COMM['post_id'])):
                        $Read->FullRead("SELECT post_title, post_name FROM " . DB_POSTS . " WHERE post_id = :key", "key={$COMM['post_id']}");
                        $CommTitle = (!empty($Read->getResult()[0]['post_title']) ? $Read->getResult()[0]['post_title'] : null);
                        $CommLink = (!empty($Read->getResult()[0]['post_name']) ? BASE . '/artigo/' . $Read->getResult()[0]['post_name'] : null);
                    elseif (!empty($COMM['page_id'])):
                        $Read->FullRead("SELECT page_title, page_name FROM " . DB_PAGES . " WHERE page_id = :key", "key={$COMM['page_id']}");
                        $CommTitle = (!empty($Read->getResult()[0]['page_title']) ? $Read->getResult()[0]['page_title'] : null);
                        $CommLink = (!empty($Read->getResult()[0]['page_name']) ? BASE . '/' . $Read->getResult()[0]['page_name'] : null);
                    elseif (!empty($COMM['pdt_id'])):
                        $Read->FullRead("SELECT pdt_title, pdt_name FROM " . DB_PDT . " WHERE pdt_id = :key", "key={$COMM['pdt_id']}");
                        $CommTitle = (!empty($Read->getResult()[0]['pdt_title']) ? $Read->getResult()[0]['pdt_title'] : null);
                        $CommLink = (!empty($Read->getResult()[0]['pdt_name']) ? BASE . '/produto/' . $Read->getResult()[0]['pdt_name'] : null);
                    endif;

                    //GET AUTHOR COMMENT
                    $Read->FullRead("SELECT user_id, user_name, user_lastname, user_email FROM " . DB_USERS . " WHERE user_id = :key", "key={$COMM['user_id']}");
                    $CommAuthor = ($Read->getResult() ? $Read->getResult()[0] : null);

                    //SEND E-MAIL TO AUTHOR
                    if (!empty($CommTitle) && !empty($CommLink) && $CommAuthor && $COMM['user_id'] != $_SESSION['userLogin']['user_id']):
                        require './comment.email.php';
                        $BodyMail = "
                            <p>Ol?? {$CommAuthor['user_name']}, Este E-mail R??pido ?? Para Avisar Que <b>{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}</b> Respondeu Seu Coment??rio Em Nosso Site!</p>
                            <p>Foi em <a title='Ver {$CommTitle}' target='_blank' href='{$CommLink}'>{$CommTitle}</a> dia " . date('d/m/Y H\hi', strtotime($COMM['created'])) . ". Responda {$_SESSION['userLogin']['user_name']} no link abaixo...</p>
                            <p><a title='Ver / Responder Coment??rio' target='_blank' href='{$CommLink}#comment{$ThisComment}'>VER/RESPONDER COMENT??RIO!</a></p>
                            <p><b>Confira Agora Mesmo {$CommAuthor['user_name']}. E Fique a Vontade Para Responder!</b></p>
                            <p>Obrigado Mais Uma Vez Pela Intera????o Em Nosso Site. Sua Opini??o ?? Muito Valiosa Para Nossa Equipe :)</p>
                            <p><i>Atenciosamente, " . SITE_NAME . "!</i></p>
                        ";
                        $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
                        $SendEmail = new Email;
                        $SendEmail->EnviarMontando("Nova resposta em seu coment??rio", $Mensagem, SITE_NAME, MAIL_USER, "{$CommAuthor['user_name']} {$CommAuthor['user_lastname']}", $CommAuthor['user_email']);
                    endif;

                    //SEND E-MAIL TO RESPONDER
                    if (!empty($UserResponder) && $UserResponder != $CommAuthor['user_id'] && $UserResponder != $_SESSION['userLogin']['user_id']):
                        //GET AUTHOR COMMENT
                        $Read->FullRead("SELECT user_id, user_name, user_lastname, user_email FROM " . DB_USERS . " WHERE user_id = :key", "key={$UserResponder}");
                        $CommAuthor = ($Read->getResult() ? $Read->getResult()[0] : null);

                        require_once './comment.email.php';
                        $BodyMail = "
                            <p>Ol?? {$CommAuthor['user_name']}, este e-mail r??pido ?? para avisar que <b>{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}</b> comentou sua resposta em nosso site!</p>
                            <p>Foi em <a title='Ver {$CommTitle}' target='_blank' href='{$CommLink}'>{$CommTitle}</a> dia " . date('d/m/Y H\hi', strtotime($COMM['created'])) . ". Responda {$_SESSION['userLogin']['user_name']} no link abaixo...</p>
                            <p><a title='Ver/Responder Coment??rio' target='_blank' href='{$CommLink}#comment{$ThisComment}'>VER/RESPONDER COMENT??RIO!</a></p>
                            <p><b>Confira Agora Mesmo {$CommAuthor['user_name']}. E Fique a Vontade Para Responder!</b></p>
                            <p>Obrigado Mais Uma Vez Pela Intera????o Em Nosso Site. Sua Opini??o ?? Muito Valiosa Para Nossa Equipe :)</p>
                            <p><i>Atenciosamente, " . SITE_NAME . "!</i></p>
                        ";
                        $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
                        $SendEmail = new Email;
                        $SendEmail->EnviarMontando("Novo Coment??rio Em Sua Resposta", $Mensagem, SITE_NAME, MAIL_USER, "{$CommAuthor['user_name']} {$CommAuthor['user_lastname']}", $CommAuthor['user_email']);
                    endif;
                endif;
            endif;

            //RETURN COMMENT
            $UserAvatar = (!empty($_SESSION['userLogin']['user_thumb']) ? "uploads/{$_SESSION['userLogin']['user_thumb']}" : "admin/_img/no_avatar.jpg");
            $CommStars = str_repeat("&starf;", $POST['rank']) . str_repeat("&star;", 5 - $POST['rank']);
            $jSON['response'] = "<article class='comments_single comment_response ajax_response' id='comment{$ThisComment}'><div class='comments_single_avatar'><img alt='{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}' title='{$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}' src='" . BASE . "/tim.php?src={$UserAvatar}&w=200&h=200'></div><div class='comments_single_content'><header><h1 class='font_green'><b>??? {$_SESSION['userLogin']['user_name']} {$_SESSION['userLogin']['user_lastname']}</b></h1></header><div class='comments_single_comment'>" . nl2br($POST['comment']) . "</div><div class='comments_single_ui'><span class='stars'>{$CommStars}</span><span class='date'>DIA " . date('d.m.Y H\hi', strtotime($POST['created'])) . "</span></div><div class='comments_single_likes' id='{$ThisComment}'><span><span class='na'>N/A</span></span></div></div></article>";

            //MODERATE COMMENT TARGET
            if (!empty($POST['alias_id'])):
                $UpdateData = ['status' => 3];
                $Update->ExeUpdate(DB_COMMENTS, $UpdateData, "WHERE id = :alias", "alias={$POST['alias_id']}");
                $jSON['alias'] = true;
            endif;
        endif;
        break;

    //ON CHANGE GET USER
    case 'getuser':
        if (empty($POST['email'])):
            $jSON['trigger'] = AjaxErro("Por Favor, Informe Seu E-mail Para Continuar :)", E_USER_ERROR);
        elseif (!Check::Email($POST['email']) || !filter_var($POST['email'], FILTER_VALIDATE_EMAIL)):
            $jSON['trigger'] = AjaxErro("O E-mail Informado N??o Tem Um Formato V??lido! :/", E_USER_ERROR);
        else:
            $Read->FullRead("SELECT user_name FROM " . DB_USERS . " WHERE user_email = :email", "email={$POST['email']}");
            if (!$Read->getResult()):
                $jSON['create'] = true;
            else:
                $jSON['trigger'] = AjaxErro("Bem-vindo(a) {$Read->getResult()[0]['user_name']}. Informe Sua Senha Para Comentar! :)");
                $jSON['login'] = true;
            endif;
        endif;
        break;

    //LOGIN USER
    case 'loginuser':
        unset($POST['user_name'], $POST['user_lastname'], $POST['user_code']);
        if (empty($POST['user_email']) || !Check::Email($POST['user_email']) || !filter_var($POST['user_email'], FILTER_VALIDATE_EMAIL)):
            $jSON['trigger'] = AjaxErro("<b>ERRO AO LOGAR:</b> Informe Seu <b>E-mail</b> Para Logar e Interagir!", E_USER_ERROR);
        elseif (empty($POST['user_password']) || strlen($POST['user_password']) < 5 || strlen($POST['user_password']) > 11):
            $jSON['trigger'] = AjaxErro("<b>ERRO AO LOGAR:</b> Informe Sua <b>Senha</b> Para Logar e Interagir!", E_USER_ERROR);
        else:
            $PassWord = hash("sha512", $POST['user_password']);
            $Read->ExeRead(DB_USERS, "WHERE user_email = :email AND user_password = :pass", "email={$POST['user_email']}&pass={$PassWord}");
            if (!$Read->getResult()):
                $jSON['trigger'] = AjaxErro("<b>ERRO AO LOGAR:</b> E-mail ou Senha Informados N??o Conferem! Caso Tenha Esquecido Sua Senha: <span class='comment_recover_password'>RECUPERAR MINHA SENHA</span>", E_USER_ERROR);
                $_SESSION['recover']['email'] = $POST['user_email'];
            else:
                $_SESSION['userLogin'] = $Read->getResult()[0];
                $jSON['user'] = true;
            endif;
        endif;
        break;

    //CREATE NEW USER
    case 'createuser':
        unset($POST['user_password'], $POST['user_code']);
        if (in_array('', $POST)):
            $jSON['trigger'] = AjaxErro("<b>OPSSS:</b> Informe Seu Nome e Sobrenome Para Continuar!", E_USER_ERROR);
        elseif (!Check::Email($POST['user_email']) || !filter_var($POST['user_email'], FILTER_VALIDATE_EMAIL)):
            $jSON['trigger'] = AjaxErro("<b>OPSSS:</b> O E-mail Informado N??o Tem Um Formato V??lido!", E_USER_ERROR);
        else:
            $Read->FullRead("SELECT user_id, user_name FROM " . DB_USERS . " WHERE user_email = :email", "email={$POST['user_email']}");
            if ($Read->getResult()):
                $jSON['trigger'] = AjaxErro("Bem-vindo(a) {$Read->getResult()[0]['user_name']}. Informe Sua Senha Para Comentar :)");
            else:
                //CADASTRA USU??RIO
                $Password = substr(md5(time()), 0, 6);
                $POST['user_password'] = hash('sha512', $Password);
                $POST['user_channel'] = 'Coment??rios';
                $POST['user_registration'] = date('Y-m-d H:i:s');
                $POST['user_level'] = 1;

                $Create->ExeCreate(DB_USERS, $POST);
                $POST['user_id'] = $Create->getResult();
                $_SESSION['userLogin'] = $POST;

                //SEND CREATE ACCOUNT
                require_once './comment.email.php';
                $BodyMail = "
                    <p style='font-size: 1.3em'>Caro(a) {$POST['user_name']},</p>
                    <p>Antes de mais nada gostar??amos de agradecer pelo seu coment??rio, sua opini??o ?? muito importante para n??s.</p>
                    <p><b>E com isso, dar as boas vindas ao nosso site.</b></p>
                    <p>Uma nova conta foi criada para que voc?? possa ter mais comodidade ao interagir conosco. Segue abaixo os dados de sua conta " . SITE_NAME . ".
                    <p style='font-size: 1.1em'>
                        Login: {$POST['user_email']}<br>
                        Senha: {$Password}
                    </p>";
                //FOR USER ACCOUNT MANAGER
                if (ACC_MANAGER):
                    $BodyMail .= "
                    <p>Com esses dados voc?? tamb??m pode efetuar LOGIN para gerenciar sua conta. Complete seu perf??l em nosso site {$POST['user_name']}.</p>
                    <p><a title='Minha Conta' target='_blank' href='" . BASE . "/conta/login'>ACESSAR MINHA CONTA AGORA!</a></p>
                ";
                endif;
                $BodyMail .= "
                    <p>A partir de agora, sempre que acessar nosso site voc?? pode usar esses dados para identificar-se, e assim ter acesso ao melhor do nosso conte??do...</p>
                    <p><b>Seja muito bem-vindo(a) {$POST['user_name']}...</b></p>
                    <p><i>Atenciosamente, " . SITE_NAME . "!</i></p>
                ";
                $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
                $SendEmail = new Email;
                $SendEmail->EnviarMontando("Seja bem-vindo(a) {$POST['user_name']}", $Mensagem, SITE_NAME, MAIL_USER, "{$POST['user_name']} {$POST['user_lastname']}", $POST['user_email']);
                $jSON['user'] = true;
            endif;
        endif;
        break;

    //SEND RECOVER
    case 'recoversend':
        $_SESSION['recover']['code'] = substr(md5(time()), 0, 6);
        $jSON['email'] = $_SESSION['recover']['email'];

        $Read->FullRead("SELECT user_email, user_name, user_lastname FROM " . DB_USERS . " WHERE user_email = :em ", "em={$_SESSION['recover']['email']}");
        if (!$Read->getResult()):
            $jSON['trigger'] = AjaxErro("Desculpe. Mas N??o Foi Poss??vel Enviar o C??digo de Acesso! Voc?? Pode Tentar Novamente. <span class='comment_recover_password'>Reenviar C??digo de Acesso!</span>", E_USER_WARNING);
        else:
            $CommAuthor = $Read->getResult()[0];

            //SEND CODE TO LOGIN
            require_once './comment.email.php';
            $BodyMail = "
                    <p>Ol?? {$CommAuthor['user_name']}, voc?? est?? recebendo esse e-mail pois solicitou um c??digo de acesso a sua conta em nosso site.</p>
                    <p>Caso n??o tenha solicitado esse c??digo. Por favor nos desculpe pelo incomodo. E apenas ignore este e-mail :)</p>
                    <p>Segue seu c??digo de acesso:</p>
                    <p style = 'font-size: 2em;'>{$_SESSION['recover']['code']}</p>
                    <p>Copie esse c??digo e cole no campo de login em nosso site para curtir um coment??rio, ou enviar o seu!</p>
                    <p><i>Atenciosamente, " . SITE_NAME . "!</i></p>
                    ";
            $Mensagem = str_replace('#mail_body#', $BodyMail, $MailContent);
            $SendEmail = new Email;
            $SendEmail->EnviarMontando("Confira seu c??digo de acesso", $Mensagem, SITE_NAME, MAIL_USER, "{$CommAuthor['user_name']} {$CommAuthor['user_lastname']}", $CommAuthor['user_email']);
            $jSON['trigger'] = AjaxErro("Um Novo C??digo de Acesso Foi Enviado Para Seu E-mail!");
        endif;
        break;

    //RECOVER USER
    case 'recoveruser':
        if (empty($POST['user_code'])):
            $jSON['trigger'] = $jSON['trigger'] = AjaxErro("Informe o C??digo de Acesso Para Continuar!", E_USER_ERROR);
        elseif (empty($_SESSION['recover']['email']) || empty($_SESSION['recover']['code'])):
            $jSON['trigger'] = $jSON['trigger'] = AjaxErro("N??o Existe Um C??digo de Acesso Para Recuperar!", E_USER_ERROR);
            $jSON['recover_error'] = true;
        elseif ($POST['user_code'] != $_SESSION['recover']['code']):
            $jSON['trigger'] = $jSON['trigger'] = AjaxErro("O C??digo de Acesso N??o Confere. Verifique Seu E-mail!", E_USER_ERROR);
        else:
            //READ FROM SESSION
            $Read->ExeRead(DB_USERS, "WHERE user_email = :email ", "email={$_SESSION['recover']['email']}");
            if (!$Read->getResult()):
                $jSON['trigger'] = $jSON['trigger'] = AjaxErro("N??o Foi Poss??vel Recuperar. Favor Tente Novamente!", E_USER_ERROR);
                $jSON['recover_error'] = true;
            else:
                $_SESSION['userLogin'] = $Read->getResult()[0];
                $jSON['user'] = true;
            endif;
        endif;
        break;
endswitch;

echo json_encode($jSON);
